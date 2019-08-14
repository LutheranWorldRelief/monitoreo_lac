<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "filter".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Filter.
 *
 * @property int                  $id
 * @property string               $name
 * @property string               $start
 * @property string               $end
 * @property string               $slug
 * @property int                  $order
 * @property int                  $filter_id
 *
 * @property \app\models\Filter   $filter
 * @property \app\models\Filter[] $filters
 */
abstract class Filter extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'start', 'end', 'slug'], 'required'],
            [['order', 'filter_id'], 'integer'],
            [['name', 'start', 'end', 'slug'], 'string', 'max' => 255],
            [['filter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Filter::className(), 'targetAttribute' => ['filter_id' => 'id']],
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
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'slug' => Yii::t('app', 'Slug'),
            'order' => Yii::t('app', 'Order'),
            'filter_id' => Yii::t('app', 'Filter ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(\app\models\Filter::className(), ['id' => 'filter_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(\app\models\Filter::className(), ['filter_id' => 'id']);
    }
}
