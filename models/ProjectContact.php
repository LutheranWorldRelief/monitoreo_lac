<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%project_contact}}".
 *
 * Check the base class at app\models\base\ProjectContact in order to
 * see the column names and relations.
 */
class ProjectContact extends base\ProjectContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'contact_id'], 'required'],
            [['project_id','product_id', 'contact_id', 'age_development_plantation', 'age_productive_plantation'], 'integer'],
            [['area', 'development_area', 'productive_area', 'yield'], 'number'],
            [['date_entry_project', 'date_end_project'], 'date', 'format' => 'yyyy-MM-dd'],
            [
                ['date_entry_project'],
                'compare',
                'compareAttribute' => 'date_end_project',
                'operator' => '<=',
                'type' => 'date',
                'skipOnEmpty' => true,
                'when' => function ($model) {
                    Yii::warning($model->date_end_project, 'kar_loggin');
                    if ($model->date_end_project === null || TRIM($model->date_end_project) == '')
                        return false;
                    return true;
                },
            ],
            [
                ['date_end_project'],
                'compare',
                'compareAttribute' => 'date_entry_project',
                'operator' => '>=',
                'type' => 'date',
                'skipOnEmpty' => true,
                'when' => function ($model) {
                    Yii::warning($model->date_entry_project, 'kar_loggin');
                    if ($model->date_entry_project === null || TRIM($model->date_entry_project) == '')
                        return false;
                    return true;
                },
            ]
        ];
    }
}
