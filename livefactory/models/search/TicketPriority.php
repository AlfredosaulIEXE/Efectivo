<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TicketPriority as TicketPriorityModel;

/**
 * TicketPriority represents the model behind the search form about `livefactory\models\TicketPriority`.
 */
class TicketPriority extends TicketPriorityModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
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
        //$query = TicketPriorityModel::find();
		if(empty($_GET['sort'])){
        	 $query = TicketPriorityModel::find()->orderBy('sort_order');
		}else{
			$query = TicketPriorityModel::find();
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
