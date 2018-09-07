<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contact as ContactModel;

/**
 * Contact represents the model behind the search form of `app\models\Contact`.
 */
class Contact extends ContactModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'organization_id', 'education_id', 'men_home', 'women_home', 'type_id'], 'integer'],
            [['name', 'last_name', 'first_name', 'document', 'title', 'sex', 'community', 'municipality', 'city', 'country', 'phone_personal', 'phone_work', 'created', 'modified'], 'safe'],
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
        $query = ContactModel::find();

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

        $user = Yii::$app->user->identity;
        if (!$user) {
            $user = new AuthUser();
            $user->is_superuser = 0;
        }
        if (!$user->is_superuser)
            $query->andWhere(['in', 'id', $user->getContactAllowed()]);


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'education_id' => $this->education_id,
            'men_home' => $this->men_home,
            'women_home' => $this->women_home,
            'created' => $this->created,
            'modified' => $this->modified,
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'document', $this->document])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'community', $this->community])
            ->andFilterWhere(['like', 'municipality', $this->municipality])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'phone_personal', $this->phone_personal])
            ->andFilterWhere(['like', 'phone_work', $this->phone_work]);

        return $dataProvider;
    }
}
