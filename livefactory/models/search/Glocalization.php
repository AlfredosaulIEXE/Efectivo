<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Glocalization as GlocalizationModel;

/**
 * Glocalization represents the model behind the search form about `\livefactory\models\Glocalization`.
 */
class Glocalization extends GlocalizationModel
{
    public function rules()
    {
        return [
            [['id', 'added_at', 'updated_at'], 'integer'],
            [['language', 'locale'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = GlocalizationModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'locale', $this->locale]);

        return $dataProvider;
    }
}
