<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_queue_users".
 *
 * @property integer $id
 * @property integer $queue_id
 * @property integer $user_id
 */
class QueueUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_queue_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['queue_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'queue_id' => Yii::t('app', 'Queue ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
	public static  function getUsers($user_id)

	{

		$dataProvider = User::find ()->where ( [

				'id' => $user_id 

		] )->asArray()->all();

		

		return $dataProvider;

	}
}
