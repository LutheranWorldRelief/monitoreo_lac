<?php

namespace app\models\search;

use app\models\SqlContactEvent as SqlContactEventModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SqlContactEvent represents the model behind the search form of `app\models\SqlContactEvent`.
 */
class SqlContactEvent extends SqlContactEventModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'event_id'], 'integer'],
            [['name', 'country_id', 'org_name', 'type_name', 'event', 'organizer', 'start', 'end', 'place'], 'safe'],
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
        $query = SqlContactEventModel::find();

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
            'contact_id' => $this->contact_id,
            'event_id' => $this->event_id,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'country_id', $this->country_id])
            ->andFilterWhere(['like', 'org_name', $this->org_name])
            ->andFilterWhere(['like', 'type_name', $this->type_name])
            ->andFilterWhere(['like', 'event', $this->event])
            ->andFilterWhere(['like', 'organizer', $this->organizer])
            ->andFilterWhere(['like', 'place', $this->place]);

        return $dataProvider;
    }
}
