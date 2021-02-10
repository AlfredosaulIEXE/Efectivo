<?php

namespace livefactory\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TicketCategory;

/**
 * searchTicketCategory represents the model behind the search form about `livefactory\models\TicketCategory`.
 */
class searchTicketCategory extends TicketCategory
{
    public function rules()
    {
        return [
            [['id', 'active', 'parent_id', 'department_id', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['name', 'label', 'description'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TicketCategory::find()->where("parent_id=0")->orderBy('sort_order');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'parent_id' => $this->parent_id,
            'department_id' => $this->department_id,
            'sort_order' => $this->sort_order,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
