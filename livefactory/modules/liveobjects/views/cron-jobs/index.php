	<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\CronJobs $searchModel
 */

$this->title = Yii::t('app', 'Cron Jobs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert alert-info">
Below are the cron jobs available in the system. Please setup the ones you want to run, in your cPanel/Server cron job settings. The Test button will only execute the php code and does not imply that the cron job has been setup on your server.
</div>
<img src="http://www.zlatalipa.cz/grafika/loadingprogressbar_animated.gif" style="display:none" id="process">
<div style="display:none" id="progress-box">
<h5 id="title"><?= Yii::t('app','Please wait...')?></h5>
<div class="progress progress-striped active">
    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:5%">
        <span class="sr-only">40% Complete (success)</span>
    </div>
</div>
</div>
<!--<div style="display:none" id="stop">
<?= Yii::t('app','Cron Job Successfully Executed')?>
</div>-->
<div class="cron-jobs-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cron_job_name',
			[
				'format'=>'raw',
				'attribute'=>'cron_job_description',
			],
			[
				'format'=>'raw',
				'attribute'=>'cron_job_path',
				'width'=>'40%',
			],

            [
                'class' => '\kartik\grid\ActionColumn',
				'header'=>'Action',
				'width'=>'10%',
				'contentOptions' => ['style' => 'width:150px;'],
                'buttons' => [
				'view' => function ($url, $model) {
					return '<a href="javascript:void(0)" class="btn btn-xs btn-info" title="Test" onClick="callAjax(\''.$model->cron_job_path.'\')">Test Cron</a>';
				},
				'delete' => function ($url, $model) {
					return '';
				},
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/cron-jobs/update','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Edit'),
													'class'=>'btn btn-xs btn-info'
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', ' Add'), ['create'], ['class' => 'btn btn-success btn-sm']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info btn-sm']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
function callAjax(url){
		var width = 10;
		$('#title').text('<?= Yii::t('app','Please wait...')?>');
		var refreshIntervalId = setInterval(function(){
			$('[role="progressbar"]').css('width',width+'%');
			width=width+5;
		},1000)
		$('#stop').hide();
		$('#progress-box').show();
		$.post('<?=$_SESSION['base_url']?>?r='+url,function(){
			
		}).done(function(){
			$('[role="progressbar"]').css('width','100%');
			$('#title').text('<?= Yii::t('app','Cron job executed successfully.')?>');
			clearInterval(refreshIntervalId);
			$('#stop').show();
		})
}
</script>
