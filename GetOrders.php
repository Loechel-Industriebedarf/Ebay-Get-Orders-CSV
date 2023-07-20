<?php
/*  � 2013 eBay Inc., All Rights Reserved */ 
/* Licensed under CDDL 1.0 -  http://opensource.org/licenses/cddl1.php */
?>
<?php require_once('get-common/keys.php')  //include keys file for auth token and other credentials ?>
<?php require_once('get-common/eBaySession.php')  //include session file for curl operations ?>
<?php
//SiteID must also be set in the Request's XML
//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, Germany = 77 ....
//SiteID Indicates the eBay site to associate the call with
$siteID = 77;
//the call being made:
$verb = 'GetOrders';

date_default_timezone_set('Europe/London');

//Time with respect to GMT -> Ebay seems to work in GMT timezone. So, yeah.
//by default retreive orders in last 30 minutes
$CreateTimeFrom = file_get_contents('last.txt');
//$CreateTimeFrom = "2021-08-30T00:00:00";

//2 minutes in the past
/* 
https://developer.ebay.com/devzone/xml/docs/reference/ebay/getorders.html : 
"Note: If a GetOrders call is made within a few seconds after the creation of a multiple line item order, 
the caller runs the risk of retrieving orders that are in an inconsistent state, since the order consolidation 
involved in a multiple line item order may not have been completed. For this reason, it is recommended that 
sellers include the CreateTimeTo field in the call, and set its value to: Current Time - 2 minutes. "
*/
$CreateTimeToDT = DateTime::createFromFormat('U.u', microtime(true) - 60 * 10);
$CreateTimeTo = $CreateTimeToDT->format("Y-m-d\TH:i:s.999\Z");

$CreateTimeFrom = "2023-07-19T08:26:24.000Z";
$CreateTimeTo = "2023-07-19T08:36:32.825Z";

//$now = gmdate("Y-m-d\TH:i:s", time() + 7200);
$now = gmdate("Y-m-d\TH:i:s", time() + 7200);



//echo $CreateTimeFrom . '<br>' . $CreateTimeTo . '<br>' . $now . '<br><br>';


//If you want to hard code From and To timings, Follow the below format in "GMT".
//$CreateTimeFrom = YYYY-MM-DDTHH:MM:SS; //GMT
//$CreateTimeTo = YYYY-MM-DDTHH:MM:SS; //GMT


///Build the request Xml string
$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
$requestXmlBody .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
$requestXmlBody .= "<CreateTimeFrom>".$CreateTimeFrom."</CreateTimeFrom><CreateTimeTo>".$CreateTimeTo."</CreateTimeTo>";
$requestXmlBody .= '<OrderRole>Seller</OrderRole><OrderStatus>All</OrderStatus>';
$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>".$userToken."</eBayAuthToken></RequesterCredentials>";
$requestXmlBody .= '</GetOrdersRequest>';


//Create a new eBay session with all details pulled in from included keys.php
$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);

//send the request and get response
$responseXml = $session->sendHttpRequest($requestXmlBody);
if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
    die('<P>Error sending request');

//Xml string is parsed and creates a DOM Document object
$responseDoc = new DomDocument();
$responseDoc->loadXML($responseXml);


//get any error nodes
$errors = $responseDoc->getElementsByTagName('Errors');
$response = simplexml_import_dom($responseDoc);
$entries = $response->PaginationResult->TotalNumberOfEntries;

//if there are error nodes
if ($errors->length > 0) {
    echo '<P><B>eBay returned the following error(s):</B>';
    //display each error
    //Get error code, ShortMesaage and LongMessage
    $code = $errors->item(0)->getElementsByTagName('ErrorCode');
    $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
    $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
    
    //Display code and shortmessage
    echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
    
    //if there is a long message (ie ErrorLevel=1), display it
    if (count($longMsg) > 0)
        echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
}else { //If there are no errors, continue
    if(isset($_GET['debug']))
    {  
       header("Content-type: text/xml");
       print_r($responseXml);
    }else
     { 
		 //$responseXml is parsed to csv
        include_once 'toCsv.php';
    }
} 
?>