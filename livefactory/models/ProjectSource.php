<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "{{%tbl_project_source}}".
 *
 * @property int $id
 * @property string $source
 * @property string $label
 * @property int $active
 * @property int $sort_order
 * @property int $added_at
 * @property int $updated_at
 */
class ProjectSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tbl_project_source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source', 'label', 'active', 'sort_order'], 'required'],
            [['sort_order', 'added_at', 'updated_at', 'active'], 'integer'],
            [['source', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'source' => Yii::t('app', 'Source'),
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
