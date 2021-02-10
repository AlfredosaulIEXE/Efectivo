<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_group_chat".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $message
 * @property integer $sent
 * @property integer $recd
 * @property integer $entity_id
 * @property string $entity_type
 */
class GroupChat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_group_chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message', 'entity_id', 'entity_type'], 'required'],
            [['user_id', 'recd', 'entity_id'], 'integer'],
            [['message'], 'string'],
            [['sent'], 'safe'],
            [['entity_type'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'message' => Yii::t('app', 'Message'),
            'sent' => Yii::t('app', 'Sent'),
            'recd' => Yii::t('app', 'Recd'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
        ];
    }

	public function beforeSave($insert) {
		$this->message = Html::encode($this->message);
		return parent::beforeSave($insert);
	}
}
