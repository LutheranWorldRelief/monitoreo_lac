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
 * @property int                        $list_id
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
            [['list_id', 'order'], 'integer'],
            [['tag', 'slug'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 45],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataList::className(), 'targetAttribute' => ['list_id' => 'id']],
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
            'tag' => 'Tag',
            'value' => 'Value',
            'list_id' => 'List ID',
            'notes' => 'Notes',
            'slug' => 'Slug',
            'order' => 'Order',
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
        return $this->hasOne(\app\models\DataList::className(), ['id' => 'list_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDataLists()
    {
        return $this->hasMany(\app\models\DataList::className(), ['list_id' => 'id']);
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
