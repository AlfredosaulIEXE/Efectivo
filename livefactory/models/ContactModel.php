<?php



namespace livefactory\models;

use livefactory\models\Contact;
use livefactory\models\Customer;
use livefactory\models\Lead;

use Yii;

use yii\filters\VerbFilter;

use yii\db\Query;

class ContactModel extends \yii\db\ActiveRecord

{

	/**

     * @inheritdoc

     */

    public static function tableName()

    {

        return '';

    }

	public static function contactInsert($entity_id,$entity_type){

		// add contact
		$contact = new Contact();
		$contact->first_name = $_REQUEST['first_name'];
		$contact->last_name = $_REQUEST['last_name'];
		$contact->middle_name = $_REQUEST['middle_name'];
		$contact->mobile = $_REQUEST['mobile'];
		$contact->email = $_REQUEST['email'];
		$contact->phone = $_REQUEST['phone'];
		$contact->phone_ext = $_REQUEST['phone_ext'];
		$contact->fax = $_REQUEST['fax'];
		$contact->entity_id=$entity_id;
		$contact->entity_type=$entity_type;
		$contact->added_at=strtotime(date('Y-m-d H:i:s'));
		$contact->save();

		return $contact->id;
	}

	public static function contactUpdate($id){

		$first_name=!empty($_REQUEST['first_name'])?$_REQUEST['first_name']:null;
		$last_name=!empty($_REQUEST['last_name'])?$_REQUEST['last_name']:null;
		$middle_name =!empty($_REQUEST['middle_name'])?$_REQUEST['middle_name']:null;
		$mobile=!empty($_REQUEST['mobile'])?$_REQUEST['mobile']:null;
		$email=!empty($_REQUEST['email'])?$_REQUEST['email']:null;
		$phone=!empty($_REQUEST['phone'])?$_REQUEST['phone']:null;
		$phone_ext=!empty($_REQUEST['phone_ext'])?$_REQUEST['phone_ext']:null;
		$fax=!empty($_REQUEST['fax'])?$_REQUEST['fax']:null;
		$updated_at=strtotime(date('Y-m-d H:i:s'));

		// find contact
		$model = Contact::findOne($id);

		$old_email = $model->email;

		$model->first_name = $first_name;
		$model->last_name = $last_name;
		$model->middle_name = $middle_name;
		$model->mobile = $mobile;
		$model->email = $email;
		$model->phone = $phone;
		$model->phone_ext = $phone_ext;
		$model->fax = $fax;
		$model->updated_at = $updated_at;

		$model->save();
		
		if($model->is_primary == 1 || $model->is_primary == '1')
				{
					// update lead or customer
					if($model->entity_type == 'customer')
					{
						$customer_model = Customer::findOne($model->entity_id);
						$customer_model->first_name = $model->first_name;
						$customer_model->last_name = $model->last_name;
						$customer_model->email = $model->email;
						$customer_model->phone = $model->phone;
						$customer_model->mobile = $model->mobile;
						$customer_model->fax = $model->fax;
						$customer_model->save();
					}
					else if($model->entity_type == 'lead')
					{
						$lead_model = Lead::findOne($model->entity_id);
						$lead_model->first_name = $model->first_name;
						$lead_model->last_name = $model->last_name;
						$lead_model->middle_name = $model->middle_name;
						$lead_model->email = $model->email;
						$lead_model->phone = $model->phone;
						$lead_model->mobile = $model->mobile;
						$lead_model->fax = $model->fax;
						$lead_model->save();
					}
				}

		// find user of this contact
		$user = User::findOne(['email' => $old_email]);

		// if user exists
		if($user)
		{
			// update user
			if($user->email != $_REQUEST['email'])
			{
				$user->email = $_REQUEST['email'];
				$user->save();
			}
		}
		
		return $id;

	}

}

