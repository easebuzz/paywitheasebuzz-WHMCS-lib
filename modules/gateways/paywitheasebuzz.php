<?php
function paywitheasebuzz_config() {

    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"PayWithEasebuzz"),
     "MerchantKey" => array("FriendlyName" => "MerchantKey", "Type" => "text", "Size" => "20", ),
     "SALT" => array("FriendlyName" => "SALT", "Type" => "text", "Size" => "20",),
     "mode" => array("FriendlyName" => "mode", "Type" => "text", "Description" => "TEST or LIVE", ),
		
    );
	return $configarray;
}


function paywitheasebuzz_link($params) {


	# Gateway Specific Variables 
	$key = trim($params['MerchantKey']);
	$SALT = trim($params['SALT']);
	$gatewaymode = trim($params['mode']);
	$surl = $params['systemurl'] . '/modules/gateways/callback/paywitheasebuzz-response.php';
	$furl = $params['systemurl'] . '/modules/gateways/callback/paywitheasebuzz-response.php';
	

	# Invoice Variables
	$txnid = $params['invoiceid'];
	$productinfo = trim("invoice id".$params["invoiceid"]);
    	$amount = $params['amount']; # Format: ##.##

	# Client Variables
	$firstname = $params['clientdetails']['firstname'];
	$lastname = $params['clientdetails']['lastname'];
	$email = $params['clientdetails']['email'];
	$address1 = $params['clientdetails']['address1'];
	$city = $params['clientdetails']['city'];
	$state = $params['clientdetails']['state'];
	$postcode = $params['clientdetails']['postcode'];
	$country = $params['clientdetails']['country'];
	$phone = $params['clientdetails']['phonenumber'];
        
    $hashSequence = $key.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||';
    
    $hashSequence .= $SALT;
    $hash = strtolower(hash('sha512', $hashSequence));
	# System Variables
	$companyname = 'paywitheasebuzz';
	$systemurl = $params['systemurl'];
    $PostURL = "https://testpay.easebuzz.in/pay/secure";
    if($gatewaymode == 'LIVE')
      $PostURL = "https://pay.easebuzz.in/pay/secure";
      else
      $PostURL = "https://testpay.easebuzz.in/pay/secure";

	$code = '<form method="post" action='.$PostURL.' name="frmTransaction" id="frmTransaction" onSubmit="return validate()">
<input type="hidden" name="key" value="'.$key.'" />
<input type="hidden" name="productinfo" value="'.$productinfo.'" />
<input type="hidden" name="txnid" value="'.$txnid.'" />
<input type="hidden" name="firstname" value="'.$firstname.'" />
<input type="hidden" name="address1" value="'.$address1.'" />
<input type="hidden" name="city" value="'.$city.'" />
<input type="hidden" name="state" value="'.$state.'" />
<input type="hidden" name="country" value="'.$country.'" />
<input type="hidden" name="postal_code" value="'.$postcode.'" />
<input type="hidden" name="email" value="'.$email.'" />
<input type="hidden" name="phone" value="'.$phone.'" />
<input type="hidden" name="amount" value="'.$amount.'" />
<input type="hidden" name="hash" value="'.$hash.'" />
<input type="hidden" name="surl" value="'.$surl.'" />   
<input type="hidden" name="furl" value="'.$furl.'" />
<input type="submit" value="Pay Now" />
</form>';

	return $code;

}
?>
