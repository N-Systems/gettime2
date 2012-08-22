<?php

class OkPay extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		global $cookie,$order;
		
		$this->name = 'okpay';
		$this->tab = 'Payment';
		$this->version = '1.0';
		
		$this->currencies = true;
		$this->currencies_mode = 'radio';

		parent::__construct();
		
		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('OKPAY');
		$this->description = $this->l('Accepts OKPAY e-currency payments');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
	}

	public function install()
	{
		if (!parent::install() 
                        OR !Configuration::updateValue('OKPAY_RECEIVER', 'your-okpay@address.com')
                        OR !Configuration::updateValue('OKPAY_CURRENCY', 'customer')
                        OR !$this->registerHook('payment')
			OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('OKPAY_RECEIVER') 
                  OR !Configuration::deleteByName('OKPAY_CURRENCY')
                  OR !parent::uninstall())
			return false;
		return true;
	}

	public function getContent()
	{
		if (isset($_POST['submitOkPay'])){
			Configuration::updateValue('OKPAY_RECEIVER', $_POST['receiver']);
			Configuration::updateValue('OKPAY_CURRENCY', $_POST['currency']);
			$this->displayConf();
		}

		$this->displayOkPay();
		$this->displayFormSettings();
		return $this->_html;
	}

	private function displayConf()
	{
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Settings updated').'
		</div>';
	}

	
	
	private function displayOkPay()
	{
		$this->_html .= '
		<img src="../modules/okpay/okpay.gif" style="float:left; margin-right:15px;" />
		<b>'.$this->l('This module allows you to accept OKPAY e-currency.').'</b><br /><br />
		'.$this->l('If the client chooses this payment mode, your OKPAY account will automatically credited with referral bonus.').'<br />
		<div style="clear:both;">&nbsp;</div>';
	}

	private function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('OKPAY_RECEIVER', 'OKPAY_CURRENCY'));
		$receiver = array_key_exists('receiver', $_POST) ? $_POST['receiver'] : (array_key_exists('OKPAY_RECEIVER', $conf) ? $conf['OKPAY_RECEIVER'] : '');
		$currency = array_key_exists('currency', $_POST) ? $_POST['currency'] : (array_key_exists('OKPAY_CURRENCY', $conf) ? $conf['OKPAY_CURRENCY'] : 'prestashop');

		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Settings').'</legend>
			<label>'.$this->l('OKPAY Receiver ID').'</label>
			<div class="margin-form"><input type="text" size="33" name="receiver" id="receiver" value="'.htmlentities($receiver, ENT_COMPAT, 'UTF-8').'" /> Unique OKPAY wallet ID / email address or mobile phone number linked to your wallet.</div>

			<label>'.$this->l('Currency').'</label>
			<div class="margin-form">
				<input type="radio" name="currency" value="prestashop" '.($currency == 'prestashop' ? 'checked="checked"' : '').' /> '.$this->l('Use PrestaShop currency').'
				<br /><input type="radio" name="currency" value="customer" '.($currency == 'customer' ? 'checked="checked"' : '').' /> '.$this->l('Use customer currency').'
			</div>

			<br /><center><input type="submit" name="submitOkPay" value="'.$this->l('Update settings').'" class="button" /></center>
		</fieldset>
		</form>';
	}
	
	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $smarty;

		$address = new Address(intval($params['cart']->id_address_invoice));
		$customer = new Customer(intval($params['cart']->id_customer));
		$receiver = Configuration::get('OKPAY_RECEIVER');
		$currency = $this->getCurrency();

		if (empty($receiver))
			return $this->l('OkPay error: (undefined receiver)');

		if (!Validate::isLoadedObject($address) OR !Validate::isLoadedObject($customer) OR !Validate::isLoadedObject($currency))
			return $this->l('OkPay error: (invalid address or customer)');
			
		$products = $params['cart']->getProducts();

		foreach ($products as $key => $product)
		{
			$products[$key]['name'] = str_replace('"', '\'', $product['name']).'!'.$key.'!';
			if (isset($product['attributes']))
				$products[$key]['attributes'] = str_replace('"', '\'', $product['attributes']);
			$products[$key]['name'] = htmlentities(utf8_decode($product['name']));
			$products[$key]['okpayAmount'] = number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '');
		}

		$smarty->assign(array(
			'address' => $address,
			'country' => new Country(intval($address->id_country)),
			'customer' => $customer,
			'receiver' => $receiver,
			'currency' => $currency,
			'okpayUrl' => "https://www.okpay.com/process.html",
			// products + discounts - shipping cost
			'amount' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 4), $currency), 2, '.', ''),
			// shipping cost + wrapping
			'shipping' =>  number_format(Tools::convertPrice(($params['cart']->getOrderShippingCost() + $params['cart']->getOrderTotal(true, 6)), $currency), 2, '.', ''),
			'discounts' => $params['cart']->getDiscounts(),
			'products' => $products,
			// products + discounts + shipping cost
			'total' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $currency), 2, '.', ''),
			'id_cart' => intval($params['cart']->id),
			'goBackUrl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'order-confirmation.php?key='.$customer->secure_key.'&id_cart='.intval($params['cart']->id).'&id_module='.intval($this->id),
			'notify' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/okpay/ipn-handler.php',
			'cancelUrl' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'index.php',
			'this_path' => $this->_path
		));

		return $this->display(__FILE__, 'okpay.tpl');
        }
	
	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		return $this->display(__FILE__, 'confirmation.tpl');
	}

	public function getL($key)
	{
		$translations = array(
		        'to_okpay'        => $this->l('Customer redirected to OKPAY to complete payment.'),
			'ok_txn_gross'    => $this->l('OKPAY key \'ok_txn_gross\' not specified, can\'t control amount paid.'),
			'ok_txn_status'   => $this->l('OKPAY key \'ok_txn_status\' not specified, can\'t control payment validity'),
			'payment'         => $this->l('Payment: '),
			'ok_txn_invoice'  => $this->l('OKPAY key \'ok_custom\' not specified, can\'t rely to cart'),
			'ok_txn_id'       => $this->l('OKPAY key \'ok_txn_id\' not specified, transaction unknown'),
			'ok_txn_currency' => $this->l('OKPAY key \'ok_txn_currency\' not specified, currency unknown'),
			'cart'            => $this->l('Cart not found'),
			'order'           => $this->l('Order has already been placed'),
			'transaction'     => $this->l('OKPAY Transaction ID: '),
			'verified'        => $this->l('The OKPAY transaction could not be VERIFIED.'),
			'connect'         => $this->l('Problem connecting to the OKPAY server. '),
			'socketmethod'    => $this->l('Verification failure (using fsockopen). Returned: '),
			'curlmethod'      => $this->l('Verification failure (using cURL). Returned: '),
			'curlmethodfailed'=> $this->l('Connection using cURL failed')
		);
		return $translations[$key];
	}
	
	function validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL, $dont_touch_amount = false)
	{
		if (!$this->active)
			return ;

		$currency = $this->getCurrency();
		$cart = new Cart(intval($id_cart));
		$cart->id_currency = $currency->id;
		$cart->save();
		parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, true);
	}

}

?>