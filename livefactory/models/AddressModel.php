<?php

namespace livefactory\models;
use livefactory\models\Address;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class AddressModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
	public static function addressInsert($entity_id,$entity_type) {
		$addAddress = new Address();
		$addAddress->address_1=$_REQUEST['address_1'];
		$addAddress->address_2=$_REQUEST['address_2'];
		$addAddress->num_ext=$_REQUEST['num_ext'];
		$addAddress->num_int=$_REQUEST['num_int'];
		$addAddress->block=mb_strtoupper($_REQUEST['block']);
		$addAddress->country_id=$_REQUEST['country_id'] ? $_REQUEST['country_id'] : 156;
		$addAddress->state_id=$_REQUEST['state_id'] ? $_REQUEST['state_id'] : 0;
		$addAddress->city_id=$_REQUEST['city_id'];
		$addAddress->delegation=mb_strtoupper($_REQUEST['delegation']);
		$addAddress->zipcode=$_REQUEST['zipcode'];
		$addAddress->entity_id = $entity_id;
		$addAddress->entity_type = $entity_type;
		$addAddress->is_primary = $entity_type == 'lead' ? 1 : 0;
		$addAddress->added_at=strtotime(date('Y-m-d H:i:s'));
		$addAddress->save();
		$aid=$addAddress->id;
		return $aid;
	}
	public static function subAddressInsert($entity_id,$entity_type) {
		$addAddress = new Address();
		$addAddress->address_1=$_REQUEST['sub_address_1'];
		$addAddress->address_2=$_REQUEST['sub_address_2'];
		$addAddress->country_id=$_REQUEST['sub_country_id'];
		$addAddress->state_id=$_REQUEST['sub_state_id'];
		$addAddress->city_id=$_REQUEST['sub_city_id'];
		$addAddress->zipcode=$_REQUEST['sub_zipcode'];
		$addAddress->entity_id=$entity_id;
		$addAddress->entity_type=$entity_type;
		$addAddress->added_at=strtotime(date('Y-m-d H:i:s'));
		$addAddress->save();
		$aid=$addAddress->id;
		return $aid;
	}
	public static function subAddressUpdate($id) {
		$editAddress = Address::findOne($id);
		$editAddress->address_1=$_REQUEST['sub_address_1'];
		$editAddress->address_2=$_REQUEST['sub_address_2'];
		$editAddress->country_id=$_REQUEST['sub_country_id'];
		$editAddress->state_id=$_REQUEST['sub_state_id'];
		$editAddress->city_id=$_REQUEST['sub_city_id'];
		$editAddress->zipcode=$_REQUEST['sub_zipcode'];
		$editAddress->updated_at=strtotime(date('Y-m-d H:i:s'));
		$editAddress->update();
		$aid=$editAddress->id;
		return $aid;
	}
	public static function addressUpdate($id) {
		$editAddress = Address::findOne($id);
		$editAddress->address_1=!empty($_REQUEST['address_1'])?$_REQUEST['address_1']:null;
		//$editAddress->address_2=!empty($_REQUEST['address_2'])?$_REQUEST['address_2']:null;
        $editAddress->num_ext=!empty($_REQUEST['num_ext'])?$_REQUEST['num_ext']:null;
        $editAddress->num_int=!empty($_REQUEST['num_int'])?$_REQUEST['num_int']:null;
        $editAddress->block=!empty($_REQUEST['block'])?mb_strtoupper($_REQUEST['block']):null;
		//$editAddress->country_id=!empty($_REQUEST['country_id'])?$_REQUEST['country_id']:null;
		$editAddress->state_id=!empty($_REQUEST['state_id'])?$_REQUEST['state_id']:0;
		$editAddress->city_id=!empty($_REQUEST['city_id'])?$_REQUEST['city_id']:0;
        $editAddress->delegation=!empty($_REQUEST['delegation'])?mb_strtoupper($_REQUEST['delegation']):null;
		$editAddress->zipcode=!empty($_REQUEST['zipcode'])?$_REQUEST['zipcode']:null;
		$editAddress->updated_at=strtotime(date('Y-m-d H:i:s'));
		$editAddress->update();
		$aid=$editAddress->id;
		return $aid;
	}
}
