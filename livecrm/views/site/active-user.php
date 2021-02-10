<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
 <link href="css/bootstrap.css" rel="stylesheet">
 <link href="css/style.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown" style="padding-top:50px">
	<?php
		if(!empty($success)){?>
			<div class="alert alert-success"><?=$success?></div>
	<?php	}
		if(!empty($error)){ ?>
			<div class="alert alert-danger"><?=$error?></div>
	<?php	}
	?>
    <a href="index.php?r=site/login" class="btn btn-primary btn-block">Back to Login</a>
</div>
</body>