<?php
require APPPATH.'libraries/REST_Controller.php';

class assets extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Assets_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $asset = $this->Assets_model->get_asset();

        if(count($asset) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $asset
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $asset
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $asset_category_id = $this->security->xss_clean($this->input->post("asset_category_id"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $company_asset_code = $this->security->xss_clean($this->input->post("company_asset_code"));
        $name = $this->security->xss_clean($this->input->post("name"));
        $purchase_date = $this->security->xss_clean($this->input->post("purchase_date"));
        $invoice_number = $this->security->xss_clean($this->input->post("invoice_number"));
        $manufacturer = $this->security->xss_clean($this->input->post("manufacturer"));
        $serial_number = $this->security->xss_clean($this->input->post("serial_number"));
        $warranty_end_date = $this->security->xss_clean($this->input->post("warranty_end_date"));
        $asset_note = $this->security->xss_clean($this->input->post("asset_note"));
        $asset_image = $this->security->xss_clean($this->input->post("asset_image"));
        $is_working = $this->security->xss_clean($this->input->post("is_working"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $asset_category_id = $this->form_validation->set_rules("asset_category_id", "asset_category_id", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $company_asset_code = $this->form_validation->set_rules("company_asset_code", "company_assets_code", "required");
        $name = $this->form_validation->set_rules("name", "Name", "required");
        $purchase_date = $this->form_validation->set_rules("purchase_date", "purchase_date", "required");
        $invoice_number = $this->form_validation->set_rules("invoice_number", "invoice_number", "required");
        $manufacturer = $this->form_validation->set_rules("manufacturer", "Manufacturer", "required");
        $serial_number = $this->form_validation->set_rules("serial_number", "Serial_number", "required");
        $warranty_end_date = $this->form_validation->set_rules("warranty_end_date", "Warrenty_end_date", "required");
        $asset_note = $this->form_validation->set_rules("asset_note", "asset_note", "required");
        $asset_image = $this->form_validation->set_rules("asset_image", "asset_image", "required");
        $is_working = $this->form_validation->set_rules("is_working", "is_working", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($asset_category_id) && !empty($company_id) && !empty($employee_id) && !empty($company_asset_code)  && !empty($name) && !empty($purchase_date) && !empty($invoice_number) && !empty($manufacturer) && !empty($serial_number) && !empty($warranty_end_date) && !empty($asset_note) && !empty($asset_image) && !empty($is_working) && !empty($created_at)){
                
                $assetadd = array(
    
                    "asset_category_id" => $asset_category_id,
                    "company_id" => $company_id,
                    "employee_id" => $employee_id,
                    "company_asset_code" => $company_asset_code,
                    "name" => $name,
                    "purchase_date" => $purchase_date,
                    "invoice_number" => $invoice_number,
                    "manufacturer" => $manufacturer,
                    "serial_number" => $serial_number,
                    "warranty_end_date" => $warranty_end_date,
                    "asset_note" => $asset_note,
                    "asset_image" => $asset_image,
                    "is_working" => $is_working,
                    "created_at" => $created_at,
                );
    
                if($this->Assets_model->add_asset($assetadd)){
                    
                    $this->response(array(
                        "status" => 1,
                        "message" => "Asset created" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create Asset" ,    
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                }
                
            } else {
                
                $this->response(array(
                    "status" => 0,
                    "Message" => "fields cannot be empty"
                ), REST_Controller::HTTP_NOT_FOUND);

            }
        }


    }

    public function index_delete(){
        $data = json_decode(file_get_contents("php://input"));
        $id = $this->security->xss_clean($data->assets_id);

        if($this->Assets_model->delete_assets_record($id)){
            $this->response(array(
                "status" => 1,
                "message" => "Assets deleted"
              ) , REST_Controller::HTTP_OK );

        } else {
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete Assets"
              ) , REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){

        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->assets_id) || isset($data->asset_category_id) || isset($data->company_id) || isset($data->employee_id) || isset($data->company_asset_code) || isset($data->name) || isset($data->purchase_date) || isset($data->invoice_number)  || isset($data->manufacturer) || isset($data->serial_number) || isset($data->warranty_end_date) || isset($data->asset_note) || isset($data->asset_image) || isset($data->is_working) || isset($data->created_at)){
            $assets_id = $data->assets_id;
            
            $update_assets = array(
                
                "asset_category_id" => $data->asset_category_id,
                "company_id" => $data->company_id,
                "employee_id" => $data->employee_id,
                "company_asset_code" => $data->company_asset_code,
                "name" => $data->name,
                "purchase_date" => $data->purchase_date,
                "invoice_number" => $data->invoice_number,
                "manufacturer" => $data->manufacturer,
                "serial_number" => $data->serial_number,
                "warranty_end_date" => $data->warranty_end_date,
                "asset_note" => $data->asset_note,
                "asset_image" => $data->asset_image,
                "is_working" => $data->is_working,
                "created_at" => $data->created_at,

            );

            if($this->Assets_model->update_assets_record($update_assets, $assets_id)){
                $this->response(array(
                    "status" => 1,
                    "message" => "Assets updated successfully"
                ), REST_Controller::HTTP_OK);

            } else{

                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update Asset"
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else{
            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

}

?>