<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TicketCategory as TicketCategoryModel;
/**
 * TicketCategory1 represents the model behind the search form about `livefactory\models\TicketCategory1`.
 */
class TicketCategory extends TicketCategoryModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'department_id','parent_id' ,'sort_order', 'added_at', 'updated_at'], 'integer'],
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
       ///// $query = TicketCategory1Model::find();
		if(empty($_GET['sort'])){
        	 $query = TicketCategoryModel::find()->orderBy('sort_order');
		}else{
			$query = TicketCategoryModel::find();
		}
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'department_id' => $this->department_id,
			'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
	public function searchSubCategory($params,$id)
    {
        $query = TicketCategoryModel::find()->where("parent_id=$id")->orderBy('sort_order');
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
