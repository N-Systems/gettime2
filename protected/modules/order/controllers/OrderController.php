<?php

class OrderController extends YFrontController
{
    public $mode='real'; // empty if real mode, 'sandbox' for testing

	public function actionIndex()
	{
		$this->render('index');
	}

    public function  actionCheck()
    {
        return true;

    }
    public  function actionSave()
    {
        return true;
    }
    public function actionProcessPaypal()
    {

       // read the post from PayPal system and add 'cmd'
        $req = 'cmd=' . urlencode('_notify-validate');

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        if ($this->mode=='sandbox')
        {
            $paypalURL='https://www.sandbox.paypal.com/cgi-bin/webscr';
            $paypalHTTP='Host: www.sandbox.paypal.com';
        }
        else
        {
            $paypalURL='https://www.paypal.com/cgi-bin/webscr';
            $paypalHTTP='Host: www.paypal.com';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($paypalHTTP));
        $res = curl_exec($ch);
        curl_close($ch);

        Yii::log('Transaction begins','info','paypal');


        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_date=$_POST['payment_date'];
        $payment_userid=$_POST['payer_id'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];



//


        if (strcmp ($res, "VERIFIED") == 0) {
            Yii::log('Transaction Verified','info','paypal','transaction');
            if (($payment_status=='Completed') and ($receiver_email=='info@bettime.info'))
            {
                Yii::log('Transaction Payment is Complete','info','paypal');
                $order=Order::model()->findAll('txn_id=:txn_id',array('txn_id'=>$txn_id));
                if (count($order)>0)
                {
                    Yii::log('Order already Exists','info','paypal');
                    if ($order->payment_status !='Completed')
                    {
                        Yii::log('Trying to update order status','info','paypal');
                        if (($order->delivery_adress==$payer_email) and  ($order->ordersum==$payment_amount))
                            {
                                $order->payment_status='Completed';
                                if ($order->save())
                                {
                                    Yii::log('Order update succesfull','info','paypal');
                                }
                                else
                                {
                                    Yii::log('Cannot update order, DB error','error');
                                    header('HTTP/1.0 401 Cannot modify order', true, 40);
                                }
                            } // if email and summ matches
                        else
                        {
                            Yii::log('Payer adress and summ dont match with existing','info','paypal');
                        }
                    } // if order status != completed
                    else
                    {
                        Yii::log('Transaction status is incomplete - skipping','info','paypal');
                    }

                } // if count(order)>0
                else
                {
                    // if no Previous txns
                    Yii::log('Creating new order','info','paypal');
                    $myorder=new Order();

                    $myorder->payment_status=$payment_status;
                    $myorder->delivery_adress=$payer_email;
                    //13:54:56 Aug 12, 2012 PDT
                   // $myorder->orderdate=date('Y-m-D');
//                    if (($payment_amount<50) and ($currency!='EUR'))
//                    {
//                        Yii::log('Sum is less 50 or currency dont match','info','paypal');
//                        exit;
//                    }
                    $myorder->ordersum=$payment_amount;
                    $myorder->userid=$payment_userid;
                    $myorder->txn_id=$txn_id;
                    $myorder->tip=json_encode($_POST);

                    $tipNumber=substr($item_number,0,3);

                    if ($tipNumber=='TIP')
                    {
                        $tipNumber2=substr($item_number,3,1);
                        $tip=Tips::model()->forsale()->find('tip_number=:tip_number',array('tip_number'=>$tipNumber2));
                        $tipText='Date: '.strval($tip->untillDate).' | time: '.strval($tip->untillTime).' | championship: '.strval($tip->championship).' | teams: '.strval($tip->gamename).' | bet: '.strval($tip->stavka).' | odd: '.strval($tip->ratio);

                        if (count($tip)>0)
                        {
                           $tipText='Date: '.strval($tip->untillDate).' | time: '.strval($tip->untillTime).' | championship: '.strval($tip->championship).' | teams: '.strval($tip->gamename).' | bet: '.strval($tip->stavka).' | odd: '.strval($tip->ratio);
                        }
                    }
                    if ($myorder->save())
                    {
                        $headers = array('MIME-Version: 1.0','Content-type: text/html; charset=iso-8859-1');
                        Yii::log('Order succesfully saved, trying to send email','info','paypal');
                        Yii::app()->email->send('info@bettime.info',$payer_email,'Your beting tips from Bettime.info',$tipText);
                        Yii::app()->email->send('info@bettime.info','info@bettime.info','New order!',json_encode($_POST));
                        $myorder->delivery_status='delivered';
                        if ($myorder->save())
                            Yii::log('Order succesfully saved, email sent','info','paypal');
                        else
                            Yii::log('Failed updating delivery status','info','paypal');
                    }
                    else
                    {
                        Yii::log('Problem with saving new order','error');
                                             header('HTTP/1.0 401 Cannot save order', true, 40);
                    }
                }
            }  // if completed and email match
            else
            {
                Yii::log('Transaction is incomplete or receiver email dont match. Skippingd','info','paypal');
            }
        } // if VERIFIED
        else if (strcmp ($res, "INVALID") == 0) {
            Yii::log('Transaction is INVALID','info','paypal');
            header('HTTP/1.0 401 Bad Request', true, 40);
        }
        else
        {
            Yii::log('Incorrect answer from IPN server','info','paypal');
               header('HTTP/1.0 402 Bad Request', true, 40);
        }




    } //function

