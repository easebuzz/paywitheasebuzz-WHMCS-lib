<?php
ob_start();
# Required File Includes
include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");


$gatewaymodule = "paywitheasebuzz"; # Enter your gateway module name here replacing template

$GATEWAY = getGatewayVariables($gatewaymodule);
if (!$GATEWAY["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback

$response = array();
$response = $_POST;

# Get Returned Variables - Adjust for Post Variable Names from your Gateway's Documentation
$status = $response["status"];
$fee = $response['amount'];
$amount = $response["amount"];
$invoiceid = $response["txnid"];
$transid = $response["easepayid"];
#$amount = ($request_params["transaction_amount"]) / 100;

$invoiceid = checkCbInvoiceID($invoiceid, 'paywitheasebuzz'); # Checks invoice ID is a valid invoice number or ends processing

checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

#$invoiceid = checkCbInvoiceID($invoiceid,$GATEWAY["name"]); # Checks invoice ID is a valid invoice number or ends processing

#checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

if($response['status']=='success') {
    # Successful
    
    addInvoicePayment($invoiceid, $transid, $amount,0, $gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
    	//echo $invoiceid;
	logTransaction($GATEWAY["name"],$response,"Successful"); # Save to Gateway Log: name, data array, status
	redirSystemURL("id=" . $invoiceid . "&paymentsuccess=true", "viewinvoice.php");
	return 1;
} else {
	# Unsuccessful
    logTransaction($GATEWAY["name"],$response,"Unsuccessful"); # Save to Gateway Log: name, data array, status
 	redirSystemURL("id=" . $invoiceid . "&paymentfailed=true", "viewinvoice.php");
	return 1;
}


//$filename = $GATEWAY['systemurl'].'/viewinvoice.php?id=' . $invoiceid;     // path of your viewinvoice.php
//HEADER("location:$filename");

?>
