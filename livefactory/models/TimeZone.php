<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_time_zone".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $zone
 * @property integer $added_at
 */
class TimeZone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_time_zone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'zone', 'added_at'], 'required'],
            [['category_id', 'added_at'], 'integer'],
            [['zone'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'zone' => Yii::t('app', 'Zone'),
            'added_at' => Yii::t('app', 'Added At'),
        ];
    }
}
