<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\Ticket;
use yii\helpers\ArrayHelper;
use livefactory\models\Department;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Queue $searchModel
 */

$this->title = Yii::t('app', 'Queue');
$this->params['breadcrumbs'][] = $this->title;

function statusLabel($status)
{
    if ($status != '1') {
        $label = "<span class=\"label label-danger\">" . Yii::t('app', 'Inactive') . "</span>";
    } else {
        $label = "<span class=\"label label-primary\">" . Yii::t('app', 'Active') . "</span>";
    }
    return $label;
}
$status = array(
    '0' => Yii::t('app', 'Inactive'),
    '1' => Yii::t('app', 'Active')
);
?>


    <?php Yii::$app->request->enableCsrfValidation = true; ?>
	<div class="queue-index">

    <?php
    
Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn'
            ],
           /* [
                'class' => 'yii\grid\SerialColumn',
                'header' => ''
            ],*/
            
            [
                
                'attribute' => 'queue_title',
                
                'width' => '300px',
                
                'format' => 'raw',
                
                'value' => function ($model, $key, $index, $widget)
                
                {
                    
                    return '<a href="index.php?r=liveobjects/queue/update&id=' . $model->id . '">' . $model->queue_title . '</a>';
                }
            ]
            ,
            [
                
                'attribute' => 'department_id',
                
                'filterType' => GridView::FILTER_SELECT2,
                
                'format' => 'raw',
                
                'width' => '300px',
                
                'filter' => ArrayHelper::map(Department::find()->orderBy('name')
                    ->where("active=1")
                    ->asArray()
                    ->all(), 'id', 'name'),
                
                'filterWidgetOptions' => [
                    
                    'options' => [
                        
                        'placeholder' => Yii::t('app', 'All...')
                    ]
                    ,
                    
                    'pluginOptions' => [
                        
                        'allowClear' => true
                    ]
                    
                ]
                ,
                
                'value' => function ($model, $key, $index, $widget)
                
                {

					if (isset($model->department)) {
						return $model->department->name;
					}
                }
            ]
            ,
			 [
                
                'attribute' => 'queue_supervisor_user_id',
                
                'filterType' => GridView::FILTER_SELECT2,
                
                'format' => 'raw',
                
                'width' => '300px',
                
                'filter' => ArrayHelper::map(User::find()->orderBy('first_name')
                    ->where("active=1")
                    ->asArray()
                    ->all(), 'id', function ($user, $defaultValue)
                {
                    $username = $user['username'] ? $user['username'] : $user['email'];
                    return $user['first_name'] . ' ' . $user['last_name'] . ' (' . $username . ')';
                }),
                
                'filterWidgetOptions' => [
                    
                    'options' => [
                        
                        'placeholder' => Yii::t('app', 'All...')
                    ]
                    ,
                    
                    'pluginOptions' => [
                        
                        'allowClear' => true
                    ]
                    
                ]
                ,
                
                'value' => function ($model, $key, $index, $widget)
                
                {

					if (isset($model->queueSupervisorUser) && ! empty($model->queueSupervisorUser->first_name)) {
                            $username = $model->queueSupervisorUser->username ? $model->queueSupervisorUser->username : $model->queueSupervisorUser->email;
                            if (Yii::$app->params['USER_IMAGE'] == 'Yes') {
                                $users = '<div class="project-people">';
                                $path = '../users/' . $model->queueSupervisorUser->id . '.png';
                                if (file_exists($path)) {
                                    $image = '<img  class="img-circle"  src="' . $path . '"  data-toggle="hover" data-placement="top" data-content="' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')">';
                                } else {
                                    $image = '<img   class="img-circle" src="../users/nophoto.jpg"  data-toggle="hover" data-placement="top" data-content="' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')">';
                                }
                                $users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->queueSupervisorUser->id . '\')">' . $image . '</a>';
                                
                                $users .= '</div>';
                                return $users;
                            } else {
                                $users = ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->queueSupervisorUser->id . '\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')</a>';
                                return $users;
                            }
                        }
                }
            ]
            ,
            
            [
                'attribute' => 'id',
                'label' => Yii::t('app', 'Assigned Users'),
                'filter' => '',
                'format' => 'raw',
                'width' => '300px',
                'value' => function ($model, $key, $index, $widget)
                {
                    $users = '<div class="project-people">';
                    foreach (\livefactory\models\search\Queue::getQueueUsers($model->id) as $p_user) {
						foreach (\livefactory\models\QueueUsers::getUsers($p_user['user_id']) as $queueuser) {
				        $path = '../users/' . $p_user['user_id'] . '.png';


                        if (file_exists($path)) {
                           // $image = '<img  class="img-circle"  src="../users/' . $p_user['user_id'] . '.png" data-content="' . $p_user['first_name'] . ' ' . $p_user['last_name'] . ' (' . $p_user['username'] . ')">';
						   $image = '<img  class="img-circle"  src="../users/' . $p_user['user_id'] . '.png"  data-toggle="hover" data-placement="top" data-content="' . $queueuser['first_name'] . ' ' . $queueuser['last_name'] . ' (' . $queueuser['username'] . ')">';
                        } else {
                           // $image = '<img   class="img-circle" src="../users/nophoto.jpg" data-content="' . $p_user['first_name'] . ' ' . $p_user['last_name'] . ' (' . $p_user['username'] . ')">';
						   $image = '<img   class="img-circle" src="../users/nophoto.jpg" data-toggle="hover" data-placement="top" data-content="'. $queueuser['first_name'] . ' ' . $queueuser['last_name'] . ' (' . $queueuser['username'] . ')" >';
                        }
                        $users .= ' <a href="javascript:void(0)" onClick="showPopup(\'' . $p_user['user_id'] . '\')">' . $image . '</a>';
						}}
                    $users .= '</div>';
                    return $users;
                }
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $status,
                'filterWidgetOptions' => [
                    'options' => [
                        'placeholder' => Yii::t('app', 'All...')
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
                'value' => function ($model, $key, $index, $widget)
                {
                    return statusLabel($model->active);
                }
            ],
            
            [
                'class' => '\kartik\grid\ActionColumn',
                'header' => 'Actions',
                'buttons' => [
                    'update' => function ($url, $model)
                    {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl([
                            'liveobjects/queue/update',
                            'id' => $model->id,
                            'edit' => 't'
                        ]), [
                            'title' => Yii::t('app', 'Edit')
                        ]);
                    },
                    'view' => function ($url, $model)
                    {
                        
                        return '';
                    },
					'delete' => function($url,$model){
													if(Ticket::find()->andwhere('queue_id='.$model->id)->count() > 0)
													{
														return '';
													}
													else
													{
														return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['liveobjects/queue/delete','id' => $model->id]), [
															'title' => Yii::t('app', 'Delete'),
															'data' => [                          
																		'method' => 'post',                          
																		'confirm' => Yii::t('app', 'Are you sure?')],
																	  ]);
													}
												}
                ]
                
            ]
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,
        
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => '<form action="" method="post" name="frm"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_del" value="true">'.Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
                'create'
            ], [
                'class' => 'btn btn-success btn-sm'
            ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', "Delete Selected") . '</a>',
            'after' => '</form>'.Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                'index'
            ], [
                'class' => 'btn btn-info btn-sm'
            ]),
            'showFooter' => false
        ]
    ]);
    Pjax::end();
    ?>

</div>


<script>

	function all_del(){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			document.frm.submit()

		} else {

			

		}	

	}

</script>
<script>
	function showPopup(id){
		//alert('index.php?r=liveobjects/queue/ajax-user-detail&id='+id);
		$.post('index.php?r=liveobjects/queue/ajax-user-detail&id='+id,function(r){
			$('.modal-body').html(r);
		}).done(function(){
			$('.bs-example-modal-lg').modal('show');
		})
	}
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

