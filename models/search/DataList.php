<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DataList as DataListModel;

/**
 * DataList represents the model behind the search form about `app\models\DataList`.
 */
class DataList extends DataListModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'list_id'], 'integer'],
            [['name', 'tag', 'value', 'notes', 'slug'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DataListModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'list_id' => $this->list_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
