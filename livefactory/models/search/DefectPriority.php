<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\DefectPriority as DefectPriorityModel;

/**
 * DefectPriority represents the model behind the search form about `livefactory\models\DefectPriority`.
 */
class DefectPriority extends DefectPriorityModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'added_at','sort_order', 'updated_at'], 'integer'],
            [['priority', 'label'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		if(empty($_GET['sort'])){
        	$query = DefectPriorityModel::find()->orderBy('sort_order');
		}else{
			$query = DefectPriorityModel::find();
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

        $query->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}