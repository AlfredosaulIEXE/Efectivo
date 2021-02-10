<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Invoice as InvoiceModel;

/**
 * Invoice represents the model behind the search form about `livefactory\models\Invoice`.
 */
class Invoice extends InvoiceModel
{
    public function rules()
    {
        return [
            [['id', 'generated_from_estimation', 'estimation_id', 'customer_id', 'invoice_status_id', 'linked_to_project', 'project_id', 'currency_id', 'discount_type_id', 'active', 'created_by_user_id', 'added_at', 'updated_at'], 'integer'],
            [['invoice_number', 'tax_number', 'date_created', 'date_due', 'po_number', 'notes'], 'safe'],
            [['sub_total', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total', 'total_paid'], 'number'],
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
			$query = InvoiceModel::find()
				 ->andwhere (['=', 'customer_id', Yii::$app->user->identity->entity_id]);
		}
		else
		{
			$query = InvoiceModel::find();
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
            'defaultOrder' => [
                'added_at' => SORT_DESC
            ]
        ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'generated_from_estimation' => $this->generated_from_estimation,
            'estimation_id' => $this->estimation_id,
            //'date_created' => $this->date_created,
            //'date_due' => $this->date_due,
            'customer_id' => $this->customer_id,
            'linked_to_project' => $this->linked_to_project,
            'project_id' => $this->project_id,
            'currency_id' => $this->currency_id,
            'sub_total' => $this->sub_total,
            'discount_type_id' => $this->discount_type_id,
            'discount_figure' => $this->discount_figure,
            'discount_amount' => $this->discount_amount,
            'total_tax_amount' => $this->total_tax_amount,
            'grand_total' => $this->grand_total,
            'total_paid' => $this->total_paid,
            'active' => $this->active,
            'created_by_user_id' => $this->created_by_user_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
			->andFilterWhere(['like', 'tax_number', $this->tax_number])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'invoice_status_id', $this->invoice_status_id])
            ->andFilterWhere(['like', 'notes', $this->notes]);

		if($this->date_created)
			$query->andFilterWhere(['between', 'date_created', strtotime($this->date_created), strtotime($this->date_created)+24*60*60]);

		if($this->date_due)
			$query->andFilterWhere(['between', 'date_due', strtotime($this->date_due), strtotime($this->date_due)+24*60*60]);

        return $dataProvider;
    }
}
