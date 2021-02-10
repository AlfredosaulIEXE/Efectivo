<?php
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\models\search\CommonModel;
use livecrm\assets\AppAsset;
use livefactory\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\db\Query;
//use kartik\icons\Icon;
//Icon::map($this, Icon::EL); // Maps the Elusive icon font framework

/* @var $this \yii\web\View */
/* @var $content string */

$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
//echo $protocol;
AppAsset::register ( $this );
//$_SESSION['base_url']=$protocol."://$_SERVER[SERVER_NAME]$l[PHP_SELF]";
$_SESSION['base_url']=Url::base(true).'/index.php';
//$base_url=substr_replace($_SESSION['base_url'],'web/index.php');
$base_url=$_SESSION['base_url'];
setcookie('include_folder',$base_url,time()+7200);

if(!isset($_GET['r']))
    $_GET['r'] = 'site/index';

if(isset($_GET['r']))
    setcookie('pagepath',$_GET['r'],time()+7200);
extract(CommonModel::getThemeSetting());
$replace1=array(' ','.');
$replace2=array('','');
if(!isset($_SESSION['username'])){
    if(isset(Yii::$app->user->identity))
        $_SESSION['username']=str_replace($replace1,$replace2,Yii::$app->user->identity->first_name)."_".trim(str_replace($replace1,$replace2,Yii::$app->user->identity->last_name))."_".Yii::$app->user->identity->id;
}

function getUserQueuesCount(){
    $connection = \Yii::$app->db;
    $sql="select tbl_queue_users.*,tbl_queue.* from tbl_queue_users,tbl_queue where tbl_queue.id = tbl_queue_users.queue_id and tbl_queue_users.user_id = '".Yii::$app->user->identity->id."'";
    $command=$connection->createCommand($sql);
    $dataReader=$command->queryAll();
    return count($dataReader);
}

