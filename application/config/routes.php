<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*-------------Web Routes---------------------*/
$route['home'] = "Main/home";
$route['select_store/(:num)'] = 'Main/select_store/$1';
$route['user_store/(:num)'] = "Main/select_store_id/$1";
$route['checkout']='Main/checkout';
$route['ShoppingCart']='Main/ShoppingCart';
$route['Login'] = 'Main/login';
$route['productDetail']='Main/productDetail';
//$route['login']['POST']='index.php/main/login';
$route['logindata']['POST'] = 'Main/logindata';

$route['getSubcategory']='Main/getSubcategory';
$route['getSubcategory1']='Main/getSubcategory1';
$route['account']='Main/account';

$route['accountDetail/(.+)']='Main/accountDetail/$1';
$route['EmailVerify']='Main/EmailVerify';
$route['cancel']='Main/cancel';
$route['success']='Main/success';
$route['privacy']='Main/privacy';
$route['storecheckoutdata']='Main/storecheckoutdata';
$route['departmentitemid']='Main/departmentitemid';
$route['about']='Main/about';
$route['return_policy']='Main/return_policy';
$route['disclaimer']='Main/disclaimer';
$route['terms']='Main/terms';
$route['contact']='Main/contact';
$route['faq']='Main/faq';
$route['orderplacedcheck']='Main/orderplacedcheck';
$route['getRelatedProduct']='Main/getRelatedProduct';
$route['searchresult']='Main/searchListView';
$route['pay']='AuthorizeNet/pay';
$route['paymentAlternate']='AuthorizeNet/paymentAlternate';
$route['getCustomerPaymentProfile']='AuthorizeNet/getCustomerPaymentProfile';
$route['createCustomerProfile']='AuthorizeNet/createCustomerProfile';
$route['chargeCustomerProfile']='AuthorizeNet/chargeCustomerProfile';
$route['createCustomerPaymentProfile']='AuthorizeNet/createCustomerPaymentProfile';
$route['refundTransaction']='AuthorizeNet/refundTransaction';
$route['removeCard']='AuthorizeNet/removeCard';

$route['getlistitem']='Main/getlistitemnew';
$route['departmentitemid_new']='Web_api/departmentitemid_new';
$route['404_override'] = 'Main';
$route['translate_uri_dashes'] = FALSE;
$route['post/(:any)']='userOprationCtrl/post';
$route['select_store/(:any)']="Main/select_store/$1";
$route['get_picker_details']="Main/get_picker_details";
$route['driver_contract/(:any)']="Main/driver_contract";
$route['RepeatOrder']='Main/RepeatOrder';
$route['membership']='Main/membership';

$route['membership_payment']='AuthorizeNet/membership_payment';
//----------Social media login

//$route['login_with_google']='Main/logingoogle';
$route['social_media_login']='Main/logingoogle';
$route['registration']='Main/registration';
$route['profile']='Main/profile';
$route['allzipcodes']['GET']='Web_api/allzipcodes';
$route['checkzipcode']['GET']='Web_api/checkzipcode';
$route['logindata']= 'Web_api/logindata';
$route['getCategory']='Web_api/getCategory';
$route['addcartitem']='Web_api/addcartitem';
$route['updateitemcart']='Web_api/updateitemcart';
$route['getCartItem']='Web_api/getCartItem';
$route['deleteitemcart']='Web_api/deleteitemcart';
$route['getBestSellerlogin']='Web_api/getBestSellerlogin';
$route['getBestSeller']='Web_api/getBestSeller';
$route['signupform'] = 'Web_api/signupform';
$route['UpdateProfile']='Web_api/UpdateProfile';
$route['ChangePassword']='Web_api/ChangePassword';
$route['ContactUs']='Web_api/ContactUs';
$route['membership_plan']='Web_api/get_membership_plan';
$route['refer_to_friend']='Web_api/refer_to_friend';
$route['check_promocode']='Web_api/check_promocode';
$route['updatecartvalue']['POST']='Web_api/updatecartvalue';
$route['getCountry']='Web_api/getCountry';
$route['getState']='Web_api/getState';
$route['getCity']='Web_api/getCity';
$route['getDeliveryTimeslot']='Web_api/getDeliverySlots';
$route['getsavecard']='Web_api/getsavecard';
$route['saveCard']='Web_api/saveCard';
$route['placeorder']['POST']='Web_api/placeorder';
$route['OrderHistory']='Web_api/OrderHistory';
$route['searchList']='Web_api/searchList';
$route['getItemDescription']['GET']='Web_api/getItemDescription';
$route['OrderDetail']='Web_api/OrderDetail';
$route['cancelorder']='Web_api/cancelOrder';
$route['GetAlternateProductDetailApi']='Web_api/GetAlternateProductDetailApi';
$route['alternatPlaceorder']='Web_api/alternatPlaceorder';
$route['logout']['POST'] = 'Web_api/logout';
//$route['checkZipcode/(:num)'] = 'api/Web_api/checkZipcode/$1'; // Example 4
//$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
/*-------------Web Api Routes End---------------------*/

