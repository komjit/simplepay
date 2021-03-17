<?php

namespace KomjIT\SimplePay\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use KomjIT\SimplePay\Models\SimplePayStart;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $config = [
            //HUF
            'HUF_MERCHANT' => "OMS5440501",            //merchant account ID (HUF)
            'HUF_SECRET_KEY' => "2kBqzsGbyIjfL3S1Bu231U6K8YybAuU9",          //secret key for account ID (HUF)

            'SANDBOX' => true,

            //common return URL
            'URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/back.php',
            'URLS_SUCCESS' => 'http://' . $_SERVER['HTTP_HOST'] . '/success.php',       //url for successful payment
            'URLS_FAIL' => 'http://' . $_SERVER['HTTP_HOST'] . '/fail.php',             //url for unsuccessful
            'URLS_CANCEL' => 'http://' . $_SERVER['HTTP_HOST'] . '/cancel.php',         //url for cancel on payment page
            'URLS_TIMEOUT' => 'http://' . $_SERVER['HTTP_HOST'] . '/timeout.php',       //url for payment page timeout

            'GET_DATA' => (isset($_GET['r']) && isset($_GET['s'])) ? ['r' => $_GET['r'], 's' => $_GET['s']] : [],
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,

            'LOGGER' => true,                              //basic transaction log
            'LOG_PATH' => 'log',                           //path of log file

            //3DS
            'AUTOCHALLENGE' => true,                      //in case of unsuccessful payment with registered card run automatic challange
        ];

        $trx = new SimplePayStart();

        $currency = 'HUF';
        $trx->addData('currency', $currency);

        $trx->addConfig($config);

//ORDER PRICE/TOTAL
//-----------------------------------------------------------------------------------------
        $trx->addData('total', 25);

//ORDER ITEMS
//-----------------------------------------------------------------------------------------
        /*
        $trx->addItems(
            array(
                'ref' => 'Product ID 1',
                'title' => 'Product name 1',
                'description' => 'Product description 1',
                'amount' => '1',
                'price' => '0',
                'tax' => '0',
                )
        );


        $trx->addItems(
            array(
                'ref' => 'Product ID 2',
                'title' => 'Product name 2',
                'description' => 'Product description 2',
                'amount' => '2',
                'price' => '5',
                'tax' => '0',
                )
        );
        */


// SHIPPING COST
//-----------------------------------------------------------------------------------------
//$trx->addData('shippingCost', 20);


// DISCOUNT
//-----------------------------------------------------------------------------------------
//$trx->addData('discount', 10);


// ORDER REFERENCE NUMBER
// uniq oreder reference number in the merchant system
//-----------------------------------------------------------------------------------------
        $trx->addData('orderRef', str_replace(array('.', ':', '/'), "", @$_SERVER['SERVER_ADDR']) . @date("U", time()) . rand(1000, 9999));


// CUSTOMER
// customer's name
//-----------------------------------------------------------------------------------------
//$trx->addData('customer', 'v2 SimplePay Teszt');


// EMAIL
// customer's email
//-----------------------------------------------------------------------------------------
        $trx->addData('customerEmail', 'sdk_test@otpmobil.com');


// LANGUAGE
// HU, EN, DE, etc.
//-----------------------------------------------------------------------------------------
        $trx->addData('language', 'HU');


// TWO STEP
// true, or false
// If this field does not exist is equal false value
// Possibility of two step needs IT support setting
//-----------------------------------------------------------------------------------------
        /*
        $twoStep = false;
        if (isset($_REQUEST['twoStep'])) {
            $twoStep = true;
        }
        $trx->addData('twoStep', $twoStep);
        */


// TIMEOUT
// 2018-09-15T11:25:37+02:00
//-----------------------------------------------------------------------------------------
        $timeoutInSec = 600;
        $timeout = @date("c", time() + $timeoutInSec);
        $trx->addData('timeout', $timeout);


// METHODS
// CARD or WIRE
//-----------------------------------------------------------------------------------------
        $trx->addData('methods', array('CARD'));


// REDIRECT URLs
//-----------------------------------------------------------------------------------------

// common URL for all result
        $trx->addData('url', $config['URL']);

// uniq URL for every result type

        $trx->addGroupData('urls', 'success', $config['URLS_SUCCESS']);
        $trx->addGroupData('urls', 'fail', $config['URLS_FAIL']);
        $trx->addGroupData('urls', 'cancel', $config['URLS_CANCEL']);
        $trx->addGroupData('urls', 'timeout', $config['URLS_TIMEOUT']);


// INVOICE DATA
//-----------------------------------------------------------------------------------------
        $trx->addGroupData('invoice', 'name', 'SimplePay V2 Tester');
//$trx->addGroupData('invoice', 'company', '');
        $trx->addGroupData('invoice', 'country', 'hu');
        $trx->addGroupData('invoice', 'state', 'Budapest');
        $trx->addGroupData('invoice', 'city', 'Budapest');
        $trx->addGroupData('invoice', 'zip', '1111');
        $trx->addGroupData('invoice', 'address', 'Address 1');
//$trx->addGroupData('invoice', 'address2', 'Address 2');
//$trx->addGroupData('invoice', 'phone', '06201234567');


// DELIVERY DATA
//-----------------------------------------------------------------------------------------
        /*
        $trx->addGroupData('delivery', 'name', 'SimplePay V2 Tester');
        $trx->addGroupData('delivery', 'company', '');
        $trx->addGroupData('delivery', 'country', 'hu');
        $trx->addGroupData('delivery', 'state', 'Budapest');
        $trx->addGroupData('delivery', 'city', 'Budapest');
        $trx->addGroupData('delivery', 'zip', '1111');
        $trx->addGroupData('delivery', 'address', 'Address 1');
        $trx->addGroupData('delivery', 'address2', '');
        $trx->addGroupData('delivery', 'phone', '06203164978');
        */


//payment starter element
// auto: (immediate redirect)
// button: (default setting)
// link: link to payment page
//-----------------------------------------------------------------------------------------
        $trx->formDetails['element'] = 'button';


//create transaction in SimplePay system
//-----------------------------------------------------------------------------------------
        $trx->runStart();


//create html form for payment using by the created transaction
//-----------------------------------------------------------------------------------------
        $trx->getHtmlForm();


//print form
//-----------------------------------------------------------------------------------------
        print $trx->returnData['form'];


// test data
//-----------------------------------------------------------------------------------------
        print "API REQUEST";
        print "<pre>";
        print_r($trx->getTransactionBase());
        print "</pre>";

        print "API RESULT";
        print "<pre>";
        print_r($trx->getReturnData());
        print "</pre>";
    }
}
