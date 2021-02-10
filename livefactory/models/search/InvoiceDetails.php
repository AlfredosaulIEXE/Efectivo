<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\InvoiceDetails as InvoiceDetailsModel;

/**
 * InvoiceDetails represents the model behind the search form about `livefactory\models\InvoiceDetails`.
 */
class InvoiceDetails extends InvoiceDetailsModel
{
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'product_id', 'tax_id', 'active', 'added_at', 'updated_at'], 'integer'],
            [['product_description', 'payment_method', 'notes'], 'safe'],
            [['payment_amount', 'rate', 'quantity', 'tax_amount', 'total'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = InvoiceDetailsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'product_id' => $this->product_id,
            'payment_amount' => $this->payment_amount,
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
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
