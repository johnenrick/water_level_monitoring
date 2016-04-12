<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_device extends API_Controller {
    /*
     * Access Control List
     * 1    - createDevice
     * 2    - retrieveDevice
     * 4    - updateDevice
     * 8    - deleteDevice
     * 16   - batchCreateDevice
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_device");
        $this->APICONTROLLERID = 1;
    }
    public function createDevice(){
        $this->accessNumber = 1;
        if($this->checkACL()){
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('longitude', 'Longitude', 'required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_device->createDevice(
                        $this->input->post("description"),
                        $this->input->post("longitude"),
                        $this->input->post("latitude")
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
    public function retrieveDevice(){
        $this->accessNumber = 2;
        if($this->checkACL()){
            $result = $this->m_device->retrieveDevice(
                    $this->input->post("retrieve_type"),
                    $this->input->post("limit"),
                    $this->input->post("offset"), 
                    $this->input->post("sort"),
                    $this->input->post("ID"), 
                    $this->input->post("condition")
                    );
            if($this->input->post("limit")){
                $this->responseResultCount($this->m_device->retrieveDevice(
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
    public function updateDevice(){
        $this->accessNumber = 4;
        if($this->checkACL()){
            
            $result = $this->m_device->updateDevice(
                    $this->input->post("ID"),
                    $this->input->post("condition"),
                    $this->input->post("updated_data")
                    );
            $this->responseDebug($this->input->post("updated_data"));
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
    public function deleteDevice(){
        $this->accessNumber = 8;
        if($this->checkACL()){
            $result = $this->m_device->deleteDevice(
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
