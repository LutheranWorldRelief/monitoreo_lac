<?php

namespace app\models\search;

use app\models\Event as EventModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Event represents the model behind the search form about `app\models\Event`.
 */
class Event extends EventModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'structure_id', 'country_id', 'organization_id'], 'integer'],
            [['name', 'title', 'organizer', 'text', 'start', 'end', 'place', 'notes'], 'safe'],
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
        $query = EventModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'start' => SORT_DESC
                ]
            ],
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
            'structure_id' => $this->structure_id,
            'country_id' => $this->country_id,
            'organization_id' => $this->organization_id,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'organizer', $this->organizer])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
