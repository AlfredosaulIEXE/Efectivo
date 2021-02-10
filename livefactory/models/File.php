<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_file".
 *
 * @property integer $id
 * @property string $file_name
 * @property string $file_title
 * @property string $file_type
 * @property string $file_path
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $added_at
 * @property integer $updated_at
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'file_title', 'file_type', 'file_path', 'entity_id', 'entity_type'], 'required'],
            [['entity_id', 'added_at', 'updated_at','added_by_user_id'], 'integer'],
            [['file_name', 'file_title', 'file_type', 'file_path', 'entity_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_title' => Yii::t('app', 'File Title'),
            'file_type' => Yii::t('app', 'File Type'),
            'file_path' => Yii::t('app', 'File Path'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
			'added_by_user_id' => Yii::t('app', 'Added By'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'added_by_user_id']);
    }

	public function beforeSave($insert) {
		$this->file_name = Html::encode($this->file_name);
		$this->file_title = Html::encode($this->file_title);
		$this->file_type = Html::encode($this->file_type);
		//$this->file_path = Html::encode($this->file_path);
		//$this->entity_type = Html::encode($this->entity_type);
		return parent::beforeSave($insert);
	}

	public function afterDelete()
	{
		$file_extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
		unlink(Yii::$app->getBasePath()."\\attachments\\".$this->id.".".$file_extension);
		return parent::afterDelete();
	}
}
