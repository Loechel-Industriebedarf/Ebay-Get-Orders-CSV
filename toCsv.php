<?php
	//Stores all information
	$list = array ( );
	//Headline
	array_push($list, array('Verkaufsprotokollnummer', 'Mitgliedsname', 'Vollständiger Name des Käufers', 'E-Mail des Käufers', 'Käuferadresse 1', 'Käuferadresse 2', 
			'Ort des Käufers', 'Staat des Käufers', 'Postleitzahl des Käufers', 'Land des Käufers', 
			'Bestellnummer', 'Artikelnummer', 'Transaktions-ID', 'Artikelbezeichnung', 'Stückzahl', 
			'Verkaufspreis', 'Inklusive Mehrwertsteuersatz', 'Verpackung und Versand', 'Versicherung', 'Gesamtpreis', 
			'Zahlungsmethode', 'PayPal Transaktions-ID', 'Rechnungsnummer', 'Rechnungsdatum', 'Verkaufsdatum', 'Kaufabwicklungsdatum', 'Bezahldatum', 'Versanddatum', 
			'Versandservice', 'Abgegebene Bewertungen', 'Erhaltene Bewertungen', 'Notizzettel', 'Bestandseinheit', 'Private Notizen', 'Produktkennung-Typ', 'Produktkennung-Wert', 'Produktkennung-Wert 2', 
			'Variantendetails', 'Produktreferenznummer', 'Verwendungszweck', 'Sendungsnummer', 'eBay Plus', 'Nebenkosten'));


	if ($entries == 0) {
		echo $now." NO new orders.";
	}
	else{
	$orders = $response->OrderArray->Order;
	
    if ($orders != null) {
		echo $now." New orders get parsed.";
		
        foreach ($orders as $order) {
				$shippingAddress = $order->ShippingAddress;
				$ShippingServiceSelected = $order->ShippingServiceSelected;
				$externalTransaction = $order->ExternalTransaction;
				
				$transactions = $order->TransactionArray;
                if ($transactions) {
                    // iterate through each transaction for the order
                    foreach ($transactions->Transaction as $transaction) {						
						$title = $transaction->Item->Title;
						$quantity = $transaction->QuantityPurchased;
						//Packs with multiple items?
						if(strpos($title, 'er Pack')){
							$strpostitle = substr($title,0,strpos($title,"er Pack")); //Cut everything after "er Pack"
							$lastspace = strrpos($strpostitle, ' '); //Search for last space
							$strpostitle = substr($strpostitle, $lastspace, strlen($strpostitle)); //Cut everything before last space
							$quantity *= intval($strpostitle);
						}
					
						array_push($list, array($order->OrderID, $order->BuyerUserID, $shippingAddress->Name, $transaction->Buyer->Email, $shippingAddress->Street1, $shippingAddress->Street2, 
						$shippingAddress->CityName, $shippingAddress->StateOrProvince, $shippingAddress->PostalCode, $shippingAddress->CountryName,
						$transaction->OrderLineItemID, $transaction->Item->SKU, $transaction->TransactionID, $transaction->Item->Title, $quantity,
						$transaction->TransactionPrice, $order->ShippingDetails->SalesTax->SalesTaxAmount, $ShippingServiceSelected->ShippingServiceCost, "0,00", $order->AmountPaid,
						$order->CheckoutStatus->PaymentMethod, $transaction->TransactionID, '', '', $externalTransaction->ExternalTransactionTime, $externalTransaction->ExternalTransactionTime, $externalTransaction->ExternalTransactionTime, '', 
						$ShippingServiceSelected->ShippingService, 'Nein', '', '', $transaction->Item->SKU, '', '', '', '',
						'', '', '', '', 'Nein', $externalTransaction->FeeOrCreditAmount));
                    }
                }
        }
    }else{
		echo $now." NO new orders.";
	}
	
		//Write the transactions to file

		$fp = fopen('ebayOrder.csv', 'w');

		for ($i = 0; $i < count($list); $i++) {
			fputcsv($fp, $list[$i], ';');
		}

		fclose($fp);
		
		
		$fp = fopen('last.txt', 'w+');
		fwrite($fp, $CreateTimeTo);
		fclose($fp);
	}
		
?>