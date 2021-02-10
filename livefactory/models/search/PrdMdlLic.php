<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\PrdMdlLic as PrdMdlLicModel;

/**
 * PrdMdlLic represents the model behind the search form about `livefactory\models\PrdMdlLic`.
 */
class PrdMdlLic extends PrdMdlLicModel
{
    public function rules()
    {
        return [
            [['id', 'prd_lic_id', 'prd_mdl_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PrdMdlLicModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'prd_lic_id' => $this->prd_lic_id,
            'prd_mdl_id' => $this->prd_mdl_id,
        ]);

        return $dataProvider;
    }
	public function searchPrdMdls($params,$entity_id)
    {
        $query = PrdMdlLicModel::find()->joinWith('module')->orderBy('tbl_prd_mdl.mdl_name')->where("prd_lic_id =".$entity_id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'prd_lic_id' => $this->prd_lic_id,
            'prd_mdl_id' => $this->prd_mdl_id,
        ]);

        return $dataProvider;
    }
}
