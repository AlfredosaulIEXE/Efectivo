<?php

namespace livefactory\modules\cron\controllers;

use Yii;
use yii\helpers\Html;

use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\ProjectStatus;
use yii\web\User;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class CronController extends Controller
{
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function actionIndex(){
		$sql="SELECT tbl_user.first_name,tbl_user.last_name,tbl_project.id,tbl_project.project_name FROM `tbl_project`,tbl_user WHERE tbl_project.project_owner_id=tbl_user.id and tbl_project.project_status_id != '".ProjectStatus::_COMPLETED."'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			return $this->render('index', [
				'dataProvider' => $dataReader,
			]);
	}
	public function actionProjectSummaryReport(){
		$sql="SELECT tbl_user.first_name,tbl_user.last_name,tbl_project.id,tbl_project.project_name FROM `tbl_project`,tbl_user WHERE tbl_project.project_owner_id=tbl_user.id and tbl_project.project_status_id != '".ProjectStatus::_COMPLETED."'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			return $this->render('project-summary-report', [
				'dataProvider' => $dataReader,
			]);
	}
	public function actionDatabaseBackup(){
			return $this->render('database-backup');
	}
	public function actionDailyUserWork(){
		$sql="SELECT tbl_user.first_name,tbl_user.last_name,tbl_project.id,tbl_project.project_name FROM `tbl_project`,tbl_user WHERE tbl_project.project_owner_id=tbl_user.id and tbl_project.project_status_id != '".ProjectStatus::_COMPLETED."'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			return $this->render('daily-user-work', [
				'dataProvider' => $dataReader,
			]);
	}	
	public function actionTimelySpentReport(){
		$sql="SELECT tbl_user.first_name,tbl_user.last_name,tbl_project.id,tbl_project.project_name FROM `tbl_project`,tbl_user WHERE tbl_project.project_owner_id=tbl_user.id and tbl_project.project_status_id != '".ProjectStatus::_COMPLETED."'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			return $this->render('timely-spent-report', [
				'dataProvider' => $dataReader,
			]);
	}	

	public function actionSupportEmailAutomaticTickets(){
			return $this->render('support_email_automatic_tickets', [
				'dataProvider' => $dataReader,
			]);
	}	
}