//fetch user in the queue
function getUserQueues(){
    $connection = \Yii::$app->db;
    $sql="select tbl_queue_users.*,tbl_queue.* from tbl_queue_users,tbl_queue where tbl_queue.id = tbl_queue_users.queue_id and tbl_queue_users.user_id = '".Yii::$app->user->identity->id."'";
    $command=$connection->createCommand($sql);
    $dataReader=$command->queryAll();
    //var_dump($dataReader);
    if(isset($_GET['Ticket']['queue_id']))
        $qid = $_GET['Ticket']['queue_id'];
    else
        $qid = 0;

    if(isset($_REQUEST['Ticket']['queue_id']))
    {
        $label_name ="<ul class='nav nav-second-level'>";
    }
    else
    {
        $label_name ="<ul class='nav nav-second-level collapse'>";
    }
    if(count($dataReader) > 0){
        foreach($dataReader as $role){
            $val = $qid==$role['queue_id']?'active':'';
            //$sel = $qid==$role['queue_id']?'selected':'';
            $label_name.="<li class='".$val."'><a href='index.php?Ticket[queue_id]=$role[queue_id]&r=support/ticket/queue&id=$role[queue_id]'>".$role['queue_title']."</a></li>";
        }
    }
    $label_name.="</ul>";
    //var_dump($label_name);
    return $label_name;
}
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>

    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="7A0IUjHYfvck4r-cty7FCqfbzbRSSnEFqspUGfhflG4" />

    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>

    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">



    <!-- Morris -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v3" rel="stylesheet">
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Ion.RangeSlider -->
    <link href="css/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
    <link href="css/plugins/ionRangeSlider/ion.rangeSlider.skinModern.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


    <?=Yii::$app->params['RTL_THEME']=='Yes'?'<link href="css/plugins/bootstrap-rtl/bootstrap-rtl.min.css" rel="stylesheet">':'' ?>
    <style>
        <?php if( isset($_GET['r']) && $_GET['r'] !='site/index' &&  $_GET['r'] !=''){?>
        .gray-bg{background:#f3f3f4 !important}
        <?php } ?>
        .cke_contents {
            min-height:250px
        }
        .modal-backdrop.in{
            height:100%;
            position:fixed
        }
        .nav-tabs .active{
            border-bottom:1px solid #fff  !important;
        }
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{
            border-color:#dddddd #dddddd #fff !important
        }
        .theme-config {display:none !important}
        .theme-user {
            overflow: hidden;
            position: absolute;
            right: 0;
            top: 90px;
        }
        <?php if(Yii::$app->params['RTL_THEME']=='Yes'){ ?>
        .datetimepicker{
            width:200px !important
        }
        .btn-toolbar .dropdown-menu > li > a{
            text-align:right
        }
        .ibox-tools{
            float:left
        }
        .ibox-title h5{
            float:right
        }
        .minimalize-styl-2{float:right !important}
        .navbar-top-links{margin-left:50px}
        #page-wrapper{margin:0 220px 0 0}
        body.mini-navbar #page-wrapper{margin: 0 70px 0 0;}
        .close {float:left !important}
        .modal-footer{text-align:right !important }
        .mini-navbar .nav-second-level{
            left:-140px;
        }
        <?php }

        if($DEFAULT =='1'){
         ?>
        .panel-info {
            border-color: #1c84c6 !important;
        }
        .panel-info > .panel-heading {
            background-color: #1c84c6 !important;
            border-color: #1c84c6 !important;
        }
       .migrateclass{ border-color: #1eacae !important}
       .migrateclass > .panel-heading{ border-color: #1eacae!important;background-color: #1eacae!important;}

        if($BLUE_LIGHT =='1'){
        ?>
         .panel-info {
             border-color: #1c84c6 !important;
         }
        .panel-info > .panel-heading {
            background-color: #1c84c6 !important;
            border-color: #1c84c6 !important;
        }

        <?php }
        if($YELLOW =='1'){
        ?>
        .panel-info {
            border-color: #ecba52 !important;
        }
        .panel-info > .panel-heading {
            background-color: #ecba52 !important;
            border-color: #ecba52 !important;
        }
        <?php } ?>

        <?php
        if($RED =='1'){
        ?>
        .panel-info {
            border-color: #FF0000 !important;
        }
        .panel-info > .panel-heading {
            background-color: #FF0000 !important;
            border-color: #FF0000 !important;
        }
        <?php } ?>

        <?php
        if($GREEN =='1'){
        ?>
        .panel-info {
            border-color: #1ab394 !important;
        }
        .panel-info > .panel-heading {
            background-color: #1ab394 !important;
            border-color: #1ab394 !important;
        }
        <?php } ?>
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121821004-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-121821004-8');
    </script>
    <script>
         user_role = <?php if(isset(Yii::$app->user->id)) echo json_encode((new Query())->select('auth_assignment.item_name')->from('tbl_user')->leftJoin('auth_assignment', 'auth_assignment.user_id = tbl_user.id')->leftJoin('auth_item','auth_item.name = auth_assignment.item_name')->where('auth_item.type = 2 && id = ' . Yii::$app->user->id)->all());?>;
         //console.log(user_role[0]["item_name"]);
        var conn = new WebSocket('ws://138.68.60.92:8080');
        var user_id = <?php echo  Yii::$app->user->id ?>;
        var last_window = true;
        var wsData = {
            user_id: <?php echo  Yii::$app->user->id ?>,
            tab_id: "<?php echo uniqid('tab_'); ?>",
            role_id: user_role[0]["item_name"]
        };


        window.onunload = function () {
            console.log('onunload');
            var jsonData = wsData;
            jsonData.command = "closeTab";
            conn.send(JSON.stringify(jsonData));
        };
        $session = [];


        conn.onopen = function(e) {
            console.log("Connection established!");
            subscribe(wsData);
        };
        conn.onclose = function(e){
            console.log("Connection closed!!");
        };

        conn.onmessage = function(e) {
             console.log('data: ' , e.data , ', ');
            $session[user_id] = e.data;
             console.log('session_id: ' ,$session );
             console.log("server",wsData["tab_id"]);
            if (e.data == wsData["tab_id"])
            {
                alert('Has abierto otra ventana.');
                $(document).ready(function()
                {
                    $("#mostrarmodal").modal("show");
                });
                document.body.innerHTML = '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">\n' +
                    '      <div class="modal-dialog">\n' +
                    '        <div class="modal-content">\n' +
                    '           <div class="modal-header">\n' +
                    '          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                    '              <h3>Advertencia</h3>\n' +
                    '           </div>\n' +
                    '           <div class="modal-body">\n' +
                    '              <h4>Solo se podra tener una pestaña activa del CRM en el navegador</h4>\n' +
                    '       </div>\n' +
                    '           <div class="modal-footer">\n' +
                    '          <a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>\n' +
                    '           </div>\n' +
                    '      </div>\n' +
                    '   </div>\n' +
                    '</div>';
            }
        };

        function subscribe(jsonData) {
            jsonData.command = "subscribe";
            //console.log(jsonData);
            conn.send(JSON.stringify(jsonData));
        }

        function sendMessage(msg) {
            conn.send(JSON.stringify({command: "message", message: msg}));
        }
    </script>
    <!-- Alert from user activity where the modal check if is in activity-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</head>


<?php
if(floatval(phpversion()) < 5.4){?>
    <div class="alert alert-danger"><h1>Php Version should be Greater than 5.3</h1></div>
    <?php exit();
}?>
<?php
CommonModel::destroyUserSessionStatus();

if (!Yii::$app->user->isGuest && isset($_GET['r']) && strpos($_GET['r'],'active-user') === false)
{

?>


<body class="fixed-navigation <?=Yii::$app->params['RTL_THEME']=='Yes'?'rtls':''?> <?=$COLLAPSE_MENU?'mini-navbar':''?> <?=$FIXED_SIDEBAR?'fixed-sidebar':''?> <?=$TOP_NAVBAR?'fixed-nav':''?> <?=$BOXED_LAYOUT?'boxed-layout':''?> <?=$BLUE_LIGHT?'skin-1':''?>  <?=$YELLOW?'skin-3':''?> <?=$RED?'skin-4':''?>">
<?php
$this->beginBody();
?>

<div id="wrapper">



    <?php

    function activeParentMenu($array){
        return  in_array($_GET['r'],$array)?'active':' ';
    }

    function activeSecondLevelMenu($array){
        return  in_array($_GET['r'],$array)?'active':'collapse';
    }

    function activeThirdLevelMenu($array){
        return in_array($_GET['r'],$array)?'active':'collapse';
    }

    function activeThirdLevelEstimateMenu($entity_type){
        return ($_GET['entity_type'] == $entity_type ) ? 'active' : 'collapse';
    }

    function activeMenu($link){

        return  $_GET['r']==$link?'active':' ';
    }
    function activemyMenu($id)
    {
        return $id!=''?'active':' ';
    }
    function activesubmenuMenu( $action, $entity_type )
    {
        $path = parse_url( $_SERVER['REQUEST_URI']);
        $route = $_GET['r'];
        $route = explode( "/", trim( $route, "/" ) );
        return ( $action == $route[2] && $_GET['entity_type'] == $entity_type ) ? 'active' : ' ';
    }
    function activecustomerMenu($entity_type)
    {
        return ($_GET['entity_type'] == $entity_type ) ? 'active' : ' ';
    }
    function activeleadMenu($entity_type)
    {
        return ($_GET['entity_type'] == $entity_type ) ? 'active' : ' ';
    }
    ?>




    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="<?=$FIXED_SIDEBAR?'slimScrollDiv':'sidebar-collapse'?>" <?=$FIXED_SIDEBAR?'style="position:relative;overflow:hidden;width:auto;height:100%"':''?>>
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">

                        <center>
                              <span>
                                  <?php $office =(new Query())->select('tbl_user.office_id, tbl_office.business_name, tbl_office.updated_at')->from('tbl_user')->join('LEFT JOIN', 'tbl_office', 'tbl_office.id = tbl_user.office_id')->where('tbl_user.id = ' . Yii::$app->user->id)->one(); ?>
                                  <?php if(file_exists('../office/' . $office['office_id'] .'.png')){?>
                                      <img src="../office/<?=$office['office_id']?>.png?v<?=$office['updated_at']?>" height="170" class="upload  img-responsive">
                                  <?php }else{?>
                                      <img alt="image" style="height:48px" src="../logo/logo.png" />
                                  <?php } ?>


                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?=isset($office['business_name']) ? $office['business_name'] : ''?></strong>
                             </span>
                            </a>
                        </center>

                    </div>
                    <div class="logo-element">
                        <?php echo Yii::t('app', 'Live'); ?>
                    </div>
                </li>
                <li class="<?=activeMenu('site/index')?>">
                    <a href="index.php?r=site/index"><i class="fa fa-th-large"></i> <span class="nav-label"><?php echo Yii::t('app', 'Dashboard'); ?></span></a>

                </li>

                <!-- Begin sales menu -->
                <!-- Added by Ashish on 02/05/2017 for Lead management -->
                <?php
                $sales_menu=array('sales/lead/create','sales/lead/','sales/lead/view','sales/lead/my-leads', 'sales/lead/crm', 'sales/lead/appointments');
                if((Yii::$app->user->can('Lead.Index')
                        || Yii::$app->user->can('Lead.Create') || Yii::$app->user->can('Lead.MyLead'))
                    && in_array('sales',Yii::$app->params['modules']))
                {
                    ?>
                    <li class="<?= activeParentMenu($sales_menu)?>">
                        <a href="#"><i class="fa fa-diamond"></i> <span class="nav-label"><?php echo Yii::t('app', 'Sales'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($sales_menu)?>">
                            <?php if(Yii::$app->user->can('Lead.Create')){?>
                                <li class="<?=activeMenu('sales/lead/create')?>"><a href="index.php?r=sales/lead/create"><?php echo Yii::t('app', 'Add Lead'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Lead.MyLead')){?>
                                <li class="<?=activeMenu('sales/lead/my-leads')?>"><a href="index.php?r=sales/lead/my-leads"><?php echo Yii::t('app', 'My Leads'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Lead.AllLeads')){?>
                                <li class="<?=activeMenu('sales/lead/')?>"><a href="index.php?r=sales/lead/"><?php echo Yii::t('app', 'Manage Leads'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Lead.Crm')){?>
                                <li class="<?=activeMenu('sales/lead/crm')?>"><a href="index.php?r=sales/lead/crm"><?php echo Yii::t('app', 'Leads CRM'); ?></a></li>
                             <?php } if(Yii::$app->user->can('Lead.Appointments')){?>
                                <li class="<?=activeMenu('sales/lead/appointments')?>"><a href="index.php?r=sales/lead/appointments"><?php echo Yii::t('app', 'Appointments'); ?></a></li>
                            <?php } ?>

                        </ul>
                    </li>

                <?php }
                ?>
                <!-- End sales menu -->

                <?php
                //echo "test = ".Yii::$app->user->can('Customer.Index');
                $customer_menu=array('customer/customer/create','customer/customer/index','customer/customer/customer-view', 'sales/lead/my-leads', 'sales/lead/service', 'sales/lead/my-leadsa', 'sales/lead/appointmentsa');
                $customer_menu_reports = array('sales/lead/report_customer', 'sales/lead/logcustomer');
                if((Yii::$app->user->can('Customer.Index') || Yii::$app->user->can('Customer.Create')) && in_array('customer',Yii::$app->params['modules'])){
                    ?>
                    <li class="<?= activeParentMenu($customer_menu)?>">
                        <a href="#"><i class="fa fa-users"></i> <span class="nav-label"><?php echo Yii::t('app', 'Customers Attention'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($customer_menu)?>">
                            <?php if((Yii::$app->user->can('Admin')) || (Yii::$app->user->can('Customer.Index'))){?>
                                <li class="<?=activeMenu('sales/lead/my-leadsa')?>"><a href="index.php?r=sales/lead/my-leadsa"><?php echo Yii::t('app', 'Mis Clientes'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Customer.Index')){?>
                                <li class="<?=activeMenu('sales/lead/service')?>"><a href="index.php?r=sales/lead/service"><?php echo Yii::t('app', 'Manage Customers'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Lead.Appointments')){?>
                                <li class="<?=activeMenu('sales/lead/appointmentsa')?>"><a href="index.php?r=sales/lead/appointmentsa"><?php echo Yii::t('app', 'Appointments'); ?></a></li>
                            <?php } ?>
                            <li class="<?= activeParentMenu($customer_menu_reports)?>">
                                <a href="#"><span class="nav-label"><?php echo Yii::t('app', 'Reportes'); ?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level <?= activeThirdLevelMenu($customer_menu)?>">
                                    <?php if((Yii::$app->user->can('Admin')) || (Yii::$app->user->can('Customer.Index'))){?>
                                        <li class="<?=activeMenu('sales/lead/report_customer')?>"><a href="index.php?r=sales/lead/report_customer"><?php echo Yii::t('app', 'Reporte de atención a clientes'); ?></a></li>

                                    <?php } if(Yii::$app->user->can('Admin') || (Yii::$app->user->can('Customer.Index'))){?>
                                        <li class="<?=activeMenu('sales/lead/logcustomer')?>"><a href="index.php?r=sales/lead/logcustomer"><?php echo Yii::t('app', 'Bitacora de gestores'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>

                <?php }
                ?>
<!--                //insurance-->
                <?php
                //echo "test = ".Yii::$app->user->can('Customer.Index');
                $insurance_menu=array('insurance/my_insurance','insurance/all_insurance','insurance_appointmentsi','insurance_reports','sales/lead/my-leadsi', 'sales/lead/insuranceindex',  'sales/lead/appointmentsi');
                $insurance_menu_sub = array('sales/lead/insurance', 'sales/lead/rankinginsurance', 'sales/lead/useractivityinsurance', 'sales/lead/loginsurance');
                if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){
                    ?>
                    <li class="<?= activeParentMenu($insurance_menu)?>">
                        <a href="#"><i class="fa fa-users"></i> <span class="nav-label"><?php echo Yii::t('app', 'Seguros'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($insurance_menu)?>">
                            <?php if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                <li class="<?=activeMenu('sales/lead/my-leadsi')?>"><a href="index.php?r=sales/lead/my-leadsi"><?php echo Yii::t('app', 'Mis Seguros'); ?></a></li>
                            <?php } if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                <li class="<?=activeMenu('sales/lead/insuranceindex')?>"><a href="index.php?r=sales/lead/insuranceindex"><?php echo Yii::t('app', 'Todos los seguros'); ?></a></li>
                            <?php } if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                <li class="<?=activeMenu('sales/lead/appointmentsi')?>"><a href="index.php?r=sales/lead/appointmentsi"><?php echo Yii::t('app', 'Citas'); ?></a></li>
                            <?php } ?>
                            <li class="<?= activeParentMenu($insurance_menu_sub)?>">
                                <a href="#"><span class="nav-label"><?php echo Yii::t('app', 'Reportes'); ?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level <?= activeThirdLevelMenu($insurance_menu_sub)?>">
                                    <?php if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                        <li class="<?=activeMenu('sales/lead/insurance')?>"><a href="index.php?r=sales/lead/insurance"><?php echo Yii::t('app', 'Reporte de venta de seguros'); ?></a></li>
                                    <?php } if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                        <li class="<?=activeMenu('sales/lead/rankinginsurance')?>"><a href="index.php?r=sales/lead/rankinginsurance"><?php echo Yii::t('app', 'Ranking de seguros'); ?></a></li>
                                    <?php } if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                        <li class="<?=activeMenu('sales/lead/useractivityinsurance')?>"><a href="index.php?r=sales/lead/useractivityinsurance"><?php echo Yii::t('app', 'Bitacora de actividad gestores'); ?></a></li>

                                     <?php } if((Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){?>
                                        <li class="<?=activeMenu('sales/lead/loginsurance')?>"><a href="index.php?r=sales/lead/loginsurance"><?php echo Yii::t('app', 'Bitacora de  gestores'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>

                <?php }
                ?>


                <?php
                $ticket_menu = array('support/ticket/create');
                if(in_array('support',Yii::$app->params['modules']) && Yii::$app->user->can('Queue.index')){
                    $label = getUserQueues();
                    ?>

                    <li class="<?php
                    if(isset($_REQUEST['Ticket']['queue_id']))
                    {
                        echo activemyMenu($_REQUEST['Ticket']['queue_id']);
                    }
                    ?>">

                        <?php
                        if(getUserQueuesCount() > 0)
                        {
                            ?>
                            <a href="#"><i class="fa fa-database"></i> <span class="nav-label"><?php echo Yii::t('app', 'My Queues'); ?></span><span class="fa arrow"></span></a>
                            <?php
                        }
                        ?>

                        <?= $label ?>
                    </li>

                    <?php
                }
                ?>


                <?php
                $ticket_menu = array('support/ticket/create','support/ticket/my-tickets','support/ticket/index','support/ticket/my-calendar','support/ticket/update','support/ticket/job-queue');
                if((Yii::$app->user->can('Ticket.Index') || Yii::$app->user->can('Ticket.Create') || Yii::$app->user->can('Ticket.MyCalendar') || Yii::$app->user->can('Ticket.MyTicket')) && in_array('support',Yii::$app->params['modules'])){
                    ?>
                    <li class="<?= activeParentMenu($ticket_menu)?>">
                        <a href="#"><i class="fa fa-ticket"></i> <span class="nav-label"><?php echo Yii::t('app', 'Tickets'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($ticket_menu)?>">
                            <?php if(Yii::$app->user->can('Ticket.Create')){ ?>
                                <li class="<?=activeMenu('support/ticket/create')?>"><a href="index.php?r=support/ticket/create"><?php echo Yii::t('app', 'Create Ticket'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Ticket.MyTicket')){?>
                                <li class="<?=activeMenu('support/ticket/my-tickets')?>"><a href="index.php?r=support/ticket/my-tickets"><?php echo Yii::t('app', 'My Tickets'); ?><span class="label label-warning pull-right livecrm-skin"><?=CommonModel::getPendingTicketCountLabel()?></span></a></li>
                            <?php } if(Yii::$app->user->can('Ticket.Index')){?>
                                <li class="<?=activeMenu('support/ticket/index')?>"><a href="index.php?r=support/ticket/index"><?php echo Yii::t('app', 'Manage Tickets'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Ticket.MyCalendar')){?>
                                <li class="<?=activeMenu('support/ticket/my-calendar')?>"><a href="index.php?r=support/ticket/my-calendar"><?php echo Yii::t('app', 'My Calendar'); ?></a></li>

                                <!-- <li class="<?=activeMenu('support/ticket/job-queue')?>"><a href="index.php?r=support/ticket/job-queue"><?php echo Yii::t('app', 'Job Queue'); ?></a></li> -->
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                $resolution_menu = array('support/ticket-resolution/index','support/ticket-resolution/update');
                if(in_array('support',Yii::$app->params['modules']) && (Yii::$app->user->can('Resolutions.index'))){
                    ?>

                    <li class="<?= activeParentMenu($resolution_menu)?>">
                        <a href="#"><i class="fa fa-book"></i> <span class="nav-label"><?php echo Yii::t('app', 'Resolution'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($resolution_menu)?>">
                            <li class="<?=activeMenu('support/ticket-resolution/index')?>"><a href="index.php?r=support/ticket-resolution/index"><?php echo Yii::t('app', 'Manage Resolutions'); ?></a></li>
                        </ul>
                    </li>
                    <?php
                }
                ?>

                <?php
                $project_menu=array('pmt/project/create','pmt/project/index','pmt/project/project-view');
                if((Yii::$app->user->can('Project.Index') || Yii::$app->user->can('Project.Create')) && in_array('pmt',Yii::$app->params['modules'])){
                    ?>
                    <li class="<?= activeParentMenu($project_menu)?>">
                        <a href="#"><i class="fa fa-briefcase"></i> <span class="nav-label"><?php echo Yii::t('app', 'Projects'); ?> </span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($project_menu)?>">
                            <?php
                            if(Yii::$app->user->can('Project.Create')){
                                ?>
                                <li class="<?=activeMenu('pmt/project/create')?>"><a href="index.php?r=pmt/project/create"><?php echo Yii::t('app', 'Add Project'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Project.Index')){?>
                                <li class="<?=activeMenu('pmt/project/index')?>"><a href="index.php?r=pmt/project/index"><?php echo Yii::t('app', 'Manage Projects'); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                }
                $task_menu=array('pmt/task/create','pmt/task/my-tasks','pmt/task/index','pmt/task/task-view','pmt/task/my-calendar','pmt/task/task-view','pmt/task/allocation','pmt/task/estimation');
                if((Yii::$app->user->can('Task.Index') || Yii::$app->user->can('Task.Create') || Yii::$app->user->can('Task.MyCalendar') || Yii::$app->user->can('Task.MyTask')) && in_array('pmt',Yii::$app->params['modules'])){
                    ?>
                    <li class="<?= activeParentMenu($task_menu)?>">
                        <a href="#"><i class="fa fa-edit"></i> <span class="nav-label"><?php echo Yii::t('app', 'Tasks'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($task_menu)?>">
                            <?php if(Yii::$app->user->can('Task.Create')){ ?>
                                <li class="<?=activeMenu('pmt/task/create')?>"><a href="index.php?r=pmt/task/create"><?php echo Yii::t('app', 'Add Task'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Task.MyTask')){?>
                                <li class="<?=activeMenu('pmt/task/my-tasks')?>"><a href="index.php?r=pmt/task/my-tasks"><?php echo Yii::t('app', 'My Tasks'); ?><span class="label label-warning pull-right livecrm-skin"><?=CommonModel::getPendingTaksCountLabel()?></span></a></li>
                            <?php } if(Yii::$app->user->can('Task.Index')){?>
                                <li class="<?=activeMenu('pmt/task/index')?>"><a href="index.php?r=pmt/task/index"><?php echo Yii::t('app', 'Manage Tasks'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Task.MyCalendar')){?>
                                <li class="<?=activeMenu('pmt/task/my-calendar')?>"><a href="index.php?r=pmt/task/my-calendar"><?php echo Yii::t('app', 'My Calendar'); ?></a></li>
                            <?php } ?>
                            <!--<li class="<?=activeMenu('pmt/task/allocation')?>"><a href="index.php?r=pmt/task/allocation"><?php echo Yii::t('app', 'Allocation'); ?></a></li>
							<li class="<?=activeMenu('pmt/task/estimation')?>"><a href="index.php?r=pmt/task/estimation"><?php echo Yii::t('app', 'Estimation'); ?></a></li> -->
                        </ul>
                    </li>
                    <?php
                }
                $defect_menu=array('pmt/defect/create','pmt/defect/my-defects','pmt/defect/index','pmt/defect/defect-view','pmt/defect/my-calendar','pmt/defect/defect-view');
                if((Yii::$app->user->can('Defect.Index') || Yii::$app->user->can('Defect.Create') || Yii::$app->user->can('Defect.MyCalendar') || Yii::$app->user->can('Defect.MyDefect')) && in_array('pmt',Yii::$app->params['modules'])){
                    ?>
                    <li class="<?= activeParentMenu($defect_menu)?>">
                        <a href="#"><i class="fa fa-bug"></i> <span class="nav-label"><?php echo Yii::t('app', 'Defects'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($defect_menu)?>">
                            <?php if(Yii::$app->user->can('Defect.Create')){ ?>
                                <li class="<?=activeMenu('pmt/defect/create')?>"><a href="index.php?r=pmt/defect/create"><?php echo Yii::t('app', 'Report Defect'); ?></a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Defect.MyDefect')){ ?>
                                <li class="<?=activeMenu('pmt/defect/my-defects')?>"><a href="index.php?r=pmt/defect/my-defects"><?php echo Yii::t('app', 'My Defects'); ?><span class="label label-warning pull-right livecrm-skin"><?=CommonModel::getPendingDefectCountLabel()?></span></a></li>
                            <?php } if(Yii::$app->user->can('Defect.Index')){ ?>
                                <li class="<?=activeMenu('pmt/defect/index')?>"><a href="index.php?r=pmt/defect/index"><?php echo Yii::t('app', 'Manage Defects'); ?></a></li>
                            <?php } if(Yii::$app->user->can('Defect.MyCalendar')){  ?>
                                <li class="<?=activeMenu('pmt/defect/my-calendar')?>"><a href="index.php?r=pmt/defect/my-calendar"><?php echo Yii::t('app', 'My Calendar'); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>




                <?php
                if((Yii::$app->user->can('Product.Index') || Yii::$app->user->can('Product.Create')) && in_array('product',Yii::$app->params['modules'])){
                    $product_menu=array('product/product/create','product/product/index','product/product/product-view');
                    ?>
                    <li class="<?= activeParentMenu($product_menu)?>">
                        <a href="#"><i class="fa fa-cart-plus"></i> <span class="nav-label"><?php echo Yii::t('app', 'Products'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($product_menu)?>">
                            <?php if(Yii::$app->user->can('Product.Create')){ ?>
                                <li class="<?=activeMenu('product/product/create')?>"><a href="index.php?r=product/product/create"><?php echo Yii::t('app', 'Add Product'); ?></a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Product.Index')){ ?>
                                <li class="<?=activeMenu('product/product/index')?>"><a href="index.php?r=product/product/index"><?php echo Yii::t('app', 'Manage Product'); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                }
                /* Estimate tree menu */
                if((Yii::$app->user->can('Customer.Estimate.Index') || Yii::$app->user->can('Customer.Estimate.Create') || Yii::$app->user->can('Sales.Estimate.Index') || Yii::$app->user->can('Sales.Estimate.Create')) && (in_array('estimate',Yii::$app->params['modules']))) {
                    $estimate_menu=array('estimate/estimate/create','estimate/estimate/index','estimate/estimate/update','estimate/estimate/view');
                    $estimate_sub_menu=array('estimate/estimate/create','estimate/estimate/index');
                    ?>
                    <li class="<?= activeParentMenu($estimate_menu)?>">
                        <a href="#"><i class="fa fa-files-o"></i> <span class="nav-label"><?php echo Yii::t('app', 'Estimates'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($estimate_menu)?>">
                            <?php
                            if(in_array('invoice',Yii::$app->params['modules']) && (Yii::$app->user->can('Customer.Estimate.Index') || Yii::$app->user->can('Customer.Estimate.Create')))
                            {
                                ?>
                                <li class="<?=activecustomerMenu('customer')?>">
                                    <a href="#"><?php echo Yii::t('app', 'Customer Estimates'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelEstimateMenu('customer')?>">
                                        <?php
                                        if(Yii::$app->user->can('Customer.Estimate.Create'))
                                        {
                                            ?>
                                            <li class="<?= activesubmenuMenu('create', 'customer') ?>">
                                                <a href="index.php?r=estimate/estimate/create&entity_type=customer"><?php echo Yii::t('app', 'Add Estimate');?> </a></li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Customer.Estimate.Index'))
                                        {
                                            ?>
                                            <li class="<?= activesubmenuMenu('index', 'customer') ?>">
                                                <a href="index.php?r=estimate/estimate/index&entity_type=customer"><?php echo Yii::t('app', 'Manage Estimate'); ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }

                            if(in_array('sales',Yii::$app->params['modules']) && (Yii::$app->user->can('Sales.Estimate.Index') || Yii::$app->user->can('Sales.Estimate.Create')))
                            {
                                ?>
                                <li class="<?=activeleadMenu('lead')?>">
                                    <a href="#"><?php echo Yii::t('app', 'Lead Estimates'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelEstimateMenu('lead')?>">
                                        <?php
                                        if(Yii::$app->user->can('Sales.Estimate.Create'))
                                        {
                                            ?>
                                            <li class="<?= activesubmenuMenu('create', 'lead') ?>">
                                                <a href="index.php?r=estimate/estimate/create&entity_type=lead"><?php echo Yii::t('app', 'Add Estimate');?> </a></li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Sales.Estimate.Index'))
                                        {
                                            ?>
                                            <li class="<?= activesubmenuMenu('index', 'lead') ?>">
                                                <a href="index.php?r=estimate/estimate/index&entity_type=lead"><?php echo Yii::t('app', 'Manage Estimate'); ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                /* End Estimate Tree Menu */

                if((Yii::$app->user->can('Invoice.Index') || Yii::$app->user->can('Invoice.Create')) && in_array('invoice',Yii::$app->params['modules'])){
                    $invoice_menu=array('invoice/invoice/create','invoice/invoice/index','invoice/invoice/view','invoice/invoice/update');
                    ?>
                    <li class="<?= activeParentMenu($invoice_menu)?>">
                        <a href="#"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo Yii::t('app', 'Invoices'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($invoice_menu)?>">
                            <?php if(Yii::$app->user->can('Invoice.Create')){ ?>
                                <li class="<?=activeMenu('invoice/invoice/create')?>"><a href="index.php?r=invoice/invoice/create"><?php echo Yii::t('app', 'Add Invoice'); ?></a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Invoice.Index')){ ?>
                                <li class="<?=activeMenu('invoice/invoice/index')?>"><a href="index.php?r=invoice/invoice/index"><?php echo Yii::t('app', 'Manage Invoice'); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                }

                $report_menu=array('pmt/task/task-assignment-report','pmt/task/task-closed-reports','pmt/task/time-spent-report','pmt/task/task-all-reports','pmt/task/task-all-reports','pmt/defect/defect-assignment-report','pmt/defect/defect-closed-reports','pmt/defect/time-spent-report','pmt/defect/defect-all-reports','pmt/defect/defect-all-reports','customer/customer/customer-all-reports','customer/customer/new-customer-report','customer/customer/customer-type-report','customer/customer/customer-country-report','user/user/user-all-reports','user/user/user-type-report','user/user/user-status-report','user/user/new-user-report','pmt/task/task-all-reports');
                $pmt_menu=array('pmt/task/task-assignment-report','pmt/task/task-closed-reports','pmt/task/time-spent-report','pmt/task/task-all-reports','pmt/task/task-all-reports','pmt/defect/defect-assignment-report','pmt/defect/defect-closed-reports','pmt/defect/time-spent-report','pmt/defect/defect-all-reports','pmt/defect/defect-all-reports');
                $customer_menu=array('customer/customer/customer-all-reports','customer/customer/new-customer-report','customer/customer/customer-type-report','customer/customer/customer-country-report');
                $lead_menu=array('sales/lead/lead-all-reports','sales/lead/new-lead-report','sales/lead/lead-type-report','sales/lead/lead-country-report', 'sales/lead/lead-status-report');
                $user_menu=array('user/user/user-all-reports','user/user/user-type-report','user/user/user-status-report','user/user/new-user-report');
                if((Yii::$app->user->can('Report.AllUser') || Yii::$app->user->can('Report.CustomerAllReports') || Yii::$app->user->can('Report.CustomerCountry') || Yii::$app->user->can('Report.CustomerType') || Yii::$app->user->can('Report.DefectAssignment') || Yii::$app->user->can('Report.DefectClosedReport ') || Yii::$app->user->can('Report.DefectTimeSpentReport') || Yii::$app->user->can('Report.NewCustomer') || Yii::$app->user->can('Report.NewUser') || Yii::$app->user->can('Report.ProjectAllReports') || Yii::$app->user->can('Report.TaskAssignmentReport') || Yii::$app->user->can('Report.TaskClosedReport') || Yii::$app->user->can('Report.TaskTimeSpentReport ') || Yii::$app->user->can('Report.UserStatus') || Yii::$app->user->can('Report.UserType') || Yii::$app->user->can('Report.LeadAllReports') || Yii::$app->user->can('Report.LeadCountry') || Yii::$app->user->can('Report.LeadType') || Yii::$app->user->can('Report.NewLead') || Yii::$app->user->can('Report.LeadFunnel') ) and (in_array('pmt',Yii::$app->params['modules'])  || in_array('user',Yii::$app->params['modules']) || in_array('customer',Yii::$app->params['modules']) || in_array('sales',Yii::$app->params['modules']) )){
                    ?>
                    <li class="<?= activeParentMenu($report_menu)?>">
                        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label"><?php echo Yii::t('app', 'Reports'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($report_menu)?>">
                            <?php
                            if((Yii::$app->user->can('Report.DefectAssignment') || Yii::$app->user->can('Report.DefectClosedReport') || Yii::$app->user->can('Report.DefectTimeSpentReport') || Yii::$app->user->can('Report.ProjectAllReports') || Yii::$app->user->can('Report.TaskAssignmentReport') || Yii::$app->user->can('Report.TaskClosedReport') || Yii::$app->user->can('Report.TaskTimeSpentReport')) && in_array('pmt',Yii::$app->params['modules'])){
                                ?>
                                <li  class="<?= activeParentMenu($pmt_menu)?>">
                                    <a href="#"><?php echo Yii::t('app', 'Project Reports'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelMenu($pmt_menu) ?>">
                                        <?php
                                        if(Yii::$app->user->can('Report.TaskAssignmentReport')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/task/task-assignment-report')?>">
                                                <a href="index.php?r=pmt/task/task-assignment-report"><?php echo Yii::t('app', 'Task Assignment Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.TaskClosedReport')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/task/task-closed-reports')?>">
                                                <a href="index.php?r=pmt/task/task-closed-reports"><?php echo Yii::t('app', 'Task Closed Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.TaskTimeSpentReport')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/task/time-spent-report')?>">
                                                <a href="index.php?r=pmt/task/time-spent-report"><?php echo Yii::t('app', 'Task Time Spent Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.DefectAssignment')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/defect/defect-assignment-report')?>">
                                                <a href="index.php?r=pmt/defect/defect-assignment-report"><?php echo Yii::t('app', 'Defect Assignment Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.DefectClosedReport')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/defect/defect-closed-reports')?>">
                                                <a href="index.php?r=pmt/defect/defect-closed-reports"><?php echo Yii::t('app', 'Defect Closed Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.DefectTimeSpentReport')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/defect/time-spent-report')?>">
                                                <a href="index.php?r=pmt/defect/time-spent-report"><?php echo Yii::t('app', 'Defect Time Spent Report'); ?></a>
                                            </li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.ProjectAllReports')){
                                            ?>
                                            <li class="<?=activeMenu('pmt/task/task-all-reports')?>">
                                                <a href="index.php?r=pmt/task/task-all-reports"><?php echo Yii::t('app', 'All Project Reports'); ?></a>
                                            </li>
                                        <?php } ?>
                                        <!--<li class="<?=activeMenu('pmt/defect/defect-all-reports')?>">
                                        <a href="index.php?r=pmt/defect/defect-all-reports"><?php echo Yii::t('app', 'All Defect Reports'); ?></a>
                                    </li>-->
                                    </ul>

                                </li>
                            <?php }
                            if((Yii::$app->user->can('Report.CustomerAllReports') || Yii::$app->user->can('Report.CustomerCountry') || Yii::$app->user->can('Report.CustomerType') || Yii::$app->user->can('Report.NewCustomer')) && in_array('customer',Yii::$app->params['modules'])){
                                ?>
                                <li  class="<?= activeParentMenu($customer_menu)?>">
                                    <a href="#"><?php echo Yii::t('app', 'Customer Reports'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelMenu($customer_menu) ?>">
                                        <?php
                                        if(Yii::$app->user->can('Report.CustomerType')){
                                            ?>
                                            <li class="<?=activeMenu('customer/customer/customer-type-report')?>"><a href="index.php?r=customer/customer/customer-type-report"><?php echo Yii::t('app', 'Customer Type Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.CustomerCountry')){
                                            ?>
                                            <li class="<?=activeMenu('customer/customer/customer-country-report')?>"><a href="index.php?r=customer/customer/customer-country-report"><?php echo Yii::t('app', 'Customer Country Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.NewCustomer')){
                                            ?>
                                            <li class="<?=activeMenu('customer/customer/new-customer-report')?>"><a href="index.php?r=customer/customer/new-customer-report"><?php echo Yii::t('app', 'New Customers Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.CustomerAllReports')){
                                            ?>
                                            <li class="<?=activeMenu('customer/customer/customer-all-reports')?>"><a href="index.php?r=customer/customer/customer-all-reports"><?php echo Yii::t('app', 'All Customer Reports'); ?></a></li>
                                        <?php }  ?>
                                    </ul>
                                </li>
                                <?php
                            }

                            if((Yii::$app->user->can('Report.LeadAllReports') || Yii::$app->user->can('Report.LeadCountry') || Yii::$app->user->can('Report.LeadType') || Yii::$app->user->can('Report.NewLead')) && in_array('sales',Yii::$app->params['modules'])){
                                ?>
                                <li  class="<?= activeParentMenu($lead_menu)?>">
                                    <a href="#"><?php echo Yii::t('app', 'Sales Reports'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelMenu($lead_menu) ?>">
                                        <?php
                                        if(Yii::$app->user->can('Report.LeadType')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/lead-type-report')?>"><a href="index.php?r=sales/lead/lead-type-report"><?php echo Yii::t('app', 'Lead Type Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.LeadStatus')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/lead-status-report')?>"><a href="index.php?r=sales/lead/lead-status-report"><?php echo Yii::t('app', 'Lead Status Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.LeadFunnel')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/lead-funnel-report')?>"><a href="index.php?r=sales/lead/lead-funnel-report"><?php echo Yii::t('app', 'Lead Funnel Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.LeadCountry')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/lead-country-report')?>"><a href="index.php?r=sales/lead/lead-country-report"><?php echo Yii::t('app', 'Lead Country Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.NewLead')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/new-lead-report')?>"><a href="index.php?r=sales/lead/new-lead-report"><?php echo Yii::t('app', 'New Leads Report'); ?></a></li>
                                        <?php }
                                        if(Yii::$app->user->can('Report.LeadAllReports')){
                                            ?>
                                            <li class="<?=activeMenu('sales/lead/lead-all-reports')?>"><a href="index.php?r=sales/lead/lead-all-reports"><?php echo Yii::t('app', 'All Sales Reports'); ?></a></li>
                                        <?php }  ?>
                                    </ul>
                                </li>
                                <?php
                            }

                            if((Yii::$app->user->can('Report.AllUser') || Yii::$app->user->can('Report.UserStatus') || Yii::$app->user->can('Report.UserType') || Yii::$app->user->can('Report.NewUser')) && in_array('user',Yii::$app->params['modules'])){?>
                                <li  class="<?= activeParentMenu($user_menu)?>">
                                    <a href="#"><?php echo Yii::t('app', 'User Reports'); ?><span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level <?= activeThirdLevelMenu($user_menu) ?>">
                                        <?php
                                        if(Yii::$app->user->can('Report.UserType')){?>
                                            <li class="<?=activeMenu('user/user/user-type-report')?>"><a href="index.php?r=user/user/user-type-report"><?php echo Yii::t('app', 'User Type Report'); ?></a></li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.UserStatus')){?>
                                            <li class="<?=activeMenu('user/user/user-status-report')?>"><a href="index.php?r=user/user/user-status-report"><?php echo Yii::t('app', 'User Status Report'); ?></a></li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.NewUser')){?>
                                            <li class="<?=activeMenu('user/user/new-user-report')?>"><a href="index.php?r=user/user/new-user-report"><?php echo Yii::t('app', 'New Users Report'); ?></a></li>
                                            <?php
                                        }
                                        if(Yii::$app->user->can('Report.AllUser')){?>
                                            <li class="<?=activeMenu('user/user/user-all-reports')?>"><a href="index.php?r=user/user/user-all-reports"><?php echo Yii::t('app', 'All User Reports'); ?></a></li>
                                        <?php }?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }
                if (Yii::$app->user->can('User.Index') || Yii::$app->user->can('Office.Index') || Yii::$app->user->can('Lead.Source')) {
                    ?>
                    <li class="<?= activeParentMenu($user_menu)?>">
                        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label"><?php echo Yii::t('app', 'Maintenance'); ?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level <?= activeSecondLevelMenu($invoice_menu)?>">
                            <?php if(Yii::$app->user->can('User.Index')){ ?>
                                <li class="<?=activeMenu('user/user/index')?>"><a href="index.php?r=user/user/index"><?=Yii::t('app', 'Manage Users')?></a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Office.Index')){ ?>
                                <li class="<?=activeMenu('liveobjects/office')?>"><a href="index.php?r=liveobjects/office"><?=Yii::t('app', 'Manage Offices')?></a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Lead.Loans')){ ?>
                                <li class="<?=activeMenu('sales/lead-loans')?>"><a href="index.php?r=sales/lead-loans">Administrar préstamos</a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Lead.Source')){ ?>
                                <li class="<?=activeMenu('sales/lead-source')?>"><a href="index.php?r=sales/lead-source">Medios de origen</a></li>
                            <?php } ?>
                            <?php if(Yii::$app->user->can('Lead.Source')){ ?>
                                <li class="<?=activeMenu('sales/lead-source')?>"><a href="index.php?r=sales/lead/deleted">Papelera de leads</a></li>
                            <?php } ?>
                            <!--User.admin-->
                            <?php if (Yii::$app->user->can('Admin')){?>
                                <li class="<?=activeMenu('liveobjects/setting/rights')?>"><a href="index.php?r=liveobjects/setting/rights">Permisos de Usuario</a></li>
                            <?php } ?>
                            <?php if (Yii::$app->user->can('Admin')){?>
                                <li class="<?=activeMenu('liveobjects/setting/rights')?>"><a href="index.php?r=sales/lead/migrateleads">Modificación de Leads</a></li>
                            <?php } ?>
                            <?php if ((Yii::$app->user->can('Admin') || Yii::$app->user->can('Audit.Member'))){?>
                                <li class="<?=activeMenu('liveobjects/unit')?>"><a href="index.php?r=liveobjects/unit">Unidad Generadora</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }
                //if (Yii::$app->user->can('Review.Index')) {
                ?>
                <li class="<?= activeParentMenu($user_menu)?>">
                    <a href="#"><i class="fa fa-search"></i> <span class="nav-label"><?php echo Yii::t('app', 'Review'); ?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level <?= activeSecondLevelMenu($invoice_menu)?>">
                        <?php //if(Yii::$app->user->can('Review.Admin') || Yii::$app->user->can('Review.Manager') || Yii::$app->user->can('Review.Sales')){ ?>
                        <li class="<?=activeMenu('sales/lead/review')?>"><a href="index.php?r=sales/lead/review"><?=Yii::t('app', 'Review Leads')?></a></li>
                        <?php //} ?>
                    </ul>
                </li>
                <?php //} ?>
                <?php if (Yii::$app->user->can('Reports.Index')): ?>
                <li class="<?= activeParentMenu($user_menu)?>">
                    <a href="#"><i class="fa fa-pie-chart"></i> <span class="nav-label"><?php echo Yii::t('app', 'Reports'); ?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level <?= activeSecondLevelMenu($invoice_menu)?>">
                        <?php if(Yii::$app->user->can('Reports.Sales')): ?>
                        <li class="<?=activeMenu('sales/lead/report')?>"><a href="index.php?r=sales/lead/report"><?=Yii::t('app', 'Sales Report')?></a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Reports.Payments')): ?>
                        <li class="<?=activeMenu('sales/lead/payments')?>"><a href="index.php?r=sales/lead/payments">Reporte de Pagos</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Payment.Validate')): ?>
                        <li class="<?=activeMenu('sales/lead/validate')?>"><a href="index.php?r=sales/lead/validate">Pagos por validar</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Reports.Effectiveness')): ?>
                        <li class="<?=activeMenu('sales/lead/effectiveness')?>"><a href="index.php?r=sales/lead/effectiveness">Reporte de Efectividad</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Reports.Ranking')): ?>
                        <li class="<?=activeMenu('sales/lead/ranking')?>"><a href="index.php?r=sales/lead/ranking">Ranking</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Reports.History')): ?>
                            <li class="<?=activeMenu('sales/lead/history')?>"><a href="index.php?r=sales/lead/history">Reporte de trabajo</a></li>
                        <?php endif;?>
                        <?php if (Yii::$app->user->can('Reports.Log')): ?>
                            <li class="<?=activeMenu('sales/lead/log')?>"><a href="index.php?r=sales/lead/log">Bitácora de agentes</a></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Reports.History')): ?>
                            <li class="<?=activeMenu('sales/lead/useractivity')?>"><a href="index.php?r=sales/lead/useractivity">Bitácora de actividad de agentes</a></li>
                        <?php endif; ?>
                        <?php if ((Yii::$app->user->can('exportMaster')) ||  !Yii::$app->user->id == 202): ?>
<!--                        --><?php //if (Yii::$app->user->id == 1 || Yii::$app->user->id == 202): ?>
                            <li class="<?=activeMenu('sales/lead/useractivity')?>"><a href="index.php?r=sales/lead/exportleads">Exportación de Leads</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

        </div>
    </nav>



    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar   <?=$TOP_NAVBAR?($BOXED_LAYOUT?'navbar-static-top':'navbar-fixed-top'):'navbar-static-top'?> gray-bg" role="navigation" style="margin-bottom: 0;<?php echo $_GET['r'] =='site/index'?'background:#fff':'';?>" >
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <?php
                    if(Yii::$app->user->identity->userType->type!="Customer")
                    {
                        ?>
                        <form role="search" class="navbar-form-custom" name="search_results" method="post" action="index.php?r=site/search-results">
                            <?php Yii::$app->request->enableCsrfValidation = true; ?>
                            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                            <div class="form-group">
                                <input type="text" value="<?= isset($_REQUEST['top_search'])?$_REQUEST['top_search']:''?>"  placeholder="<?php echo Yii::t('app', 'Search for something'); ?>..." class="form-control" name="top_search" id="top-search"">
                            </div>
                        </form>
                        <a class="minimalize-styl-2 btn btn-primary livecrm-skin " href="javascript:void(0)" onClick="document.search_results.submit()"><i class="fa fa-search"></i> </a>
                        <?php
                    }
                    ?>
                    <?php if(Yii::$app->user->can('Lead.Create')){?>
                        <a href="index.php?r=sales/lead/create" class="minimalize-styl-2 btn btn-primary"><?php echo Yii::t('app', 'Add Lead'); ?></a>
                    <?php } ?>
                    <?php if(Yii::$app->user->id == 173){?>
                        <a href="index.php?r=sales/lead/upload" class="minimalize-styl-2 btn btn-primary">Cargar leads de Excel</a>
                    <?php } ?>
                    <?php if (Yii::$app->user->can('Lead.Upload')): ?>
                        <a href="index.php?r=sales/lead/uploadleads" class="minimalize-styl-2 btn btn-primary">Carga de Leads</a>
                    <?php endif; ?>
                </div>
                <ul class="nav navbar-top-links  <?=Yii::$app->params['RTL_THEME']=='Yes'?'navbar-left':'navbar-right' ?>">
                    <li>
                        <span class="m-r-sm text-muted welcome-message time_widget"></span>
                        <span class="m-r-sm text-muted welcome-message lead_time_widget"></span>
                        <span class="m-r-sm text-muted welcome-message defect_time_widget"></span>
                    </li>
                    <li class="dropdown">
                        <?php
                        $alertCount=0;
                        $unssigned = CommonModel::getUnassignedLeads();
                        $unssignedIntmp = CommonModel::getUnassignedLeadsIntmp();
                        $appointments = CommonModel::getTodayAppointments();
                        $alertCount += $unssigned;
                        $alertCount += $appointments;
                        $alertCount += $unssignedIntmp;
                        ?>
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i> <?php if ($alertCount > 0): ?> <span class="label label-danger livecrm-skin"><?=$alertCount?></span><?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <?php if ($alertCount > 0): ?>
                                <?php if ($unssigned > 0): ?>
                                    <li>
                                        <a href="index.php?r=sales/lead/&Lead[lead_owner_id]=173">
                                            <div>
                                                <i class="fa fa-asterisk fa-fw"></i> Hay leads nuevos sin asignar
                                                <span class="pull-right text-muted small"><span class="badge"><?=$unssigned?></span></span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($unssignedIntmp > 0): ?>
                                    <li>
                                        <a href="index.php?r=sales/lead/&Lead[lead_owner_id]=228">
                                            <div>
                                                <i class="fa fa-asterisk fa-fw"></i> Hay leads nuevos sin asignar
                                                <span class="pull-right text-muted small"><span class="badge"><?=$unssignedIntmp?></span></span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($appointments > 0): ?>
                                    <li>
                                        <a href="index.php?r=sales/lead/appointments&today=true">
                                            <div>
                                                <i class="fa fa-calendar fa-fw"></i> Hay citas para hoy
                                                <span class="pull-right text-muted small"><span class="badge"><?=$appointments?></span></span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li>
                                    Sin notificaciones por el momento
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php
                    $moduleCount = 0;
                    $moduleCountSet = 0;
                    if(in_array('pmt',Yii::$app->params['modules'])){
                        $moduleCount +=1;
                        $moduleCountSet +=1;
                    }
                    if(in_array('support',Yii::$app->params['modules'])){
                        $moduleCountSet+=1;
                    }
                    if(in_array('product',Yii::$app->params['modules'])){
                        $moduleCountSet+=1;
                    }
                    if(in_array('customer',Yii::$app->params['modules'])){
                        $moduleCount+=1;
                    }
                    if(in_array('user',Yii::$app->params['modules'])){
                        $moduleCount+=1;
                    }
                    if(in_array('sales',Yii::$app->params['modules'])){
                        $moduleCountSet+=1;
                    }
                    $width =100/($moduleCountSet+2);
                    ?>

                    <?php

                    ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <?php if(file_exists('../users/'.Yii::$app->user->identity->id.'.png')){?>
                                    <img alt="image" style="height:20px" src="../users/<?=Yii::$app->user->identity->id; ?>.png" />
                                    <?php echo Yii::$app->user->identity->alias; ?>

                            <?php }else{?>
                                    <img alt="image" style="height:20px" src="../users/nophoto.jpg" />
                                    <?php
                                    if (!Yii::$app->devicedetect->isMobile())
                                    {
                                        echo Yii::$app->user->identity->alias;
                                    }

                                    ?>

                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="<?=activeMenu('user/user/change-password')?>"><a href="index.php?r=user/user/change-password"><i class="fa fa-exchange"></i>
                                    <?=Yii::t('app', 'Change Password')?></a></li>
                            <li class="<?=activeMenu('site/logout')?>"><a href="<?= Url::to(['/site/logout'])?>" data-method="post"><i class="fa fa-sign-out"></i> <?=Yii::t('app', 'Log out')?></a></li>
                        </ul>
                    </li>

                    <?php
                    if(Yii::$app->user->can('Setting.Pages'))
                    {?>
                        <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                        <?php
                    }?>
                </ul>

            </nav>
        </div>

        <!-- lets make this configuration user preferenace if wants to show the title & breadcrump -->

        <?php if(isset($_GET['r']) && $_GET['r'] !='site/index' and $_GET['r'] !=''){?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <?= Alert::widget() ?>
                <div class="col-lg-10">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <ol class="breadcrumb">
                        <?php  echo Breadcrumbs::widget ( [ 'links' => isset ( $this->params ['breadcrumbs'] ) ? $this->params ['breadcrumbs'] : [ ],
                            'homeLink' => [
                                'label' => Yii::t('app', 'Home'),
                                'url' => Yii::$app->homeurl,
                            ]
                        ]) ?>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <?php } ?>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">


                    <?= $content?>

                </div>
            </div>
        </div>
        <?php
        if(Yii::$app->params['CHAT'] && Yii::$app->user->identity->userType->type!="Customer"){
            ?>
            <div class="small-chat-box fadeInRight animated" style="max-height:300px; overflow:auto">
                <div class="title"><?php echo Yii::t('app', 'Users Login Status'); ?></div>
                <?php foreach(CommonModel::getAllUsers() as $userRow){?>
                    <div class="setings-item">
                    <span>
                        <?= CommonModel::checkUserLoggedIn($userRow['id'])?'<a href="#">
<i class="fa fa-circle text-navy"></i></a>':'<a href="#">
<i class="fa fa-circle text-danger"></i></a>'?>
                        <?php
                        $replace1=array(' ','.');
                        $replace2=array('','');
                        ?><a style="color:#666" href="javascript:void(0)" onclick="javascript:chatWith('<?=str_replace($replace1,$replace2,$userRow['first_name'])."_".trim(str_replace($replace1,$replace2,$userRow['last_name'])).'_'.$userRow['id']?>')"><?= $userRow['first_name']." ".$userRow['last_name']?></a>
                    </span>

                        <div class="switch">


                        </div>
                    </div>
                <?php } ?>
            </div>
            <div id="small-chat">

                <span class="badge badge-warning pull-right"></span>
                <a class="open-small-chat">
                    <i class="fa fa-comments"></i>

                </a>
            </div>
        <?php } ?>

        <?php
        if(Yii::$app->user->can('Setting.Pages')){?>
            <div id="right-sidebar" class="animated">
                <div class="sidebar-container">


                    <?php

                    if(sizeof(Yii::$app->params['modules']) > 8)
                    {
                        echo '<ul class="nav nav-tabs navs-5">';
                    }
                    else
                    {
                        echo '<ul class="nav nav-tabs navs-2">';
                    }
                    ?>



                    <li data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('app', 'System Settings')?>" class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-gear"></i></a></li>

                    <?php
                    if(in_array('pmt',Yii::$app->params['modules']) )
                    {
                        ?>
                        <li data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('app', 'LiveProjects Settings')?>"><a href="#control-sidebar-projects-tab" data-toggle="tab"><i class="fa fa-briefcase"></i></a></li>
                        <?php
                    }
                    ?>

                    <?php
                    if(in_array('sales',Yii::$app->params['modules']) )
                    {
                        ?>
                        <li data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('app', 'LiveSales Settings')?>"><a href="#control-sidebar-sales-tab" data-toggle="tab"><i class="fa fa-diamond"></i></a></li>
                        <?php
                    }
                    ?>

                    <?php
                    if(in_array('support',Yii::$app->params['modules']) )
                    {
                        ?>
                        <li data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('app', 'LiveSupport Settings')?>"><a href="#control-sidebar-support-tab" data-toggle="tab"><i class="fa fa-support"></i></a></li>
                        <?php
                    }
                    ?>

                    <?php
                    if(in_array('invoice',Yii::$app->params['modules']) )
                    {
                        ?>
                        <li data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('app', 'LiveInvoices Settings')?>"><a href="#control-sidebar-invoices-tab" data-toggle="tab"><i class="fa fa-file-o"></i></a></li>
                        <?php
                    }
                    ?>




                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Home tab content -->
                        <div class="tab-pane active" id="control-sidebar-home-tab">
                            <ul class="sidebar-list">
                                <li>
                                    <a href="index.php?r=liveobjects/setting">
                                        <h4><i class="menu-icon fa fa-plus-circle"></i> <?=Yii::t('app', 'Advanced System Settings')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Advanced system settings')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=user/user">
                                        <h4><i class="menu-icon fa fa-group"></i> <?=Yii::t('app', 'Users')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various system users')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=user/user-type">
                                        <h4><i class="menu-icon fa fa-flash"></i> <?=Yii::t('app', 'User Types')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various system user types')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/setting/rights">
                                        <h4><i class="menu-icon fa fa-magic"></i> <?=Yii::t('app', 'RBAC Settings')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Define role based access control for LiveCRM')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=user/user/user-sessions">
                                        <h4><i class="menu-icon fa fa-history"></i> <?=Yii::t('app', 'Session History')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Browse session history of different users')?></div>
                                    </a>
                                </li>



                                <li>
                                    <a href="index.php?r=liveobjects/glocalization">
                                        <h4><i class="menu-icon fa fa-language"></i> <?=Yii::t('app', 'Language Settings')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Make LiveCRM speak in other language of your choice')?></div>
                                    </a>
                                </li>

                                <!-- <li>
            <a href="index.php?r=liveobjects/timezone">
                <h4><i class="menu-icon fa fa-calendar"></i> <?=Yii::t('app', 'Timezone Settings')?></h4>
                <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Change timezone of system')?></div>
            </a>
          </li> -->

                                <li>
                                    <a href="index.php?r=liveobjects/currency">
                                        <h4><i class="menu-icon fa fa-calendar"></i> <?=Yii::t('app', 'Currency Settings')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Change currency of system')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/email-template">
                                        <h4><i class="menu-icon fa fa-envelope"></i> <?=Yii::t('app', 'Email Templates')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various email templates')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/announcement/index">
                                        <h4><i class="menu-icon fa fa-bullhorn"></i> <?=Yii::t('app', 'Announcements')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Create announcements to be displayed on top of dashboard')?></div>
                                    </a>
                                </li>


                                <li>
                                    <a href="index.php?r=customer/customer-type">
                                        <h4><i class="menu-icon fa fa-group"></i> <?=Yii::t('app', 'Customer Type')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various customer types')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=estimate/estimate-status">
                                        <h4><i class="menu-icon fa fa-flash"></i> <?=Yii::t('app', 'Estimate Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various estimate status')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=product/product-category">
                                        <h4><i class="menu-icon fa fa-cart-plus"></i> <?=Yii::t('app', 'Product Categories')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various product categories')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/country">
                                        <h4><i class="menu-icon fa fa-flag"></i> <?=Yii::t('app', 'Countries')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update country data')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/state">
                                        <h4><i class="menu-icon fa  fa-flag-checkered"></i> <?=Yii::t('app', 'States')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update state/region data')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/city">
                                        <h4><i class="menu-icon fa fa-street-view"></i> <?=Yii::t('app', 'Cities')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update city data')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/setting/import-data">
                                        <h4><i class="menu-icon fa fa-database"></i> <?=Yii::t('app', 'Import Data')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Import data from other systems to LiveCRM')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/setting/backup-db">
                                        <h4><i class="menu-icon fa fa-save"></i> <?=Yii::t('app', 'Backup/Restore Data')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Backup and restore LiveCRM database')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/cron-jobs">
                                        <h4><i class="menu-icon fa fa-sun-o"></i> <?=Yii::t('app', 'Cron Jobs')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Setup various cron jobs in the system')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=liveobjects/setting/license">
                                        <h4><i class="menu-icon fa fa-gavel"></i> License</h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'License Version')?></div>
                                    </a>
                                </li>

                            </ul>
                            <!-- /.control-sidebar-menu -->
                        </div>
                        <!-- /.tab-pane -->

                        <!--	-->


                        <div class="tab-pane" id="control-sidebar-sales-tab">
                            <ul class="sidebar-list">

                                <li>
                                    <a href="index.php?r=sales/lead-status">
                                        <h4><i class="menu-icon fa fa-flash"></i> <?=Yii::t('app', 'Sales Lead Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various sales lead status')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=sales/lead-type">
                                        <h4><i class="menu-icon fa fa-star"></i> <?=Yii::t('app', 'Sales Lead Types')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various sales lead types')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=sales/lead-source">
                                        <h4><i class="menu-icon fa fa-phone"></i> <?=Yii::t('app', 'Sales Lead Sources')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various sales lead sources')?></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.tab-pane -->

                        <!-- Projects Settings tab content -->
                        <div class="tab-pane" id="control-sidebar-projects-tab">
                            <!--<div class="sidebar-title">
			<h3><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Project Settings')?></h3>
		</div>-->
                            <ul class="sidebar-list">

                                <li>
                                    <a href="index.php?r=pmt/project-status">
                                        <h4><i class="menu-icon fa fa-briefcase"></i> <?=Yii::t('app', 'Project Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various project status')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/project-priority">
                                        <h4><i class="menu-icon fa fa-briefcase"></i> <?=Yii::t('app', 'Project Priority')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various project priorities')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/project-type">
                                        <h4><i class="menu-icon fa fa-briefcase"></i> <?=Yii::t('app', 'Project Type')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various project types')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/project-source">
                                        <h4><i class="menu-icon fa fa-briefcase"></i> <?=Yii::t('app', 'Project Source')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various project sources')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=pmt/defect-status">
                                        <h4><i class="menu-icon fa fa-bug"></i> <?=Yii::t('app', 'Defect Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various defect status')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/defect-priority">
                                        <h4><i class="menu-icon fa fa-bug"></i> <?=Yii::t('app', 'Defect Priority')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various defect priorities')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/defect-type">
                                        <h4><i class="menu-icon fa fa-bug"></i> <?=Yii::t('app', 'Defect Type')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various defect types')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=pmt/task-status">
                                        <h4><i class="menu-icon fa fa-tasks"></i> <?=Yii::t('app', 'Task Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various task status')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/task-priority">
                                        <h4><i class="menu-icon fa fa-tasks"></i> <?=Yii::t('app', 'Task Priority')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various task priorities')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=pmt/task-type">
                                        <h4><i class="menu-icon fa fa-tasks"></i> <?=Yii::t('app', 'Task Type')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various task types')?></div>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <!-- /.tab-pane -->

                        <!-- Support Settings tab content -->
                        <div class="tab-pane" id="control-sidebar-support-tab">
                            <!--<div class="sidebar-title">
			<h3><i class="fa fa-support"></i> <?=Yii::t('app', 'Support Settings')?></h3>
		</div>-->
                            <ul class="sidebar-list">
                                <li>
                                    <a href="index.php?r=liveobjects/department">
                                        <h4><i class="menu-icon fa fa-building"></i> <?=Yii::t('app', 'Departments')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various system departments')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=liveobjects/queue">
                                        <h4> <i class="menu-icon fa fa-server"></i> <?=Yii::t('app', 'Queues')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various queues where tickets can be routed')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=support/sla">
                                        <h4> <i class="menu-icon fa fa-clock-o"></i> <?=Yii::t('app', 'SLA')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Configure service level agreement timings for various ticket types/priorities')?></div>
                                    </a>
                                </li>

                                <li>
                                    <a href="index.php?r=support/ticket-status">
                                        <h4><i class="menu-icon fa fa-ticket"></i> <?=Yii::t('app', 'Ticket Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various ticket status')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=support/ticket-priority">
                                        <h4><i class="menu-icon fa fa-ticket"></i> <?=Yii::t('app', 'Ticket Priority')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various ticket priorities')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=support/ticket-impact">
                                        <h4><i class="menu-icon fa fa-ticket"></i> <?=Yii::t('app', 'Ticket Impact')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various ticket impacts')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=support/ticket-category">
                                        <h4><i class="menu-icon fa fa-ticket"></i> <?=Yii::t('app', 'Ticket Category')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Add/Update various ticket categories')?></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.tab-pane -->

                        <!-- Invoices Settings tab content -->
                        <div class="tab-pane" id="control-sidebar-invoices-tab">
                            <!--<div class="sidebar-title">
			<h3><i class="fa fa-file-o"></i> <?=Yii::t('app', 'Invoice Settings')?></h3>
		</div>-->
                            <ul class="sidebar-list">
                                <li>
                                    <a href="index.php?r=invoice/invoice-status">
                                        <h4><i class="menu-icon fa fa-flash"></i> <?=Yii::t('app', 'Invoice Status')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'View/Update various invoice status')?></div>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?r=invoice/tax">
                                        <h4><i class="menu-icon fa fa-dollar"></i> <?=Yii::t('app', 'Tax')?></h4>
                                        <div class="small text-muted m-t-xs"><?=Yii::t('app', 'Define various tax parameters')?></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.tab-pane -->

                    </div>

                </div>
            </div>
            <?php
        }
        ?>
        <div class="footer">
            <div class="pull-right">
                <strong><?=Yii::t('app', 'Copyright')?></strong> <a href="http://www.starnet.com.mx" target="_blank">StarNet </a> <?= date('Y')?> &copy;  CRM <?=Yii::$app->params['APPLICATION_VERSION']?>
            </div>
            <!--<div>
              <?=Yii::$app->params['company']['company_name'] ?> <?=Yii::$app->params['address']['address_1'] ?>  <?=Yii::$app->params['address']['city'] ?>  <?=Yii::$app->params['address']['state'] ?>  <?=Yii::$app->params['address']['country'] ?>
            </div>-->
        </div>

    </div>
</div>

<?php $this->endBody()?>


<?php
}
else
{
    ?>
    <?= $content?>
    <?php
}

?>

<!-- Mainly scripts -->
<!--<script src="js/jquery-2.1.1.js"></script>-->
<?php
if(isset($_GET['r']) && strpos($_GET['r'],'my-calendar') !== false){?>
    <script src='../include/calendar/fullcalendar.min.js'></script>

<?php	} ?>
<?php
if(isset($_GET['r']) && strpos($_GET['r'],'appointments') !== false){?>
    <!--           <link rel='stylesheet' href='../../vendor/fullcalendar-3.10.0/fullcalendar.css' />

               <script src='../../vendor/fullcalendar-3.10.0/lib/moment.min.js'></script>
               <script src='../../vendor/fullcalendar-3.10.0/fullcalendar.js'></script>
               <script type="text/javascript">

                   $(document).ready(function() {
                       var m = moment().format('L');
                       console.log(m);

                       $('#calendar').fullCalendar({
                           lang: 'ES',
                           header: {
                               left: 'prev,next',
                               center: 'title',
                               right: 'month,agendaWeek,agendaDay'
                           },
                           defaultDate: m,
                           navLinks: true,
                           defaultView: 'agendaWeek',
                           editable: false,
                           eventRender: function(eventObj, $el) {
                               $el.popover({
                                   title: eventObj.title,
                                   content: eventObj.description,
                                   trigger: 'hover',
                                   placement: 'top',
                                   container: 'body'
                               });
                           },
                           events:  'http://efectivo.io/livecrm/web/index.php?r=sales/lead/calendar'

                       })

                   });

               </script>
   -->
<?php	} ?>
<div class="fileinclude"></div>
<script>
    $(document).ready(function(e) {
        if(!$('body').find("[src$='bootstrap.js']").length){
            var script=document.createElement('script');
            script.type='text/javascript';
            script.src='js/bootstrap.min.js';

            $("body").append(script);
        }
    });
</script>

<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="js/plugins/flot/jquery.flot.js"></script>
<script src="js/plugins/flot/jquery.flot.time.js"></script>
<script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="js/plugins/flot/jquery.flot.spline.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.js"></script>
<script src="js/plugins/flot/jquery.flot.pie.js"></script>
<script src="js/plugins/flot/jquery.flot.symbol.js"></script>
<script src="js/plugins/flot/curvedLines.js"></script>

<!-- Peity -->
<script src="js/plugins/peity/jquery.peity.min.js"></script>
<script src="js/demo/peity-demo.js"></script>
<!-- Nestable List -->

<script src="js/plugins/nestable/jquery.nestable.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<!-- <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>-->

<!-- Jvectormap -->
<script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- IonRangeSlider -->
<script src="js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
<script src="js/plugins/Inputmask/jquery.inputmask.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/api/lead.js?v9"></script>

<?php if( ! isset($_GET['r']) || strpos($_GET['r'],'site/index') !== false):?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <script type="text/javascript">
        window.onload = function() {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 1,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });
        };
    </script>
<?php endif; ?>

<!-- Sparkline -->
<!--<script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>-->

<!-- Sparkline demo data  -->
<!-- <script src="js/demo/sparkline-demo.js"></script>-->

<!-- ChartJS-->
<!--<script src="js/plugins/chartJs/Chart.min.js"></script>-->

<script src="../include/jPages.js"></script>
<?php if(Yii::$app->params['CHAT'] && isset($_GET['r']) && strpos($_GET['r'],'site/login') === false){?>
    <script src="../include/js/chat.js"></script>
    <link type="text/css" rel="stylesheet" media="all" href="../include/css/chat.css" />

<?php } ?>
<style>

    .chatboxcontent{
        width:225px
    }
    .select2-container .select2-selection--single {
        height: auto!important;
    }
</style>

<script>
    $(document).ready(function(e) {
        $('.setings-item a').click(function(){
            $('.theme-config-box').removeClass('show');
        })
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tab"]').click(function(){
            $(".tooltip").hide();
        })
        if('<?=Yii::$app->params['RTL_THEME']?>' == 'Yes'){
            // .panel-heading .pull-right
            $('.grid-view').find('.pull-right').addClass("pull-left");
        }
        // setTimeout(function(){
        $('.theme-config').hide();
        //  },1000);
        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {

            //save the latest tab; use cookies if you like 'em better:

            localStorage.setItem('lastTab_leadview', $(this).attr('href'));

        });
        $('[data-toggle="hover"]').popover({ trigger: "hover" });


        //go to the latest tab, if it exists:

        var lastTab_leadview = localStorage.getItem('lastTab_leadview');

        if ($('a[href="' + lastTab_leadview + '"]').length > 0) {

            $('a[href="' + lastTab_leadview + '"]').tab('show');

        }

        else

        {

            // Set the first tab if cookie do not exist

            $('a[data-toggle="tab"]:first').tab('show');

        }
        if('<?=!empty($_GET['tab'])?$_GET['tab']:''?>' !=''){

            $('.<?php
                if(isset($_GET['tab']))
                    echo $_GET['tab'];
                ?>').tab('show');

        }

    });
    if('<?=isset($_COOKIE['defectStartedId'])?$_COOKIE['defectStartedId']:''?>' != ''){
        setInterval(function(){
            jQuery.post('ajax_clock2.php',function(result){
                var alink ='<a href="index.php?r=pmt/defect/defect-view&id=<?= isset($_COOKIE['defectStartedId'])?$_COOKIE['defectStartedId']:''?>"><i class="glyphicon glyphicon-time"></i> '+result+'</a>';
                jQuery(".defect_time_widget").html(alink);
            })
        },1000)
    }
    if('<?=isset($_COOKIE['taskStartedId'])?$_COOKIE['taskStartedId']:''?>' != ''){
        setInterval(function(){
            jQuery.post('ajax_clock.php',function(result){
                var alink ='<a href="index.php?r=pmt/task/task-view&id=<?= isset($_COOKIE['taskStartedId'])?$_COOKIE['taskStartedId']:''?>"><i class="glyphicon glyphicon-time"></i> '+result+'</a>';
                jQuery(".time_widget").html(alink);
            })
        },1000)
    }
    if('<?=isset($_COOKIE['ticketStartedId'])?$_COOKIE['ticketStartedId']:''?>' != ''){
        setInterval(function(){
            jQuery.post('ajax_clock3.php',function(result){
                var alink ='<a href="index.php?r=support/ticket/update&id=<?= isset($_COOKIE['taskStartedId'])?$_COOKIE['ticketStartedId']:''?>"><i class="glyphicon glyphicon-time"></i> '+result+'</a>';
                jQuery(".time_widget").html(alink);
            })
        },1000)
    }
</script>
<script type="text/javascript">


    if('<?= isset($_COOKIE['taskStartedId'])?$_COOKIE['taskStartedId']:''?>' != ''){
        /*var idleTime = 0;
        $(document).ready(function () {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function (e) {
                idleTime = 0;
            });
            $(this).keypress(function (e) {
                idleTime = 0;
            });
            $('.spin-icon').click(function(){
                $('.theme-user .theme-config-box').toggleClass('show');
            })
        });

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime > 9) { // 10 minutes
                var r = confirm("Your session will expire in 2 mins. Do you want to continue the session?");
                if (r == true) {
                    idleTime =0;
                } else {
                    window.location.href='index.php?r=pmt%2Ftask%2Ftask-view&id=<//?=isset($_COOKIE['taskStartedId'])?$_COOKIE['taskStartedId']:''?>&tasknotes=Session Expired';
                }

            }
        }*/
    }
    if('<?=isset($_COOKIE['defectStartedId'])?$_COOKIE['defectStartedId']:''?>' != ''){
        /*var idleTime = 0;
        $(document).ready(function () {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function (e) {
                idleTime = 0;
            });
            $(this).keypress(function (e) {
                idleTime = 0;
            });
            $('.spin-icon').click(function(){
                $('.theme-user .theme-config-box').toggleClass('show');
            })
        });

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime > 9) { // 10 minutes
                var r = confirm("Your session will expire in 2 mins. Do you want to continue the session?");
                if (r == true) {
                    idleTime =0;
                } else {
                    window.location.href='index.php?r=pmt/defect/defect-view&id=<//?= isset($_COOKIE['defectStartedId'])?$_COOKIE['defectStartedId']:''?>&defectnotes=Session Expired';
                }

            }
        }*/
    }

</script>
<?php
if(isset($_GET['r']) && strpos($_GET['r'],'file-manager') !== false){?>
    <style>
        .ui-state-default.elfinder-button{
            width:25px !important;
            height:25px !important
        }
    </style>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    <!-- elFinder JS (REQUIRED) -->
    <script type="text/javascript" src="../include/js/elfinder.min.js"></script>

    <!-- elFinder translation (OPTIONAL) -->
    <script type="text/javascript" src="../include/js/i18n/elfinder.ru.js"></script>
    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        $().ready(function() {
            var elf = $('#elfinder').elfinder({
                url : '../include/php/connector.php'  // connector URL (REQUIRED)
                // lang: 'ru',             // language (OPTIONAL)
            }).elfinder('instance');
        });
    </script>
<?php } ?>
<?php if(Yii::$app->request->getQueryParam('r') == 'site/index' || Yii::$app->request->getQueryParam('r') == '/payments' || strpos(Yii::$app->request->getQueryParam('r'), 'sales/lead') !== false): ?>
    <!-- Data picker -->
    <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/plugins/datapicker/bootstrap-datepicker.es.min.js"></script>
    <script src="js/api/sales_report.js?v<?= time()?>"></script>
    <!-- Include jQuery Validator plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
<?php endif; ?>
<?php if (Yii::$app->request->getQueryParam('r') == 'sales/lead/validate'): ?>
    <script src="js/api/payments_validate.js"></script>
<?php endif; ?>
<?php if (Yii::$app->request->getQueryParam('r') == 'liveobjects/office/timeclock'): ?>
    <script src="js/api/timeclock.js"></script>
<?php endif; ?>
<?php if (Yii::$app->request->getQueryParam('r') == 'sales/lead/view'): ?>
<script src="../../vendor/bower/smartwizard/dist/js/jquery.smartWizard.js"></script>
<script type="text/javascript">
    //wizard model view lead
    $(document).ready(function(){
        //wizard model settings
        $('#smartwizard').smartWizard(
            {
                selected: 0,
                lang: {
                    next: 'Siguiente',
                    previous: 'Atras'
                },
                theme: 'circles',
                transitionEffect: 'slide',
                transitionSpeed: '400',
                errorSteps: [],    // Highlight step with errors
                 toolbarSettings: {
                //     toolbarPosition: 'none', // none, top, bottom, both
                //     toolbarButtonPosition: 'right', // left, right
                //     showNextButton: true, // show/hide a Next button
                //     showPreviousButton: true, // show/hide a Previous button
                //     toolbarExtraButtons: [
                //         $('<button></button>').text('Finish')
                //             .addClass('btn btn-info')
                //             .on('click', function(){
                //                 alert('Finsih button click');
                //             }),
                //         $('<button></button>').text('Cancel')
                //             .addClass('btn btn-danger')
                //             .on('click', function(){
                //                 alert('Cancel button click');
                //             })
                //     ]
                     anchorSettings: {
                         markDoneStep: true, // add done css
                         markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                         removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
                         enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                     }
                 },
            }
        );
        //wizard model settings to forward step validation
        $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
            var elmForm = $("#form-step-" + stepNumber);
            // stepDirection === 'forward' :- this condition allows to do the form validation
            // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
            // if(stepDirection === 'forward' && elmForm){
                 elmForm.validator('validate');
                var elmErr = elmForm.find('.has-error');
                console.log(elmErr.length);
                // if(elmErr && elmErr.length > 0){
                    // Form validation failed
                    // return false;
                // }
            // }
            return true;
        });
    });
</script>
<?php endif; ?>
<!--
</body>
</html>
-->

</body>
</html>

<?php $this->endPage()?>
