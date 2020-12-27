<?php
require APPPATH.'libraries/REST_Controller.php';

class award extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Awards_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $award = $this->Awards_model->get_award();

        if(count($award) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $award
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $award
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $award_type_id = $this->security->xss_clean($this->input->post("award_type_id"));
        $gift_item = $this->security->xss_clean($this->input->post("gift_item"));
        $cash_price = $this->security->xss_clean($this->input->post("cash_price"));
        $award_photo = $this->security->xss_clean($this->input->post("award_photo"));
        $award_month_year = $this->security->xss_clean($this->input->post("award_month_year"));
        $award_information = $this->security->xss_clean($this->input->post("award_information"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $award_type_id = $this->form_validation->set_rules("award_type_id", "company_assets_code", "required");
        $gift_item = $this->form_validation->set_rules("gift_item", "Name", "required");
        $cash_price = $this->form_validation->set_rules("cash_price", "cash_price", "required");
        $award_photo = $this->form_validation->set_rules("award_photo", "award_photo", "required");
        $award_month_year = $this->form_validation->set_rules("award_month_year", "Manufacturer", "required");
        $award_information = $this->form_validation->set_rules("award_information", "Serial_number", "required");
        $description = $this->form_validation->set_rules("description", "Warrenty_end_date", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($award_type_id)  && !empty($gift_item) && !empty($cash_price) && !empty($award_photo) && !empty($award_month_year) && !empty($award_information) && !empty($description) && !empty($asset_note) && !empty($asset_image) && !empty($is_working) && !empty($created_at)){
                
                $assetadd = array(
    
                    "company_id" => $company_id,
                    "employee_id" => $employee_id,
                    "award_type_id" => $award_type_id,
                    "gift_item" => $gift_item,
                    "cash_price" => $cash_price,
                    "award_photo" => $award_photo,
                    "award_month_year" => $award_month_year,
                    "award_information" => $award_information,
                    "description" => $description,
                    "created_at" => $created_at,
                );
    
                if($this->Award_model->add($assetadd)){
                    
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
        $id = $this->security->xss_clean($data->award_id);

        if($this->Awards_model->delete_record($id)){
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
        
        if(isset($data->award_id) || isset($data->company_id)  || isset($data->employee_id) || isset($data->award_type_id) || isset($data->gift_item) || isset($data->cash_price) || isset($data->award_photo)  || isset($data->award_month_year) || isset($data->award_information) || isset($data->description) || isset($data->created_at)){
            $award_id = $data->award_id;
            
            $update_award = array(
                
                "company_id" => $data->company_id,
                "employee_id" => $data->employee_id,
                "award_type_id" => $data->award_type_id,
                "gift_item" => $data->gift_item,
                "cash_price" => $data->cash_price,
                "award_photo" => $data->award_photo,
                "award_month_year" => $data->award_month_year,
                "award_information" => $data->award_information,
                "description" => $data->description,
                "created_at" => $data->created_at,

            );

            if($this->Awards_model->update_record($update_award, $award_id)){
                $this->response(array(
                    "status" => 1,
                    "message" => "Awards updated successfully"
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