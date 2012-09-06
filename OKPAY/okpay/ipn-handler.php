<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/okpay.php');

$errors = '';
$result = false;
$okpay = new OkPay();

// Fill params
$params = 'ok_verify=true';
foreach ($_POST AS $key => $value)
	$params .= '&'.$key.'='.urlencode(stripslashes($value));

// OkPay Server
$okpayServer = 'www.okpay.com';

// Getting OkPay data...
if (function_exists('curl_exec'))
{
	// curl ready
	$ch = curl_init('https://' . $okpayServer . '/ipn-verify.html');
    
	// If the above fails, then try the url with a trailing slash (fixes problems on some servers)
 	if (!$ch)
		$ch = curl_init('https://' . $okpayServer . '/ipn-verify.html');
	
	if (!$ch)
		$errors .= $okpay->getL('connect').' '.$okpay->getL('curlmethodfailed');
	else
	{
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);

		if ($result != 'VERIFIED')
			$errors .= $okpay->getL('curlmethod').$result.' cURL error:'.curl_error($ch);
		curl_close($ch);
	}
}
elseif (($fp = @fsockopen('ssl://' . $okpayServer, 443, $errno, $errstr, 30)) || ($fp = @fsockopen($okpayServer, 80, $errno, $errstr, 30)))
{
	// fsockopen ready
	$header = 'POST /ipn-verify.html HTTP/1.0'."\r\n" .
          'Host: '.$okpayServer."\r\n".
          'Content-Type: application/x-www-form-urlencoded'."\r\n".
          'Content-Length: '.Tools::strlen($params)."\r\n".
          'Connection: close'."\r\n\r\n";
	fputs($fp, $header.$params);
 	
 	$read = '';
 	while (!feof($fp))
	{
		$reading = trim(fgets($fp, 1024));
		$read .= $reading;
		if (($reading == 'VERIFIED') || ($reading == 'INVALID'))
		{
		 	$result = $reading;
			break;
		}
 	}
	if ($result != 'VERIFIED')
		$errors .= $okpay->getL('socketmethod').$result;
	fclose($fp);
}
else
	$errors = $okpay->getL('connect').$okpay->getL('nomethod');

// Printing errors...
if ($result == 'VERIFIED') {
	if (!isset($_POST['ok_txn_gross']))
		$errors .= $okpay->getL('ok_txn_gross').'<br />';
	if (!isset($_POST['ok_txn_status']))
		$errors .= $okpay->getL('ok_txn_status').'<br />';
	elseif ($_POST['ok_txn_status'] != 'completed')
		$errors .= $okpay->getL('payment').$_POST['ok_txn_status'].'<br />';
	if (!isset($_POST['ok_invoice']))
		$errors .= $okpay->getL('ok_invoice').'<br />';
	if (!isset($_POST['ok_txn_id']))
		$errors .= $okpay->getL('ok_txn_id').'<br />';
	if (!isset($_POST['ok_txn_currency']))
		$errors .= $okpay->getL('ok_txn_currency').'<br />';
	if (empty($errors))
	{
		$cart = new Cart(intval($_POST['ok_invoice']));
		if (!$cart->id)
			$errors = $okpay->getL('cart').'<br />';
		elseif (Order::getOrderByCartId(intval($_POST['ok_invoice'])))
			$errors = $okpay->getL('order').'<br />';
		else
			$okpay->validateOrder($_POST['ok_invoice'], _PS_OS_PAYMENT_, floatval($_POST['ok_txn_gross']), $okpay->displayName, $okpay->getL('transaction').$_POST['ok_txn_id']);
	}
} else {
	$errors .= $okpay->getL('verified');
}

if (!empty($errors) AND isset($_POST['ok_invoice']))
	$okpay->validateOrder(intval($_POST['ok_invoice']), _PS_OS_ERROR_, 0, $okpay->displayName, $errors.'<br />');

?>