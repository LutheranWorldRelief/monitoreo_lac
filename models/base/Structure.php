<?php

namespace app\models\base;

use app\components\ActiveRecord;
use yii\db\ActiveQuery;
use yii;

/**
 * This is the model class for table "structure".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Structure.
 *
 * @property int                     $id
 * @property string                  $code
 * @property string                  $description
 * @property int                     $structure_id
 * @property string                  $notes
 * @property int                     $project_id
 *
 * @property \app\models\Event[]     $events
 * @property \app\models\Project     $project
 * @property \app\models\Structure   $structure
 * @property \app\models\Structure[] $structures
 */
abstract class Structure extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'project_id'], 'required'],
            [['description', 'notes'], 'string'],
            [['structure_id', 'project_id'], 'integer'],
            [['code'], 'string', 'max' => 150],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'description' => Yii::t('app', 'Description'),
            'structure_id' => Yii::t('app', 'Structure ID'),
            'notes' => Yii::t('app', 'Notes'),
            'project_id' => Yii::t('app', 'Project ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::className(), ['structure_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStructure()
    {
        return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(\app\models\Structure::className(), ['structure_id' => 'id']);
    }
}
