<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\Country;

use livefactory\models\State;

use livefactory\models\City;
use livefactory\models\search\UserType as UserTypeSearch;
use yii\helpers\ArrayHelper;
function checkUserExists($email){
    $type = UserTypeSearch::getCompanyUserType('Customer')->id;
    $sql="select * from  tbl_user  where (username='$email' and user_type_id=$type) || email='$email'"; 
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$users=$command->queryAll();
	return count($users);
}
function isCustomerUser($email){
    $type = UserTypeSearch::getCompanyUserType('Customer')->id;
    $sql="select * from  tbl_user  where user_type_id != $type and  email='$email'"; 
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$users=$command->queryAll();
	return count($users);
}
function getCustomerUserDetail($email){
    $type = UserTypeSearch::getCompanyUserType('Customer')->id;
    $sql="select * from  tbl_user  where user_type_id = $type and  email='$email'"; 
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$users=$command->queryOne();
	return $users;
}
function isPrimary($id){
	$sql="select * from  tbl_contact  where id='$id' and is_primary=1"; 
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$contact=$command->queryAll();
	return count($contact);
}
if($_REQUEST['r'] == 'customer/customer/customer-view') // Added by Ashish to disble user creation functionality for sales leads
{
if(Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER']=='Yes'){
	$btn = '<button type="button" class="btn btn-primary btn-sm" onClick="create_users()">'.Yii::t('app', 'Create Login Account').'</button>';
}
}
?>
						
    <?php 

	 Yii::$app->request->enableCsrfValidation = true;

    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');

	Pjax::begin(); 
	
	echo GridView::widget([

        'dataProvider' => $dataProviderContacts,
		'responsive' => true,'responsiveWrap' => false,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

			
			[ 

						'attribute' => 'id',
						'label'=>'#',
						'format' => 'raw',
						'visible' => Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER']=='Yes'?true:false,
						'value'=>function ($model){
							if(checkUserExists($model->email)){
								return '';
							}else{
								return '<input type="checkbox" name="con_ids[]" class="con_ids" value="'.$model->id.'">';	
							}
						}

				],
		
			[ 

					'attribute' => 'first_name',

					'format' => 'raw'

			],

			[ 

					'attribute' => 'last_name'

			],

			[ 

					'attribute' => 'email'

			],

			[ 

					'attribute' => 'phone'

			],

			[ 

					'attribute' => 'mobile',
					'width' => '10%',

			],

			[ 

					'attribute' => 'fax',
					'width' => '10%',

			],
			

			[ 
					'label'=>Yii::t('app','Is Primary'),
					'width' => '10%',
					'attribute' => 'id',
					'format'=>'raw',
					'value'=>function($model){
						if(isPrimary($model->id)){
							return '<span class="label label-primary">'.Yii::t('app','Primary').'</span>	';
						}else{
							return '<span class="label label-danger">'.Yii::t('app','Secondary').'</span>	';
						}
					}

			],

			

			

			 

            [

               'class' => '\kartik\grid\ActionColumn',
				'width' => '10%',
    			//'template'=>'{update}  {delete} {user_delete} {primary}',
				'template'=>'{update}  {delete} {primary}',

                'buttons' => [

				'width' => '100px',

								'update' => function ($url, $model)

                                                        {
														      return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&contact_edit='.$model->id.'" onClick="return load_contact();" title="'.Yii::t('app', 'Update').'"><span class="glyphicon glyphicon-pencil"></span></a>';

																
                                                        } ,
              

				


		 'delete' => function ($url, $model)
                    
                    {
                        
                        if(isPrimary($model->id)){
                            
                            return '';
                        } else {
                            
                            return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&contact_del='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete Contact').'"><span class="glyphicon glyphicon-trash"></span></a>';
                        }
                    },


												  'user_delete'=>function($url,$model){
													  if(Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER']=='Yes'){
														  $user = getCustomerUserDetail($model->email);
														  if(checkUserExists($model->email) && !isCustomerUser($model->email)){
															   return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&cus_user_del='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete User').'"><span class="fa fa-user-times"></span></a> <a title="'.Yii::t('app', 'Mail').'" href="index.php?r=user/user/mail-compose&id='.$user['id'].'">
																<span class="glyphicon glyphicon-envelope"></span>
																</a>';
														  }else{
															 return ''; 
														  }
													  }else{
															 return ''; 
														  }
												  },
												  'primary'=>function($url,$model){
														  if(!isPrimary($model->id) && checkUserExists($model->email)){
															   return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&primary='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Make Primary').'"><span class="fa fa-diamond"></span></a>';
														  }else{
															 return ''; 
														  }
												  }

				



                ],

            ],

        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,


        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Contacts').'</h3>',

            'type'=>'info',

            'before'=>'<form action="" method="post" name="frm">

            <input type="hidden" name="_csrf" value="'.$csrf.'">

            <input type="hidden" name="make_users" value="true">
            <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.contactae\').modal(\'show\');"><i class="glyphicon glyphicon-phone"></i>'.Yii::t('app', 'New Contact').' </a> '.$btn, 
            
            'after' => '</form>',

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>

     <script>
	 	function create_users(){
			if($('.con_ids').is(":checked")){
				var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");
					if (r == true) {
						document.frm.submit()
					} else { }	
			}else{
				alert("<?=Yii::t ('app','Please Select Row')?>");
			}
		
	
		}

		function get_confirm(){
		return confirm("<?=Yii::t ('app','Are you Sure!')?>");
	}

	function load_contact(){
		return window.location.reload(true);
	}
	 </script>