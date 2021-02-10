<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\ProjectStatus;
use livefactory\models\Customer;
use livefactory\models\ProjectType;
use livefactory\models\ProjectPriority;
use livefactory\models\Project;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Project $searchModel
 */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;

function statusLabel($status)
{
    if (in_array(strtolower($status), array(
        'new',
        'business',
        'completed',
        'low'
    ))) {
        $label = "<span class=\"label label-primary\">" . $status . "</span>";
    } else 
        if (in_array(strtolower($status), array(
            'acquired',
            'in process',
            'medium',
            'p2',
            'p3'
        ))) {
            $label = "<span class=\"label label-success\">" . $status . "</span>";
        } else 
            if (in_array(strtolower($status), array(
                'individual',
                'lowest'
            ))) {
                $label = "<span class=\"label label-info\">" . $status . "</span>";
            } else 
                if (in_array(strtolower($status), array(
                    'lost',
                    'needs action',
                    'highest'
                ))) {
                    $label = "<span class=\"label label-danger\">" . $status . "</span>";
                } else 
                    if (in_array(strtolower($status), array(
                        'student',
                        'on hold',
                        'high'
                    ))) {
                        $label = "<span class=\"label label-warning\">" . $status . "</span>";
                    } else {
                        $label = "<span class=\"label label-default\">" . $status . "</span>";
                    }
    return $label;
}
?>
<div class="project-index">
	<!--
	<div class="page-header">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>
	-->
	<!-- <form action="" method="post" name="frm"> -->
    <?php Yii::$app->request->enableCsrfValidation = true; ?>
   
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <!--  <p> -->
        <?php
        /*
         * echo Html::a(Yii::t('app', 'Create {modelClass}', [
         * 'modelClass' => 'Project',
         * ]), ['create'], ['class' => 'btn btn-success'])
         */
        ?>
  <!--  </p> -->

    <?php
    
    Pjax::begin();
    echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
            'columns' => [
                [
                    'class' => '\kartik\grid\CheckboxColumn'
                ],
                [
                    'class' => 'yii\grid\SerialColumn'
                ],
                
             [
                    'attribute' => 'project_id',
                    'label'=>'Project ID',
                    // 'width' => '150px',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget)
                    {
                        return '<a href="?r=pmt/project/project-view&id=' . $model->id . '">' . $model->project_id . '</a>';
                    }
                ],
                
                [
                    'attribute' => 'project_name',
                    
                    // 'width' => '150px',
                    'format' => 'raw',
                    
                ],
                
                // 'added_by',
                /*
                 * [
                 * 'attribute' => 'added_by',
                 * 'label' => 'Added by',
                 * 'filterType' => GridView::FILTER_SELECT2,
                 * 'format' => 'raw',
                 * 'width' => '120px',
                 * 'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'first_name' ),
                 * 'filterWidgetOptions' => [
                 * 'options' => [
                 * 'placeholder' => 'All...'
                 * ],
                 * 'pluginOptions' => [
                 * 'allowClear' => true
                 * ]
                 * ],
                 * 'value' => function ($model, $key, $index, $widget)
                 * {
                 * // var_dump($model->user);
                 * if (isset ( $model->user ) && ! empty ( $model->user->first_name ))
                 * return $model->user->first_name." ".$model->user->last_name;
                 * }
                 * ],
                 */
                // 'project_type_id',
                
                [
                    'attribute' => 'customer_id',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'filter' => ArrayHelper::map(Customer::find()->orderBy('customer_name')
                        ->asArray()
                        ->all(), 'id', 'customer_name'),
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
                        // var_dump($model->user);
                        if (isset($model->customer) && ! empty($model->customer->customer_name))
                            return '<a href="index.php?r=customer/customer/customer-view&id=' . $model->customer->id . '">' . $model->customer->customer_name . '</a>';
                    }
                ],
                
                [
                    'attribute' => 'project_type_id',
                    
                    // 'label' => 'Project Type',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    
                    // 'width' => '250px',
                    'filter' => ArrayHelper::map(ProjectType::find()->where("active=1")
                        ->orderBy('sort_order')
                        ->asArray()
                        ->all(), 'id', 'label'),
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
                        // var_dump($model->user);
                        if (isset($model->type) && ! empty($model->type->label))
                            return $model->type->label;
                    }
                ],
                
                [
                    'attribute' => 'project_status_id',
                    
                    // 'label' => 'Status',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    
                    // 'width' => '250px',
                    'filter' => ArrayHelper::map(ProjectStatus::find()->where("active=1")
                        ->orderBy('sort_order')
                        ->asArray()
                        ->all(), 'id', 'label'),
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
                        if (isset($model->status) && ! empty($model->status->label))
                            return statusLabel($model->status->label);
                    }
                ],
                [
                    'attribute' => 'project_priority_id',
                    
                    // 'label' => 'Project Type',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    
                    // 'width' => '250px',
                    'filter' => ArrayHelper::map(ProjectPriority::find()->where("active=1")
                        ->orderBy('sort_order')
                        ->asArray()
                        ->all(), 'id', 'label'),
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
                        if (isset($model->projectPriority) && ! empty($model->projectPriority->label))
                            return statusLabel($model->projectPriority->label);
                    }
                ],
                
            
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', 'Status'),
                    'format' => 'raw',
                    
                    // 'width' => '100px',
                    'value' => function ($model, $key, $index, $widget)
                    {
                        // var_dump($model->user);
                        if (isset($model->opentask))
                            return '<a href="index.php?r=pmt/project/project-view&id=' . $model->id . '&tasktab=true" class="btn btn-xs btn-success" >Open Task <span class="badge">' . $model->opentask . '</span></a> <br/><br/><a href="?r=pmt/project/project-view&id=' . $model->id . '&defecttab=true" class="btn btn-xs btn-success" >Open Defect <span class="badge">' . $model->opendefect . '</span></a>';
                    }
                ],
                [
                    'attribute' => 'project_owner_id',
                    'label' => Yii::t('app', 'Owner'),
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '150px',
                    'filter' => ArrayHelper::map(User::find()->orderBy('first_name')
                        ->where("active=1 and entity_type != 'customer'")
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
                        if (isset($model->user) && ! empty($model->user->first_name)) {
                            $username = $model->user->username ? $model->user->username : $model->user->email;
                            if (Yii::$app->params['USER_IMAGE'] == 'Yes') {
                                $users = '<div class="project-people">';
                                $path = '../users/' . $model->user->id . '.png';
                                if (file_exists($path)) {
                                    $image = '<img  class="img-circle"  src="' . $path . '">';
                                } else {
                                    $image = '<img   class="img-circle" src="../users/nophoto.jpg">';
                                }
                                $users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')"  data-toggle="hover" data-placement="top" data-content="' . $model->user->first_name . ' ' . $model->user->last_name . ' (' . $username . ')">' . $image . '</a>';
                                
                                $users .= '</div>';
                                return $users;
                            } else {
                                $users = ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">' . $model->user->first_name . ' ' . $model->user->last_name . ' (' . $username . ')</a>';
                                return $users;
                            }
                        }
                    }
                ],
                [
                    'attribute' => 'id',
                    'label' => Yii::t('app', 'Assigned Users'),
                    'format' => 'raw',
                    'filter' => false,
                    'width' => '150px',
                    'value' => function ($model, $key, $index, $widget)
                    {
                        if (Yii::$app->params['USER_IMAGE'] == 'Yes') {
                            $users = '<div class="project-people">';
                            foreach (Project::getProjectUsers($model->id) as $p_user) {
                                $path = '../users/' . $p_user['user_id'] . '.png';
                                if (file_exists($path)) {
                                    $image = '<img  class="img-circle"  src="../users/' . $p_user['user_id'] . '.png">';
                                } else {
                                    $image = '<img   class="img-circle" src="../users/nophoto.jpg">';
                                }
								
								$objUser = Project::getUserDetail($p_user['user_id']);
								$first_name = $objUser['first_name'];
								$last_name = $objUser['last_name'];
								$username = $objUser['username'];

                                $users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $p_user['user_id'] . '\')"  data-toggle="hover" data-placement="top" data-content="' . $first_name . ' ' . $last_name . ' (' .$username. ')">' . $image . '</a>';
                            }
                            $users .= '</div>';
                            return $users;
                        } else {
                            $users = '';
                            foreach (Project::getProjectUsers($model->id) as $p_user) {
                                $users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $p_user['user_id'] . '\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">' . Project::getUserName($p_user['user_id']) . '</a>';
                            }
                            $users .= '';
                            return $users;
                        }
                    }
                ],
		/*
		[ 
				'attribute' => 'expected_start_date',
				'label' => 'Start Date',
				'filterType' => GridView::FILTER_DATE,
										'width' => '150px',
										'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
										]
		],
		*/
		
		
		/*[ 
										'attribute' => 'expected_end_datetime',
										'label' => 'ETA',
										'filterType' => GridView::FILTER_DATE,
										'width' => '150px',
										'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
										] ,
										'value' => function ($model, $key, $index, $widget) {
										if(isset($model->expected_end_datetime)) 
											return date('F d,Y',strtotime($model->expected_end_datetime));
										} 
								],*/
								[
                    'attribute' => 'project_progress',
                    
                    // 'width'=>'80px',
                    'label' => Yii::t('app', 'Progress'),
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget)
                    {
                        $per = $model->project_progress == '' ? 0 : $model->project_progress;
                        return '<small>Progress: ' . $per . '%</small>
<div class="progress progress-mini">
<div class="progress-bar" style="width:' . $model->project_progress . '%;"></div>
</div>';
                        /*
                         * return "<div class='progress'>
                         * <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='" . $model->project_progress . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $model->project_progress . "%'>" . $model->project_progress . "</div>
                         * </div>";
                         */
                    }
                ],
                
                // 'project_status_id',
                
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{update} {view} {chat} {delete}',
                    'contentOptions' => [
                        'style' => 'width:100px;'
                    ],
                    'header' => 'Actions',
                    'buttons' => [
                        'update' => function ($url, $model)
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl([
                                'pmt/project/project-view',
                                'id' => $model->id
                            ]), [
                                'title' => Yii::t('app', 'Edit')
                            ]);
                        },
                        'chat' => function ($url, $model)
                        {
							if(Yii::$app->user->identity->userType->type=="Customer")
							{
								return '';
							}
							else
							{
								return Html::a('<span class="glyphicon glyphicon-comment"></span>', Yii::$app->urlManager->createUrl([
									'pmt/project/group-chat',
									'id' => $model->id
								]), [
									'title' => Yii::t('app', 'Group Chat')
								]);
							}
                        },
                        
                        'view' => function ($url, $model)
                        {
                            return '';
                        },
						'delete' => function($url,$model){
															if(Yii::$app->user->identity->userType->type=="Customer")
															{
																return '';
															}
															else
															{
																return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['pmt/project/delete','id' => $model->id]), [
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
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app', $this->title) . ' </h3>',
                'type' => 'info',
               'before' => '<form action="" method="post" name="frm"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_del" value="true">'.(Yii::$app->user->identity->userType->type=="Customer"?'':Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
                'create'
            ], [
                'class' => 'btn btn-success btn-sm'
            ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', "Delete Selected") ). '</a>',
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
</div>
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