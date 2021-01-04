<?php
require APPPATH.'libraries/REST_Controller.php';

class language extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Languages_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $language = $this->Languages_model->get_language();
       
        if(count($language) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Languages found",
                "data" => $language
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Languages found",
                "data" => $language
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->language_id) || isset($data->language_name) || isset($data->language_code) || isset($data->language_flag) || isset($data->is_active) || isset($data->created_at)){
            $id = $data->language_id;

            $update_language = array(
                "language_code" =>$data->language_code,
                "language_name" =>$data->language_name,
                "language_flag"=>$data->language_flag,
                "is_active" =>$data->is_active,
                "created_at"=> $data->created_at
            );

            if($this->Languages_model->update_record($update_language, $id)){

                $this->response(array(
                    "status" => 1,
                    "message" => "Language updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update Language"
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function index_post(){
        
        $language_name = $this->security->xss_clean($this->input->post("language_name"));
        $language_code = $this->security->xss_clean($this->input->post("language_code"));
        $language_flag = $this->security->xss_clean($this->input->post("language_flag"));
        $is_active = $this->security->xss_clean($this->input->post("is_active"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $language_name = $this->form_validation->set_rules("language_name", "language_name", "required");
        $language_code = $this->form_validation->set_rules("language_code", "language_code", "required");
        $language_flag = $this->form_validation->set_rules("language_flag", "language_flag", "required");
        $is_active = $this->form_validation->set_rules("is_active", "is_active", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($language_name) && !empty($language_code) && !empty($language_flag) && !empty($is_active) && !empty($created_at)){
           
                $data = array(
                    "language_code" => $language_code,
                    "language_name" =>$language_name,
                    "language_flag" =>$language_flag,
                    "is_active" => $is_active,
                    "created_at" => $created_at                  
                );

                if($this->Languages_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "New Language Added" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to add new Language" ,    
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                }

            } else{
                $this->response(array(
                    "status" => 0,
                    "message" => "Fields cannot be empty" ,    
                ), REST_Controller::HTTP_NOT_FOUND);

            }
            
        }

    }

    public function index_delete(){

        $data = json_decode(file_get_contents("php://input"));
        $language_id = $this->security->xss_clean($data->language_id);

        if($this->Languages_model->delete_record($language_id)){
            $this->response(array(
                "status" => 1,
                "message" => "Record Deleted"
            ) , REST_Controller::HTTP_OK );
        } else {
            
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete record"
            ) , REST_Controller::HTTP_NOT_FOUND);

        }
    }

}
?>

