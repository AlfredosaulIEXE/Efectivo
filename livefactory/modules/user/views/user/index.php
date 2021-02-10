<?php
use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\UserType;

use livefactory\models\UserRole;

use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 *     
 * @var yii\data\ActiveDataProvider $dataProvider
 *     
 * @var common\models\search\User $searchModel
 *     
 */

$this->title = Yii::t('app', 'Users');

$this->params['breadcrumbs'][] = $this->title;

function getUserRole($id)
{
    $connection = \Yii::$app->db;
    $sql = "select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=$id and auth_assignment.item_name=auth_item.name and auth_item.name='Admin'";
    $command = $connection->createCommand($sql);
    $dataReader = $command->queryAll();
    return count($dataReader);
}

function getUserRoles($id)
{
    $connection = \Yii::$app->db;
    $sql = "select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=" . $id . " and auth_assignment.item_name=auth_item.name";
    $command = $connection->createCommand($sql);
    $dataReader = $command->queryAll();
	$roles = '';
    if (count($dataReader) > 0) {

        foreach ($dataReader as $role) {

            $roles .= '<span class="label label-primary">' . $role['description'] . "</span><br/><br/>";
        }
    } else {
        return '<span class="label label-danger">' . Yii::t('app', 'No Roles') . '</span>';
    }
    
    return $roles;
}
?>

<div class="user-index">

	<!--

	<div class="page-header">

		<h1><?= Html::encode($this->title) ?></h1>

	</div>

	-->

    <?php
    
    // var_dump(ArrayHelper::map ( UserType::find ()->orderBy ( 'label' )->asArray ()->all (), 'id', 'label' ));
    
    $active = array(
        '0' => Yii::t('app', 'Inactive'),
        '1' => Yii::t('app', 'Active')
    );
    
    // echo $this->render('_search', ['model' => $searchModel]);    ?>

    <!--<p> -->

        <?php
        
        /*
         *
         * echo Html::a(Yii::t('app', 'Create {modelClass}', [
         *
         * 'modelClass' => 'User',
         *
         * ]), ['create'], ['class' => 'btn btn-success'])
         *
         */
        
        ?>

