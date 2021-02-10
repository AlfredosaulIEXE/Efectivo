<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\History as HistoryModel;
use yii\db\Query;

/**
 * History represents the model behind the search form about `\livefactory\models\History`.
 */
class History extends HistoryModel
{
    public function rules()
    {
        return [
            [['id', 'entity_id'], 'integer'],
            //[['code'], 'string'],
            [['notes', 'user_id', 'entity_type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $start = isset($params['start']) ? $params['start'] : '';
        $end = isset($params['end']) ? $params['end'] : '';
        $office = isset($params['office_id']) ? $params['office_id'] : '';
        $agent = isset($params['agent_id']) ? $params['agent_id'] : '';
        $sql_log = '';


        $user = Yii::$app->user->identity->attributes;
        $user_auth = (new Query())->select('*')->from('auth_assignment')->where('user_id = ' . Yii::$app->user->id)->all();
        if ($user_auth[0]['item_name'] == "Sales Manager")
        {
           $sql = "tbl_user.office_id = " . $user['id'];
        }
        else
        {
            $sql = "";
        }
        //  params log
            if ($start != '')
            {
                $start = strtotime($start . "00:00");
                $sql_log = 'tbl_history.added_at >= ' . $start;
            }
            if ($end != '')
            {
                $end = strtotime($end . "23:59");
                $sql_log = $sql_log . ' and tbl_history.added_at <= ' . $end;
            }
            if ($office != '')
            {
                if ($sql_log === ''){
                 $sql_log = ' tbl_office.id = ' . $office ;
                }
                else{
                    $sql_log = $sql_log . ' and tbl_office.id = ' . $office;
                }

            }
            if ($agent != '')
            {
                if ($sql_log === ''){
                    $sql_log = ' tbl_user.id = ' . $agent ;
                }
                else{
                    $sql_log = $sql_log . ' and tbl_user.id = ' . $agent;
                }


            }


        $query = HistoryModel::find()
        //$query->select("tbl_history.*, tbl_office.code , tbl_user.first_name , tbl_user.last_name , tbl_user.middle_name , tbl_history.id,tbl_user.office_id ,tbl_lead.c_control  , tbl_lead.lead_name , tbl_history.notes , tbl_history.added_at")
            //->from('tbl_history')
            ->leftJoin('tbl_user' , 'tbl_user.id = tbl_history.user_id')
            ->leftJoin('tbl_lead' , 'tbl_lead.id = tbl_history.entity_id')
            ->leftJoin('tbl_office' , 'tbl_office.id = tbl_user.office_id')->where($sql);

        //log filter
        if (isset($params['r']) && $params['r'] === 'sales/lead/log')
        {
            $query->andWhere($sql_log);
        }
        //$query->where("entity_type = 'log'");
        $query->orderBy('tbl_history.added_at DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tbl_history.entity_id' => $this->entity_id,
            //'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_history.notes', $this->notes])
            ->andFilterWhere(['tbl_history.user_id' => $this->user_id])
            ->andFilterWhere(['like', 'entity_type', $this->entity_type]);

        return $dataProvider;
    }
    public function searchLoginsurance($params)
    {
        if (isset($params['start']))
        {
//            var_dump('startisset');
            $start = $params['start'] . ' 00:00';
//            var_dump(strtotime($start));
        }
        else
        {
//            var_dump('start');
            $start = date('d-m-Y') . ' 00:00';
//            var_dump(strtotime($start));
        }
        if (isset($params['end']))
        {
            $end = $params['end'] . ' 23:59';
        }
        else{
            $end = date('d-m-Y') . ' 23:59';
        }
        $query = History::find()
            ->leftJoin('tbl_user' , 'tbl_user.id = tbl_history.user_id')
            ->leftJoin('tbl_lead' , 'tbl_lead.id = tbl_history.entity_id')
            ->leftJoin('tbl_office' , 'tbl_office.id = tbl_user.office_id')
            ->leftJoin('auth_assignment','auth_assignment.user_id = tbl_user.id')
            ->where('auth_assignment.item_name = "Insurance" OR auth_assignment.item_name = "Insurance.Customer"')
            ->andWhere('tbl_history.added_at >= ' . strtotime($start) . ' and tbl_history.added_at <= ' . strtotime($end));;
        $query->orderBy('tbl_history.added_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tbl_history.entity_id' => $this->entity_id,
            //'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_history.notes', $this->notes])
            ->andFilterWhere(['tbl_history.user_id' => $this->user_id])
            ->andFilterWhere(['like', 'entity_type', $this->entity_type]);

        return $dataProvider;

    }
    public function searchLogcustomer($params){
        var_dump($params);
        if (isset($params['start']))
        {
//            var_dump('startisset');
            $start = $params['start'] . ' 00:00';
//            var_dump(strtotime($start));
        }
        else
        {
//            var_dump('start');
            $start = date('d-m-Y') . ' 00:00';
//            var_dump(strtotime($start));
        }
        if (isset($params['end']))
        {
            $end = $params['end'] . ' 23:59';
        }
        else{
            $end = date('d-m-Y') . ' 23:59';
        }
        $query = History::find()
            ->leftJoin('tbl_user' , 'tbl_user.id = tbl_history.user_id')
            ->leftJoin('tbl_lead' , 'tbl_lead.id = tbl_history.entity_id')
            ->leftJoin('tbl_office' , 'tbl_office.id = tbl_user.office_id')
            ->leftJoin('auth_assignment','auth_assignment.user_id = tbl_user.id')
            ->where('auth_assignment.item_name = "Customer" OR auth_assignment.item_name = "Customer.Service" OR auth_assignment.item_name = "Customer.Service2" OR auth_assignment.item_name = "Customer.Director"')
            ->andWhere('tbl_history.added_at >= ' . strtotime($start) . ' and tbl_history.added_at <= ' . strtotime($end));
        $query->orderBy('tbl_history.added_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tbl_history.entity_id' => $this->entity_id,
            //'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_history.notes', $this->notes])
            ->andFilterWhere(['tbl_history.user_id' => $this->user_id])
            ->andFilterWhere(['like', 'entity_type', $this->entity_type]);

        return $dataProvider;


    }
	public function searchSessionActivities($params){

		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

		$start =$_GET['start'];

		$end =$_GET['end'] == '0'?time():$_GET['end'];
		
		$session_id=$_GET['session_id'];

		//var_dump($start);

		//var_dump($end);

		$query = HistoryModel::find()->where("user_id =$_GET[id] and added_at >='$start' and added_at <='$end' and session_id='$session_id'")->orderBy('id DESC');

		

		$dataProvider = new ActiveDataProvider ( [ 

				'query' => $query 

		] );

		

		if (! ($this->load ( $params ) && $this->validate ()))

		{

			return $dataProvider;

		}

		

		return $dataProvider;

		

	}

	public static function getUserActivities($id){

		return HistoryModel::find()->where("user_id=$id")->asArray()->orderBy('added_at desc')->all();

	}
}
