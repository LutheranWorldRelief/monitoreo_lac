<?php

namespace app\models\base;

use app\components\ActiveRecord;

/**
 * This is the model class for table "sql_project".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\SqlProject.
 *
 * @property int    $id
 * @property string $name
 * @property string $code
 * @property string $logo
 * @property string $colors
 * @property string $url
 * @property string $start
 * @property string $end
 * @property int    $goal_men
 * @property int    $goal_women
 * @property string $countries
 * @property int    $h
 * @property int    $m
 * @property int    $t
 */
abstract class SqlProject extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sql_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'goal_men', 'goal_women', 'h', 'm', 't'], 'integer'],
            [['name', 'code', 'colors'], 'required'],
            [['name', 'colors', 'countries'], 'string'],
            [['start', 'end'], 'safe'],
            [['code', 'logo', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'logo' => Yii::t('app', 'Logo'),
            'colors' => Yii::t('app', 'Colors'),
            'url' => Yii::t('app', 'Url'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'goal_men' => Yii::t('app', 'Goal Men'),
            'goal_women' => Yii::t('app', 'Goal Women'),
            'countries' => Yii::t('app', 'Countries'),
            'h' => Yii::t('app', 'H'),
            'm' => Yii::t('app', 'M'),
            't' => Yii::t('app', 'T'),
        ];
    }
}
