<?php


namespace livefactory\models\search;

use livefactory\models\UnitGenerate as UnitModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UnitGenerate extends UnitModel
{
    public function rules()
    {
        return [
            [['id','active'], 'integer'],
            [['name', 'description'], 'string']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search(){

        $query = UnitModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ]);

        return $dataProvider;
    }

}