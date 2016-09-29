<?php
/**
 * Vendor is implementation of an abstract AEntity
 * to store and display vendor information from QB API
 *
 * @author Denis Mednikov
 */
require_once "AEntity.php";

class Vendor extends AEntity {
   
   public function __construct() {
      parent::init("Vendor");
      $this->fileName = ConfigurationManager::AppSettings('VendorsFilename');
   }

   public function outputToFile() {
      
      $i=1;
      // turn on output buffering
    ob_start();
      foreach ($this->objectStorage as $vendor) {
         echo "Vendor[" . ($i++) . "]: {$vendor->AcctNum}\n";
            echo "\t * Id: [{$vendor->Id}]\n";
            echo "\t * Vendor GivenName: [{$vendor->GivenName}]\n";
            echo "\t * Vendor DisplayName: [{$vendor->DisplayName}]\n";
            echo "\t * Active: [{$vendor->Active}]\n";
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
