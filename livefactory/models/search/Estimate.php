<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Estimate as EstimateModel;

/**
 * Estimate represents the model behind the search form about `livefactory\models\Estimate`.
 */
class Estimate extends EstimateModel
{
    public function rules()
    {
        return [
            [['id', 'customer_id', 'currency_id', 'discount_type_id', 'active', 'added_at', 'updated_at', 'estimate_status_id'], 'integer'],
            [['estimation_code', 'entity_type', 'date_issued', 'po_number', 'notes'], 'safe'],
            [['sub_total', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			$query = EstimateModel::find()
				 ->andwhere (['=', 'customer_id', Yii::$app->user->identity->entity_id]);
		}
		else
		{
			$query = EstimateModel::find();
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'estimation_code' => $this->estimation_code,
            'date_issued' => $this->date_issued,
			'entity_type' => $this->entity_type,
            'customer_id' => $this->customer_id,
            'currency_id' => $this->currency_id,
			'estimate_status_id' => $this->estimate_status_id,
            'sub_total' => $this->sub_total,
            'discount_type_id' => $this->discount_type_id,
            'discount_figure' => $this->discount_figure,
            'discount_amount' => $this->discount_amount,
            'total_tax_amount' => $this->total_tax_amount,
            'grand_total' => $this->grand_total,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }

	 public function searchWithEntityAndID($params, $id, $entity)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			$query = EstimateModel::find()
				 ->andwhere (['=', 'customer_id', Yii::$app->user->identity->entity_id])
				->andwhere(['=', 'entity_type', $entity]);
		}
		else
		{
			$query = EstimateModel::find()->where('customer_id ='.$id .' and entity_type="'.$entity.'"');
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
            'defaultOrder' => [
                'date_issued' => SORT_DESC
            ]
        ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'estimation_code' => $this->estimation_code,
            'date_issued' => $this->date_issued,
			'entity_type' => $this->entity_type,
            'customer_id' => $this->customer_id,
            'currency_id' => $this->currency_id,
			'estimate_status_id' => $this->estimate_status_id,
            'sub_total' => $this->sub_total,
            'discount_type_id' => $this->discount_type_id,
            'discount_figure' => $this->discount_figure,
            'discount_amount' => $this->discount_amount,
            'total_tax_amount' => $this->total_tax_amount,
            'grand_total' => $this->grand_total,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }

	public function searchWithEntity($entity,$params)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			$query = EstimateModel::find()
				 ->andwhere (['=', 'customer_id', Yii::$app->user->identity->entity_id])
				->andwhere(['=', 'entity_type', $entity]);
		}
		else
		{
			$query = EstimateModel::find()->where('entity_type="'.$entity.'"');
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
            'defaultOrder' => [
                'date_issued' => SORT_DESC
            ]
        ],
        ]);
		if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'estimation_code' => $this->estimation_code,
            //'date_issued' => $this->date_issued,
			//'entity_type' => $this->entity_type,
            'customer_id' => $this->customer_id,
            'currency_id' => $this->currency_id,
			'estimate_status_id' => $this->estimate_status_id,
            'sub_total' => $this->sub_total,
            'discount_type_id' => $this->discount_type_id,
            'discount_figure' => $this->discount_figure,
            'discount_amount' => $this->discount_amount,
            'total_tax_amount' => $this->total_tax_amount,
            'grand_total' => $this->grand_total,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'notes', $this->notes]);
			
		if($this->date_issued)
			$query->andFilterWhere(['between', 'date_issued', strtotime($this->date_issued), strtotime($this->date_issued)+24*60*60]);	

        return $dataProvider;
    
    }
}
