<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_device
 *
 * @author johnenrick
 */
class M_device extends API_Model{
    public function __construct() {
        parent::__construct();
        $this->TABLE = "device";
    }
    public function createDevice($description, $longitude, $latitude){
        $newData = array(
            "description" => $description,
            "longitude" => $longitude,
            "latitude" => $latitude
        );
        return $this->createTableEntry($newData);
    }
    public function retrieveDevice($retrieveType = false, $limit = NULL, $offset = 0, $sort = array(), $ID = NULL, $condition = NULL) {
        $joinedTable = array(
            
        );
        $selectedColumn = array(
            "device.*"
        );
        
        return $this->retrieveTableEntry($retrieveType, $limit, $offset, $sort, $ID, $condition, $selectedColumn, $joinedTable);
    }
    public function updateDevice($ID = NULL, $condition = array(), $newData = array()) {
        return $this->updateTableEntry($ID, $condition, $newData);
    }
    public function deleteDevice($ID = NULL, $condition = array()){
        return $this->deleteTableEntry($ID, $condition);
    }
}
