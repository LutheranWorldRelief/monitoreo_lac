<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "project".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Project.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $code
 * @property string                       $logo
 * @property string                       $colors
 * @property string                       $url
 * @property string                       $start
 * @property string                       $end
 * @property int                          $goal_men
 * @property int                          $goal_women
 *
 * @property \app\models\ProjectContact[] $projectContacts
 * @property \app\models\Structure[]      $structures
 */
abstract class Project extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'colors'], 'required'],
            [['name', 'colors'], 'string'],
            [['start', 'end'], 'safe'],
            [['goal_men', 'goal_women'], 'integer'],
            [['code', 'logo', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'logo' => 'Logo',
            'colors' => 'Colors',
            'url' => 'Url',
            'start' => 'Start',
            'end' => 'End',
            'goal_men' => 'Goal Men',
            'goal_women' => 'Goal Women',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProjectContacts()
    {
        return $this->hasMany(\app\models\ProjectContact::className(), ['project_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(\app\models\Structure::className(), ['project_id' => 'id']);
    }
}
