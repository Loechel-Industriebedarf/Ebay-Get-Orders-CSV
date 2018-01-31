INTRODUCTION
============

     This samples demostrates how to make the GetOrders API Call using PHP

SOFTWARE REQUIREMENT
====================

     You need to have Curl for PHP installed to use this code sample.

INSTRUCTIONS
============

      1.Extract the files, and open keys.php file in get-common directory.

        If you want to use this sample in production, then set $production = true; , if you wnat to test in sandbox, set $production = false;

        fill the required

        $compatabilityLevel = 717;    // eBay API version

        $devID = 'xxxxxxxx';  

        $appID = 'xxxxxxxxx';
        $certID = 'xxxxxxxxxxxxxx';      

        //set the Server to use (Sandbox or Production)
        $serverUrl = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox
        //the token representing the eBay user to assign the call with
        $userToken = '*************';          

         // for production and/or sandbox.

      2.Now goto the url http:://path/to/GetOrders/GetOrders.php , you should see the parsed values printed.

        If you want to see how the response XML looks like, then goto url

        http:://path/to/GetOrders/GetOrders.php?debug=1 : this prints the response XML.

 

      3.Open up the GetOrders.php file, and you should be able to configure CreateTimeFrom and CreateTimeTo

        //by default this code sample  retreives orders in last 30 minutes //Time with respect to GMT

        $CreateTimeFrom = gmdate("Y-m-d\Th:i:s",time()-1800); //current time minus 30 minutes
        $CreateTimeTo = gmdate("Y-m-d\Th:i:s");

        Additionally you can change the CreateTimeFrom and CreateTimeTo values
        //If you want to hard code From and To timings, Follow the below format in "GMT".
        //$CreateTimeFrom = YYYY-MM-DDTHH:MM:SS; //GMT
        //$CreateTimeTo = YYYY-MM-DDTHH:MM:SS; //GMT

ADDITIONAL RESOURCES
====================

http://developer.ebay.com/DevZone/xml/docs/Reference/ebay/GetOrders.html

https://ebay.custhelp.com/app/answers/detail/a_id/1788/kw/1788