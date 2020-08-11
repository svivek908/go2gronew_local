<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//-------------seprate constant-------------
define('USER_CREATED_SUCCESSFULLY', 0);
define('BASEURL','http://192.168.1.78/go2gronew_local/');

define('USER_DEFINE_PASSWORD','snslogin');
define('Serverurl','http://168.235.64.100/');
define('imageurl',BASEURL. '/public/upload/');
define('starttime','09');
define('endtime','22');
define('passwordurl',BASEURL);
define('usernamesms','sidnayar');
define('apikeysms','C1EAAE7A-BC5E-C6A1-520E-96DC2D5DF856');
define('verifymobile','verifymobile');

define('Item_Beers_exceed_limit', 12);
define('Test_item_type', 'Beers');

define('ORDER_NOT_FOUND_FOR_REFUND', 0);
define('ORDER_CANNOT_BE_REFUND', 1);
define('ORDER_ALREADY_REFUNDED', 2);
define('ORDER_REFUND_SUCCESSFULLY', 3);
define('ORDER_REFUND_PROBLEM_OCCORED_BY_GETWAY', 4);
define('PAYMENT_MODE_NOT_VALID', 5);

// -------------------------------------------------------------------------------------------------------------------//

/*
 * Settings for Authorize.net
 * env variable should be set to test if beta or stagging
 */
define('AUTHORIZED_NAME_TEST', '8Q6KHa7np76y');
define('AUTHORIZED_KEY_TEST', '2836Ha75kpLA5EDA');
define('env', 'test');


// Credentials for live
//define('AUTHORIZED_NAME', '9c5V9nmV5');
//define('AUTHORIZED_KEY', '22cDxfs3yUA372u4');
//define('env', 'live');


// -------------------------------------------------------------------------------------------------------------------//

define('alternataivelink',BASEURL.'/alternate_product/');

define("GOOGLE_API_KEY","AAAAgSGJfvE:APA91bGiP2P4Hc9MocMAAWDkj4XItz8opUr2TupsrQwoMHdowDx-jPWS3ULjjaxyt2gj91VX-lSla3lGZRtk5_PySYIvMSA3oS7jnHZYr6AuCpP2_ClcVcI0TLVebmrAP-UoEea6oyNP");
define("GOOGLE_FCM_URL", "https://android.googleapis.com/gcm/send");
define("GOOGLE_IOS_FCM_URL", "https://fcm.googleapis.com/fcm/send");

define("ORDER_PLACED", "placed");
define("ORDER_PREPARE", "prepare");
define("ORDER_PACKED", "packed");
define("ORDER_SHIPPED", "shipped");
define("ORDER_OUTFORDELIVERY", "outfordelivery");
define("ORDER_DELIVERED", "delivered");
define("ORDER_REJECT", "reject");
define("ORDER_CANCLE", "cancle");
define("ORDER_SEND_ALTERNATIVE","sendalternative");

define("ORDER_TAG", "ORDER");
define("OFFER_TAG", "OFFER");
define("DEFULTIMAGE", "upload/notificationimage/default.jpg");

define("ORDER_PLACED_MSG", "Your Order Has Been Placed.");
define("ORDER_PREPARE_MSG", "Order Status - Preparing");
define("ORDER_PACKED_MSG", "Order Status - Packed");
define("ORDER_SHIPPED_MSG", "Order Status - Shipped");
define("ORDER_OUTFORDELIVERY_MSG", "Order Status - Out For Delivery");
define("ORDER_DELIVERED_MSG", "Order Status - Delivered");
define("ORDER_REJECT_MSG", "Order Status - Reject");
define("ORDER_CANCLE_MSG", "Order Status - Cancel");
define("ORDER_SEND_ALTERNATIVE_MSG","Order Status - Alternative Suggestion");
define('ITEM_LIMIT_EXCEED',192);
define('TIPS_ARR',
    serialize(
        [
            'tips'=>['7','10','12'], // list of tip % to be shown
            'default'=>'10' // default one to be shown as pre-selected tip(should be a number from above defined array only)
        ]
    )
);

define('SEARCH_SUGGESTIONS_ARR',
serialize(
    [
        'Milk','Chips','Bread','Meat','Fish'
    ]
)
);


define('STORE_PREFIX','store');

// Define Table names

define('ITEMS_TABLE','item');
define('ITEMLINK_TABLE','itemlink');
define('ITEMIMAGES_TABLE','items_images');
define('ITEMFLAG_TABLE','item_flag');
define('ITEMRATING_TABLE','item_rating');
define('TIME_SLOT','time_slot');
define('MAX_REFERRALS_ALLOWED', 1);
define('REFERRAL_DISCOUNT', 5); // $5
//------------Membership discount condition----------------------
define('MEMBERSHIP_APPLICABLE_SUBTOTAL',40); //-------order subtotal >= $40 then delivery chager 0 apply.
define('MEMBERSHIP_DELIVERY_CHARGE',0);
