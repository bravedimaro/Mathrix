<?php
require APPPATH.'libraries/REST_Controller.php';

class custom extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Custom_fields_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $custom = $this->Custom_fields_model->get_hrsale_module_attribute();

        if(count($custom) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $custom
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $custom
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $module_id = $this->security->xss_clean($this->input->post("module_id"));
        $attribute = $this->security->xss_clean($this->input->post("attribute"));
        $attribute_label = $this->security->xss_clean($this->input->post("attribute_label"));
        $attribute_type = $this->security->xss_clean($this->input->post("attribute_type"));
        $validation = $this->security->xss_clean($this->input->post("validation"));
        $priority = $this->security->xss_clean($this->input->post("priority"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $module_id = $this->form_validation->set_rules("module_id", "module_id", "required");
        $attribute = $this->form_validation->set_rules("attribute", "attribute", "required");
        $attribute_label = $this->form_validation->set_rules("attribute_label", "company_assets_code", "required");
        $attribute_type = $this->form_validation->set_rules("attribute_type", "Name", "required");
        $validation = $this->form_validation->set_rules("validation", "validation", "required");
        $priority = $this->form_validation->set_rules("priority", "priority", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($module_id) && !empty($attribute) && !empty($attribute_label)  && !empty($attribute_type) && !empty($validation) && !empty($priority) && !empty($attachment) && !empty($status) && !empty($priority) && !empty($created_at)){
                
                $customadd = array(
    
                    "module_id" => $module_id,
                    "attribute" => $attribute,
                    "attribute_label" => $attribute_label,
                    "attribute_type" => $attribute_type,
                    "validation" => $validation,
                    "priority" => $priority,
                    "created_at" => $created_at,
                );
    
                if($this->Custom_fields_model->add($customadd)){
                    
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
        $id = $this->security->xss_clean($data->custom_field_id);

        if($this->Custom_fields_model->delete_record($id)){
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
        
        if(isset($data->custom_field_id) || isset($data->company_id)  || isset($data->attribute) || isset($data->attribute_label) || isset($data->attribute_type) || isset($data->validation) || isset($data->priority)  || isset($data->created_at)){
            $custom_field_id = $data->custom_field_id;
            
            $update_complain = array(
                
                "company_id" => $data->company_id,
                "attribute" => $data->attribute,
                "attribute_label" => $data->attribute_label,
                "attribute_type" => $data->attribute_type,
                "validation" => $data->validation,
                "priority" => $data->priority,
                "created_at" => $data->created_at,

            );

            if($this->Custom_fields_model->update_record($update_complain, $custom_field_id)){
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