<?php

namespace app\controllers;

use app\models\AuthUser;
use app\models\Country;
use app\models\form\ReportForm;
use app\models\MonitoringEducation;
use app\models\MonitoringProduct;
use app\models\Organization;
use app\models\Project;
use app\models\SqlFullReportProjectContact;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use yii;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * ReportController implements the CRUD actions for Project model.
 */
class ReportController extends ControladorController
{

    public function actionIndex()
    {

        $user = Yii::$app->user;

        /* @var AuthUser $auth */
        $auth = $user->identity;
        $model = new ReportForm();

        $projects = $auth->projectsList();
        $countries = $auth->countriesList();

        $organizations = ArrayHelper::map(Organization::find()
            ->select(['id', 'name'])
            ->where(['is_implementer' => true])
            ->orderBy('name')
            ->all(), 'id', 'name');

        //        if ($data && $model->load($data) && $model->validate()){
        if ($model->load(Yii::$app->request->post())) {

            $query = (new Query());
            $query
                ->select(["CONCAT(p.code, '=>', p.name)  AS project_name,
                           o2.name                       AS organization_implementing_name,
                           c.document                    AS contact_document,
                           c.name                        AS contact_name,
                           c.last_name                   AS contact_lastname,
                           c.sex                         AS contact_sex,
                           c.birthdate                   AS contact_birthdate,
                           me.name                       AS contact_education,
                           c.phone_personal              AS contact_phone_personal,
                           c.men_home                    AS contact_men_home,
                           c.women_home                  AS contact_women_home,
                           o.name                        AS contact_organization,
                           c.city                        AS contact_country,
                           c.municipality                AS contact_municipality,
                           c.community                   AS contact_community,
                           pc.date_entry_project         AS contact_project_date_entry,
                           mp.name                       AS contact_project_product,
                           pc.area                       AS contact_project_area_farm,
                           pc.development_area           AS contact_project_dev_area,
                           pc.age_development_plantation AS contact_project_age_dev_plantation,
                           pc.productive_area            AS contact_project_productive_area,
                           pc.age_productive_plantation  AS contact_project_age_prod_plantation,
                           pc.yield                      AS contact_project_yield"])
                ->from('project p')
                ->leftJoin('project_contact pc', 'p.id = pc.project_id')
                ->leftJoin('contact c', 'pc.contact_id = c.id')
                ->leftJoin('country ctry', 'c.country_id = ctry.id')
                ->leftJoin('organization o', 'o.id = pc.organization_id')
                ->leftJoin('organization o2', 'c.organization_id = o2.id')
                ->leftJoin('monitoring_product mp', 'pc.product_id = mp.id')
                ->leftJoin('monitoring_education me', 'c.education_id = me.id');

            if ($model->project_id)
                $query->andWhere(['p.id' => $model->project_id]);

            if ($model->country_code)
                $query->andWhere(['ctry.id' => $model->country_code]);

            if ($model->org_implementing_id)
                $query->andWhere(['o.id' => $model->org_implementing_id]);

            if (!$auth->is_superuser) {
                $query
                    ->andWhere(['project_id' => $auth->projects])
                    ->andFilterWhere(['event_country_id' => $auth->countries]);
            }

            $query->groupBy([
                'project_name',
                'organization_implementing_name',
                'contact_document',
                'contact_name',
                'contact_lastname',
                'contact_sex',
                'contact_birthdate',
                'contact_education',
                'contact_phone_personal',
                'contact_men_home',
                'contact_women_home',
                'contact_organization',
                'contact_country',
                'contact_municipality',
                'contact_community',
                'contact_project_date_entry',
                'contact_project_product',
                'contact_project_area_farm',
                "contact_project_dev_area", "contact_project_age_dev_plantation",
                "contact_project_productive_area",
                "contact_project_age_prod_plantation",
                "contact_project_yield",
                'organization_implementing_name',
                'contact_id',
                'pc.date_entry_project',
                'pc.date_end_project'
            ]);

            if ($model->date_start)
                $query
                    ->andFilterHaving(['>=', 'pc.date_entry_project', $model->date_start])
                    ->andFilterHaving(['<=', 'pc.date_entry_project', $model->date_end]);

//            print_r($query->createCommand()->getRawSql());
//            exit();

            $models = $query->all();

            $spreadsheet = $this->getTemplateClean();
            $sheetData = $spreadsheet->getSheetByName("datos");

            $sheetData->fromArray($models, null, 'A3');

            $this->addCatalogAndValidation($spreadsheet);

            $this->sendExcel($spreadsheet, 'lwr_attendance_projects_' . Yii::$app->language . date('_Ymd_His'));

        }

        return $this->render('index', [
            'model' => $model,
            'projects' => $projects,
            'organizations' => $organizations,
            'countries' => $countries,
        ]);
    }

    private function getTemplateClean()
    {
        $fileName = 'lwr_contacts_';
        $spreadsheet = IOFactory::load(Yii::getAlias('@app') . "/components/excel/templates/{$fileName}" . Yii::$app->language . '.xlsx');

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /*
     * @param PhpSpreadsheet $spreadsheet
     */
    private function addCatalogAndValidation($spreadsheet)
    {
        $sheetCatalogues = $spreadsheet->getSheetByName("catalogos");
        $sheetData = $spreadsheet->getSheetByName("datos");
        $AddValidationToRange = function ($sheet, $columnStart, $rowStart, $columnEnd, $rowEnd, $range, $data) {
            if (!$sheet)
                return;

            /* @var $sheet Worksheet */
            $validation = new DataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setPromptTitle('Datos');
            $validation->setPrompt('Seleccione un valor de la lista.');

            if ($range)
                $validation->setFormula1($range);
            elseif ($data && is_array($data))
                $validation->setFormula1('"' . implode(',', $data) . '"');

            for ($column = $columnStart; $column <= $columnEnd; $column++) {
                for ($row = $rowStart; $row <= $rowEnd; $row++) {
                    $sheet->getCellByColumnAndRow($column, $row)->setDataValidation($validation);
                }
            }
        };

        if ($sheetData && $sheetCatalogues) {
            $languague = Yii::$app->language;
            $nameColumn = 'name';
            if ($languague !== 'en') {
                $nameColumn .= '_' . $languague;
            }

            $projects = Project::find()->select(["CONCAT(CASE WHEN code IS NULL or code = '' THEN '00-0000' ELSE code END, '=>', name) as name",])
                ->orderBy('code, name')
                ->asArray()
                ->all();

            $organizations = Organization::find()->select(['name'])
                ->where('is_implementer')
                ->orderBy('name')
                ->asArray()
                ->all();

            $countries = Country::allCountries();

            $products = MonitoringProduct::allProductNames($nameColumn);

            $education = MonitoringEducation::allEducationNames($nameColumn);

            $department = [];

            $sheetCatalogues->fromArray($projects, null, 'A3');
            $sheetCatalogues->fromArray($organizations, null, 'B3');
            $sheetCatalogues->fromArray($education, null, 'D3');
            $sheetCatalogues->fromArray(array_chunk($countries, 1), null, 'E3');
            $sheetCatalogues->fromArray(array_chunk($department, 1), null, 'F3');
            $sheetCatalogues->fromArray($products, null, 'Q3');

            $AddValidationToRange($sheetData, 1, 3, 1, 2000, 'catalogos!$A$2:$A$1000', null); //validate projects
            $AddValidationToRange($sheetData, 2, 3, 2, 2000, 'catalogos!$B$2:$B$1000', null); //validate orgs
            $AddValidationToRange($sheetData, 6, 3, 6, 2000, 'catalogos!$C$2:$C$1000', null); //validate sex
            $AddValidationToRange($sheetData, 8, 3, 8, 2000, 'catalogos!$D$2:$D$1000', null); //validate education
            $AddValidationToRange($sheetData, 13, 3, 13, 2000, 'catalogos!$E$2:$E$1000', null); //validate country
            $AddValidationToRange($sheetData, 17, 3, 17, 2000, 'catalogos!$Q$2:$Q$1000', null); //validate products

        }
        $spreadsheet->setActiveSheetIndex(0);

        /*for ($i = 3; $i <= 10000; $i++) {
            $conditional = new Conditional();
            $conditional->setConditionType(Conditional::CONDITION_EXPRESSION);
            $conditional->addCondition('COUNTIF($C$3:$C$10000,C' . $i . ')>1');
            $conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);

            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    /*  'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                         'argb' => Color::COLOR_RED,
                     ],
                     'endColor' => [
                         'argb' => Color::COLOR_RED,
                     ],*/
        /* ],
       'numberFormat' => [
            'formatCode' => NumberFormat::FORMAT_TEXT,
        ]
    ];

    $conditional->getStyle()->applyFromArray($styleArray); //-->getFont()->applyFromArray($styleArray);

    $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getConditionalStyles();
    $conditionalStyles[] = $conditional;

    $spreadsheet->getActiveSheet()->getStyle('C' . $i)->setConditionalStyles($conditionalStyles);
}*/
    }

    private function sendExcel($spreadsheet, $fileName = 'file')
    {
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die;
    }

    public function actionTemplateClean()
    {
        $fileName = 'lwr_contacts_';

        $spreadsheet = $this->getTemplateClean();
        $this->addCatalogAndValidation($spreadsheet);

        $this->sendExcel($spreadsheet, $fileName . Yii::$app->language . date('_Ymj_His'));
    }

}
