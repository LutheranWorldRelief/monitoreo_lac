<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Organization as OrganizationModel;

/**
 * Organization represents the model behind the search form of `app\models\Organization`.
 */
class Organization extends OrganizationModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'organization_type_id', 'organization_id', 'country_id', 'is_implementer'], 'integer'],
            [['name', 'country', 'description'], 'safe'],
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
        $query = OrganizationModel::find();

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
            'organization_type_id' => $this->organization_type_id,
            'organization_id' => $this->organization_id,
            'country_id' => $this->country_id,
            'is_implementer' => $this->is_implementer,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
