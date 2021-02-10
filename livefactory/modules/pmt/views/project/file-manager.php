<?php

////////////Notes//////////////////////////////////////

/*

1."page" parameter use for redirect to detail page

2."id_field" parameter use for detail page id parameter get name

3. "field" parameter use for  fetch title

4. "table" parameter use for get table name

*/

$this->title =  Yii::t ('app','Project Attachments');

?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>

function addAttach(){

	$('#first-col').after('<div class="col-sm-4"><div class="form-group"><div class="controls"><input class="form-control" name="filetitle[]" placeholder="File Title"><input type="file" class="form-control" name="attach[]"> </div></div> </div>	');

}

function validation(){

	if($('#att').val()	==''){

		$('#att').parent().parent().addClass('has-error');

		$('.help-block').text('<?=Yii::t ('app','This Field Required!')?>');

		

	}else{

		$('#att').parent().parent().removeClass('has-error');

		$('.help-block').text('');

		document.form1.submit();

	}

}

</script>
<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="../include/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../include/css/theme.css">

		

		
<?php
if(!isset($_SESSION)) {
     session_start();
}
$_SESSION['user_id']=Yii::$app->user->identity->id;
if(!isset($_SESSION['entity'])){
	$_SESSION['entity_id']=$_GET['entity_id'];
	$_SESSION['entity_type']='project';
}
if(!file_exists('../attachments/project'.$_GET['entity_id'])){
	mkdir('../attachments/project'.$_GET['entity_id']);
}

$entity_id=!empty($_REQUEST['entity_id'])?$_REQUEST['entity_id']:'';



?>

<div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5> <?=$this->title ?></h5>



            <div class="ibox-tools">

				<a href="index.php?r=pmt/project/project-view&id=<?=$_GET['entity_id']?>" class="btn btn-warning btn-xs" style="color:#fff;"><?=Yii::t('app', 'Skip')?></a>

                <a class="collapse-link">

                    <i class="fa fa-chevron-up"></i>

                </a>

               

                <a class="close-link">

                    <i class="fa fa-times"></i>

                </a>

            </div>

</div>

         <div class="ibox-content">

             <!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

         </div>

    </div>