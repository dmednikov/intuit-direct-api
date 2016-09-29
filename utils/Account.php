<?php

/**
 * Account Vendor is implementation of an abstract AEntity 
 * to store and display account information from QB API
 *
 * @author Denis Mednikov
 */
require_once "AEntity.php";

class Account extends AEntity {

   public function __construct() {
      parent::init("Account");
      $this->fileName = ConfigurationManager::AppSettings('AccountsFilename');
   }

   public function outputToFile() {

      $i = 1;
      // turn on output buffering
      ob_start();
      foreach ($this->objectStorage as $account) {
         echo "Account[" . ($i++) . "]: {$account->Name}\n";
         echo "\t * Id: [{$account->Id}]\n";
         echo "\t * Account Type: [{$account->AccountType}]\n";
         echo "\t * Active: [{$account->Active}]\n";
         echo "\n";
      }
      // buffer content
      $accounts = ob_get_contents();
      // flush buffer
      ob_end_clean();
      // output content to a file
      file_put_contents($this->fileName, $accounts);
   }

}
