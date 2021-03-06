<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "data_list".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\DataList.
 *
 * @property int                        $id
 * @property string                     $name
 * @property string                     $tag
 * @property string                     $value
 * @property int                        $data_list_id
 * @property string                     $notes
 * @property string                     $slug
 * @property int                        $order
 *
 * @property \app\models\Contact[]      $contacts
 * @property \app\models\Contact[]      $contacts0
 * @property \app\models\DataList       $list
 * @property \app\models\DataList[]     $dataLists
 * @property \app\models\Event[]        $events
 * @property \app\models\Organization[] $organizations
 */
abstract class DataList extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'notes'], 'string'],
            [['data_list_id', 'order'], 'integer'],
            [['tag', 'slug'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 45],
            [['data_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['data_list_id' => 'id']],
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
            'tag' => Yii::t('app', 'Tag'),
            'value' => Yii::t('app', 'Value'),
            'data_list_id' => Yii::t('app', 'List ID'),
            'notes' => Yii::t('app', 'Notes'),
            'slug' => Yii::t('app', 'Slug'),
            'order' => Yii::t('app', 'Order'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['education_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getContacts0()
    {
        return $this->hasMany(\app\models\Contact::className(), ['type_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'data_list_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDataLists()
    {
        return $this->hasMany(\app\models\DataList::className(), ['data_list_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::className(), ['country_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(\app\models\Organization::className(), ['country_id' => 'id']);
    }
}
