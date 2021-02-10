<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Office as OfficeModel;

/**
 * Office represents the model behind the search form about `livefactory\models\Office`.
 */
class Office extends OfficeModel
{
    public function rules()
    {
        return [
            [['id', 'added_at', 'updated_at'], 'integer'],
            [['code', 'rfc', 'business_name'], 'string'],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OfficeModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'rfc' => $this->rfc,
            'business_name' => $this->business_name,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
