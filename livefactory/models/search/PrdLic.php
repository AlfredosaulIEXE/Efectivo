<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\PrdLic as PrdLicModel;

/**
 * PrdLic represents the model behind the search form about `\livefactory\models\PrdLic`.
 */
class PrdLic extends PrdLicModel
{
    public function rules()
    {
        return [
            [['id', 'prd_lic_status', 'added_at', 'updated_at'], 'integer'],
            [['prd_lic_name', 'prd_lic_desc', 'prd_lic_date'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PrdLicModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'prd_lic_status' => $this->prd_lic_status,
            'prd_lic_date' => $this->prd_lic_date,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'prd_lic_name', $this->prd_lic_name])
            ->andFilterWhere(['like', 'prd_lic_desc', $this->prd_lic_desc]);

        return $dataProvider;
    }
}
