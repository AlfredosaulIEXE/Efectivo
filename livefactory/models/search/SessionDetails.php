<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\SessionDetails as SessionDetailsModel;

/**
 * Task represents the model behind the search form about `livefactory\models\Task`.
 */
class SessionDetails extends SessionDetailsModel
{
	public function rules()
    {
        return [
            /*[['user_id',  'session_id'], 'required'],*/
            [['session_id','location_ip'], 'string'],
            [['logged_out', 'logged_in','last_logged'], 'safe'],
            [['user_id'], 'integer']
        ];
    }
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios ();
	}
	
	public function search($params)
	{
		$query = SessionDetailsModel::find()->orderBy('id DESC');
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		$query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'session_id ' => $this->session_id,
            'location_ip' => $this->location_ip,
            'logged_out' => $this->logged_out,
            'logged_in' => $this->logged_in
        ]);
		if($this->last_logged ==''){
				$query->andFilterWhere([
					'logged_out' => ''
				]);
		}else{
			if($this->last_logged !='')
			$query->andFilterWhere(['not like', 'logged_out','']);
		}
        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'location_ip', $this->location_ip]);
			//var_dump($dataProvider);
		return $dataProvider;
	}
}
