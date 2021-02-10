<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Queue as QueueModel;
use livefactory\models\QueueUsers as QueueUsersModel;

/**
 * Queue represents the model behind the search form about `livefactory\models\Queue`.
 */
class Queue extends QueueModel
{
    public function rules()
    {
        return [
            [['id', 'queue_supervisor_user_id','department_id'], 'integer'],
            [['queue_title', 'active'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QueueModel::find()->orderBy('queue_title');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'department_id' => $this->department_id,
            'queue_supervisor_user_id' => $this->queue_supervisor_user_id,
        ]);

        $query->andFilterWhere(['like', 'queue_title', $this->queue_title])
            ->andFilterWhere(['like', 'active', $this->active]);

        return $dataProvider;
    }
	
	public function searchQueueUser($params, $entity_id)

	{

		$query = QueueUsersModel::find ()->where ( [

				'queue_id' => $entity_id 

		] );

		

		$dataProvider = new ActiveDataProvider ( [ 

				'query' => $query 

		] );

		

		if (! ($this->load ( $params ) && $this->validate ()))

		{

			return $dataProvider;

		}

		

		return $dataProvider;

	}
	public static  function getQueueUsers($entity_id)

	{

		$dataProvider = QueueUsersModel::find ()->where ( [

				'queue_id' => $entity_id 

		] )->asArray()->all();

		

		return $dataProvider;

	}
}
