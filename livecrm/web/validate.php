<?php

$message = array();

switch($_POST["action"])
{
	case 'get':
		$params = array();
		$params['invoice_number'] = isset($_POST["invoice_number"]) ? $_POST["invoice_number"] : '';
		$params['order_number'] = isset($_POST["order_number"]) ? $_POST["order_number"] : '';
		$params['order_date'] = isset($_POST["order_date"]) ? $_POST["order_date"] : '';
		$params['first_name'] = isset($_POST["first_name"]) ? $_POST["first_name"] : '';
		$params['last_name'] = isset($_POST["last_name"]) ? $_POST["last_name"] : '';
		$params['email'] = isset($_POST["email"]) ? $_POST["email"] : '';
		$params['phone'] = isset($_POST["phone"]) ? $_POST["phone"] : '';
		$params['module_name'] = isset($_POST["module_name"]) ? $_POST["module_name"] : '';

		$postdata = http_build_query(
									array(
											'invoice_number' => $params['invoice_number'],
											'order_number' => $params['order_number'],
											'order_date' => $params['order_date'],
											'first_name' => $params['first_name'],
											'last_name' => $params['last_name'],
											'email' => $params['email'],
											'phone' => $params['phone'],
											'module_name' => $params['module_name'],
											'domain' => $_SERVER['HTTP_HOST']
										)
									);

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);

		//$result = file_get_contents('http://livecrm.co/LiveCRMV3.0/livecrm/web/check.php', false, $context);
		//$result = file_get_contents('http://localhost/livecrm/livecrm/web/check.php', false, $context);
		$result = file_get_contents('http://license.livecrm.co/check.php', false, $context);

		$myresult = json_decode($result, true);

		//print_r( $myresult);
		$module = $myresult['data'][0]['module_name'];
		$expiry_date = $myresult['data'][0]['expiry_date'];
		//print_r( $module);
		//exit;

		if ($myresult['code'] == '0')
		{
			/* success - generate license file */
			$lic_data = ['module_name' => $module, 'invoice_number' => $params['invoice_number'], 'order_number' => $params['order_number'], 'order_date' => $params['order_date'], 'expiry_date' => $expiry_date, 'domain' => $_SERVER['HTTP_HOST']];
			file_put_contents('../config/license.dat', base64_encode(serialize($lic_data)));
			header('location:index.php?r=site/login&msg=License file successfully generated!');
		}
		else
		{
			header('location:index.php?r=liveobjects/app-license/get-license&msg='.$myresult['message']);
		}
		
		break;

	default:
		$message["code"] = "1";
		$message["message"] = "Unknown method " . $_POST["action"];
		break;
}

?>