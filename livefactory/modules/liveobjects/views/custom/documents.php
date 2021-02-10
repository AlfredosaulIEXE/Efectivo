<?php

use livefactory\models\FileModel;

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

$docHeaders = [
    'home' => 'Comprobante de Domicilio',
    'entry' => 'Comprobante de Ingresos',
    'id' => 'Identificación Oficial',
    'curp' => 'CURP',
    'birth' => 'Acta de nacimiento',
    'signed' => 'Documentos firmados'
];

$docsTitle = mb_strtoupper($docHeaders[$type]);
$entity_type = 'lead.' . $type;

$documents = FileModel::getAttachmentFiles($entity_type, $lead->id);

// Files on ZIP
$zipbutton='<button type="submit" class="btn btn-success btn-xs"><span class="fa fa-download"></span> '.Yii::t('app', 'Download All').' </button>';
if (count($documents) > 0) {
    $attachment_files = [];
    foreach ($documents as $row) {
        $attachment_files[]="../attachments/".$row['id'].strrchr($row['file_name'], ".");
    }

    $destination = "../attachments/".$entity_type."_".$_GET['id'].".zip";
    //FileModel::create_zip($attachment_files, $destination, file_exists($destination));
}
else {
    $zipbutton = '';

    if (file_exists("../attachments/".$entity_type."_".$_GET['id'].".zip"))
        unlink("../attachments/".$entity_type."_".$_GET['id'].".zip");
}
$zipbutton = '';
//
if (($index % 2) == 0)
    echo '</div><div class="row">';
?>
<div class="col-sm-6">
    <div class="panel panel-<?=$type == 'signed' ? 'primary' : 'default'?>">
        <div class="panel-heading clearfix">
            <h3 class="pull-left" style="margin: 3px 0 0"><?=$docsTitle?></h3>
            <div class="pull-right">
                <?php
                echo '<form name="frmabc" action="'.str_replace('web/index.php', 'attachments/'.$entity_type.'_'.$_GET['id'].'.zip', $_SESSION['base_url']).'" method="post" target="_blank" style="margin: 0">';
                    echo $zipbutton;
                echo '</form>';
                ?>
            </div>
        </div>
        <div class="panel-body">
            <?php if (empty($documents)): ?>
                <div class="alert alert-warning" style="margin: 0">
                    Aún no se han cargado archivos para <strong><?=$docsTitle?></strong>
                </div>
            <?php else: ?>
            <table class="table table-bordered table-condensed" style="margin: 0">
                <tbody>
                <?php foreach ($documents as $document): ?>
                <tr>
                    <td><?php
                        $iconClass = array_key_exists(strrchr($document['file_name'], "."),$icons)?$icons[strrchr($document['file_name'], ".")]:'glyphicon glyphicon-file';
                        if(strrchr($document['file_name'], ".")=='.php'){
                            echo "
									<form name='frmx".$document['id']."' action='../attachments/view_attachment.php?pagename=".$document['id'].strrchr($document['file_name'], ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$document['id'].".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$document['file_title']."</a></form>";
                        }else{
                            echo "
									<form name='frmx".$document['id']."' action='../attachments/".$document['id'].strrchr($document['file_name'], ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frmx".$document['id'].".submit()' title='View' target='_parent'><i class='".$iconClass."'></i> ".$document['file_title']."</a></form>";
                        }
                        ?></td>
                    <td width="100" class="text-center">
                        <?php
                        if(strrchr($document['file_name'], ".")=='.php'){
                            echo "
									<form name='frm".$document['id']."' action='../attachments/view_attachment.php?pagename=".$document['id'].strrchr($document['file_name'], ".")."' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frm".$document['id'].".submit()' title='".Yii::t('app', 'View')."' target='_parent' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-eye-open'></span></a></form>";
                        }else {
                            echo "
									<form name='frm" . $document['id'] . "' action='../attachments/" . $document['id'] . strrchr($document['file_name'], ".") . "' method='post' style='display:inline' target='_blank'>
									<a href='#' onClick='document.frm" . $document['id'] . ".submit()' title='" . Yii::t('app', 'View') . "' target='_parent' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-eye-open'></span></a></form>";
                        }

                        echo Yii::$app->user->can('Documents.Delete') ? ' <a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&attachment_del_id='.$document['id'].'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete attachment').'" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>' : '';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>