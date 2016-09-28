<?php
/**
 * Description of AEntity
 *
 * @author Denis Medikov
 */
require_once "utils/ConfigurationManager.php";

abstract class AEntity
{
    // Force Extending class to define this method
    abstract public function printOut();
    abstract public function findAll();
    public $filename;
    public $realmId;
    public $baseUrl;
    public $oauth;
    public $totalCountQuery;
    public $totalCount;
    
    public function init(){
        $this->fileName = ConfigurationManager::AppSettings('AccountsFilename');

        $this->realmId = ConfigurationManager::AppSettings('RealmID'); //companyId
        $this->baseUrl = ConfigurationManager::AppSettings('BaseURL');

        $this->oauth = new OAuth(ConfigurationManager::AppSettings('ConsumerKey'), ConfigurationManager::AppSettings('ConsumerSecret'), OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
        $this->oauth->setToken(ConfigurationManager::AppSettings('AccessToken'), ConfigurationManager::AppSettings('AccessTokenSecret'));

        $this->oauth->disableSSLChecks();
    }
    
    protected function getTotalCount(){
        $this->oauth->fetch( $this->totalCountQuery);

        $count = new SimpleXMLElement($this->oauth->getLastResponse());
        return $count->QueryResponse['totalCount'];
    }

}
