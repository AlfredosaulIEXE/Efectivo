<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TicketResolution as TicketResolutionModel;

/**
 * TicketResolution represents the model behind the search form about `livefactory\models\TicketResolution`.
 */
class TicketResolution extends TicketResolutionModel
{
    public function rules()
    {
        return [
            [['id', 'resolved_by_user_id'], 'integer'],
			[['resolution_number'], 'string'],
            [['subject', 'resolution', 'added_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TicketResolutionModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'resolved_by_user_id' => $this->resolved_by_user_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'resolution', $this->resolution])
			->andFilterWhere(['like', 'resolution_number', $this->resolution_number]);

        return $dataProvider;
    }

	public function searchResolutions($id)
    {
        $query = TicketResolutionModel::find()->where('id in (select resolution_id from tbl_resolution_reference where ticket_id='.$id.')');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

	public function searchNotAddedResolutions($id)
    {
        $query = TicketResolutionModel::find()->where('id not in (select resolution_id from tbl_resolution_reference where ticket_id='.$id.')');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       
        return $dataProvider;
    }
}


