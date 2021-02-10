	<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;


?>
<!--<div class="progress progress-striped active">
    <div class="progress-bar progress-bar-danger myprogress" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:10%">
        <span class="sr-only">40% Complete (success)</span>
    </div>
</div>-->
<img src="http://www.zlatalipa.cz/grafika/loadingprogressbar_animated.gif" style="display:none" id="process">
<div class="cron-jobs-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider1,
        //'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'cron_job_name',
            'cron_job_description',
            'cron_job_path',

            [
                'class' => '\kartik\grid\ActionColumn',
				'header'=>'Actions',
                'buttons' => [
				'delete' =>function($url,$model){
					return '';	
				},
				'view' =>function($url,$model){
					return '<a href="javascript:void(0)" class="btn btn-xs btn-info" title="Test" onClick="callAjax(\''.$model->cron_job_path.'\')"><i class="fa fa-magic"></i></a>';
				},
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/cron-jobs/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
													'class'=>'btn btn-xs btn-info',
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
		'toolbar'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Cron Jobs').' </h3>',
            'type'=>'info',
            'before'=>false,                                                                                                                                                          'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<script>
function callAjax(url){
	/*var width = 10;
	setInterval(function(){
		$('.myprogress').
	},1000)*/
	$('#process').show();
	if(url =='cron/cron'){
		$.post('<?=$_SESSION['base_url']?>?r='+url,function(){
			
		}).done(function(){
			$('#process').hide()
		})
	}else{
		$.post('<?=str_replace('livecrm/include/web/index.php','',$_SESSION['base_url'])?>?r='+url,function(){
			
		}).done(function(){
			$('#process').hide()
		})
	}
}
</script>

