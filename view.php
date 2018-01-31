<?php
/*  © 2013 eBay Inc., All Rights Reserved */ 
/* Licensed under CDDL 1.0 -  http://opensource.org/licenses/cddl1.php */
header("Content-Type: text/plain; charset=UTF-8");
//$response = simplexml_import_dom($responseDoc);
if ($entries == 0) {
    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
} else {
    $orders = $response->OrderArray->Order;
    if ($orders != null) {
        foreach ($orders as $order) {
            echo "Order Information:\n";
            echo "OrderID ->" . $order->OrderID . "\n";
            echo "Order -> Status:" . $orderStatus = $order->OrderStatus . "\n";

            //if the order is completed, print details
            if ($orderStatus) {

                // get the amount paid
                $AmountPaid = $order->AmountPaid;
                $AmountPaidAttr = $AmountPaid->attributes();
                echo "AmountPaid : " . $AmountPaid . " "  .$AmountPaidAttr["currencyID"]. "\n";

                // get the payment method 
                if($order->PaymentMethod)
                echo "PaymentMethod : " . $order->PaymentMethod . "\n";


                // get the checkout message left by the buyer, if any
                if ($order->BuyerCheckoutMessage) {
                    echo "BuyerCheckoutMsg : " . $order->BuyerCheckoutMessage . "\n";
                }

                // get the sales tax, if any 
                $SalesTaxAmount = $order->ShippingDetails->SalesTax->SalesTaxAmount;
                $SalesTaxAmountAttr = $SalesTaxAmount->attributes();
                echo "SalesTaxAmount : " . $SalesTaxAmount. " " .$SalesTaxAmountAttr["currencyID"] .  "\n";

                // get the external transaction information - if payment is made via PayPal, then this is the PayPal transaction info
                $externalTransaction = $order->ExternalTransaction;
                if ($externalTransaction) {
                    echo "ExternalTransactionID  : " . $externalTransaction->ExternalTransactionID . "\n";
                    echo "ExternalTransactionTime  : " . $externalTransaction->ExternalTransactionTime . "\n";
                    $externalTransactionFeeAttr = $externalTransaction->FeeOrCreditAmount->attributes();
                    echo "ExternalFeeOrCreditAmount  : " . $externalTransaction->FeeOrCreditAmount . " " .$externalTransactionFeeAttr["currencyID"]  . " \n";
                    echo "ExternalTransactionPaymentOrRefundAmount   : " . $externalTransaction->PaymentOrRefundAmount . " " .$externalTransactionFeeAttr["currencyID"]  . " \n";
                }

                // get the shipping service selected by the buyer
                $ShippingServiceSelected = $order->ShippingServiceSelected;
                if($ShippingServiceSelected){
                echo "Shipping Service Selected  : " . $ShippingServiceSelected->ShippingService . " \n";
                $ShippingCostAttr = $ShippingServiceSelected->ShippingServiceCost->attributes();
                echo "ShippingServiceCost  : " . $ShippingServiceSelected->ShippingServiceCost . " " . $ShippingCostAttr["currencyID"] . "\n";
                }
               
                // get the buyer's shipping address 
                $shippingAddress = $order->ShippingAddress;
                $address = $shippingAddress->Name . ",\n";
                if ($shippingAddress->Street1 != null) {
                    $address .=  $shippingAddress->Street1 . ",";
                }
                if ($shippingAddress->Street2 != null) {
                    $address .=  $shippingAddress->Street2 . "\n";
                }
                if ($shippingAddress->CityName != null) {
                    $address .= 
                            $shippingAddress->CityName . ",\n";
                }
                if ($shippingAddress->StateOrProvince != null) {
                    $address .= 
                            $shippingAddress->StateOrProvince . "-";
                }
                if ($shippingAddress->PostalCode != null) {
                    $address .= 
                            $shippingAddress->PostalCode . ",\n";
                }
                if ($shippingAddress->CountryName != null) {
                    $address .= 
                            $shippingAddress->CountryName . ".\n";
                }
                if ($shippingAddress->Phone != null) {
                    $address .=  $shippingAddress->Phone . "\n";
                }
                if($address){
                 echo "Shipping Address : " . $address;
                }else echo "Shipping Address: Null" . "\n";

                $transactions = $order->TransactionArray;
                if ($transactions) {
                    echo "Transaction Array \n";
                    // iterate through each transaction for the order
                    foreach ($transactions->Transaction as $transaction) {
                        // get the OrderLineItemID, Quantity, buyer's email and SKU

                        echo "OrderLineItemID : " . $transaction->OrderLineItemID . "\n";
                        echo "QuantityPurchased  : " . $transaction->QuantityPurchased . "\n";
                        echo "Buyer Email : " . $transaction->Buyer->Email . "\n";
                        $SKU = $transaction->Item->SKU;
                        if ($SKU) {
                            echo "Transaction -> SKU  :" . $SKU ."\n";
                        }
                        
                        // if the item is listed with variations, get the variation SKU
                        $VariationSKU = $transaction->Variation->SKU;
                        if ($VariationSKU != null) {
                            echo "Variation SKU  : " . $VariationSKU. "\n";
                        }
                        echo "TransactionID: " . $transaction->TransactionID . "\n";
                        $transactionPriceAttr = $transaction->TransactionPrice->attributes();
                        echo "TransactionPrice : " . $transaction->TransactionPrice . " " . $transactionPriceAttr["currencyID"] . "\n";
                        echo "Platform : " . $transaction->Platform . "\n";
                    }
                }
            }//end if
            echo "---------------------------------------------------- \n";
        }
    }else{
	echo "No Order Found";
	}
}
?>