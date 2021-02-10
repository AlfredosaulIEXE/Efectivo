<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\DiscountType as DiscountTypeModel;

/**
 * DiscountType represents the model behind the search form about `livefactory\models\DiscountType`.
 */
class DiscountType extends DiscountTypeModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'added_at', 'updated_at'], 'integer'],
            [['discount_type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DiscountTypeModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'discount_type', $this->discount_type]);

        return $dataProvider;
    }
}
