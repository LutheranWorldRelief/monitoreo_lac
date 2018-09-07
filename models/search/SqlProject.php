<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SqlProject as SqlProjectModel;

/**
 * SqlProject represents the model behind the search form of `app\models\SqlProject`.
 */
class SqlProject extends SqlProjectModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'goal_men', 'goal_women', 'h', 'm', 't'], 'integer'],
            [['name', 'code', 'logo', 'colors', 'url', 'start', 'end', 'countries'], 'safe'],
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
        $query = SqlProjectModel::find();

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
            'start' => $this->start,
            'end' => $this->end,
            'goal_men' => $this->goal_men,
            'goal_women' => $this->goal_women,
            'h' => $this->h,
            'm' => $this->m,
            't' => $this->t,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'colors', $this->colors])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'countries', $this->countries]);

        return $dataProvider;
    }
}
