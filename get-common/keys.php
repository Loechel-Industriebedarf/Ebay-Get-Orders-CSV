<?php
/*  © 2013 eBay Inc., All Rights Reserved */ 
/* Licensed under CDDL 1.0 -  http://opensource.org/licenses/cddl1.php */
    //show all errors
    error_reporting(E_ALL);

    // these keys can be obtained by registering at http://developer.ebay.com
    
    $production         = true;   // toggle to true if going against production
    $compatabilityLevel = 717;    // eBay API version
    
    if ($production) {
        $devID = '6fec6dc3-ab32-46a8-bff1-2b2668872f5a';   // these prod keys are different from sandbox keys
        $appID = 'datenman-Loechel-PRD-05d7a0307-a4c267e2';
        $certID = 'PRD-5d7a030740ad-5afe-4c73-9b00-a5c4';
        //set the Server to use (Sandbox or Production)
        $serverUrl = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox
        //the token representing the eBay user to assign the call with
        $userToken = 'AgAAAA**AQAAAA**aAAAAA**lItwWg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AAkoShCZGEogidj6x9nY+seQ**ahcEAA**AAMAAA**US8PVOY/HUWJ8S/gWpE4bPwqvfM/qhkAfcYGLV8hE4EsUXDMkn/Gv4wi4N5uvJ4vBH3S2J5Am0epxLyCFC2Xg844X1tBuOc7ExDmCxdafdL+nEs4GT7RcbLnxyoSGzlMicD+d2rjqVGl/3cXmjl+NR8ZsBkTbJ/SqWAm/OekcjwE2dmcXDbBZxEMsz3N/VQrXClzuR8JT4vhJWoKIRe6R7uXwqVMrRK9jgAJRiVLYDc6BpaKOqmmUgxukxqydZyC94RnHXkLMXiblB3seWg8RHIMXHC7pbKHolUq3UuM+OYW9GRiElXyqKWfVBn8gnlZ9z3ehCzVWKM9CzdDVCKXiIB4l4CU/uNECCsDPs5zACxk0xL+Uz/qFhQe0+3qOyqz+JVpq/eRV1NaYDlsuSAEKAmoyfd6QSKxTbUofSj+Wj5KflOXDJ2RFnB9WCJWt/Umr4cArfCwDBiQus1lJmbOIlq+HXleiQzua80iZyr3axE5KQF1Hmnfs0qvxnTNGTE46AWqqnwxdHvaoQRPzi8dmLLO5If4V5KWUIDFU6yuBg2SUR6OiNsT78fxKVJnaCbTqhhR7bqhuorYXyaWbea6xE/aKGHk4b2W6AR5iP52n2vfEKWgCCT0Low03+yWSwuJH7VkOpgFuhGlezkq3Z2x4dMmzy2QQcvUSpF3c71HsXWFN0bykaxavMqQn66Msz8m08brBP4MxOmxDSGRfy1ZD5XNsgiBgq21Xx+JUTFBBeRcvvyZyHeVuMVf6N9Tqg15';          
    } else {  
        // sandbox (test) environment
        $devID = 'xxxxxxxx';   // these SB keys are different from prod keys
        $appID = 'xxxxxxxxx';
        $certID = 'xxxxxxxxxxxxxx';
        //set the Server to use (Sandbox or Production)
        $serverUrl = 'https://api.sandbox.ebay.com/ws/api.dll';
        // the token representing the eBay user to assign the call with
        // this token is a long string - don't insert new lines - different from prod token
        $userToken = '*************';          
    }
    
    
?>