<?php if(empty($tips)):?>
    <tr style="height: 10px;">
        <td colspan="4"><h2>No more betting tips today. Please come back tomorrow!</h2></td>
    </tr>
<?php endif;?>
<?php $i=0;
foreach($tips as $tip):?>
<tr>
    <td><?=$tip['tip_number']?></td>
    <td><?=Yii::app()->dateFormatter->format('HH:mm',$tip['untillTime'])?></td>
    <td><?=Yii::app()->numberFormatter->format('#.00',$tip['ratio'])?></td>
    <td>&#8364;<?=$tip['price']?></td>
    <td><?php if ($i==0):?>
         <?php if(BETTIME_PRODUCTION==false) {?>
             <img src='/img/pay.gif' alt=''>
         <?php } else {?>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="Z8WH5CNNHCUNE">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG_global.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <form  method="post" action="https://www.okpay.com/process.html"><input type="hidden" name="ok_receiver" value="OK669203365"/>
            <input type="hidden" name="ok_item_1_name" value="TIP1"/>
            <input type="hidden" name="ok_currency" value="EUR"/>
            <input type="hidden" name="ok_item_1_type" value="digital"/>
            <input type="hidden" name="ok_item_1_price" value="50"/>
            <input type="hidden" name="ok_fees" value="1"/>
            <input type="hidden" name="ok_return_success" value="http://bettime.info/success"/>
            <input type="hidden" name="ok_return_fail" value="http://bettime.info/fail"/>
            <input type="hidden" name="ok_ipn" value="http://bettime.info/okpay/listener"/>
            <input type="image" name="submit" alt="OKPAY Payment" src="https://www.okpay.com/img/buttons/en/buy/b07g56x35en.png"/></form>
            <?php } ?>
     <?endif?>
    <?php if ($i==1):?>
            <?php if(BETTIME_PRODUCTION==false) {?>
                <img src='/img/pay.gif' alt=''>
            <?php } else {?>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="8PDC668K6LXCW">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG_global.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <form  method="post" action="https://www.okpay.com/process.html"><input type="hidden" name="ok_receiver" value="OK669203365"/>
                <input type="hidden" name="ok_item_1_name" value="TIP2"/>
                <input type="hidden" name="ok_currency" value="EUR"/>
                <input type="hidden" name="ok_item_1_type" value="digital"/>
                <input type="hidden" name="ok_item_1_price" value="50"/>
                <input type="hidden" name="ok_fees" value="1"/>
                <input type="hidden" name="ok_return_success" value="http://bettime.info/success"/>
                <input type="hidden" name="ok_return_fail" value="http://bettime.info/fail"/>
                <input type="hidden" name="ok_ipn" value="http://bettime.info/okpay/listener"/>
                <input type="image" name="submit" alt="OKPAY Payment" src="https://www.okpay.com/img/buttons/en/buy/b07g56x35en.png"/></form>
                <?php } ?>
     <?endif?>

    <?php if ($i==2):?>
            <?php if(BETTIME_PRODUCTION==false) {?>
                <img src='/img/pay.gif' alt=''>
            <?php } else {?>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="S3VP83FRLNMBL">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG_global.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <form  method="post" action="https://www.okpay.com/process.html"><input type="hidden" name="ok_receiver" value="OK669203365"/>
                <input type="hidden" name="ok_item_1_name" value="TIP3"/>
                <input type="hidden" name="ok_currency" value="EUR"/>
                <input type="hidden" name="ok_item_1_type" value="digital"/>
                <input type="hidden" name="ok_item_1_price" value="50"/>
                <input type="hidden" name="ok_fees" value="1"/>
                <input type="hidden" name="ok_return_success" value="http://bettime.info/success"/>
                <input type="hidden" name="ok_return_fail" value="http://bettime.info/fail"/>
                <input type="hidden" name="ok_ipn" value="http://bettime.info/okpay/listener"/>
                <input type="image" name="submit" alt="OKPAY Payment" src="https://www.okpay.com/img/buttons/en/buy/b07g56x35en.png"/></form>
                <?php } ?>
    <?endif?>


     <?php $i=$i+1; ?>



    </td>
</tr>
<?endforeach;?>