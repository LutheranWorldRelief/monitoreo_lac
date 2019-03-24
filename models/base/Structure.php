<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "structure".
 * Please do not add custom code to this file, as it is supposed to be overriden
 * by the gii model generator. Custom code belongs to app\models\Structure.
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property int $structure_id
 * @property string $notes
 * @property int $project_id
 *
 * @property \app\models\Event[] $events
 * @property \app\models\Project $project
 * @property \app\models\Structure $structure
 * @property \app\models\Structure[] $structures
 */
abstract class Structure extends \app\components\ActiveRecord
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
            'id' => 'ID',
            'code' => 'Code',
            'description' => 'Description',
            'structure_id' => 'Structure ID',
            'notes' => 'Notes',
            'project_id' => 'Project ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::className(), ['structure_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructure()
    {
        return $this->hasOne(\app\models\Structure::className(), ['id' => 'structure_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(\app\models\Structure::className(), ['structure_id' => 'id']);
    }
}
