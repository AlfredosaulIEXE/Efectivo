<?php
$type = Yii::$app->request->getQueryParam('type');

$margin = ($type == 'cover' || $type == 'receipt') ? '1.5cm' : '4.0cm 2.0cm 3.0cm';
?>
<!doctype html>
<html lang="es_MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir</title>
    <style type="text/css" media="all">
        html, body {
            width: 100%;
            height: 100%;
        }

        html {
            display: table;
            height: 100%;
            width: 100%;
        }

        body {
            display: table-row;
            background: #fff;
            color: #000;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
        }
        table {
            margin: auto;
        }
        td {
            font-weight: lighter;
            text-align: left;
            padding: 3px 3px;
        }
        tr.top-box td:last-child {
            padding-left: 35px;
        }
        td.left-box {
            border: 1px solid #000;
            border-width: 1px 0 1px 1px;
        }
        td.right-box {
            border: 1px solid #000;
        }
        .b-t-n {
            border-bottom: none!important;
        }
        table.main {
            border-collapse: collapse; table-layout: fixed; width: 640pt; /*border: 1px solid #000;*/
        }
        table.row {
            width: 460pt;
        }
        table.contractp2 {
            width: 630pt;
        }
        table.contractp2 p {
            /*font-size: 11pt;*/
        }
        table.cover {
            width: 100%;
        }
        table.cover td {
            text-transform: uppercase;
            width: 20%;
        }
        table.receipt td {
            width: 33.33333333%;
        }
        td.title {
            color: #0f7b9f;
            padding: 10px 5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr.label td {
            background-color: #f5f5f5;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            text-transform: uppercase;
            padding: 5px 5px;
            font-size: 7pt;
        }
        tr.label td:last-child,
        tr.value td:last-child {
            border-right: 1px solid #000;
        }
        tr.value td {
            font-weight: bold;
            text-transform: uppercase;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            padding: 6px 5px;
        }
        tr.value.divider td {
            padding: 0;
            border-width: 1px 0 0;
        }
        .text-justify {
            text-align: justify;
            line-height: 16pt;
        }
        .text-right {
            text-align: right!important;
        }
        .text-center {
            text-align: center!important;
        }
        li {
            padding: 5px 0;
            text-align: justify;
        }
        .yesorno {
            display: inline-block;
            float: right;
        }

        .yesorno > span {
            display: inline-block;
            margin: 0 10px;
        }
        @media print {
            @page {
                size: letter;
                margin: <?=$margin?>;
            }
        }
    </style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="1023" class="main">
    <tr>
        <td>
            <?=$content; ?>
        </td>
    </tr>
</table>
</body>
</html>