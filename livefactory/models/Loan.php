<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%tbl_loan}}".
 *
 * @property integer $id
 * @property integer $active
 * @property string $code
 * @property string $description
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class Loan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tbl_loan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'description', 'active', 'sort_order'], 'required'],
            [['active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['code', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'description' => Yii::t('app', 'Description'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert) {
        $this->code = Html::encode($this->code);
        $this->description = Html::encode($this->description);
        return parent::beforeSave($insert);
    }
}
