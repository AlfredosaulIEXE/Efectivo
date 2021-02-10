<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Office $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Office',
]) . ' ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(".inp").change(function(){
        readURL(this);
        ajaxFileUpload(this);
        //$('#w0').submit();
    });
    $('.upload').click(function(){
        $('.inp').click();
    })
    function ajaxFileUpload(upload_field)
    {
        console.log(upload_field.form);
        // Checking file type
        /*var re_text = /\.jpg|\.gif|\.jpeg/i;
        var filename = upload_field.value;
            if (filename.search(re_text) == -1) {
                alert("File should be either jpg or gif or jpeg");
                upload_field.form.reset();
                return false;
            }*/
        document.getElementById('picture_preview').innerHTML = '<div><img src="http://i.hizliresim.com/xAmY7B.gif" width="100%" border="0" /></div>';
        upload_field.form.action = 'index.php?r=liveobjects/office/update&id=<?=$_GET['id']?>&edit=t';
        upload_field.form.target = 'upload_iframe';
        upload_field.form.submit();
        upload_field.form.action = '';
        upload_field.form.target = '';
        setTimeout(function(){
            document.getElementById('picture_preview').innerHTML = '';
        },2500)
        return true;
    }
</script>
<div class="announcement-update">
 <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5> <?= Html::encode($this->title) ?></h5>

            <div class="ibox-tools">

                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
               
                <a class="close-link" href="index.php?r=liveobjects/office">
                    <i class="fa fa-times"></i>
                </a>
            </div>
</div>
         <div class="ibox-content">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div></div>
</div>
