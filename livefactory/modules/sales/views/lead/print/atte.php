<?php
$signature = './img/signature.png';
$height = 90;


if ($model->office_id == 2 || $model->office_id == 10 || $model->office_id == 15) {
    $signature = './img/signature-mau.png';
} else if ($model->office_id == 8) {
    $signature = './img/signature-mm.jpg';
} else if ($model->office_id == 5 || $model->office_id == 7) {
    $signature = './img/signature-p2.png';
    $height = 40;
} else if ($model->office_id == 9 || $model->office_id == 18) {
    $signature = './img/signature-sol.jpg';
}else if ($model->office_id == 12 ){
    $signature = './img/signature-pue3.jpg';
}else if($model->office_id == 11 || $model->office_id == 16){
    $signature = './img/signature-mauricio.jpg';
}
else if($model->office_id == 13 || $model->office_id == 19){
    $signature = './img/firma-salvador.jpg';
}
else if ($model->office_id == 17){
    $signature = './img/firma-irma.jpg';
}
?>
<table border="0" cellspacing="0" cellpadding="0" class="row">
    <tbody>
    <tr>
        <td class="text-center" width="50%">
            <div style="height: 90px;display: flex;align-items: flex-end;justify-content: center;">
                <strong>Atentamente:</strong>
            </div>
            <div style="height: 40px">
                <p><strong><?=strtoupper($model->office->business_name)?></strong></p>
            </div>
        </td>

    </tr>
    </tbody>
</table>