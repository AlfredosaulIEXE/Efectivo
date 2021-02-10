<?php

namespace livefactory\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property integer $office_id
 * @property string $username
 * @property string $alias
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $about
 * @property integer $user_type_id
 * @property integer $active
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $added_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;
	const ROLE_USER = 1;
	public $auth_key='';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'email', 'alias'], 'required'],
            [['about','entity_type'], 'string'],
            [['office_id', 'user_type_id', 'active',  'added_at', 'updated_at','entity_id'], 'integer'],
            [['username', 'alias', 'email','password_hash', 'first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
			[[ 'email'],'email'],
			[['username'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'office_id' => Yii::t('app', 'Office'),
            'username' => Yii::t('app', 'Username Code'),
            'alias' => Yii::t('app', 'Alias'),
            'email' => Yii::t('app', 'Email'),
            ///'password' => Yii::t('app', 'Password'),
			'password_hash' => Yii::t('app', 'Password'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'about' => Yii::t('app', 'About'),
            'user_type_id' => Yii::t('app', 'User Type'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added'),
            'updated_at' => Yii::t('app', 'Updated At'),
			'entity_id' => Yii::t('app', 'Customer'),
        ];
    }
	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne ( [ 
				'id' => $id,
				'active' => self::STATUS_ACTIVE 
		] );
	}
	
	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException ( '"findIdentityByAccessToken" is not implemented.' );
	}
	
	/**
	 * Finds user by username
	 *
	 * @param string $username        	
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne ( [ 
				'username' => $username,
				'active' => self::STATUS_ACTIVE 
		] );
	}
	
	/**
	 * Finds user by password reset token
	 *
	 * @param string $token
	 *        	password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (! static::isPasswordResetTokenValid ( $token ))
		{
			return null;
		}
		
		return static::findOne ( [ 
				'password_reset_token' => $token,
				'active' => self::STATUS_ACTIVE 
		] );
	}
	
	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token
	 *        	password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty ( $token ))
		{
			return false;
		}
		$expire = Yii::$app->params ['user.passwordResetTokenExpire'];
		$parts = explode ( '_', $token );
		$timestamp = ( int ) end ( $parts );
		return $timestamp + $expire >= time ();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey ();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getFullName()
	{
		return $this->first_name.' '.$this->last_name;
	}
	
	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey () === $authKey;
	}
	
	/**
	 * Validates password
	 *
	 * @param string $password
	 *        	password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		//return $this->password === $password;
		return Yii::$app->security->validatePassword ( $password, $this->password_hash );
	}
	
	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password        	
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash ($password);
	}

	public function beforeSave($insert)
	{
		if ($this->entity_id == NULL)
		{
			$this->entity_id=0;
		}
		$this->username = mb_strtoupper($this->username);
		$this->first_name = mb_strtoupper($this->first_name);
		$this->last_name = mb_strtoupper($this->last_name);
        $this->middle_name = mb_strtoupper($this->middle_name);
		$this->email = Html::encode($this->email);
		$this->about = Html::encode($this->about);
		return parent::beforeSave ( $insert );
	}

    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }


	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString ();
	}
	
	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString () . '_' . time ();
	}

	
	public function getUserType()
	{
		return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
	}
	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\users\\".$this->id.".png";
		$file2 = Yii::$app->getBasePath()."\\users\\user_".$this->id.".png";
		if(file_exists($file1))
		{
			unlink($file1);
		}
		if(file_exists($file2))
		{
			unlink($file2);
		}

		/*Delete Project Users */
		if(in_array('pmt',Yii::$app->params['modules']))
		{
			foreach (ProjectUser::find()->where(['project_id'=> $this->id])->all() as $record) 
			{
				$record->delete();
			}
		}

		/*Delete Queue Users */
		if(in_array('support',Yii::$app->params['modules']))
		{
			foreach (QueueUsers::find()->where(['queue_id'=> $this->id])->all() as $record) 
			{
				$record->delete();
			}
		}

		return parent::afterDelete();
	}
}
