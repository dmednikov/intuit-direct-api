<?php

try {
    $oauth = new OAuth("qyprdZrORl2gSGHJZRqvKV7HLfOKb6","v4PmNNqcs12uKX2BV6WISRlXfL9ewSFHw07Llcx5",OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_AUTHORIZATION);

    $oauth->setToken("lvprdS7AoEZ3uWjQJcrSRaT1iQvETdlMwqwhobOWpdowVRah","mTRkAkWWSZTjkbLQbVWof9cVfA6TYD3tQI5499A7");
	
	$oauth->disableSSLChecks();
    $oauth->fetch("https://sandbox-quickbooks.api.intuit.com/v3/company/193514450140099/query?query=SELECT%20COUNT(*)%20FROM%20Vendor&minorversion=4");

    $response_info = $oauth->getLastResponseInfo();	
	$fileName = "vendors.txt";

	$count = new SimpleXMLElement($oauth->getLastResponse());
	$totalCount = $count->QueryResponse['totalCount']; //total amount of records

	$currentCount = 1 ;
	
	$i = 1;// how many records
	// turn on output buffering
	ob_start();
	while( $currentCount < $totalCount){
		//call QB API
		$oauth->fetch("https://sandbox-quickbooks.api.intuit.com/v3/company/193514450140099/query?query=select%20%2A%20from%20Vendor%20STARTPOSITION%20".$currentCount."%20MAXRESULTS%201000&minorversion=4");
		//get response in XML
		$response = new SimpleXMLElement($oauth->getLastResponse());
		//reset the count of the returned results
		$newCount = $response->QueryResponse->Vendor->count();
		$currentCount += $newCount;

		foreach($response->QueryResponse->Vendor as $vendor)
		{
			echo "Vendor[".($i++)."]: {$vendor->AcctNum}\n";
			echo "\t * Id: [{$vendor->Id}]\n";
			echo "\t * Vendor GivenName: [{$vendor->GivenName}]\n";
			echo "\t * Vendor DisplayName: [{$vendor->DisplayName}]\n";
			echo "\t * Active: [{$vendor->Active}]\n";
			echo "\n";
		}
		
	}

	// buffer content
	$accounts = ob_get_contents();
	// flush buffer
	ob_end_clean(); 
	// output content to a file
	file_put_contents($fileName, $accounts);

	echo "Please find all vendors in vendor.txt\n";


		

} catch(OAuthException $E) {
    echo "Exception caught!\n";
    echo "Response: ". $E->lastResponse . "\n";
	echo "<pre>";
	print_r($E);
}
?>