/*-------------Mobile api Routes---------------------*/
$route['pincode/(:num)'] = 'Mobapi/pincode';
$route['register'] = 'Mobapi/registration';
$route['loginuser'] = 'Mobapi/login';
$route['category']['GET'] = 'Mobapi/category';
$route['bestseller']['GET'] = 'Mobapi/bestseller';
$route['passwordchange']['GET'] = 'Mobapi/passwordchange';
$route['forgetpassreq'] = 'Mobapi/forgetpassreq';
$route['updatenewpassword'] = 'Mobapi/updatenewpassword';
$route['relateditem/(:any)/(:num)'] = 'Mobapi/relateditem/$1/$1';
$route['item/(:any)/(:num)'] = 'Mobapi/getItemDescription/$1/$1';
$route['update_profile/'] = 'Mobapi/update_profile';
$route['itembydept/(:any)/(:num)'] = 'Mobapi/itembydept/$1/$1';
$route['newitemsbydepts_all/(.+)'] = 'Mobapi/newitemsbydepts_all/$1';
$route['orders/(.+)'] = 'Mobapi/orders/$1';
$route['orderdetail/(:any)/(:num)'] = 'Mobapi/orderdetail/$1/$1';
$route['getusersavecard']='Mobapi/getsavecard';
$route['usersavecard']='Mobapi/usersavecard';
$route['placeorder_mobile']['POST']='Mobapi/placeorder_mobile';
$route['itemsearch']='Mobapi/itemsearch';
$route['getcartitems']='Mobapi/getcartitems';
$route['additemcart']='Mobapi/additemcart';
$route['itemdeletecart']='Mobapi/itemdeletecart';
$route['cartitemupdate']='Mobapi/cartitemupdate';
$route['getslot/(:num)']='Mobapi/getslot/$1';
$route['getalternativeitem/(.+)']='Mobapi/getalternativeitem/$1';
$route['alternatplaceorder_modile']='Mobapi/alternatplaceorder';
$route['updatecheckoutcart']='Mobapi/updatecheckoutcart';
$route['forgetpasswordcheckvalid']='Main/forgetpasswordcheckvalid';
$route['invalid']='Main/invalid';
$route['confirm']='Main/confirm';

/*-------------Mobile api Routes End---------------------*/

/*-------------Admin Routes---------------------*/
$route['Go2gro_adminlogin'] = 'Demo';
$route['Go2gro_adminlogout'] = 'Demo/logout';
$route['Admin_dashboard'] = 'Admin_control/index';
$route['allordercount'] = 'Admin_control/allordercount';
$route['Admin/allorders'] = 'Admin_control/all_orders';
$route['Admin/neworders/(:num)'] = 'Admin_control/neworders/$1';
$route['Admin/deleverorder/(:num)'] = 'Admin_control/deleverorder/$1';
$route['Admin/rejectorder/(:num)'] = 'Admin_control/rejectorder/$1';
$route['Admin/cancelorder/(:num)'] = 'Admin_control/cancelorder/$1';
$route['Admin/saleDatailByOrderStatus'] = 'Admin_control/saleDatailByOrderStatus';
$route['Admin/report'] = 'Admin_control/report';
$route['Admin/getReport'] = 'Admin_control/getReport';
$route['Admin/userDetails'] = 'Admin_control/userDetails';
$route['Admin/getUsers/(:num)'] = 'Admin_control/getUsers/$1';
$route['Admin/viewproduct'] = 'Product';
$route['Admin/addproduct'] = 'Product/addproduct';
$route['Admin/addnewitem'] = 'Product/addnewitem';
$route['Admin/editproduct/(:num)'] = 'Product/editproduct/$1';
$route['Admin/edititem/(:num)'] = 'Product/edititem/$1';
$route['Admin/updateitem'] = 'Product/updateitem/';
$route['Admin/delete_image'] = 'Product/delete_image/';
$route['Admin/make_primary'] = 'Product/make_primary/';
$route['Admin/getalliteam'] = 'Product/getalliteam';
$route['Admin/getMothersCategory'] = 'Product/getMothersCategory';
//-------------store select for admin
$route['Admin/get_stores'] = 'Admin_control/get_stores';
$route['Admin/save_store_to_session'] = 'Admin_control/save_store_to_session';
//-----------------store select for admin end---------------

/*-------------Admin Routes End---------------------*/
$route['default_controller'] = 'Main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
