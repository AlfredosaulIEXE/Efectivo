<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Address as AddressModel;

/**
 * Address represents the model behind the search form about `\livefactory\models\Address`.
 */
class Address extends AddressModel
{
    public function rules()
    {
        return [
            [['id', 'country_id', 'state_id', 'city_id', 'added_at', 'updated_at'], 'integer'],
            [['address_1', 'address_2', 'zipcode'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AddressModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'address_1', $this->address_1])
            ->andFilterWhere(['like', 'address_2', $this->address_2])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode]);

        return $dataProvider;
    }
	public static function companyAddress($id){
		$sql="select tbl_country.country,tbl_state.state,tbl_city.city,tbl_address.* from tbl_city,tbl_country,tbl_state,tbl_address,tbl_company where tbl_country.id=tbl_address.country_id and tbl_address.entity_id='$id' and tbl_address.is_primary='1' and tbl_address.entity_type='user' and tbl_state.id=tbl_address.state_id and tbl_city.id=tbl_address.city_id";

		$connection = \Yii::$app->db;

		$command=$connection->createCommand($sql);

		$dataReader=$command->queryOne();

		return $dataReader;
	}
}