    public function actionProcessOkpay()
    {

        $email = "info@betttime.info"; // <------ change to your email address!!!
        $header = ""; 
        $emailtext = "THIS IS TIP PAYED BY OKPAY"; 
         
        // Read the post from OKPAY and add 'ok_verify' 
        $req = 'ok_verify=true'; 
        
        foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
        }
        Yii::log('Transaction begins','info','okpay');
        
        
        // Post back to OKPAY to validate 
        $header .= "POST /ipn-verify.html HTTP/1.0\r\n"; 
        $header .= "Host: www.okpay.com\r\n"; 
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
        $fp = fsockopen ('www.okpay.com', 80, $errno, $errstr, 30); 
         
        // Process validation from OKPAY
        if (!$fp) 
        {
            Yii::log('HTTP Error readind from host','info','okpay');
        } else
        {
            Yii::log('NO HTTP Error!','info','okpay');
          fputs ($fp, $header . $req); 
          while (!feof($fp))
          { 
            $res = fgets ($fp, 1024); 
            if (strcmp ($res, "VERIFIED") == 0) // !!!!!!
            { 
              // TODO: 
              // Check the "ok_txn_status" is "completed"
                $payment_status = $_POST['ok_txn_status'];
                $txn_id = $_POST['ok_txn_id'];
                $receiver_email = $_POST['ok_receiver_email'];
                $payment_amount = $_POST['ok_txn_gross'];
                $payer_email = $_POST['ok_payer_email'];
                $payment_userid=$_POST['ok_payer_id'];
                $item_number=$_POST['ok_item_1_name'];
                $currency=$_POST['ok_txn_currency'];
                $transactionDate=$_POST['ok_txn_datetime'];

                if (($payment_status=='completed') and ($receiver_email=='info@bettime.info') and ($payment_amount=='50') and ($currency=='EUR'))
              {
                  Yii::log('Transaction Payment is Complete','info','okpay');
                  $order=Order::model()->findAll('txn_id=:txn_id',array('txn_id'=>$txn_id));
                  if (count($order)>0)
                  {
                      Yii::log('Order already Exists','info','okpay');
                      if (ucwords($order->payment_status)!='COMPLETED')
                      {
                          Yii::log('Trying to update order status','info','okpay');
                          if (($order->delivery_adress==$payer_email) and  ($order->ordersum==$payment_amount))
                              {
                                  $order->payment_status='Completed';
                                  if ($order->save())
                                  {
                                      Yii::log('Order update succesfull','info','okpay');
                                  }
                                  else
                                  {
                                      Yii::log('Cannot update order, DB error','error');
                                      header('HTTP/1.0 401 Cannot modify order', true, 40);
                                  }
                              } // if email and summ matches
                          else
                          {
                              Yii::log('Payer adress and summ dont match with existing','info','okpay');
                          }
                      } // if order status != completed
                      else
                      {
                          Yii::log('Transaction status is incomplete - skipping','info','okpay');
                      }

                  } // if count(order)>0
                  else
                  {
                      // if no Previous txns
                      Yii::log('Creating new order','info','okpay');
                      $myorder=new Order();

                      $myorder->payment_status=$payment_status;
                      $myorder->delivery_adress=$payer_email;
                      //13:54:56 Aug 12, 2012 PDR
                     // $myorder->orderdate=date('Y-m-D');

                      $myorder->ordersum=$payment_amount;
                      $myorder->userid=$payment_userid;
                      $myorder->txn_id=$txn_id;
                      $myorder->tip=json_encode($_POST);
                      $myorder->orderdate=$transactionDate;

                      $tipNumber=substr($item_number,0,3);
                     if (($payment_amount>=50) and ($currency=='EUR'))
                     {
                          if ($tipNumber=='TIP')
                          {
                              $tipNumber2=substr($item_number,3,1);
                              $tip=Tips::model()->forsale()->find('tip_number=:tip_number',array('tip_number'=>$tipNumber2));
                              $tipText='Date: '.strval($tip->untillDate).' | time: '.strval($tip->untillTime).' | championship: '.strval($tip->championship).' | teams: '.strval($tip->gamename).' | bet: '.strval($tip->stavka).' | odd: '.strval($tip->ratio);

                              if (count($tip)>0)
                              {
                                 $tipText='Date: '.strval($tip->untillDate).' | time: '.strval($tip->untillTime).' | championship: '.strval($tip->championship).' | teams: '.strval($tip->gamename).' | bet: '.strval($tip->stavka).' | odd: '.strval($tip->ratio);
                              }
                          }
                          if ($myorder->save())
                          {
                              $headers = array('MIME-Version: 1.0','Content-type: text/html; charset=iso-8859-1');
                              Yii::log('Order succesfully saved, trying to send email','info','paypal');
                              Yii::app()->email->send('info@bettime.info',$payer_email,'Your beting tips from Bettime.info',$tipText);
                              Yii::app()->email->send('info@bettime.info','info@bettime.info','New order!',json_encode($_POST));
                              $myorder->delivery_status='delivered';
                              if ($myorder->save())
                                  Yii::log('Order succesfully saved, email sent','info','okpay');
                              else
                                  Yii::log('Failed updating delivery status','info','okpay');
                          }
                          else
                          {
                              Yii::log('Problem with saving new order','error');
                              header('HTTP/1.0 401 Cannot save order', true, 40);
                          }
                     }
                    else
                      {
                          Yii::log('Sum is less 50 or currency not euro','info','okpay');
                          Yii::app()->email->send('info@bettime.info','info@bettime.info','Strange transaction!',json_encode($_POST));
                      }
                  }
              }  // if completed and email match
              else
              {
                  Yii::log('Transaction is incomplete or receiver email dont match. Skipping','info','okpay');
              }

            }
            else if (strcmp ($res, "INVALID") == 0)
            { 
                Yii::log('Transaction is INVALID','info','okpay');
                header('HTTP/1.0 401 Bad Request', true, 40);
            } 
            else if (strcmp ($res, "TEST")== 0)
            {
                Yii::log('Transaction is TEST','info','okpay');
            }
          } 
          fclose ($fp); 
        }





    } //function

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

