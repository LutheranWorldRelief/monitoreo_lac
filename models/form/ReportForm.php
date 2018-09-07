<?php
namespace app\models\form;

class ReportForm extends \yii\base\Model
{
    public $send_it;
    public $date_start;
    public $date_end;
    public $project_id;
    public $org_implementing_id;
    public $country_id;
    public $country_code;

    public function init()
    {
        parent::init();
        $this->date_start = null;   //date('Y-') . '01-01';
        $this->date_end = null;     //date('Y-m-d');
        $this->send_it = 1;
    }

    public function rules()
    {
        return [
            [['date_start', 'date_end'], 'date'],
//          [['date_start', 'date_end'], 'required'],
            [['project_id', 'country_code'], 'string'],
            [['org_implementing_id', 'country_id', 'send_it'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'date_start'    => 'Fecha Inicio',
            'date_end'      => 'Fecha Fin',
            'project_id'    => 'Proyecto',
            'org_implementing_id' => 'Organización Implementadora',
            'country_id'    => 'País',
            'country_code'  => 'País',
        ];
    }
}