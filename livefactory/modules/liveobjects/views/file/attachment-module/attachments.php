<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\FileModel;
use yii\helpers\ArrayHelper;
?>
    <?php 
	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
	if($_REQUEST['r']=='sales/lead/view')
		$entity_type='lead';
	else if($_REQUEST['r']=='customer/customer/customer-view')
		$entity_type='customer';
	else if ($_REQUEST['r']=='pmt/defect/defect-view')
		$entity_type='defect';
	else if ($_REQUEST['r']=='pmt/project/project-view')
		$entity_type='project';
	else if ($_REQUEST['r']=='support/ticket/update')
		$entity_type='ticket';
	else if ($_REQUEST['r']=='pmt/task/task-view')
		$entity_type='task';
	 //if(isset($entity_type) && file_exists("../attachments/".$entity_type."_".$_GET['id'].".zip"))
	  if(isset($entity_type))
	  {
		$zipbutton='<a href="" onclick="zipsubmit()" class="btn btn-success btn-sm"><span class="fa fa-download"></span> '.Yii::t('app', 'Download All').' </a>';
		/*if (file_exists("../attachments/".$entity_type."_".$_GET['id'].".zip"))
		{
			unlink("../attachments/".$entity_type."_".$_GET['id'].".zip");
		}*/
	  }
	  else
	  {
		  $zipbutton = '';
	  }

	  if ($zipbutton != '' && count(FileModel::getAttachmentFiles($entity_type,$_GET['id'])) == 0)
	  {
		  $zipbutton = '';
		  if (file_exists("../attachments/".$entity_type."_".$_GET['id'].".zip"))
		  {
			unlink("../attachments/".$entity_type."_".$_GET['id'].".zip");
		  }
	  }

	 Yii::$app->request->enableCsrfValidation = true;
    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProviderAttach,
		'responsive' => true,'responsiveWrap' => false,
        //'filterModel' => $searchModelAttch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[ 
					'attribute' => 'file_title',
					'width' => '20%',
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget) {
						$icons['.php']='glyphicon glyphicon-file';
						$icons['.txt']='glyphicon glyphicon-file';
						$icons['.xlsx']='fa fa-file-excel-o';
						$icons['.xls']='fa fa-file-excel-o';
						$icons['.gif']='fa fa-image';
						$icons['.png']='fa fa-image';
						$icons['.jpg']='fa fa-image';
						$icons['.jpeg']='fa fa-image';
						$icons['.docx']='fa fa-file-word-o';
						$icons['.doc']='fa fa-file-word-o';
						$iconClass = array_key_exists(strrchr($model->file_name, "."),$icons)?$icons[strrchr($model->file_name, ".")]:'glyphicon glyphicon-file';
						if(strrchr($model->file_name, ".")=='.php'){
								return "
									<form name='frmx".$model->id."' action='../attachments/view_attachment.php?pagename=".$model->id.strrchr($model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$model->id.".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$model->file_title."</a></form>";
							}else{
                                    return "
									<form name='frmx".$model->id."' action='../attachments/".$model->id.strrchr($model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$model->id.".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$model->file_title."</a></form>";
					}
				}, 
			],
			[ 
				'attribute' => 'file_name',
				'width' => '20%' 
			],
			[ 
					'attribute' => 'added_at',
					'label'=>Yii::t('app', 'Added'),
					'width' => '25%' ,
					'format'=>'raw',
					'value' => function ($model, $key, $index, $widget) {
					if($model->added_at !='0') {
						if(strlen($model->added_at) >4){
							return date('jS \of F Y H:i:s',$model->added_at);
						}else{
							return $model->added_at;
						}
					} else{
						return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
					}
				}
			],
			/*[ 
					'attribute' => 'updated_at',
					'label'=>Yii::t('app', 'Updated'),
					'width' => '25%' ,
					'format'=>'raw',
					'value' => function ($model, $key, $index, $widget) {
					if($model->updated_at !='0') {
						if(strlen($model->updated_at) >4){
							return date('jS \of F Y H:i:s',$model->updated_at);
						}else{
							return $model->updated_at;
						}
					} else{
						return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
					}
				}
			],*/
			[ 
					'attribute' => 'added_by_user_id',
					'width' => '25%' ,
					'format'=>'raw',
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->user)) {
						return $model->user->first_name." ".$model->user->last_name." (".$model->user->username.")";
					}
				}
			],
            [
               'class' => '\kartik\grid\ActionColumn',
    			//'template'=>'{update} {view} {mail} {delete}',
				'template'=>'{view} {mail} {delete}',
                'buttons' => [
				'width' => '100px',
                /*'update' => function ($url, $model) {
                                    return"<form name='frm_att".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&attach_update=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_att".$model->id.".submit()' title='Edit' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";},*/
				'view' => function ($url, $model) {
							if(strrchr($model->file_name, ".")=='.php'){
								return "
									<form name='frm".$model->id."' action='../attachments/view_attachment.php?pagename=".$model->id.strrchr($model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frm".$model->id.".submit()' title='".Yii::t('app', 'View')."' target='_parent'><span class='glyphicon glyphicon-eye-open'></span></a></form>";
							}else{
                                    return "
									<form name='frm".$model->id."' action='../attachments/".$model->id.strrchr($model->file_name, ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frm".$model->id.".submit()' title='".Yii::t('app', 'View')."' target='_parent'><span class='glyphicon glyphicon-eye-open'></span></a></form>";
					}
				},
				'mail' => function($url,$model){
					 return '<a href="javascript:void(0)" onClick="sendAttachment(\''.$model->file_name.'\',\''.$model->id.strrchr($model->file_name, ".").'\')" title="'.Yii::t('app', 'Mail').'"><span class="glyphicon glyphicon-envelope"></span></a>';
				},
				'delete' => function ($url, $model) {
					return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&attachment_del_id='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete attachment').'"><span class="glyphicon glyphicon-trash"></span></a>';
					/*return '<form action="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&attachment_del_id='.$model->id.'" method="post" id="n'.$model->id.'" style="display:inline">
					<input type="hidden" value="'.$csrf.'" name="_csrf">
					<a href="javascript:void(0)" onClick="formSubmit(\'n'.$model->id.'\')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
					</form>';*/
/*return Html::a ( '<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl ( [ 
															'attachment_del_id',
															'id' => $model->id
													] ), [ 
															'title' => Yii::t('app', 'Delete' ) ,
															'data-method'=>"post",
															'onclick'=>'return confirm("Are you Sure!")'
													] );*/
                                   /* return '<a href="index.php?r=pmt/task%2Ftask-view&id='.$_REQUEST['id'].'&attachment_del_id='.$model->id.'" onClick="return confirm(\'Are you Sure!\')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';*/
									
									/* Html::a(' <span class="glyphicon glyphicon-trash danger"></span>', Yii::$app->urlManager->createUrl(['task/task-view','id'=>$_REQUEST['id'],'attachment_id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                  ]);*/
												  }
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>true,
        'panel' => [
            'heading'=>'<i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Attachments'),
            'type'=>'info',
			
			//'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.savepopup\').modal(\'show\');"><i class="glyphicon glyphicon-save"></i> '.Yii::t('app', 'New Attachment').'  </a> '.$zipbutton,   
			'before'=> '<form name="frmabc" action="'.str_replace('web/index.php', 'attachments/'.$entity_type.'_'.$_GET['id'].'.zip', $_SESSION['base_url']).'" method="post" target="_blank"> 
			<a href="javascript:void(0)" class="btn btn-primary btn-sm" onClick="$(\'.savepopup\').modal(\'show\');"><i class="fa fa-upload"></i> '.Yii::t('app', 'New Attachment').'  </a>&nbsp'.$zipbutton.'</form>',   
			//'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
           // 'after'=>'',
			'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
	<?php
	//print_r($entity_type);
	//print_r($_GET['id']);
	foreach(FileModel::getAttachmentFiles($entity_type,$_GET['id']) as $row){
		$attachment_files[]="../attachments/".$row['id'].strrchr($row['file_name'], ".");
	}
	//var_dump($attachment_files);exit;
	function create_zip($files = array(),$destination = '',$overwrite = true) {
			//if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && !$overwrite) { return false; }
			//vars
			$valid_files = array();
			//if files were passed in...
			if(is_array($files)) {
				//cycle through each file
				foreach($files as $file) {
					//make sure the file exists
					if(file_exists($file)) {
						$valid_files[] = $file;
					}
				}
			}
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new \ZipArchive();
				if($zip->open($destination,file_exists($destination) ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				//add the files
				foreach($valid_files as $file) {
					$zip->addFile($file,$file);
				}
				//debug
				//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
				
				//close the zip -- done!
				$zip->close();
				
				//check to make sure the file exists
				return file_exists($destination);
			}
			else
			{
				return false;
			}
		}
	$fileModel = new FileModel();
	create_zip($attachment_files,"../attachments/".$entity_type."_".$_GET['id'].".zip");
	?>
	<script>
	function zipsubmit()
	{
		//var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");
		frmabc.submit();
	}
	function formSubmit(id){
		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");
		if (r == true) {
			$('#'+id).submit()
		} else {
			
		}	
	}
	function get_confirm(){
		return confirm("<?=Yii::t ('app','Are you Sure!')?>");
	}
	</script>