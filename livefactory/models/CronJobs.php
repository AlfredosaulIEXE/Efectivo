<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_cron_jobs".
 *
 * @property integer $id
 * @property string $cron_job_name
 * @property string $cron_job_description
 * @property string $cron_job_path
 */
class CronJobs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cron_jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cron_job_name', 'cron_job_description', 'cron_job_path'], 'required'],
            [['cron_job_name','cron_job_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cron_job_name' => Yii::t('app', 'Cron Job Name'),
            'cron_job_description' => Yii::t('app', 'Cron Job Description'),
            'cron_job_path' => Yii::t('app', 'Cron Job Path'),
        ];
    }

	public function beforeSave($insert) {
		$this->cron_job_name = Html::encode($this->cron_job_name);
		$this->cron_job_path = Html::encode($this->cron_job_path);
		return parent::beforeSave($insert);
	}
}
