<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Department as DepartmentModel;

/**
 * Department represents the model behind the search form about `livefactory\models\Department`.
 */
class Department extends DepartmentModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'added_at','sort_order', 'updated_at'], 'integer'],
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
       /// $query = DepartmentModel::find();
		if(empty($_GET['sort'])){
        	 $query = DepartmentModel::find()->orderBy('sort_order');
		}else{
			$query = DepartmentModel::find();
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
