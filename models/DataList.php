<?php

namespace app\models;

use Mpdf\Tag\Q;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%data_list}}".
 *
 * Check the base class at app\models\base\DataList in order to
 * see the column names and relations.
 */
class DataList extends base\DataList
{

    public static function itemsBySlug($slug, $label = 'name', $id = 'value', $order = " DESC")
    {
        $countries = (new Query())->select(['id', $label])
            ->from('country')
            ->orderBy($label . $order)
            ->orderBy([$label => SORT_ASC,])
            ->all();

        return ArrayHelper::map($countries, 'id', $label);
    }

    public static function itemsBySlugParticipante($slug, $label = 'name', $id = 'value', $order = " DESC")
    {
        $participantes = (new Query())->select(['id', $label])
            ->from('monitoring_contacttype')
            ->orderBy($label . $order)
            ->orderBy([$label => SORT_ASC,])
            ->all();

        return ArrayHelper::map($participantes, 'id', $label);
    }

    public static function idItemBySlug($slug, $name)
    {
        $model = DataList::find()->where(['slug' => $slug])->one();
        if (!$model)
            return null;
        $item = DataList::find()->andWhere(['data_list_id' => $model->id, 'name' => $name])->one();

        if ($item)
            return $item->id;
        return null;
    }

    public static function CreateItem($slug, $name)
    {
        $model = DataList::find()->where(['slug' => $slug])->one();
        if (!$model) {
            $model = new DataList();
            $model->name = $slug;
            $model->slug = $slug;
            $model->save();
        }
        $item = new DataList();
        $item->data_list_id = $model->id;
        $item->name = $name;
        $item->validate();
        if ($item->save())
            return $item->id;
        return null;
    }

    public static function CountryName($id)
    {
        $country = (new Query())->select('name')
            ->from('country')
            ->orwhere(['id' => $id])
            ->one();

        if (is_array($country))
            return $country['name'];

        return null;
    }

    public static function CountryCode($name)
    {
        $country = (new Query())->select('id')
            ->from('country')
            ->orwhere(['name' => $name])
            ->orWhere(['name_es' => $name])
            ->one();

        if (is_array($country))
            return $country['id'];

        return null;
    }

    public static function CountriesCode()
    {
        $countries = (new Query())->select('id', 'name')
            ->from('country');

        return ArrayHelper::map($countries, 'id', 'name');
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge(
            $rules,
            [
                [['name'], 'required'],
            ]
        );
    }

    public function search($params)
    {
        $query = DataList::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        $query
            ->andFilterWhere(['value' => $this->value])
            ->andFilterWhere(['data_list_id' => $this->data_list_id])
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $provider;
    }

    public function delete()
    {
        $deleted = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->data_list_id > 0) {
                $pais = self::find()->where(['id' => $this->data_list_id, 'slug' => 'countries'])->one();
                if ($pais) {
                    $contact = Contact::find()->where(['country' => $this->value])->one();
                    if ($contact)
                        throw new Exception("Se tienen registros asociados");
                }
            }

            if ($this->dataLists) {
                foreach ($this->dataLists as $key => $m) {
                    $deleted = $m->delete();
                    if (!$deleted) break;
                }
            }

            if (!$deleted)
                throw new Exception(Yii::t('app', 'No se lograron eliminar los registros asociados'));

            $deleted = parent::delete();

            if (!$deleted)
                throw new Exception(Yii::t('app', 'No se logró eliminar el registro'));

            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            echo $e;

        } catch (Throwable $e) {
            $transaction->rollBack();
            echo $e;
        }

        return $deleted;
    }

    public function beforeValidate()
    {
        $valid = parent::beforeValidate(); // TODO: Change the autogenerated stub
        if ($this->data_list_id > 0) {
            $pais = self::find()->where(['id' => $this->data_list_id, 'slug' => 'countries'])->one();
            if ($pais) {
                if (empty($this->value)) {
                    $this->addError('value', Yii::t('app', 'Debe agregar el código corto de país de 2 letras en el campo value'));
                    $valid = false;

                }

                if (!(strlen($this->value) == 2)) {
                    $tamanio = strlen($this->value);
                    $this->addError('value', Yii::t('app', 'Debe agregar el código corto de país de 2 letras en el campo value, actualmente tiene $tamanio caracteres'));
                    $valid = false;
                }

                $valid &= $this->validaDetalleValueUnico('countries', $this->value, $this->id);

            }
        }
        return $valid;
    }

    public function validaDetalleValueUnico($slug, $value, $id)
    {
        $valid = true;
        $model = self::find()->leftJoin('data_list l', 'data_list.data_list_id = l.id')
            ->where(['l.slug' => $slug, 'data_list.value' => $value]);
        if (!is_null($id))
            $model->andWhere("data_list.id not in ($id)");
        $model = $model->one();
        if ($model) {
            $pais = $model->name;
            $this->addError('value', Yii::t('app', 'Debe agregar el código corto único de país en el campo value, actualmente este código es del país $pais'));
            $valid = false;
        }
        return $valid;
    }

}