/*
 * test_ipn=1
 *  payment_type=instant
 *   payment_date=12:53:36 Aug 07, 2012
 * PDT
 *  payment_status=Completed
 *  address_status=confirmed
 *  payer_status=unverified
 * first_name=John
 *  last_name=Smith
 *  payer_email=buyer@paypalsandbox.com
 *  payer_id=TESTBUYERID01
 * address_name=John Smith
 * address_country=United States
 *   address_country_code=US
 *  address_zip=95131
 *  address_state=CA
 * address_city=San Jose
 *  address_street=123, any street
 *  receiver_email=seller@paypalsandbox.com
 *  receiver_id=TESTSELLERID1
 *  residence_country=US
 * item_name1=something
 *  item_number1=AK-1234
 *  quantity1=1
 *  tax=2.02
 *  mc_currency=USD
 *  mc_fee=0.44
 *   mc_gross_1=9.34
 *    mc_handling=2.06
 *   mc_handling1=1.67
 *  mc_shipping=3.02
 *  mc_shipping1=1.02
 *  txn_type=cart
 *  txn_id=36871953
 *  notify_version=2.4
 *  custom=xyz123
 * invoice=abc1234
 *  charset=windows-1252
 *  verify_sign=AFcWxV21C7fd0v3bYYYRCpSSRl31AFDscdIXEPJQvTJkv0cOAAXXsFdw
 *  VERIFIED
 *
 *
 *
 *  $item_name1=$_POST['item_name1'];
 //        $item_number1=$_POST['item_number1'];
 //        $item_quantity1=$_POST['quantity1'];
 //        $item_quantity=$_POST['quantity'];

 *
 *
 *
 */
}