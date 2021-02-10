<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "lot_task".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $last_logged
 * @property integer $logged_out
 * @property integer $logged_in
 * @property string $location_ip
 * @property string $session_id
 */
class SessionDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_session_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id',  'session_id'], 'required'],
            [['session_id','location_ip'], 'string'],
            [['logged_out', 'logged_in','last_logged'], 'safe'],
            [['user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'last_logged' => Yii::t('app', 'Last Logged'),
            'logged_out' => Yii::t('app', 'Logged Out'),
            'logged_in' => Yii::t('app', 'Logged In'),
            'location_ip' => Yii::t('app', 'Location'),
            'session_id' => Yii::t('app', 'Session Id'),
            'user_id' => Yii::t('app', 'User'),
        ];
    }
	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
