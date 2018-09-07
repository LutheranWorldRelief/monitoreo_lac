<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[SqlEventSex]].
 *
 * @see SqlEventSex
 */
class SqlEventSexQuery extends \yii\db\ActiveQuery {

    public static function find() {
        return \app\models\SqlEventSex::find();
    }

    public function camposActividades() {
        return $this->select(
                        "activity_id," .
                        " activity," .
                        " sum(m) as m," .
                        " sum(f) as f," .
                        " sum(total) as total"
                )->groupBy(['activity_id']);
    }

    public function camposEventos() {
        return $this->select(
                        "id," .
                        " activity_id," .
                        " name," .
                        " m," .
                        " f," .
                        " total"
        );
    }
//    pendiente que los de la actividad sean el total con distinct contacts

    public function event($id = null) {
        if ($id)
            return $this->andFilterWhere(['=', 'id', $id]);
        return $this->andWhere('1 = 1');
    }

    public function totalDesc() {
        return $this->orderBy(['total' => SORT_DESC]);
    }

    public function rango($desde, $hasta) {
        if ($desde && $hasta)
            return $this->andFilterWhere(['>=', 'start', $desde])->andFilterWhere(['<=', 'start', $hasta]);
        return $this->andWhere('1 = 1');
    }

}
