<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_water_level extends API_Controller {
    /*
     * Access Control List
     * 1    - createWaterLevel
     * 2    - retrieveWaterLevel
     * 4    - updateWaterLevel
     * 8    - deleteWaterLevel
     * 16   - batchCreateWaterLevel
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_water_level");
        $this->APICONTROLLERID = 1;
    }
    public function createWaterLevel(){
        $this->accessNumber = 1;
        if($this->checkACL()){
            $this->form_validation->set_rules('device_ID', 'Device', 'required');
            $this->form_validation->set_rules('measurement', 'Measurement', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_water_level->createWaterLevel(
                        $this->input->post("device_ID"),
                        $this->input->post("measurement")
                        );
                if($result){
                    $this->actionLog($result);
                    $this->responseData($result);
                }else{
                    $this->responseError(3, "Failed to create");
                }
            }else{
                if(count($this->form_validation->error_array())){
                    $this->responseError(102, $this->form_validation->error_array());
                }else{
                    $this->responseError(100, "Required Fields are empty");
                }
            }
        }else{
            $this->responseError(1, "Not Authorized");
        }
        $this->outputResponse();
    }
    public function retrieveWaterLevel(){
        $this->accessNumber = 2;
        if($this->checkACL()){
            $result = $this->m_water_level->retrieveWaterLevel(
                    $this->input->post("retrieve_type"),
                    $this->input->post("limit"),
                    $this->input->post("offset"), 
                    $this->input->post("sort"),
                    $this->input->post("ID"), 
                    $this->input->post("condition")
                    );
            if($this->input->post("limit")){
                $this->responseResultCount($this->m_water_level->retrieveWaterLevel(
                    1,
                    NULL,
                    NULL,
                    NULL,
                    $this->input->post("ID"), 
                    $this->input->post("condition")
                    ));
            }
            if($result){
                $this->actionLog(json_encode($this->input->post()));
                $this->responseData($result);
            }else{
                $this->responseError(2, "No Result");
            }
        }else{
            $this->responseError(1, "Not Authorized");
        }
        $this->outputResponse();
    }
    public function updateWaterLevel(){
        $this->accessNumber = 4;
        if($this->checkACL()){
            
            $result = $this->m_water_level->updateWaterLevel(
                    $this->input->post("ID"),
                    $this->input->post("condition"),
                    $this->input->post("updated_data")
                    );
            if($result){
                $this->actionLog(json_encode($this->input->post()));
                $this->responseData($result);
            }else{
                $this->responseError(3, "Failed to Update");
            }
        }else{
            $this->responseError(1, "Not Authorized");
        }
        $this->outputResponse();
    }
    public function deleteWaterLevel(){
        $this->accessNumber = 8;
        if($this->checkACL()){
            $result = $this->m_water_level->deleteWaterLevel(
                    $this->input->post("ID"), 
                    $this->input->post("condition")
                    );
            if($result){
                $this->actionLog(json_encode($this->input->post()));
                $this->responseData($result);
            }else{
                $this->responseError(3, "Failed to delete");
            }
        }else{
            $this->responseError(1, "Not Authorized");
        }
        $this->outputResponse();
    }
}
