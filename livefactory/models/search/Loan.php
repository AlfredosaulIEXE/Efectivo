<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Loan as LoanModel;

/**
 * LeadSource represents the model behind the search form about `livefactory\models\LeadSource`.
 */
class Loan extends LoanModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['code', 'description'], 'safe'],
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
        	 $query = LoanModel::find()->orderBy('sort_order');
		}else{
			 $query = LoanModel::find();
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

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
