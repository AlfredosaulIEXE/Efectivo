<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\ProjectSource as ProjectSourceModel;

/**
 * ProjectSource represents the model behind the search form about `livefactory\models\ProjectSource`.
 */
class ProjectSource extends ProjectSourceModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'added_at','sort_order', 'updated_at'], 'integer'],
            [['source', 'label'], 'safe'],
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
        	 $query = ProjectSourceModel::find()->orderBy('sort_order');
		}else{
			 $query = ProjectSourceModel::find();
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

        $query->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
