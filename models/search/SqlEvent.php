<?php

namespace app\models\search;

use app\models\AuthUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SqlEvent as SqlEventModel;

/**
 * SqlEvent represents the model behind the search form of `app\models\SqlEvent`.
 */
class SqlEvent extends SqlEventModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'structure_id', 'implementing_organization_id', 'country_id', 'h', 'm', 't', 'project_id'], 'integer'],
            [['name', 'title', 'organizer', 'text', 'start', 'end', 'place', 'notes', 'country', 'organization', 'project', 'code', 'structure'], 'safe'],
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
        $query = SqlEventModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'start' => SORT_DESC,
                    'id' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $user = Yii::$app->user->identity;
        if (!$user) {
            $user = new AuthUser();
            $user->is_superuser = 0;
        }
        if (!$user->is_superuser)
            $query->andWhere(['in', 'id', $user->getEventAllowed()]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'structure_id' => $this->structure_id,
            'implementing_organization_id' => $this->implementing_organization_id,
            'start' => $this->start,
            'end' => $this->end,
            'country_id' => $this->country_id,
            'h' => $this->h,
            'm' => $this->m,
            't' => $this->t,
            'project_id' => $this->project_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'organizer', $this->organizer])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'organization', $this->organization])
            ->andFilterWhere(['like', 'project', $this->project])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'structure', $this->structure]);

        return $dataProvider;
    }
}
