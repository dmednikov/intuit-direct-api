<?php
if (!extension_loaded('oauth')) {
   exit("OAuth extension is not installed. Please install it prior to running this script.");
}
require_once "utils/Account.php";
require_once "utils/Vendor.php";

try {
   $account = new Account();
   $account->findAll();
   $account->outputToFile();


   $vendor = new Vendor();
   $vendor->findAll();
   $vendor->outputToFile();

   echo "Files were successfully created<br />";
   echo "<a href='" . $account->getFilename() . "'>Accounts</a><br />";
   echo "<a href='" . $vendor->getFilename() . "'>Vendors</a><br />";
} catch (OAuthException $E) {
   echo "Something went wrong. Please check your configuratioin options<br />";
   echo "Response received from QB API: <strong>" . $E->getMessage() . "</strong>";
}
?>