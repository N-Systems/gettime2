<p class="payment_module">
	<a href="javascript:$('#okpay_form').submit();" title="{l s='Pay with OkPay' mod='okpay'}">
		<img src="{$module_template_dir}okpay.png" alt="{l s='Pay with OkPay' mod='okpay'}" />
		{l s='Pay with OkPay' mod='okpay'}
	</a>
</p>

<form action="{$okpayUrl}" method="post" id="okpay_form" class="hidden">
	<input type="hidden" name="upload" value="1" />
	<input type="hidden" name="ok_payer_first_name" value="{$address->firstname}" />
	<input type="hidden" name="ok_payer_last_name" value="{$address->lastname}" />
	<input type="hidden" name="ok_payer_street" value="{$address->address1} {$address->address2}" />
	<input type="hidden" name="ok_payer_city" value="{$address->city}" />
	<input type="hidden" name="ok_payer_zip" value="{$address->postcode}" />
	<input type="hidden" name="ok_payer_country" value="{$country->iso_code}" />
	<input type="hidden" name="ok_payer_email" value="{$customer->email}" />
{if !$discounts}
	<input type="hidden" name="ok_item_1_shipping" value="{$shipping}" />
	{counter assign=i}
	{foreach from=$products item=product}
	<input type="hidden" name="ok_item_{$i}_name" value="{$product.name}{if isset($product.attributes)} - {$product.attributes}{/if}" />
	<input type="hidden" name="ok_item_{$i}_price" value="{$product.okpayAmount}" />
	<input type="hidden" name="ok_item_{$i}_quantity" value="{$product.quantity}" />
	{counter print=false}
	{/foreach}
{else}
	<input type="hidden" name="ok_item_1_name" value="{l s='My cart' mod='okpay'}" />
	<input type="hidden" name="ok_item_1_price" value="{$total}" />
	<input type="hidden" name="ok_item_1_quantity" value="1" />
{/if}
	<input type="hidden" name="ok_receiver" value="{$receiver}" />
	<input type="hidden" name="ok_currency" value="{$currency->iso_code}" />
	<input type="hidden" name="ok_item_1_custom_1_value" value="{$customer->id}" />
	<input type="hidden" name="ok_invoice" value="{$id_cart}" />
	<input type="hidden" name="ok_return_success" value="{$goBackUrl}" />
	<input type="hidden" name="ok_return_fail" value="{$cancelUrl}" />
	<input type="hidden" name="ok_ipn" value="{$notify}" />
    <input type="hidden" name="rm" value="2" />
	<input type="hidden" name="bn" value="PRESTASHOP_WPS" />
	<input type="hidden" name="cbt" value="{l s='Return to' mod='okpay'} {$meta_title}" />
</form>
