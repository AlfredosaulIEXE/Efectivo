<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\CronJobs as CronJobsModel;

/**
 * CronJobs represents the model behind the search form about `livefactory\models\CronJobs`.
 */
class CronJobs extends CronJobsModel
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['cron_job_name', 'cron_job_description', 'cron_job_path'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CronJobsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'cron_job_name', $this->cron_job_name])
            ->andFilterWhere(['like', 'cron_job_description', $this->cron_job_description])
            ->andFilterWhere(['like', 'cron_job_path', $this->cron_job_path]);

        return $dataProvider;
    }
}
