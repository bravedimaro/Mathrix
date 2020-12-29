<?php
require APPPATH.'libraries/REST_Controller.php';

class department extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Department_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $department = $this->Department_model->get_department();

        if(count($department) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $department
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $department
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $department_name = $this->security->xss_clean($this->input->post("department_name"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $location_id = $this->security->xss_clean($this->input->post("location_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $added_by = $this->security->xss_clean($this->input->post("added_by"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $department_name = $this->form_validation->set_rules("department_name", "department_name", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $location_id = $this->form_validation->set_rules("location_id", "company_assets_code", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "Name", "required");
        $added_by = $this->form_validation->set_rules("added_by", "added_by", "required");
        $status = $this->form_validation->set_rules("status", "status", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($department_name) && !empty($company_id) && !empty($location_id)  && !empty($employee_id) && !empty($added_by) && !empty($status) && !empty($created_at)){
                
                $departmentadd = array(
    
                    "department_name" => $department_name,
                    "company_id" => $company_id,
                    "location_id" => $location_id,
                    "employee_id" => $employee_id,
                    "added_by" => $added_by,
                    "status" => $status,
                    "created_at" => $created_at,
                );
    
                if($this->Department_model->add($departmentadd)){
                    
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
        $id = $this->security->xss_clean($data->department_id);

        if($this->Department_model->delete_record($id)){
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
        
        if(isset($data->department_id) || isset($data->company_id) || isset($data->location_id) || isset($data->employee_id) || isset($data->added_by) || isset($data->status)  || isset($data->created_at)){
            $department_id = $data->department_id;
            
            $update_department = array(
                
                // "company_id" => $data->company_id,
                "company_id" => $data->company_id,
                "location_id" => $data->location_id,
                "employee_id" => $data->employee_id,
                "added_by" => $data->added_by,
                "status" => $data->status,
                "created_at" => $data->created_at,

            );

            if($this->Department_model->update_record($update_department, $department_id)){
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