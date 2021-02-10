<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_announcement".
 *
 * @property integer $id
 * @property string $message
 * @property integer $user_type_id
 * @property integer $is_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Announcement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_announcement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'is_status', 'created_at', 'created_by'], 'required'],
            [['user_type_id', 'is_status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'user_type_id' => Yii::t('app', 'User Type'),
            'is_status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
	
	public function getUserType()
	{
		return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
	}
	
	public function getUsername()
	{
		return $this->hasOne(User::className(), ['id' => 'created_by']);
	}

	public function beforeSave($insert) {
		$this->message = Html::encode($this->message);
		return parent::beforeSave($insert);
	}
}
