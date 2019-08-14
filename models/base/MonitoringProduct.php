<?php

namespace app\models\base;

use app\components\ActiveRecord;

class MonitoringProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_es', 'name_fr'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Product'),
            'name_es' => Yii::t('app', 'Producto'),
            'name_fr' => Yii::t('app', 'Produit'),
        ];
    }

    public function getProjectContract()
    {
        return $this->hasOne(\app\models\ProjectContact::className(), ['product_id' => 'id']);
    }


}
