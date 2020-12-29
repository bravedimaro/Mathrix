<?php
require APPPATH.'libraries/REST_Controller.php';

class role extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Role_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $role = $this->Role_model->all_user_roles();
       
        if(count($role) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $role
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $role
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->role_id) || isset($data->company_id) || isset($data->role_name) || isset($data->role_access) || isset($data->role_resources) || isset($data->created_at)){
            $id = $data->role_id;

            $update_role = array(
                "role_name" =>$data->role_name,
                "company_id" =>$data->company_id,
                "role_access"=>$data->role_access,
                "role_resources" =>$data->role_resources,
                "created_at"=> $data->created_at
            );

            if($this->Role_model->update_record($update_role, $id)){

                $this->response(array(
                    "status" => 1,
                    "message" => "Chat updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update chat"
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
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $role_name = $this->security->xss_clean($this->input->post("role_name"));
        $role_access = $this->security->xss_clean($this->input->post("role_access"));
        $role_resources = $this->security->xss_clean($this->input->post("role_resources"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $role_name = $this->form_validation->set_rules("role_name", "role_name", "required");
        $role_access = $this->form_validation->set_rules("role_access", "role_access", "required");
        $role_resources = $this->form_validation->set_rules("role_resources", "role_resources", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($company_id) && !empty($role_name) && !empty($role_access) && !empty($role_resources) && !empty($created_at)){
           
                $data = array(
                    "role_name" => $role_name,
                    "company_id" =>$company_id,
                    "role_access" =>$role_access,
                    "role_resources" => $role_resources,
                    "created_at" => $created_at                  
                );

                if($this->Role_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "Company created" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create message" ,    
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
        $role_id = $this->security->xss_clean($data->role_id);

        if($this->Role_model->delete_record($role_id)){
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

