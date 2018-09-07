<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%structure}}".
 *
 * Check the base class at app\models\base\Structure in order to
 * see the column names and relations.
 */
class Structure extends \app\models\base\Structure {

    public function getstructure_name() {
        if ($this->structure)
            return $this->structure->description;
        return '';
    }

    public function getproject_name() {
        if ($this->project)
            return $this->project->name;
        return '';
    }

    public static function listDataBlank($label = 'nombre', $id = null) {
        return [null => 'Seleccione'] + self::listData($label, $id);
    }

    public static function listData($label = 'nombre', $id = null) {
        if ($id)
            return ArrayHelper::map(self::find()->andFilterWhere(['project_id' => $id])->all(), 'id', 'nombre_largo');
        return ArrayHelper::map(self::find()->all(), 'id', 'nombre_largo');
    }

    public function getnombre_largo() {
        if ($this->structure)
            $project = $this->structure->getnombre_largo();
        else
            $project = $this->project ? ' / ' . $this->project->name : ' ';
        if (!$this->structure)
            return $this->description . ' ' . $project;
        return $this->description . ' :: ' . $project;
    }

}
