<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_customer".
 *
 * @property integer $id
 * @property string $customer_name
 * @property integer $customer_type_id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property integer $added_at
 * @property integer $updated_at
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_name' ,'customer_owner_id', 'email', 'first_name', 'last_name', 'customer_type_id'], 'required'],
			[['email'], 'email', 'message' => 'Please enter a valid email address <i class="fa fa-envelope" aria-hidden="true"></i>'],
            [['customer_type_id', 'added_at','customer_owner_id', 'updated_at'], 'integer'],
			[['mobile'],'integer', 'message'=>'Please enter valid 10 digit mobile no <i class="fa fa-mobile" aria-hidden="true"></i>'],
            [['customer_name', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'fax'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_name' => Yii::t('app', 'Customer Name'),
			'customer_owner_id' => Yii::t('app', 'Customer Owner'),
            'customer_type_id' => Yii::t('app', 'Customer Type'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'fax' => Yii::t('app', 'Fax'),
            'contact_id' => Yii::t('app', 'Contact'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getCustomerType()
    {
    	return $this->hasOne(CustomerType::className(), ['id' => 'customer_type_id']);
    }
	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'customer_owner_id']);
    }

	public function beforeSave($insert) {
		$this->customer_name = Html::encode($this->customer_name);
		$this->email = Html::encode($this->email);
		$this->first_name = Html::encode($this->first_name);
		$this->last_name = Html::encode($this->last_name);
		$this->phone = Html::encode($this->phone);
		$this->mobile = Html::encode($this->mobile);
		$this->fax = Html::encode($this->fax);
		return parent::beforeSave($insert);
	}

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\customers\\".$this->id.".png";
		$file2 = Yii::$app->getBasePath()."\\attachments\\customer_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}
		if(file_exists($file2))
		{
			unlink($file2);
		}

		/*Delete Contacts */
		foreach (Contact::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Addresses */
		foreach (Address::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}
		
		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}
		
		/*Delete Estimates */
		if(in_array('estimate',Yii::$app->params['modules']))
		{
			foreach (Estimate::find()->where(['customer_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
			{
				$record->delete();
			}
		}

		/*Delete Invoice */
		if(in_array('invoice',Yii::$app->params['modules']))
		{
			foreach (Invoice::find()->where(['customer_id'=> $this->id])->all() as $record) 
			{
				$record->delete();
			}
		}

		/*Delete Projects */
		if(in_array('pmt',Yii::$app->params['modules']))
		{
			foreach (Project::find()->where(['customer_id'=> $this->id])->all() as $record) 
			{
				$record->delete();
			}
		}

		/*Delete Tickets */
		if(in_array('support',Yii::$app->params['modules']))
		{
			foreach (Ticket::find()->where(['ticket_customer_id'=> $this->id])->all() as $record) 
			{
				$record->delete();
			}
		}

		/*Delete Users */
		foreach (User::find()->where(['entity_id'=> $this->id, 'entity_type' => 'customer'])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}
