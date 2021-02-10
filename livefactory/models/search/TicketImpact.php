<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TicketImpact as TicketImpactModel;

/**
 * TicketImpact represents the model behind the search form about `livefactory\models\TicketImpact`.
 */
class TicketImpact extends TicketImpactModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['impact', 'label'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        //$query = TicketImpactModel::find();
		if(empty($_GET['sort'])){
        	 $query = TicketImpactModel::find()->orderBy('sort_order');
		}else{
			$query = TicketImpactModel::find();
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

        $query->andFilterWhere(['like', 'impact', $this->impact])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
