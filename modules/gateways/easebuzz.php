<?php
function easebuzz_config() {

    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"Easebuzz"),
     "MerchantKey" => array("FriendlyName" => "MerchantKey", "Type" => "text", "Size" => "20", ),
     "SALT" => array("FriendlyName" => "SALT", "Type" => "text", "Size" => "20",),
     "mode" => array("FriendlyName" => "mode", "Type" => "text", "Description" => "TEST or LIVE", ),
	
    );
    return $configarray;
}

function easebuzz_link($params) {
 

	# Gateway Specific Variables
	$key = trim($params['MerchantKey']);
	$SALT = trim($params['SALT']);
	$gatewaymode = trim($params['mode']);
	$surl = $params['systemurl'] . '/modules/gateways/callback/easebuzz-response.php';
	$furl = $params['systemurl'] . '/modules/gateways/callback/easebuzz-response.php';

	# Invoice Variables
	$txnid = trim($params['invoiceid']);
	$productinfo = trim("invoice id".$params["invoiceid"]);
        $amount = trim($params['amount']); # Format: ##.##

	# Client Variables
	$firstname = trim($params['clientdetails']['firstname']);
	$lastname = trim($params['clientdetails']['lastname']);
	$email = trim($params['clientdetails']['email']);
	$address1 = trim($params['clientdetails']['address1']);
	$city = trim($params['clientdetails']['city']);
	$state = trim($params['clientdetails']['state']);
	$postcode = trim($params['clientdetails']['postcode']);
	$country = trim($params['clientdetails']['country']);
	$phone = trim($params['clientdetails']['phonenumber']);
        
        
    $hashSequence = $key.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||';
    
    $hashSequence .= $SALT;
    $hash = strtolower(hash('sha512', $hashSequence));
	# System Variables
    $companyname = 'Easebuzz';
    $systemurl = $params['systemurl'];
    $PostURL = "https://testpay.easebuzz.in/pay/secure";
    if($gatewaymode == 'LIVE')
      $PostURL = "https://pay.easebuzz.in/pay/secure";
	# Enter your code submit to the easebuzz gateway...

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