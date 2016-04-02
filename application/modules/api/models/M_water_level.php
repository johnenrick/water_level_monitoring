<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of m_water_level
 *
 * @author johnenrick
 */
class M_water_level extends API_Model{
    public function __construct() {
        parent::__construct();
        $this->TABLE = "water_level";
    }
    public function createWaterLevel($firstParameter){
        $newData = array(
            "device_ID" => $deviceID,
            "measurement" => $measurement,
            "datetime" => $datetime
        );
        return $this->createTableEntry($newData);
    }
    public function retrieveWaterLevel($retrieveType = false, $limit = NULL, $offset = 0, $sort = array(), $ID = NULL, $condition = NULL) {
        $joinedTable = array(
            
        );
        $selectedColumn = array(
            "water_level.*"
        );
        
        return $this->retrieveTableEntry($retrieveType, $limit, $offset, $sort, $ID, $condition, $selectedColumn, $joinedTable);
    }
    public function updateWaterLevel($ID = NULL, $condition = array(), $newData = array()) {
        return $this->updateTableEntry($ID, $condition, $newData);
    }
    public function deleteWaterLevel($ID = NULL, $condition = array()){
        return $this->deleteTableEntry($ID, $condition);
    }
}
