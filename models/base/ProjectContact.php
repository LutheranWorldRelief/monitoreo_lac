<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;
use Yii;

/**
 * This is the model class for table "project_contact".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\ProjectContact.
 *
 * @property int $id
 * @property int $project_id
 * @property int $contact_id
 * @property int $product_id
 * @property double $area
 * @property double $development_area
 * @property double $productive_area
 * @property int $age_development_plantation
 * @property int $age_productive_plantation
 * @property double $yield
 * @property string $date_entry_project
 * @property string $date_end_project
 *
 * @property \app\models\Contact $contact
 * @property \app\models\Project $project
 */
abstract class ProjectContact extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'contact_id'], 'required'],
            [['project_id', 'product_id', 'contact_id', 'age_development_plantation', 'age_productive_plantation'], 'integer'],
            [['area', 'development_area', 'productive_area', 'yield'], 'number'],
            [['date_entry_project', 'date_end_project'], 'safe'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'area' => Yii::t('app', 'Area'),
            'development_area' => Yii::t('app', 'Development Area'),
            'productive_area' => Yii::t('app', 'Productive Area'),
            'age_development_plantation' => Yii::t('app', 'Age Development Plantation'),
            'age_productive_plantation' => Yii::t('app', 'Age Productive Plantation'),
            'yield' => Yii::t('app', 'Yield'),
            'date_entry_project' => Yii::t('app', 'Date Entry Project'),
            'date_end_project' => Yii::t('app', 'Date End Project'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(\app\models\Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'project_id']);
    }
}
