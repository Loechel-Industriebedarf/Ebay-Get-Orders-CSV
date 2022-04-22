<?php
	$csvPath = "../ebayOrder.csv";
	
	//Checks, if the csv already exists
	if(file_exists($csvPath)){
		echo $now." CSV file was not processed yet!";
		exit();
	}

	//Stores all information
	$list = array ( );
	//Headline
	array_push($list, array('Verkaufsprotokollnummer', 'Mitgliedsname', 'Vollst�ndiger Name des K�ufers', 'E-Mail des K�ufers', 'K�uferadresse 1', 'K�uferadresse 2', 
			'Ort des K�ufers', 'Staat des K�ufers', 'Postleitzahl des K�ufers', 'Land des K�ufers', 
			'Bestellnummer', 'Artikelnummer', 'Transaktions-ID', 'Artikelbezeichnung', 'St�ckzahl', 
			'Verkaufspreis', 'Inklusive Mehrwertsteuersatz', 'Verpackung und Versand', 'Versicherung', 'Gesamtpreis', 
			'Zahlungsmethode', 'PayPal Transaktions-ID', 'Rechnungsnummer', 'Rechnungsdatum', 'Verkaufsdatum', 'Kaufabwicklungsdatum', 'Bezahldatum', 'Versanddatum', 
			'Versandservice', 'Abgegebene Bewertungen', 'Erhaltene Bewertungen', 'Notizzettel', 'Bestandseinheit', 'Private Notizen', 'Produktkennung-Typ', 'Produktkennung-Wert', 'Produktkennung-Wert 2', 
			'Variantendetails', 'Produktreferenznummer', 'Verwendungszweck', 'Sendungsnummer', 'eBay Plus', 'Nebenkosten', 'Land', 'Telefon', 'From', 'To', 'Now'));


	if ($entries == 0) {
		echo $now." NO new orders from " . $CreateTimeFrom . " to " . $CreateTimeTo;
	}
	else{
	$orders = $response->OrderArray->Order;

    if ($orders != null) {
		echo $now." New orders get parsed.";
		
        foreach ($orders as $order) {
			/*
			echo "<pre>";
			var_dump($order);
			echo "</pre>";
			*/

			$shippingAddress = $order->ShippingAddress;
			$ShippingServiceSelected = $order->ShippingServiceSelected;
			$externalTransaction = $order->ExternalTransaction;
			$checkoutmessage = $order->BuyerCheckoutMessage;
			$checkoutmessage = preg_replace('/\s+/', ' ', trim($checkoutmessage)); //Our ERP-system has problems with \r\n in messages. This removes them.
			
			$transactions = $order->TransactionArray;
                if ($transactions) {
                    // iterate through each transaction for the order
					$i = 0;
                    foreach ($transactions->Transaction as $transaction) {	
						$title = $transaction->Item->Title;
						$quantity = $transaction->QuantityPurchased;
						$price = $transaction->TransactionPrice;
						$fees = (doubleval($price) + doubleval($ShippingServiceSelected->ShippingServiceCost)) / doubleval($quantity) * 0.11;
						$paymentID = $order->ExternalTransaction->ExternalTransactionID;
						
						//For users who put their street number in the second address field
						if(strlen($shippingAddress->Street2) < 5){
							$street01 = $shippingAddress->Street1 . " " . $shippingAddress->Street2;
							$street02 = "";
						}
						else{
							$street01 = $shippingAddress->Street1;
							$street02 = $shippingAddress->Street2;
						}
						//Packs with multiple items?
						if(strpos(strtolower($title), 'er pack')){
							$strpostitle = substr($title,0,strpos(strtolower($title),"er pack")); //Cut everything after "er Pack"
							$lastspace = strrpos($strpostitle, ' '); //Search for last space
							if($lastspace > 0){
								$strpostitle = substr($strpostitle, $lastspace, strlen($strpostitle)); //Cut everything before last space
							}	
							$quantity *= intval($strpostitle); //Get "real" quantity
							$price = doubleval($price) / doubleval($strpostitle); //Get "real" price
							$fees = $fees / doubleval($strpostitle) + 0.01; //Get "real" fees
						}
						/*
						if($quantity > 1 || $i == 1){
							$fees = 0;
						}
						$i = 1; //Fees should only be imported once
						*/
						
						//On some transactions the paymentID gets set to "SIS". We don't want this.
						if($paymentID == "SIS"){
							$paymentID = "";
						}

						//Check if the customer has a "StaticAlias" Address
						if(!isset($transaction->Buyer->StaticAlias) || $transaction->Buyer->StaticAlias == "" || $transaction->Buyer->StaticAlias == null){
							$email = $transaction->Buyer->Email;
						}
						else{
							$email = $transaction->Buyer->StaticAlias;
						}

						array_push($list, array($order->OrderID, $order->BuyerUserID, $shippingAddress->Name, $email, $street01, $street02, 
						$shippingAddress->CityName, $shippingAddress->StateOrProvince, $shippingAddress->PostalCode, $shippingAddress->CountryName,
						$transaction->OrderLineItemID, $transaction->Item->SKU, $transaction->TransactionID, $transaction->Item->Title, $quantity,
						$price, $order->ShippingDetails->SalesTax->SalesTaxAmount, $ShippingServiceSelected->ShippingServiceCost, "0,00", $order->AmountPaid,
						$order->CheckoutStatus->PaymentMethod, $paymentID, '', '', $externalTransaction->ExternalTransactionTime, $externalTransaction->ExternalTransactionTime, $externalTransaction->ExternalTransactionTime, '', 
						$ShippingServiceSelected->ShippingService, 'Nein', '', '', $transaction->Item->SKU, $checkoutmessage, '', '', '',
						'', '', '', '', 'Nein', $fees, $shippingAddress->Country, $shippingAddress->Phone, $CreateTimeFrom, $CreateTimeTo, $now));
                    }
                }
        }
    }else{
		echo $now." NO new orders.";
	}
	
		//Write the transactions to file

		$csvWriter = fopen($csvPath, 'w');

		for ($i = 0; $i < count($list); $i++) {
			fputcsv($csvWriter, $list[$i], ';');
		}

		fclose($csvWriter);
		
		
		$lastWriter = fopen('last.txt', 'w+');
		fwrite($lastWriter, $CreateTimeTo);
		fclose($lastWriter);
	}
		
?>