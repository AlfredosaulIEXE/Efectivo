<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_note".
 *
 * @property integer $id
 * @property string $description
 * @property integer $date
 * @property integer $status
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $added_at
 * @property integer $updated_at
 * @property  integer user_id
 */
class Appointment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_appointment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'date', 'time'], 'required'],
            [['description', 'date', 'time'], 'string'],
            [['entity_id', 'status','user_id', 'added_at', 'updated_at','type'], 'integer'],
            [['entity_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Description'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'status' => Yii::t('app', 'Concreted'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($insert) {
        //$this->notes = Html::encode($this->notes);
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLead()
    {
        return $this->hasOne(Lead::className(), ['id' => 'entity_id']);
    }
}
