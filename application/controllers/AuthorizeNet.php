<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'vendor/autoload.php';
//require 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/*
 * Example usage of Authorize.net's
 * Advanced Integration Method (AIM)
 */
class AuthorizeNet extends CI_Controller
{
	public $responseText;
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->helper('mail');
        $this->load->helper('sms');
		$this->load->library('payment');
		//$this->load->library('authorize_net');
		$this->responseText = array("1"=>"Approved", "2"=>"Declined", "3"=>"Error", "4"=>"Held for Review");
	}

	public function paymentAlternate()
	{
		// Authorize.net lib
		$card_number=$this->input->post('card_number');

		$card_expiry=$this->input->post('expiry_date');
		$card_cvc=$this->input->post('cvv');
		$total=$this->input->post('amount');

		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);

		// Set the transaction's refId
		$refId = 'ref' . time();
		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($card_number);
		$creditCard->setExpirationDate($card_expiry);
		// Set the token specific info
		$creditCard->setIsPaymentToken(true);
		$creditCard->setCryptogram("EjRWeJASNFZ4kBI0VniQEjRWeJA=");

		$paymentOne = new AnetAPI\PaymentType();
		$paymentOne->setCreditCard($creditCard);

		//create a transaction
		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("authCaptureTransaction");
		$transactionRequestType->setAmount($total);
		$transactionRequestType->setPayment($paymentOne);
		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId( $refId);
		$request->setTransactionRequest( $transactionRequestType);
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse( $envurl);

		if ($response != null)
		{
			if($response->getMessages()->getResultCode() =='Ok')
			{
				$tresponse = $response->getTransactionResponse();

				if ($tresponse != null && $tresponse->getMessages() != null)
				{
					$data = array("error"=>false, "message"=>"Success", "Transaction Response code"=>$tresponse->getResponseCode(),
						"approval_code"=>$tresponse->getAuthCode(), "trans_id"=>$tresponse->getTransId()
					,"Code"=>$tresponse->getMessages()[0]->getCode(), "Description"=>$tresponse->getMessages()[0]->getDescription());
				}
				else
				{
					$data = array("error"=>true, "message"=>"Transaction Failed");

					if($tresponse->getErrors() != null)
					{
						$data = array("error"=>true, "Error code"=>$tresponse->getErrors()[0]->getErrorCode(), "message"=>$tresponse->getErrors()[0]->getErrorText()
						);
					}
				}
			}
			else
			{
				$tresponse = $response->getTransactionResponse();

				if($tresponse != null && $tresponse->getErrors() != null)
				{
					$data = array("error"=>true, "Error code"=>$tresponse->getErrors()[0]->getErrorCode(), "message"=>$tresponse->getErrors()[0]->getErrorText()
					);
				}
				else
				{
					$data = array("error"=>true, "Error code"=>$tresponse->getErrors()[0]->getErrorCode(), "message"=>$tresponse->getErrors()[0]->getErrorText()
					);
				}
			}
		}
		else
		{
			$data = array("error"=>true, "message"=>"No response returned");
		}
		responseJSON($data);
	}


	function createCustomerProfile()
	{

		$card_number=$this->input->post('card_number');
		$card_expiry=$this->input->post('card_expiry');
		$card_cvc=$this->input->post('card_cvc');
		$firstname=$this->input->post('firstname');
		$lastname=$this->input->post('lastname');
		$email=$this->input->post('email');
		$country=$this->input->post('country');
		$state=$this->input->post('state');
		$city=$this->input->post('city');
		$address=$this->input->post('address');
		$mobile=$this->input->post('mobile');
		$pin_code=$this->input->post('pin_code');

		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);

		// Set the transaction's refId
		$refId = 'ref' . time();

		// Create a Customer Profile Request
		//  1. (Optionally) create a Payment Profile
		//  2. (Optionally) create a Shipping Profile
		//  3. Create a Customer Profile (or specify an existing profile)
		//  4. Submit a CreateCustomerProfile Request
		//  5. Validate Profile ID returned

		// Set credit card information for payment profile
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($card_number);
		$creditCard->setExpirationDate($card_expiry);
		$creditCard->setCardCode($card_cvc);
		$paymentCreditCard = new AnetAPI\PaymentType();
		$paymentCreditCard->setCreditCard($creditCard);

		// Create the Bill To info for new payment type
		$billTo = new AnetAPI\CustomerAddressType();
		$billTo->setFirstName($firstname);
		$billTo->setLastName($lastname);
		//$billTo->setCompany("Souveniropolis");
		$billTo->setAddress($address);
		$billTo->setCity($city);
		$billTo->setState($state);
		$billTo->setZip($pin_code);
		$billTo->setCountry($country);
		$billTo->setPhoneNumber($mobile);
		//$billTo->setfaxNumber("999-999-9999");

		// Create a customer shipping address
		$customerShippingAddress = new AnetAPI\CustomerAddressType();
		$customerShippingAddress->setFirstName($firstname);
		$customerShippingAddress->setLastName($lastname);
		//$customerShippingAddress->setCompany($address);
		$customerShippingAddress->setAddress(rand() . $address);
		$customerShippingAddress->setCity($city);
		$customerShippingAddress->setState($state);
		$customerShippingAddress->setZip($pin_code);
		$customerShippingAddress->setCountry($country);
		$customerShippingAddress->setPhoneNumber($mobile);
		//$customerShippingAddress->setFaxNumber("999-999-9999");

		// Create an array of any shipping addresses
		$shippingProfiles[] = $customerShippingAddress;


		// Create a new CustomerPaymentProfile object
		$paymentProfile = new AnetAPI\CustomerPaymentProfileType();
		$paymentProfile->setCustomerType('individual');
		$paymentProfile->setBillTo($billTo);
		$paymentProfile->setPayment($paymentCreditCard);
		$paymentProfile->setDefaultpaymentProfile(true);
		$paymentProfiles[] = $paymentProfile;


		// Create a new CustomerProfileType and add the payment profile object
		$customerProfile = new AnetAPI\CustomerProfileType();
		$customerProfile->setDescription("Create user Profile Id");
		$customerProfile->setMerchantCustomerId("M_" . time());
		$customerProfile->setEmail($email);
		$customerProfile->setpaymentProfiles($paymentProfiles);
		$customerProfile->setShipToList($shippingProfiles);


		// Assemble the complete transaction request
		$request = new AnetAPI\CreateCustomerProfileRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);
		$request->setProfile($customerProfile);

		// Create the controller and get the response
		$controller = new AnetController\CreateCustomerProfileController($request);
		$response = $controller->executeWithApiResponse($envurl);

		if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {


			$paymentProfiles = $response->getCustomerPaymentProfileIdList();
			foreach($paymentProfiles as $paymentprof);
			{
				$data=array("error"=>false,"message"=>"Succesfully created customer profile ","CustomerProfileId"=>$response->getCustomerProfileId(),
					"paymentProfilesID"=>$paymentprof);
			}
			//echo "SUCCESS: PAYMENT PROFILE ID : " . $paymentProfiles[0] . "\n";
		} else {

			$errorMessages = $response->getMessages()->getMessage();

			$data=array("error"=>true,"message"=>"Invalid response ","Response"=> $errorMessages[0]->getCode(),
				"ResponseText"=>$errorMessages[0]->getText());
		}
		responseJSON($data);
	}

	//------------------------createCustomerPaymentProfile-----------------
	function createCustomerPaymentProfile()
	{

		$existingcustomerprofileid=$this->input->post('customerProfileId');
		$card_number=$this->input->post('card_number');
		$card_expiry=$this->input->post('card_expiry');
		$card_cvc=$this->input->post('card_cvc');
		$firstname=$this->input->post('firstname');
		$lastname=$this->input->post('lastname');
		//	$email=$this->input->post('email');
		$country=$this->input->post('country');
		$state=$this->input->post('state');
		$city=$this->input->post('city');
		$address=$this->input->post('address');
		$mobile=$this->input->post('mobile');
		$pin_code=$this->input->post('pin_code');
		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);


		// Set the transaction's refId
		$refId = 'ref' . time();

		// Create a Customer Profile Request
		//  1. (Optionally) create a Payment Profile
		//  2. (Optionally) create a Shipping Profile
		//  3. Create a Customer Profile (or specify an existing profile)
		//  4. Submit a CreateCustomerProfile Request
		//  5. Validate Profile ID returned

		// Set credit card information for payment profile
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber($card_number);
		$creditCard->setExpirationDate($card_expiry);
		$creditCard->setCardCode($card_cvc);
		$paymentCreditCard = new AnetAPI\PaymentType();
		$paymentCreditCard->setCreditCard($creditCard);

		// Create the Bill To info for new payment type
		$billto = new AnetAPI\CustomerAddressType();
		$billto->setFirstName($firstname);
		$billto->setLastName($lastname);
		//$billto->setCompany();
		$billto->setAddress($address);
		$billto->setCity($city);
		$billto->setState($state);
		$billto->setZip($pin_code);
		$billto->setCountry($country);
		$billto->setPhoneNumber($mobile);
		//$billto->setfaxNumber("999-999-9999");

		// Create a new Customer Payment Profile object
		$paymentprofile = new AnetAPI\CustomerPaymentProfileType();
		$paymentprofile->setCustomerType('individual');
		$paymentprofile->setBillTo($billto);
		$paymentprofile->setPayment($paymentCreditCard);
		$paymentprofile->setDefaultPaymentProfile(true);

		$paymentprofiles[] = $paymentprofile;

		// Assemble the complete transaction request
		$paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
		$paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);

		// Add an existing profile id to the request
		$paymentprofilerequest->setCustomerProfileId($existingcustomerprofileid);
		$paymentprofilerequest->setPaymentProfile($paymentprofile);
		$paymentprofilerequest->setValidationMode("liveMode");

		// Create the controller and get the response
		$controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
		$response = $controller->executeWithApiResponse($envurl);

		if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
			$data=array("error"=>false, "message"=>"Create Customer Payment Profile SUCCESS", "response"=>$response->getCustomerPaymentProfileId());

		} else {
			$errorMessages = $response->getMessages()->getMessage();

			$data=array("error"=>true, "message"=>"ERROR Invalid response", "response"=>$errorMessages[0]->getCode(),
				"responseText"=>$errorMessages[0]->getText());
		}
		responseJSON($data);
	}

	//-------------------getCustomerPaymentProfile------------
	function getCustomerPaymentProfile() {

	 	$customerProfileId=$this->input->post('customerProfileId');
		$customerPaymentProfileId=$this->input->post('customerpaymentprofile');

		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);

		// Set the transaction's refId
		$refId = 'ref' . time();

		//request requires customerProfileId and customerPaymentProfileId
		$request = new AnetAPI\GetCustomerPaymentProfileRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId( $refId);
		$request->setCustomerProfileId($customerProfileId);
		$request->setCustomerPaymentProfileId($customerPaymentProfileId);

		$controller = new AnetController\GetCustomerPaymentProfileController($request);
		$response = $controller->executeWithApiResponse($envurl);

		if(($response != null)){
			if ($response->getMessages()->getResultCode() == "Ok")
			{
				$data = array("error"=>false, "message"=>"Success", "ProfileID"=>$response->getPaymentProfile()->getCustomerPaymentProfileId(),
					"BillingAddress"=> $response->getPaymentProfile()->getbillTo()->getAddress()
				,"CardLast4"=>$response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(),"expiry"=>$response->getPaymentProfile()->getPayment()->getCreditCard()->getexpirationDate(), "Cardtype"=>$response->getPaymentProfile()->getPayment()->getCreditCard()->getcardType());

				if($response->getPaymentProfile()->getSubscriptionIds() != null)
				{
					if($response->getPaymentProfile()->getSubscriptionIds() != null)
					{
						foreach($response->getPaymentProfile()->getSubscriptionIds() as $subscriptionid)

						$data=array("error"=>false, "message"=>"List of subscriptions", "subscriptionid"=>$subscriptionid);
					}
				}
			}
			else
			{
				$errorMessages = $response->getMessages()->getMessage();

				$data=array("error"=>true, "message"=>"Invalid response", "Response"=> $errorMessages[0]->getCode(), "Responsetext"=>$errorMessages[0]->getText());
			}
		}
		else{

			$data=array("error"=>true, "message"=>"NULL Response Error");
		}
		responseJSON($data);
	}

	//-------------------chargeCustomerProfile---------------
	function chargeCustomerProfile()
	{
		$profileid=$this->input->post('profileid');
		$paymentprofileid=$this->input->post('paymentprofileid');
		$amount=$this->input->post('amount');
		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);

		// Set the transaction's refId
		$refId = 'ref' . time();

		$profileToCharge = new AnetAPI\CustomerProfilePaymentType();
		$profileToCharge->setCustomerProfileId($profileid);
		$paymentProfile = new AnetAPI\PaymentProfileType();
		$paymentProfile->setPaymentProfileId($paymentprofileid);
		$profileToCharge->setPaymentProfile($paymentProfile);

		$transactionRequestType = new AnetAPI\TransactionRequestType();
		$transactionRequestType->setTransactionType("authOnlyTransaction");
		$transactionRequestType->setAmount($amount);
		$transactionRequestType->setProfile($profileToCharge);

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId( $refId);
		$request->setTransactionRequest( $transactionRequestType);
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse($envurl);
        if ($response != null)
        {
            if($response->getMessages()->getResultCode() == 'Ok')
            {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null)
                {
                    $data=array("error"=>false,"message"=>"Success","Response"=> $tresponse->getResponseCode(),"AUTHCODE"=>$tresponse->getAuthCode(),
                        "TRANSID"=>$tresponse->getTransId(),"Code"=>$tresponse->getMessages()[0]->getCode(),"Description"=>$tresponse->getMessages()[0]->getDescription());
				}
                else
                {
                    if($tresponse->getErrors() != null)
                    {
                        $data=array("error"=>true,"message"=>"Transaction Failed","Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"Error message"=>$tresponse->getErrors()[0]->getErrorText());

                    }
                }
            }
            else
            {
                $tresponse = $response->getTransactionResponse();
                if($tresponse != null && $tresponse->getErrors() != null)
                {
                    $data=array("error"=>true,"message"=>"Transaction Failed","Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"Error message"=>$tresponse->getErrors()[0]->getErrorText());
                }
                else
                {
					$data=array("error"=>true,"message"=>$tresponse->getErrors()[0]->getErrorCode());
					// $data=array("error"=>true,"message"=>"Transaction Failed","Error code"=>$tresponse->getErrors()[0]->getErrorCode(),"Error message"=>$tresponse->getErrors()[0]->getErrorText());
                }
            }
        }
        else
        {
            $data=array("error"=>true,"message"=>"No response returned");
        }
        responseJson($data);
	}

	//	function capturePreviouslyAuthorizedAmount($transactionid)
	//	{
	//		/* Create a merchantAuthenticationType object with authentication details
	//           retrieved from the constants file */
	//		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//		$api_loginId = $this->config->item('api_login_id');
	//		$apiTransectionKey =$this->config->item('api_transaction_key');
	//		$envurl = $this->config->item('authorize_env_url');
	//		$merchantAuthentication->setName($api_loginId);
	//		$merchantAuthentication->setTransactionKey($apiTransectionKey);
	//
	//		// Set the transaction's refId
	//		$refId = 'ref' . time();
	//
	//		// Now capture the previously authorized  amount
	//		echo "Capturing the Authorization with transaction ID : " . $transactionid . "\n";
	//		$transactionRequestType = new AnetAPI\TransactionRequestType();
	//		$transactionRequestType->setTransactionType("priorAuthCaptureTransaction");
	//		$transactionRequestType->setRefTransId($transactionid);
	//
	//
	//		$request = new AnetAPI\CreateTransactionRequest();
	//		$request->setMerchantAuthentication($merchantAuthentication);
	//		$request->setTransactionRequest( $transactionRequestType);
	//
	//		$controller = new AnetController\CreateTransactionController($request);
	//		$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	//
	//		if ($response != null)
	//		{
	//			if($response->getMessages()->getResultCode() == 'Ok')
	//			{
	//				$tresponse = $response->getTransactionResponse();
	//
	//				if ($tresponse != null && $tresponse->getMessages() != null)
	//				{
	//					echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
	//					echo "Successful." . "\n";
	//					echo "Capture Previously Authorized Amount, Trans ID : " . $tresponse->getRefTransId() . "\n";
	//					echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
	//					echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
	//				}
	//				else
	//				{
	//					echo "Transaction Failed \n";
	//					if($tresponse->getErrors() != null)
	//					{
	//						echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
	//						echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	//					}
	//				}
	//			}
	//			else
	//			{
	//				echo "Transaction Failed \n";
	//				$tresponse = $response->getTransactionResponse();
	//				if($tresponse != null && $tresponse->getErrors() != null)
	//				{
	//					echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
	//					echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
	//				}
	//				else
	//				{
	//					echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
	//					echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
	//				}
	//			}
	//		}
	//		else
	//		{
	//			echo  "No response returned \n";
	//		}
	//
	//		return $response;
	//	}

	//-----------------refundTransaction------------------
	function refundTransaction($amount)
	{
		/* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$api_loginId = $this->config->item('api_login_id');
		$apiTransectionKey =$this->config->item('api_transaction_key');
		$envurl = $this->config->item('authorize_env_url');
		$merchantAuthentication->setName($api_loginId);
		$merchantAuthentication->setTransactionKey($apiTransectionKey);

		// Set the transaction's refId
		$refId = 'ref' . time();

		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();
		$creditCard->setCardNumber("0015");
		$creditCard->setExpirationDate("XXXX");
		$paymentOne = new AnetAPI\PaymentType();
		$paymentOne->setCreditCard($creditCard);
		//create a transaction
		$transactionRequest = new AnetAPI\TransactionRequestType();
		$transactionRequest->setTransactionType( "refundTransaction");
		$transactionRequest->setAmount($amount);
		$transactionRequest->setPayment($paymentOne);

		$request = new AnetAPI\CreateTransactionRequest();
		$request->setMerchantAuthentication($merchantAuthentication);
		$request->setRefId($refId);
		$request->setTransactionRequest( $transactionRequest);
		$controller = new AnetController\CreateTransactionController($request);
		$response = $controller->executeWithApiResponse( $envurl);

		if ($response != null)
		{
			if($response->getMessages()->getResultCode() =='Ok')
			{
				$tresponse = $response->getTransactionResponse();

				if ($tresponse != null && $tresponse->getMessages() != null)
				{
					echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
					echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
					echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
					echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
				}
				else
				{
					echo "Transaction Failed \n";
					if($tresponse->getErrors() != null)
					{
						echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
						echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
					}
				}
			}
			else
			{
				echo "Transaction Failed \n";
				$tresponse = $response->getTransactionResponse();
				if($tresponse != null && $tresponse->getErrors() != null)
				{
					echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
					echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
				}
				else
				{
					echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
					echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
				}
			}
		}
		else
		{
			echo  "No response returned \n";
		}

		return $response;
	}

	function abc(){
		$this->refundTransaction('40');
	}

	//-----------------removeCard------------------
	public  function removeCard()
	{
		$response_result = array();
		$this->form_validation->set_rules('Authorization','Auth key','required');
		if($this->form_validation->run() == TRUE){
			$auth =$this->input->post('Authorization');
			$customerProfileId = $this->input->post('profileid');
			$customerPaymentProfileId = $this->input->post('paymentprofileid');
			$userid = getuserid('users',$auth);
            if($userid){
            	if ($customerPaymentProfileId != "NA" && $customerProfileId != "NA") {
            		$getsavecardbyid = $this->Model->get_record('user_save_card',array('customerProfileId' => $customerProfileId,'customerPaymentProfileId' => $customerPaymentProfileId, 'user_id' => $userid));
            		if(count($getsavecardbyid) > 0){
            			$isspayment = $this->payment->deleteCustomerPaymentProfile($customerProfileId,$customerPaymentProfileId);
            			if($isspayment){
            				$last_id_update = $this->Model->update('user_save_card',array('status' => '1'),array('user_id' =>$userid,'customerProfileId' => $customerProfileId,'customerPaymentProfileId' => $customerPaymentProfileId));
            				if($last_id_update){
            					$response_result["iscardremove"] = true;
				                $response_result["error"] = false;
				                $response_result["message"] = "card deleted sucessfully";
            				}
            			}else {
			                $response_result["error"] = true;
			                $response_result["message"] = "card not deleted";
			            }
            		}else{
            			$response_result["error"] = true;
            			$response_result["message"] = "something went wrong please try again";
            		}
            	}else{
            		$response_result["error"] = true;
        			$response_result["message"] = "Information Not Valid";
            	}
            }else{
                $response_result['error'] = true;
                $response_result['message'] = 'invalid auth key';
            }
		}else{
			$response_result["error"] = true;
            $response_result["message"] = strip_tags(validation_errors());
		}
		responseJSON($response_result);
	}

	//-------------------------Membership payment---------
	public function membership_payment(){
		$response_result = array("Error code"=> "","Error message"=>"");
		$this->form_validation->set_rules('Authorization','Auth key','required');
		$this->form_validation->set_rules('paymentpayprofileid','paymentpayprofileid','required');
		$this->form_validation->set_rules('profileid','profileid','required');
		$this->form_validation->set_rules('selected_plan','selected plan','required');
		$this->form_validation->set_rules('selected_Cardid','selected Cardid','required');
		if($this->form_validation->run() == TRUE){
			$auth=$this->input->post('Authorization');
			$profileid=$this->input->post('profileid');
			$paymentprofileid=$this->input->post('paymentpayprofileid');
			$selected_planid=$this->input->post('selected_plan');
			$selected_Cardid=$this->input->post('selected_Cardid');
			try{
				$this->Model->transstart();
				$userid = getuserid('users',$auth);
	            if($userid){
					if ($paymentprofileid != "NA" && $profileid != "NA") {
						$getsavecardbyid = $this->Model->get_record('user_save_card',array('customerProfileId' => $profileid,'customerPaymentProfileId' => $paymentprofileid, 'user_id' => $userid));
		        		if(count($getsavecardbyid) > 0){
		        			$plan_details = $this->Model->get_selected_data(array('id','plan_name','price','duration','description','status','create_at'),'membership_plan',array('id' => $selected_planid));
		        			if(count($plan_details) > 0){
								$check_rec = check_user_membership($userid);
					        	if($check_rec){
					        		$response_result["error"] = true;
					                $response_result["message"] = "You have already take a membership";
					        	}
					        	else
					        	{
					        		$amount =  $plan_details[0]['price'];
									$duration =  $plan_details[0]['duration'];
									$plan_name =  $plan_details[0]['plan_name'];

									/* Create a merchantAuthentication and capture amount with authentication details
						           	retrieved from the constants file */

									$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
									$api_loginId = $this->config->item('api_login_id');
									$apiTransectionKey =$this->config->item('api_transaction_key');
									$envurl = $this->config->item('authorize_env_url');
									$merchantAuthentication->setName($api_loginId);
									$merchantAuthentication->setTransactionKey($apiTransectionKey);
									// Set the transaction's refId
									$refId = 'ref' . time();
									//--------------setCreditinfo--------------------------
									$profileToCharge = new AnetAPI\CustomerProfilePaymentType();
									$profileToCharge->setCustomerProfileId($profileid);
									$paymentProfile = new AnetAPI\PaymentProfileType();
									$paymentProfile->setPaymentProfileId($paymentprofileid);
									$profileToCharge->setPaymentProfile($paymentProfile);

									//-------------setTransactionRequestType----------------
									$transactionRequestType = new AnetAPI\TransactionRequestType();
							        $transactionRequestType->setTransactionType("authCaptureTransaction");
							        $transactionRequestType->setAmount($amount);
							        $transactionRequestType->setProfile($profileToCharge);

							        //-------------Transaction request------------
							        $request = new AnetAPI\CreateTransactionRequest();
									$request->setMerchantAuthentication($merchantAuthentication);
									$request->setRefId($refId);
									$request->setTransactionRequest($transactionRequestType,'x_duplicate_window=0');
									$controller = new AnetController\CreateTransactionController($request);
									//$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
									$response = $controller->executeWithApiResponse($envurl);
									if($response != null)
						    		{
						    			$tresponse = $response->getTransactionResponse();
						    			$transactionId = $tresponse->getTransId();
						    			$responseCode = $tresponse->getResponseCode();
						    			$paymentStatus =""; 
						    			if(isset($this->responseText[$tresponse->getResponseCode()])){
						    				$paymentStatus = $this->responseText[$tresponse->getResponseCode()];
						    			}
						    			
						    			//----------------Store transection data-----------------
						        		$plan_info = array('planid' => $selected_planid,'amount' => $amount, 'duration' =>$duration,'plan_name'=>$plan_name);
						        		
						    			if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
								        {
								            $authCode = $tresponse->getAuthCode();
								            $reponseType = "success";
								            $paymentResponse = $tresponse->getMessages()[0]->getDescription();
						      				//----------get membership expire date----------
						        			$current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
											$current_timestamp = strtotime($current_time); 
											$expire_timestamp = strtotime("+".(int)$duration." month");  // Getting timestamp of 1 month from now
											$final_expire = date("Y-m-d H:i:s",+$expire_timestamp);
											$final_expiretimestamp = strtotime($final_expire);
											//{"creation":1545650514,"expire":1548328914};
											$plan_info["creation"] = $current_timestamp;
											$plan_info["expire"] = $final_expiretimestamp;
											$plan_info_json = json_encode($plan_info);

						        			$user_update_data = array("creation" =>$current_timestamp, "expire" =>$final_expiretimestamp,"customerProfileId" => $profileid,"customerPaymentProfileId" => $paymentprofileid,"message" => "new_user");
						        			$user_update_jsondata = json_encode($user_update_data);

								            $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
								            	'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,'amount' => $amount,'payment_status' => $paymentStatus,
								            	'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));

								            $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
								            if($authorizenet_payment_add){
								            	$user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid ,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
								            	if($user_rec_update){
								            		$plan_take_user = "Monthly";
								            		if($duration == 12){
												        $plan_take_user ="Yearly";
												    }
												    $user_info = $this->Model->get_selected_data(array('first_name','last_name','mobile','email_id'),'users',array('id' => $userid));
												    $uemail = $user_info[0]['email_id'];
												    $umobile = $user_info[0]['mobile'];
												    $uname = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];

												    $data['message'] = "Dear Go2Gro Costomer,<br/> Your ".$plan_take_user." Membership plan has been successfully created.<br/><br/>Thank you for choosing Go2Gro as your delivery service. We deeply value your business.<br/><br/>Regards,<br/> Go2Gro Customer Support";
												    $sms_message = "Dear Go2Gro Costomer,\n Your ".$plan_take_user." Membership plan has been successfully created.\nThank you for choosing Go2Gro as your delivery service. We deeply value your business.\nRegards,\n Go2Gro Customer Support";
												    $sms_message = urlencode($sms_message);

												    $mail_msg = $this->load->view('go2gro_web/template/membership',$data,true);
	                        						$issendmail = $this->general->send_mail($uemail,$uname . ' membership information',$mail_msg);
	                        						$issendsms = $this->general->sendsms($umobile, $sms_message);
	                        						$response_result["ismailsend"] = "Mail Not send";
	                        						$response_result["issmssend"] = "SMS Not send";
	                        						if ($issendmail) {
							                            $response_result["ismailsend"] = "Mail send sucessfully";
							                        }
							                        if ($issendsms) {
							                            $response_result["issmssend"] = "SMS send sucessfully";
							                        }
							                        $response_result["error"] = false;
									                $response_result["message"] = "You have join membership successfully\n".$paymentResponse;
								            	}
								            }else{
								            	$response_result["error"] = true;
								                $response_result["message"] = "Something wrong when data update";
								            }
								        }else{
								        	$errorcode = $errormsg = "";
								        	$paymentResponse = "Charge Credit Card ERROR :  Invalid response";
								        	if($tresponse->getResponseCode() != null){
								        		$paymentResponse = $tresponse->getErrors()[0]->getErrorText();
								        		$errorcode = $tresponse->getErrors()[0]->getErrorCode();
								        		$errormsg = $tresponse->getErrors()[0]->getErrorText();
								        	}
								        	$user_update_jsondata=""; $authCode ="";
								        	$plan_info_json = json_encode($plan_info);
								        
											$authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
								            	'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
								            	'amount' => $amount,'payment_status' => $paymentStatus,
								            	'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
								            
								            $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
								            if($authorizenet_payment_add){
								            	$user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
								            	$response_result["error"] = true;
								                $response_result["message"] = $errormsg;
								            }
								   		}
						    		}else{
						    			$response_result["error"] = true;
						                $response_result["message"] = "Charge Credit Card Null response returned";
						            }
					        	}
		        			}else{
		        				$response_result["error"] = true;
				                $response_result["message"] = "Invalid Membership plan";
				            }
		        		}else{
		        			$response_result["error"] = true;
				            $response_result["message"] = "something went wrong please try again";
				        }
					}else{
						$response_result["error"] = true;
				        $response_result["message"] = "Information Not Valid";
				    }
				}else{
					$response_result['error'] = true;
	                $response_result['message'] = 'invalid auth key';
				}
				$this->Model->transcomplete();
			}catch (customException $e){
				$this->Model->transrollback();
				$response_result["error"] = true;
            	$response_result["message"] = $e->errorMessage();
			}
		}else{
			$response_result["error"] = true;
            $response_result["message"] = strip_tags(validation_errors());
		}
		responseJson($response_result);
	}

	//------------------------Membership auto renew----------------
	public function membership_renew_payment(){
		$response_result = array("Error code"=> "","Error message"=>"");
		$renew_membership_users = $this->Model->get_renew_membership_userslist('active');
		$renew_membership_inactive_users = $this->Model->get_renew_membership_userslist('inactive');
		try{
			$this->Model->transstart();
			if(count($renew_membership_users) > 0){
				foreach ($renew_membership_users as $key => $renew_user) {
					$datetime = json_decode($renew_user['membership_date']);
					$expire = $datetime->expire;
					$current_time = date("Y-m-d H:i:s",time());
	            	$current_timestamp = strtotime($current_time);
	            	if($expire < $current_timestamp){
						//---------user info------
						$auth = $renew_user['api_key'];
						$userid = $renew_user['userid'];
						$name = $renew_user['first_name']." ".$renew_user['last_name'];
						$email_id = $renew_user['email_id'];
						$mobile = $renew_user['mobile'];
						$pincode = $renew_user['pincode'];
						$selected_planid = $renew_user['membership_plan_id'];
						$previous_details = $renew_user['membership_date'];
						$amount =  $price = $renew_user['price'];
						$duration =  $renew_user['duration'];
						$plan_name =  $renew_user['plan_name'];

						//---------user save  cards get----
						$getsavecardbyid = $this->Model->get_record('user_save_card',array('user_id' => $userid,'status'=>0));
						if(count($getsavecardbyid) > 0){
							$membership_not_renew = true;
							foreach ($getsavecardbyid as $card_value) 
							{
								//--------payment info---------
								$profileid = $card_value['customerProfileId'];
								$paymentprofileid =	$card_value['customerPaymentProfileId'];

								$errorcode = ''; $errormsg = ''; $paymentResponse = "Charge Credit Card ERROR :  Invalid response";
								$user_update_jsondata=''; $authCode =''; $transactionId=''; $responseCode=''; $plan_info_json='';
								$paymentStatus = '';
								// Create a merchantAuthentication and capture amount with authentication details
					           	//retrieved from the constants file 

								$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
								$api_loginId = $this->config->item('api_login_id');
								$apiTransectionKey =$this->config->item('api_transaction_key');
								$envurl = $this->config->item('authorize_env_url');
								$merchantAuthentication->setName($api_loginId);
								$merchantAuthentication->setTransactionKey($apiTransectionKey);
								// Set the transaction's refId
								$refId = 'ref' . time();
								//--------------setCreditinfo--------------------------
								$profileToCharge = new AnetAPI\CustomerProfilePaymentType();
								$profileToCharge->setCustomerProfileId($profileid);
								$paymentProfile = new AnetAPI\PaymentProfileType();
								$paymentProfile->setPaymentProfileId($paymentprofileid);
								$profileToCharge->setPaymentProfile($paymentProfile);

								//-------------setTransactionRequestType----------------
								$transactionRequestType = new AnetAPI\TransactionRequestType();
						        $transactionRequestType->setTransactionType("authCaptureTransaction");
						        $transactionRequestType->setAmount($amount);
						        $transactionRequestType->setProfile($profileToCharge);

						        //-------------Transaction request------------
						        $request = new AnetAPI\CreateTransactionRequest();
								$request->setMerchantAuthentication($merchantAuthentication);
								$request->setRefId($refId);
								$request->setTransactionRequest($transactionRequestType,'x_duplicate_window=0');
								$controller = new AnetController\CreateTransactionController($request);
								//$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
								$response = $controller->executeWithApiResponse($envurl);
								if ($response != null)
					    		{
					    			$tresponse = $response->getTransactionResponse();
					    			$transactionId = $tresponse->getTransId();
					    			$responseCode = $tresponse->getResponseCode();
					    			$paymentStatus =""; 
					    			if(isset($this->responseText[$tresponse->getResponseCode()])){
					    				$paymentStatus = $this->responseText[$tresponse->getResponseCode()];
					    			}
					    			
					    			//----------------Store transection data-----------------
					        		$plan_info = array('planid' => $selected_plan,'amount' => $amount, 'duration' =>$duration,'plan_name'=>$plan_name);
					        		
					    			if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
							        {
							            $authCode = $tresponse->getAuthCode();
							            $reponseType = "success";
							            $paymentResponse = $tresponse->getMessages()[0]->getDescription();
					      				//----------get membership expire date----------
					        			$current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
										$current_timestamp = strtotime($current_time); 
										$expire_timestamp = strtotime("+".(int)$duration." month");  // Getting timestamp of 1 month from now
										$final_expire = date("Y-m-d H:i:s",+$expire_timestamp);
										$final_expiretimestamp = strtotime($final_expire);
										//{"creation":1545650514,"expire":1548328914};
										$plan_info["creation"] = $current_timestamp;
										$plan_info["expire"] = $final_expiretimestamp;
										$plan_info_json = json_encode($plan_info);
					        			$user_update_data = array("creation" =>$current_timestamp, "expire" =>$final_expiretimestamp,"customerProfileId" => $profileid,"customerPaymentProfileId" => $paymentprofileid,"message" => "Renew_user");
					        			$user_update_jsondata = json_encode($user_update_data);
							            
							            $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
							            	'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
							            	'amount' => $amount,'payment_status' => $paymentStatus,
							            	'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));

							            $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
							            if($authorizenet_payment_add){
							            	$user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid ,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "Renew successfully",'auto_renew_membership_datetime'=> date('Y-m-d H:i:s')),array('id' => $userid));
							            	if($user_rec_update){
							            		$plan_take_user = "Monthly";
							            		if($duration == 12){
											        $plan_take_user ="Yearly";
											    }
											    $user_info = $this->Model->get_selected_data(array('first_name','last_name','mobile','email_id'),'users',array('id' => $userid));
											    $uemail = $user_info[0]['email_id'];
											    $umobile = $user_info[0]['mobile'];
											    $uname = $user_info[0]['first_name'].' '.$user_info[0]['last_name'];

											    $data['message'] = "Dear Go2Gro Costomer,<br/> Your ".$plan_take_user." Membership plan has been successfully renewed.<br/><br/>Thank you for choosing Go2Gro as your delivery service. We deeply value your business.<br/><br/>Regards,<br/> Go2Gro Customer Support";
											    $sms_message = "Dear Go2Gro Costomer,\n Your ".$plan_take_user." Membership plan has been successfully renewed.\n Thank you for choosing Go2Gro as your delivery service. We deeply value your business.\n Regards,\n Go2Gro Customer Support";
											    $sms_message = urlencode($sms_message);

											    $mail_msg = $this->load->view('go2gro_web/template/membership',$data,true);
	                    						$issendmail = $this->general->send_mail($uemail,$uname . ' Renew membership information',$mail_msg);
	                    						$issendsms = $this->general->sendsms($umobile, $sms_message);
	                    						$response_result["ismailsend"] = "Mail Not send";
	                    						$response_result["issmssend"] = "SMS Not send";
	                    						if ($issendmail) {
						                            $response_result["ismailsend"] = "Mail send sucessfully";
						                        }
						                        if ($issendsms) {
						                            $response_result["issmssend"] = "SMS send sucessfully";
						                        }
						                    }
						                    $membership_not_renew = false;
											break;
						                }
							        }
							        else
							        {
										if($tresponse->getResponseCode() != null){
											$paymentResponse = $tresponse->getErrors()[0]->getErrorText();
											$errorcode = $tresponse->getErrors()[0]->getErrorCode();
											$errormsg = $tresponse->getErrors()[0]->getErrorText();
										}
										$plan_info_json = json_encode($plan_info);
									}
					    		}else{
					    			$response_result["error"] = true;
					                $response_result["message"] = "Charge Credit Card Null response returned";
					    		}
							} //foreach user save card closed
							if($membership_not_renew == true){
								$authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
					            	'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
					            	'amount' => $amount,'payment_status' => $paymentStatus,
					            	'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
							    $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
						        if($authorizenet_payment_add){
						        	$user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid ,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "Not renew payment fail",'auto_renew_membership_datetime'=> date('Y-m-d H:i:s')),array('id' => $userid));
						        }
								$response_result["error"] = true;
				                $response_result["message"] = $errorcode;
				                $response_result["Error code"] = $errorcode;
							}
						}
					}
				} //foreach renew_membership_users
				responseJson($response_result);
			}
			//--------------------update users for inactive renew membership by admin-----------
			if(count($renew_membership_inactive_users) > 0){
				foreach ($renew_membership_inactive_users as $inactve_user) {
					$datetime = json_decode($inactve_user['membership_date']);
					$expire = $datetime->expire;
					$current_time = date("Y-m-d H:i:s",time());
	            	$current_timestamp = strtotime($current_time);
	            	if($expire < $current_timestamp){
	            		$userid = $inactve_user['userid'];
	            		$auto_renew_membership_status ="Not renew cancel by  admin";
	                	$auto_renew_membership_datetime = date('Y-m-d H:i:s');
	                	$this->Model->update('users',array('auto_renew_membership_status' => $auto_renew_membership_status,'auto_renew_membership_datetime' => $auto_renew_membership_datetime),array('id' => $userid));
	            	}
				}
			}
			$this->Model->transcomplete();
		}catch (customException $e){
			$this->Model->transrollback();
			$response_result["error"] = true;
        	$response_result["message"] = $e->errorMessage();
		}
	}
}

/* EOF */