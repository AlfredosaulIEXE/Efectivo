<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Note as NoteModel;

/**
 * Note represents the model behind the search form about `\livefactory\models\Note`.
 */
class Note extends NoteModel
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'entity_id', 'added_at', 'updated_at'], 'integer'],
            [['notes', 'entity_type', 'note_type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = NoteModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'entity_id' => $this->entity_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'entity_type', $this->entity_type])
			->andFilterWhere(['like', 'note_type', $this->note_type]);

        return $dataProvider;
    }
}
