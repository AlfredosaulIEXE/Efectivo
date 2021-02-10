<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\PrdMdl as PrdMdlModel;

/**
 * PrdMdl represents the model behind the search form about `\livefactory\models\PrdMdl`.
 */
class PrdMdl extends PrdMdlModel
{
    public function rules()
    {
        return [
            [['id', 'mdl_status','added_at', 'updated_at'], 'integer'],
            [['mdl_name', 'mdl_desc'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PrdMdlModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'mdl_status' => $this->mdl_status,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mdl_name', $this->mdl_name])
            ->andFilterWhere(['like', 'mdl_desc', $this->mdl_desc]);


        return $dataProvider;
    }
}
