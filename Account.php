<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 *
 * @author Denis Medikov
 */
require_once "AEntity.php";

class Account extends AEntity{
    
    public function __construct() {
        parent::init();
        
        $this->totalCountQuery = $this->baseUrl . "/v3/company/" . $this->realmId . "/query?query=SELECT%20COUNT(*)%20FROM%20Account&minorversion=4";
    }
    public function printOut() {
        return "Account " . $this->totalCount;
    }
    
    public function findAll(){
        $this->totalCount = parent::getTotalCount();
    }
    
}
