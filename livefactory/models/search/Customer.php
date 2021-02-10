<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Customer as CustomerModel;
use livefactory\models\Ticket;
use livefactory\models\Project;

/**
 * Customer represents the model behind the search form about `\livefactory\models\Customer`.
 */
class Customer extends CustomerModel
{
    public function rules()
    {
        return [
            [['id', 'customer_type_id','customer_owner_id', 'added_at', 'updated_at'], 'integer'],
            [['customer_name', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'fax'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerModel::find();

		/*if(empty($_GET['sort'])){
        	 $query = CustomerModel::find()->orderBy('customer_name');
		}else{
			 $query = CustomerModel::find();
		}*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_type_id' => $this->customer_type_id,
			'customer_owner_id' => $this->customer_owner_id,
            //'contact_id' => $this->contact_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'fax', $this->fax]);

        return $dataProvider;
    }
	public function searchProject($params,$customer_id)

    {

        $query = ProjectModel::find()->where("customer_id=$customer_id");



        $dataProvider = new ActiveDataProvider([

            'query' => $query,

        ]);



        



        return $dataProvider;

    }
	public function searchAddresses($params, $entity_id)

	{

		$sql ="select tbl_city.city,tbl_country.country,tbl_state.state,tbl_address.*,tbl_customer_addresses.* from tbl_city,tbl_country,tbl_state,tbl_address,tbl_customer_addresses where tbl_city.id=tbl_address.city_id and tbl_state.id=tbl_address.state_id and tbl_country.id=tbl_address.country_id  and tbl_address.entity_id=$entity_id and tbl_address.is_primary='1' and tbl_address.entity_type='customer'";

			$connection = \Yii::$app->db;

			$command=$connection->createCommand($sql);

			$dataReader=$command->queryAll();

		

		return $dataReader;

	}

	public function searchCustomer(){

		$sql ="select 			

					tbl_city.city,

					tbl_country.country,

					tbl_state.state,

					tbl_address.*,

					tbl_customer.*,

					tbl_customer_type.type 

				from 
					tbl_customer

					 
					LEFT JOIN tbl_customer_type
					ON tbl_customer.customer_type_id=tbl_customer_type.id
					
					LEFT JOIN tbl_address
					ON tbl_address.entity_id=tbl_customer.id and tbl_address.is_primary='1'
					
					LEFT JOIN tbl_country
					ON tbl_country.id=tbl_address.country_id
					
					LEFT JOIN tbl_state
					ON tbl_state.id=tbl_address.state_id
					
					LEFT JOIN tbl_city
					ON tbl_city.id=tbl_address.city_id
					where tbl_address.entity_type='customer'";

					

			$connection = \Yii::$app->db;

			$command=$connection->createCommand($sql);

			$dataReader=$command->queryAll();

		

		return $dataReader;

	}
	public function searchProjects($params, $customer_id)

	{

		$query = Project::find ()->where ( [

				'customer_id' => $customer_id 

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
	public function searchTickets($params, $customer_id)

	{

		$query = Ticket::find ()->where ( [

				'ticket_customer_id' => $customer_id 

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
}
