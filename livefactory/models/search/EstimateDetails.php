<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\EstimateDetails as EstimateDetailsModel;

/**
 * EstimateDetails represents the model behind the search form about `livefactory\models\EstimateDetails`.
 */
class EstimateDetails extends EstimateDetailsModel
{
    public function rules()
    {
        return [
            [['id', 'estimate_id', 'product_id', 'tax_id', 'active', 'added_at', 'updated_at'], 'integer'],
            [['product_description', 'description'], 'safe'],
            [['rate', 'quantity', 'tax_amount', 'total'], 'number'],
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
        	 $query = EstimateDetailsModel::find()->orderBy('sort_order');
		}else{
			 $query = EstimateDetailsModel::find();
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'estimate_id' => $this->estimate_id,
            'product_id' => $this->product_id,
            'rate' => $this->rate,
            'quantity' => $this->quantity,
            'tax_id' => $this->tax_id,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_description', $this->product_description])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
	public function searchEstimate($params,$entity_id)
    {
        $query = EstimateDetailsModel::find()->where("estimate_id=$entity_id");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'estimate_id' => $this->estimate_id,
            'product_id' => $this->product_id,
            'rate' => $this->rate,
            'quantity' => $this->quantity,
            'tax_id' => $this->tax_id,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_description', $this->product_description])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
