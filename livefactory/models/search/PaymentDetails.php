<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\PaymentDetails as PaymentDetailsModel;

/**
 * PaymentDetails represents the model behind the search form about `livefactory\models\PaymentDetails`.
 */
class PaymentDetails extends PaymentDetailsModel
{
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'added_at', 'updated_at','status'], 'integer'],
            [['payment_date', 'payment_method', 'notes'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentDetailsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'payment_date' => $this->payment_date,
            'amount' => $this->amount,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }

	public function searchWithInvoiceID($id)
    {
        $query = PaymentDetails::find()->where('invoice_id="'.$id.'"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

       
            return $dataProvider;
    
    }
}
