<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class  Payment {
	/*
	 * Example usage of Authorize.net's
	 * Advanced Integration Method (AIM)
	 */
	function refundTransaction($cardnumber, $expdate, $amount, $tranxid)
	{
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        if(env=="test"){
            $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
            $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
        }else{
            $merchantAuthentication->setName(AUTHORIZED_NAME);
            $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
        }

		// Set the transaction's refId
		$refId = 'ref' . time();

		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($cardnumber);
		$creditCard->setExpirationDate($expdate);
		$paymentOne = new AnetAPI\PaymentType();
		$paymentOne->setCreditCard($creditCard);
		//create a transaction
		$transactionRequest = new AnetAPI\TransactionRequestType();
		$transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setRefTransId($tranxid);
		$transactionRequest->setAmount($amount);
		$transactionRequest->setPayment($paymentOne);


		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);

		$request->setTransactionRequest( $transactionRequest);
		$controller = new AnetController\CreateTransactionController($request);
        if(env=="test"){
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }else{
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

		if ($response != null)
		{
			if($response->getMessages()->getResultCode() == "Ok")
			{
				$tresponse = $response->getTransactionResponse();

				if ($tresponse != null && $tresponse->getMessages() != null)
				{
					$data = array("error"=>false, "message"=>"Refund SUCCESS", "trans_id"=>$tresponse->getTransId(),"Code"=>$tresponse->getMessages()[0]->getCode(),
					"Description"=>$tresponse->getMessages()[0]->getDescription(),"Transaction Response code"=>$tresponse->getResponseCode() );
				}
				else
				{
					if($tresponse->getErrors() != null)
					{$data = array("error"=>true, "message"=>"Transaction Failed", "Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"
					Error message"=>$tresponse->getErrors()[0]->getErrorText());
					}
				}
			}
			else
			{
				$tresponse = $response->getTransactionResponse();
				if($tresponse != null && $tresponse->getErrors() != null)
				{$data = array("error"=>true, "message"=>"Transaction Failed", "Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"Error message"=>$tresponse->getErrors()[0]->getErrorText());
				}
				else
				{
					$data = array("error"=>true, "message"=>"Transaction Failed", "Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"
					Error message"=>$tresponse->getErrors()[0]->getErrorText());
				}
			}
		}
		else
		{
			$data = array("error"=>true, "message"=>"No response returned");

		}
		return $data;
	}
	//------------------capturePreviouslyAuthorizedAmount---------------
	function capturePreviouslyAuthorizedAmount($transactionid,$amount)
	{
    	/* Create a merchantAuthenticationType object with authentication details
       	retrieved from the constants file */
    	$resp="";
	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    if(env=="test"){
	        $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
	    }else{
	        $merchantAuthentication->setName(AUTHORIZED_NAME);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
	    }

	    // Set the transaction's refId
	    $refId = 'ref' . time();

	    // Now capture the previously authorized  amount
	    //echo "Capturing the Authorization with transaction ID : " . $transactionid . "\n";
	    $transactionRequestType = new AnetAPI\TransactionRequestType();
	    $transactionRequestType->setTransactionType("priorAuthCaptureTransaction");
	    $transactionRequestType->setRefTransId($transactionid);
	    $transactionRequestType->setAmount($amount);


	    $request = new AnetAPI\CreateTransactionRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setTransactionRequest( $transactionRequestType);

	    $controller = new AnetController\CreateTransactionController($request);
	    if(env=="test"){
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    }else{
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	    }

	    if ($response != null)
	    {
	        if($response->getMessages()->getResultCode() == 'Ok')
	        {
	            $tresponse = $response->getTransactionResponse();

	            if ($tresponse != null && $tresponse->getMessages() != null)
	            {
                	//$resp= " Transaction Response code : " . $tresponse->getResponseCode() . "\n Successful." . "\n Capture Previously Authorized Amount, Trans ID : " . $tresponse->getRefTransId() . "\n Code : " . $tresponse->getMessages()[0]->getCode() . "\n Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
	              	//  echo "Successful." . "\n";
	                //echo "Capture Previously Authorized Amount, Trans ID : " . $tresponse->getRefTransId() . "\n";
	                //echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
	               	// echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
					$resp=true;
	            }
	            else
	            {
	              	$resp= "Transaction Failed \n";
	                if($tresponse->getErrors() != null)
	                {
	                    //$resp= "Transaction Failed \n Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n  Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	                    //echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	                    $resp=false;
	                }
	            }
        	}
	        else
	        {
	           // $resp= "Transaction Failed \n";
	            $tresponse = $response->getTransactionResponse();
	            if($tresponse != null && $tresponse->getErrors() != null)
	            {
	                $resp=false;
	               //$resp= "Transaction Failed \n Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	               // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	            }
	            else
	            {
	                $resp=false;
	               // $resp= "Transaction Failed \n Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
	               // echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
	            }
	        }
	    }
	    else
	    {
	        $resp=false;
	        //$resp=  "No response returned \n";
	    }

	    return $resp;
	}

	//--------------deleteCustomerPaymentProfile--------------
	function deleteCustomerPaymentProfile($customerProfileId,$customerpaymentprofileid) {
	    /* Create a merchantAuthenticationType object with authentication details
	       retrieved from the constants file */
	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    if(env=="test"){

	        $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
	    }else{
	        $merchantAuthentication->setName(AUTHORIZED_NAME);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
	    }

	    // Set the transaction's refId
	    $refId = 'ref' . time();

	    // Use an existing payment profile ID for this Merchant name and Transaction key

	    $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setCustomerProfileId($customerProfileId);
	    $request->setCustomerPaymentProfileId($customerpaymentprofileid);
	    $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
	    if(env=="test"){

	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    }else{
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	    }

	    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
	    {
	        $resp=true;
	       	// echo "SUCCESS: Delete Customer Payment Profile  SUCCESS  :" . "\n";
	    }
	    else
	    {

	     	/*  echo "ERROR :  Delete Customer Payment Profile: Invalid response\n";
	        $errorMessages = $response->getMessages()->getMessage();
	       	echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";*/
	        $resp=false;
	    }
	    return $resp;
	}


	function getTransactionListForCustomerRequest($customerProfileId)
	{
	    /* Create a merchantAuthenticationType object with authentication details
	       retrieved from the constants file */
	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    if(env=="test"){

	        $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
	    }else{
	        $merchantAuthentication->setName(AUTHORIZED_NAME);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
	    }


	    // Set the transaction's refId
	    $refId = 'ref' . time();

	    $request = new AnetAPI\GetTransactionListForCustomerRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setCustomerProfileId($customerProfileId);

	    $controller = new AnetController\GetTransactionListForCustomerController($request);

	    if(env=="test"){

	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    }else{
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	    }

	    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
	        if (null != $response->getTransactions()) {
	            $txnArray = array();
	            foreach ($response->getTransactions() as $tx) {

	                $txnArray[] = array("TransactionID" => $tx->getTransId(), "transaction_status" => $tx->getTransactionStatus(), "accountnumber" => $tx->getAccountNumber()
	                , "settleAmount" => $tx->getsettleAmount(), "accountType" => $tx->getaccountType(),
	                    "customerPaymentProfileId" => $tx->getProfile()->getcustomerPaymentProfileId(),
	                    "firstname" => $tx->getfirstName(), "lastname" => $tx->getlastname());
	            }
	            $data = array("error" => false, "message" => "success" , "data"=>$txnArray);
	        } else {

	            $data = array("error" =>true, "message" => "No transactions associated with given customer profile"
	            );
	        }
	    } else {
	        $errorMessages = $response->getMessages()->getMessage();
	        $data = array("error" => true, "message" => "ERROR :  Invalid response", "errormessage" => $errorMessages[0]->getText(),
	            "errormessage" => $errorMessages[0]->getCode());


	    }

	    return $data;
	    //$this->common->responseJson($data);
	}

	function voidTransaction($transactionid)
	{
	    /* Create a merchantAuthenticationType object with authentication details
	       retrieved from the constants file */
	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    if(env=="test"){

	        $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
	    }else{
	        $merchantAuthentication->setName(AUTHORIZED_NAME);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
	    }

	    // Set the transaction's refId
	    $refId = 'ref' . time();

	    //create a transaction
	    $transactionRequestType = new AnetAPI\TransactionRequestType();
	    $transactionRequestType->setTransactionType("voidTransaction");
	    $transactionRequestType->setRefTransId($transactionid);

	    $request = new AnetAPI\CreateTransactionRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setRefId($refId);
	    $request->setTransactionRequest( $transactionRequestType);
	    $controller = new AnetController\CreateTransactionController($request);
	    if(env=="test"){

	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    }else{
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	    }


	    if ($response != null)
	    {
	        if($response->getMessages()->getResultCode() == 'Ok')
	        {
	            $tresponse = $response->getTransactionResponse();

	            if ($tresponse != null && $tresponse->getMessages() != null)
	            {
	                $resp="true";
	                //  echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
	                //  echo " Void transaction SUCCESS AUTH CODE: " . $tresponse->getAuthCode() . "\n";
	                //  echo " Void transaction SUCCESS TRANS ID  : " . $tresponse->getTransId() . "\n";
	                //  echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
	                // echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";

	            }
	            else
	            {
	                // echo "Transaction Failed \n";
	                if($tresponse->getErrors() != null)
	                {
	                    // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
	                    // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	                    $resp="false";
	                }
	            }
	        }
	        else
	        {
	            // echo "Transaction Failed \n";
	            $tresponse = $response->getTransactionResponse();
	            if($tresponse != null && $tresponse->getErrors() != null)
	            {
	                // echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
	                // echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	                $resp="false";
	            }
	            else
	            {
	                // echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
	                // echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
	                $resp="false";
	            }
	        }
	    }
	    else
	    {
	        $resp="false";
	        // echo  "No response returned \n";
	    }

	    return $resp;
	}

	function getTransactionDetails($transactionId)
	{
	    /* Create a merchantAuthenticationType object with authentication details
	       retrieved from the constants file */
	    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	    if(env=="test"){

	        $merchantAuthentication->setName(AUTHORIZED_NAME_TEST);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY_TEST);
	    }else{
	        $merchantAuthentication->setName(AUTHORIZED_NAME);
	        $merchantAuthentication->setTransactionKey(AUTHORIZED_KEY);
	    }

	    // Set the transaction's refId
	    $refId = 'ref' . time();

	    $request = new AnetAPI\GetTransactionDetailsRequest();
	    $request->setMerchantAuthentication($merchantAuthentication);
	    $request->setTransId($transactionId);

	    $controller = new AnetController\GetTransactionDetailsController($request);

	    if(env=="test"){

	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    }else{
	        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
	    }

	    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
	    {

	        $data = array("error" => false, "message" => "SUCCESS" , "status"=>$response->getTransaction()->getTransactionStatus(),
	            "amount"=>$response->getTransaction()->getAuthAmount(),"transID"=>$response->getTransaction()->getTransId());
	    }
	    else
	    {

	        $errorMessages = $response->getMessages()->getMessage();

	        $data = array("error" => true, "message" => "Invalid response" , "errorcode"=>$errorMessages[0]->getCode(),"errormessage"=>$errorMessages[0]->getText());
	    }
	    return $data;
	}
}

?>