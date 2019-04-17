<?php

namespace app\models;

use Exception;
use Yii;

/**
 * This is the model class for table "{{%filter}}".
 *
 * Check the base class at app\models\base\Filter in order to
 * see the column names and relations.
 */
class Filter extends base\Filter
{
    public function delete()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->filters as $d)
                $d->delete();
            if (parent::delete()) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }

    }

    public function estableceSlug()
    {
        foreach ($this->filters as $d) {
            $d->slug = $this->slug;
            $d->save();
        }
    }

}
