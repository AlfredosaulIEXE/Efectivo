<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\LeadType as LeadTypeModel;

/**
 * LeadType represents the model behind the search form about `livefactory\models\LeadType`.
 */
class LeadType extends LeadTypeModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['type', 'label'], 'safe'],
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
        	 $query = LeadTypeModel::find()->orderBy('sort_order');
		}else{
			 $query = LeadTypeModel::find();
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

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