<!--    </p> -->

	

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <?php
    
    Pjax::begin();
    
    echo GridView::widget([
        
        'dataProvider' => $dataProvider,
        
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        
        'columns' => [
            
            [
                
                'class' => 'yii\grid\SerialColumn'
            ]
            ,
            [
                
                'attribute' => 'id',
                
                'label' => false,
                'filter' => false,
                
                'format' => 'raw',
                
             //   'width' => '50px',
                
                'value' => function ($model, $key, $index, $widget)
                
                {
                    if (! getUserRole($model->id) > 0)
                        return "<input type='checkbox' value='" . $model->id . "' name='selection[]' />";
                    
                    return '';
                }
            ]
            ,
            
            [
                
                'attribute' => 'id',
                
                'label' => Yii::t('app', 'Image'),
                
                'format' => 'raw',
                
                'width' => '50px',
                
                'value' => function ($model, $key, $index, $widget)
                
                {
                    
                    $users = '<div class="project-people">';
                    
                    $path = '../users/' . $model->id . '.png';
                    
                    if (file_exists($path)) {
                        
                        $image = '<img  src="../users/' . $model->id . '.png">';
                    } else {
                        
                        $image = '<img src="../users/nophoto.jpg">';
                    }
                    
                    $users .= ' <a href="index.php?r=user/user/view&id=' . $model->id . '&edit=t">' . $image . '</a>';
                    
                    $users .= '</div>';
                    
                    return $users;
                }
            ]
            ,
            
            // 'id',
            
            // 'first_name',
            [
                'attribute' => 'first_name',
                'format' => 'raw',
                'value' => function ($model)
                {
                    return '<a href="index.php?r=user/user/view&id=' . $model->id . '&edit=t">' . $model->first_name . '</a>';
                }
            ],
            
            // 'last_name',
            [
                'attribute' => 'last_name',
                'format' => 'raw',
                'value' => function ($model)
                {
                    return '<a href="index.php?r=user/user/view&id=' . $model->id . '&edit=t">' . $model->last_name . '</a>';
                }
            ],
            'username',
            'alias',
            
            // 'auth_key',
            
            // 'password_hash',
            
            // 'password_reset_token',
            
            'email:email',
            
            [
                
                'attribute' => 'user_type_id',
                
                // 'label' => 'Type',
                
                'filterType' => GridView::FILTER_SELECT2,
                
                'format' => 'raw',
                
                'width' => '150px',
                
                'filter' => ArrayHelper::map(UserType::find()->orderBy('label')
                    ->asArray()
                    ->all(), 'id', 'label'),
                
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
                    
                    // var_dump($model->user);
                    
                    if (isset($model->userType) && ! empty($model->userType->label))
                        
                        return $model->userType->label;
                }
            ]
            ,

								/*[ 

										'attribute' => 'user_role_id',

										'label' => 'Role',

										'filterType' => GridView::FILTER_SELECT2,

										'format' => 'raw',

										'width' => '150px',

										'filter' => ArrayHelper::map ( UserRole::find ()->orderBy ( 'label' )->asArray ()->all (), 'id', 'label' ),

										'filterWidgetOptions' => [ 

												'options' => [ 

														'placeholder' => 'All...' 

												],

												'pluginOptions' => [ 

														'allowClear' => true 

												] 

										],

										'value' => function ($model, $key, $index, $widget)

										{

											// var_dump($model->user);

											if (isset ( $model->userRole ) && ! empty ( $model->userRole->label ))

												return $model->userRole->label;

										} 

								],*/

								[
                
                'attribute' => 'active',
                
                // 'label' => 'active',
                
                'filterType' => GridView::FILTER_SELECT2,
                
                'format' => 'raw',
                
                'width' => '150px',
                
                'filter' => $active,
                
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
                    
                    if ($model->active != '1') {
                        
                        return '<span class="label label-danger">' . Yii::t('app', 'Inactive') . '</span>';
                    } else {
                        
                        return '<span class="label label-primary">' . Yii::t('app', 'Active') . '</span>';
                    }
                }
            ]
            ,
            [
                    'attribute' => 'added_at',
                'label' => 'Fecha de creación',
                'value' => function ($model, $key, $index, $widget)

                {
                    return date('d-m-Y H:i ', $model->added_at);
                }
            ],
            [
                    'attribute' => 'updated_at',
                'label' =>  'Fecha de modificación',
                'value' => function ($model, $key, $index, $widget)

                {
                    return date('d-m-Y H:i', $model->updated_at);
                }
            ],
            [
                
                'attribute' => 'id',
                
                'label' => Yii::t('app', 'Roles'),
                'filter' => false,
                
                'format' => 'raw',
                
                'width' => '100px',
                
                'value' => function ($model, $key, $index, $widget)
                {
                    
                    return getUserRoles($model->id);
                }
            ]
            ,
            
            // 'role',
            
            // 'active',
            
            // 'created_at',
            
            // 'updated_at',
            
            [
                
                'class' => '\kartik\grid\ActionColumn',
                'header' => 'Action Buttons',
                'template' => '{update} {view} {mail} {view_role} {delete}',
                'width' => '150px',
                'buttons' => [
                    
                    'update' => function ($url, $model)
                    
                    {
                        
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl([
                            
                            'user/user/update',
                            
                            'id' => $model->id,
                            
                            'edit' => 't'
                        ]
                        ), [
                            
                            'title' => Yii::t('app', 'Edit')
                        ]
                        );
                    },
                    
                    'mail' => function ($url, $model)
                    {
                        $btn ='';
						if(!empty($model->entity_id) && $model->entity_type=='customer'){
							$btn='<a href="index.php?r=customer/customer/customer-view&id='.$model->entity_id .'" title="Customer"><span class="glyphicon glyphicon-user"></span></a> ';
						}
                        return $btn.'<a href="index.php?r=user/user/mail-compose&id=' . $model->id . '" title="Mail"><span class="glyphicon glyphicon-envelope"></span></a>';
                    },
                    'view_role' => function ($url, $model)
                    {
                        
                        return ''; // '<a href="javascript:void(0)" title="View Roles" onClick="showRoles(\''.$model->id.'\')"><span class="fa fa-check-square-o"></span></a>';
                    },
                    
                    'delete' => function ($url, $model)
                    
                    {
                        
                        if (getUserRole($model->id) > 0) {
                            
                            return '';
                        } else {
                            
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl([
                                
                                'user/user/delete',
                                
                                'id' => $model->id
                            ]
                            ), [
                                
                                'title' => Yii::t('app', 'Delete'),
                                'data-method' => "post",
                                'onclick' => 'return confirm("Are you Sure!")'
                            ]
                            );
                        }
                    }
                ]
                
            ]
            
        ]
        ,
        
        'responsive' => true,
        
        'hover' => true,
        
        'condensed' => true,
        
        'floatHeader' => false,
        
        'panel' => [
            
            'heading' => '<i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title),
            
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
        
    ]
    );
    
    Pjax::end();
    
    ?>





</div>
<div class="modal fade myModel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">User Roles</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
	function showRoles(id){
		$.post("index.php?r=user/user/ajex-get-roles&id="+id,function(r){
			$('.modal-body').html(r);
		}).done(function(){
			$('.myModel').modal('show');
		})
	}
	function all_del(){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			document.frm.submit()

		} else {

			

		}	

	}
</script>