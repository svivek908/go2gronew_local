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
		//  $this->load->model("post");
		//$this->load->library('authorize_net');
		//$this->load->library("common");
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
		$this->common->responseJSON($data);
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
//		$billto->setCompany();
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
		$this->common->responseJSON($data);
	}

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
		//return $response;
	}

	function chargeCustomerProfile( )
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

	public  function removeCard()
	{
		$auth1=$this->input->post('Authorization');
		$profileid=$this->input->post('profileid');
		$paymentprofileid=$this->input->post('paymentprofileid');
		$data = array('method' => "userreemovecard", "customerPaymentProfileId" => $paymentprofileid,"customerProfileId"=>$profileid, "Authorization" => $auth1);
		$res=$this->common->curlpostRequest4($data);
		$res=json_decode($res);
		$this->common->responseJson($res);
		return true;

	}


	//-------------------------Membership payment---------
	public function membership_payment(){
		$auth1=$this->input->post('Authorization');
		$profileid=$this->input->post('profileid');
		$paymentprofileid=$this->input->post('paymentpayprofileid');
		$selected_plan=$this->input->post('selected_plan');
		$selected_Cardid=$this->input->post('selected_Cardid');

		$data = array('method' => "membership_payment", "customerPaymentProfileId" => $paymentprofileid,"customerProfileId"=>$profileid, "Authorization" => $auth1,'selected_plan' => $selected_plan, 'selected_Cardid' => $selected_Cardid);
		$res=$this->common->curlpostRequest4($data);
		$res=json_decode($res); //$result =array();
		if($res->error == false){
			$userid = $res->userid;

			$check_rec = array('check_user_membership/'.$userid);
        	$check_res = $this->common->curlpostRequest($check_rec);
        	$check_res = json_decode($check_res);
        	if($check_res->error == true){
        		$result =array("error"=>true,"message"=>"You have already take a membership","Error code"=>"","Error message" =>"");
        	}
        	else
        	{
        		$amount =  $res->plan_details->price;
				$duration =  $res->plan_details->duration;
				$plan_name =  $res->plan_details->plan_name;

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

	        			$user_update_data = array("creation" =>$current_timestamp, "expire" =>$final_expiretimestamp,"customerProfileId" => $profileid,"customerPaymentProfileId" => $paymentprofileid,"message" => "new_user");
	        			$user_update_jsondata = json_encode($user_update_data);

			            $insert_data = array('method' => "membership_payment_insert", "Authorization" => $auth1,"authCode" => $authCode,"transactionId"=>$transactionId, "responseCode" => $responseCode,"paymentResponse" => $paymentResponse,"paymentStatus"=>$paymentStatus, "amount"=> $amount,"selected_plan" => $selected_plan, "plan_info_json" => $plan_info_json,"user_update_jsondata" => $user_update_jsondata,"userid"=>$userid,"auto_renew_membership_status"=>"NA","auto_renew_membership_datetime"=>"0000-00-00 00:00:00","duration" =>$duration,"mail_type" =>"New membership","mail_status" => "Success");

						$res = $this->common->curlpostRequest4($insert_data);
						$res=json_decode($res);
						if($res->error == false){
							$result =array("error"=>false,"message"=>"You have join membership successfully\n".$paymentResponse,"Error code"=>"","Error message" =>"");
						}else{
							$result =array("error"=>true,"message"=>$res->message,"Error code"=>"","Error message" =>"");
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
			        	$insert_data = array('method' => "membership_payment_insert", "Authorization" => $auth1,"authCode" => $authCode,"transactionId"=>$transactionId, "responseCode" => $responseCode,"paymentResponse" => $paymentResponse,"paymentStatus"=>$paymentStatus, "amount"=> $amount,"selected_plan" => $selected_plan, "plan_info_json" => $plan_info_json,"user_update_jsondata" => $user_update_jsondata,"userid"=>$userid,"auto_renew_membership_status"=>"NA","auto_renew_membership_datetime"=>"0000-00-00 00:00:00","duration" =>$duration,"mail_type" => "New membership","mail_status" => "Fail");
						$res = $this->common->curlpostRequest4($insert_data);
						$res= json_decode($res);
			        	if($res->error == false){
			        		$result =array("error"=>true,"message"=>$errormsg,"Error code"=>$errorcode,"Error message"=>"");
						}
						else{
							$result =array("error"=>true,"message"=>$res->message,"Error code"=>"","Error message" =>"");
						}
			   		}
	    		}else{
	    			$result =array("error"=>true,"message"=>"Charge Credit Card Null response returned","Error code"=>"","Error message" =>"");
	    		}
        	}
		}else{
			$result = $res;
		}
		$this->common->responseJson($result);
	}

	//---------------------------------------------Membership auto renew----------------
	public function membership_renew_payment(){
		$res = $this->common->curlpostRequest(array('get_membership_renewusers'),'','');
		$res = json_decode($res,true);
		//
		if(isset($res['renew_membership_users']) && count($res['renew_membership_users']) > 0){

			foreach ($res['renew_membership_users'] as $key => $value) {
				//---------user info------
				$auth1 = $value['auth'];
				$userid = $value['user_id'];
				$email_id = $value['email_id'];
				$selected_plan = $value['membership_plan_id'];
				$previous_details = $value['previous_details'];
				$amount =  $price = $value['price'];
				$duration =  $value['duration'];
				$plan_name =  $value['plan_name'];

				//---------user save  cards get----
				$user_pay_cards = array('get_users_paycard/'.$userid);
				$user_pay_cards = $this->common->curlpostRequest($user_pay_cards);
				$user_pay_cards = json_decode($user_pay_cards,true);
				if($user_pay_cards['error'] == false){
					$membership_not_renew = true;
					foreach ($user_pay_cards['user_save_card'] as $card_value) 
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
					            
					            $insert_data = array('method' => "membership_payment_insert", "Authorization" => $auth1,"authCode" => $authCode,"transactionId"=>$transactionId, "responseCode" => $responseCode,"paymentResponse" => $paymentResponse,"paymentStatus"=>$paymentStatus, "amount"=> $amount,"selected_plan" => $selected_plan, "plan_info_json" => $plan_info_json,"user_update_jsondata" => $user_update_jsondata,"userid"=>$userid,"auto_renew_membership_status"=>"Renew successfully","auto_renew_membership_datetime"=>date('Y-m-d H:i:s'),"duration" =>$duration,"mail_type" => "Renew membership","mail_status" => "Success");

								$res = $this->common->curlpostRequest4($insert_data);
								$res=json_decode($res);
								if($res->error == false){
									$result =array("error"=>false,"message"=>"You have join membership successfully\n".$paymentResponse,"Error code"=>"","Error message" =>"");
								}else{
									$result =array("error"=>true,"message"=>$res->message,"Error code"=>"","Error message" =>"");
								}
								$membership_not_renew = false;
								break;
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
			    			$result =array("error"=>true,"message"=>"Charge Credit Card Null response returned","Error code"=>"","Error message" =>"");
			    		}
					} //foreach user save card closed
					if($membership_not_renew == true){
						$insert_data = array('method' => "membership_payment_insert", "Authorization" => $auth1,"authCode" => $authCode,"transactionId"=>$transactionId, "responseCode" => $responseCode,"paymentResponse" => $paymentResponse,"paymentStatus"=>$paymentStatus, "amount"=> $amount,"selected_plan" => $selected_plan, "plan_info_json" => $plan_info_json,"user_update_jsondata" => $user_update_jsondata,"userid"=>$userid,"auto_renew_membership_status"=>"Not renew payment fail","auto_renew_membership_datetime"=>date('Y-m-d H:i:s'),"duration" =>$duration,"mail_type" => "Renew membership","mail_status" => "Fail");
						$res = $this->common->curlpostRequest4($insert_data);
						$res= json_decode($res);
						if($res->error == false){
							$result =array("error"=>true,"message"=>$errormsg,"Error code"=>$errorcode,"Error message"=>"");
						}
						else{
							$result =array("error"=>true,"message"=>$res->message,"Error code"=>"","Error message" =>"");
						}
					}
				}
			} // foreach renew_membership_users
			//--------------------update users for inactive renew membership by admin-----------
			$res = $this->common->curlpostRequest(array('update_inactive_membership_renewusers'),'','');
		}else{
			$result =array("error"=>true,"message"=>"No users found","Error code"=>"","Error message" =>"");
		}
		print_r($result);
		die();
		//$this->common->responseJson($result);
	}
}




/* EOF */