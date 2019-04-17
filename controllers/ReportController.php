<?php

namespace app\controllers;

use app\models\AuthUser;
use app\models\DataList;
use app\models\form\ReportForm;
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
use Yii;
use yii\helpers\ArrayHelper;

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

            $query = SqlFullReportProjectContact::find();

            $query
                ->select([
                    'CONCAT(IF(project_code IS NULL or project_code = \'\', "00-0000", project_code), \'=>\', project_name) AS project_name',
                    'organization_implementing_name',
                    'contact_document',
                    'contact_name',
                    'TRIM("") AS contact_lastname',
                    'IF(contact_sex="F","Mujer", IF(contact_sex="M","Hombre", null)) AS sex',
                    'contact_birthdate',
                    'contact_education',
                    'contact_phone_personal',
                    'contact_men_home',
                    'contact_women_home',
                    'contact_organization',
                    'contact_country',
                    'contact_municipality',
                    'contact_community',
                    'IF(contact_project_date_entry!="", contact_project_date_entry, MIN(event_date_start)) as contact_project_date_entry',
                    'contact_project_product',
                    'contact_project_area_farm',
                    'contact_project_dev_area',
                    'contact_project_age_dev_plantation',
                    'contact_project_productive_area',
                    'contact_project_age_prod_plantation',
                    'contact_project_yield',
                ]);

            $query
                ->andFilterWhere(['project_id' => $model->project_id])
                ->andFilterWhere(['event_country_code' => $model->country_code])
                ->andFilterWhere(['organization_implementing_id' => $model->org_implementing_id]);

            if (!$auth->is_superuser) {
                $query
                    ->andWhere(['project_id' => $auth->projects])
                    ->andFilterWhere(['event_country_id' => $auth->countries]);
            }

            $query->groupBy([
                'project_id',
                'organization_implementing_id',
                'contact_id',
            ]);

            $query
                ->andFilterHaving(['>=', 'contact_project_date_entry', $model->date_start])
                ->andFilterHaving(['<=', 'contact_project_date_entry', $model->date_end]);

            $models = $query->asArray()->all();

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

            $projects = Project::find()->select(['CONCAT(IF(code IS NULL or code = \'\', "00-0000", code), \'=>\', name) as name',])
                ->orderBy('code, name')
                ->asArray()
                ->all();

            $organizations = Organization::find()->select(['name'])
                ->where('is_implementer = 1')
                ->orderBy('name')
                ->asArray()
                ->all();


            $countries = DataList::itemsBySlug('countries', 'name', 'id', 'name asc');

            $department = [];

            $sheetCatalogues->fromArray($projects, null, 'A3');
            $sheetCatalogues->fromArray($organizations, null, 'B3');
            $sheetCatalogues->fromArray(array_chunk($countries, 1), null, 'E3');
            $sheetCatalogues->fromArray(array_chunk($department, 1), null, 'F3');

            $AddValidationToRange($sheetData, 1, 3, 1, 2000, 'catalogos!$A$2:$A$1000', null); //validate projects
            $AddValidationToRange($sheetData, 2, 3, 2, 2000, 'catalogos!$B$2:$B$1000', null); //validate orgs
            $AddValidationToRange($sheetData, 6, 3, 6, 2000, 'catalogos!$C$2:$C$1000', null); //validate sex
            $AddValidationToRange($sheetData, 8, 3, 8, 2000, 'catalogos!$D$2:$D$1000', null); //validate education
            $AddValidationToRange($sheetData, 13, 3, 13, 2000, 'catalogos!$E$2:$E$1000', null); //validate country

        }
        $spreadsheet->setActiveSheetIndex(0);

        for ($i = 3; $i <= 10000; $i++) {
            $conditional = new Conditional();
            $conditional->setConditionType(Conditional::CONDITION_EXPRESSION);
            $conditional->addCondition('COUNTIF($C$3:$C$10000,C' . $i . ')>1');
            $conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => Color::COLOR_RED,
                    ],
                    'endColor' => [
                        'argb' => Color::COLOR_RED,
                    ],
                ],
                'numberFormat' => [
                    'formatCode' => NumberFormat::FORMAT_TEXT,
                ]
            ];

            $conditional->getStyle()->applyFromArray($styleArray); //-->getFont()->applyFromArray($styleArray);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getConditionalStyles();
            $conditionalStyles[] = $conditional;

            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->setConditionalStyles($conditionalStyles);
        }
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