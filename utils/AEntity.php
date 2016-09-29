<?php

/**
 * AEntity abstract to represent general functionality for retrieval of 
 * Accounts and Vendors from QB API
 *
 * @author Denis Mednikov
 */
require_once "ConfigurationManager.php";

abstract class AEntity {

   // Force Extending class to define this method
   abstract public function outputToFile();

   protected $filename = "";
   private $realmId;
   private $baseUrl;
   private $oauth;
   private $totalCountQuery = "";
   private $totalCountQueryUrl = "";
   private $totalCount = 0; //total count of results in QB
   private $fetchResultsQueryFormat = "";
   private $name = ""; //name of the type (Account or Vendor)
   protected $objectStorage = Array(); // all query results will be sored here

   /*
    * Initialize common elements
    */

   public function init($name) {

      $this->realmId = ConfigurationManager::AppSettings('RealmID'); //companyId
      $this->baseUrl = ConfigurationManager::AppSettings('BaseURL');

      $this->oauth = new OAuth(ConfigurationManager::AppSettings('ConsumerKey'), ConfigurationManager::AppSettings('ConsumerSecret'), OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
      $this->oauth->setToken(ConfigurationManager::AppSettings('AccessToken'), ConfigurationManager::AppSettings('AccessTokenSecret'));

      $this->oauth->disableSSLChecks();

      $this->name = $name;

      $this->totalCountQuery = urlencode("SELECT COUNT(*) FROM " . $this->name);
      $this->totalCountQueryUrl = $this->baseUrl . "/v3/company/" . $this->realmId . "/query?query=" . $this->totalCountQuery . "&minorversion=4";

      $this->fetchResultsQueryFormat = "SELECT * FROM " . $this->name . " STARTPOSITION %s MAXRESULTS 1000";
   }

   /*
    * Returns URL to be used in OAUTH "scrolling" fetch for results 
    */

   private function getFetchResultsUrl($startPosition) {
      $query = urlencode(sprintf($this->fetchResultsQueryFormat, $startPosition));
      return $this->baseUrl . "/v3/company/" . $this->realmId . "/query?query=" . $query . "&minorversion=4";
   }

   /*
    * Getter for fileName variable
    */

   public function getFilename() {
      return $this->fileName;
   }

   /*
    * Fetches number of results in QB, parse and returns a digital value
    */

   private function getTotalCount() {
      $this->oauth->fetch($this->totalCountQueryUrl);

      $count = new SimpleXMLElement($this->oauth->getLastResponse());
      return $count->QueryResponse['totalCount'];
   }

   /*
    * This function gets total count of result and then sccrolls thru the results
    * if total count is larger then 1000 (maximum defined by QB API).
    * If total count less then 100 simple returns results without scrolling.
    */

   public function findAll() {
      $this->totalCount = $this->getTotalCount();

      $currentCount = 1;
      $i = 1; // how many records
      while ($currentCount < $this->totalCount) {

         $url = $this->getFetchResultsUrl($currentCount);
         $this->oauth->fetch($url);
         //get response in XML
         $response = new SimpleXMLElement($this->oauth->getLastResponse());

         //reset the count of the returned results
         $newCount = $response->QueryResponse->{$this->name}->count();
         $currentCount += $newCount;

         foreach ($response->QueryResponse->{$this->name} as $obj) {
            $this->objectStorage[] = $obj;
         }
      }
   }

}
