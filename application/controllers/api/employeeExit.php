<?php
require APPPATH.'libraries/REST_Controller.php';

class employeeExit extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Employee_exit_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $exits = $this->Employee_exit_model->get_exits();

        if(count($exits) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $exits
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $exits
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $exit_date = $this->security->xss_clean($this->input->post("exit_date"));
        $exit_type_id = $this->security->xss_clean($this->input->post("exit_type_id"));
        $exit_interview = $this->security->xss_clean($this->input->post("exit_interview"));
        $reason = $this->security->xss_clean($this->input->post("reason"));
        $added_by = $this->security->xss_clean($this->input->post("added_by"));
        $is_inactivate_account = $this->security->xss_clean($this->input->post("is_inactivate_account"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $exit_date = $this->form_validation->set_rules("exit_date", "company_assets_code", "required");
        $exit_type_id = $this->form_validation->set_rules("exit_type_id", "Name", "required");
        $exit_interview = $this->form_validation->set_rules("exit_interview", "exit_interview", "required");
        $reason = $this->form_validation->set_rules("reason", "reason", "required");
        $added_by = $this->form_validation->set_rules("added_by", "Manufacturer", "required");
        $is_inactivate_account = $this->form_validation->set_rules("is_inactivate_account", "Serial_number", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($exit_date)  && !empty($exit_type_id) && !empty($exit_interview) && !empty($reason) && !empty($added_by) && !empty($is_inactivate_account) && !empty($reason) && !empty($asset_note) && !empty($asset_image) && !empty($is_working) && !empty($created_at)){
                
                $exitadd = array(
    
                    "company_id" => $company_id,
                    "employee_id" => $employee_id,
                    "exit_date" => $exit_date,
                    "exit_type_id" => $exit_type_id,
                    "exit_interview" => $exit_interview,
                    "reason" => $reason,
                    "added_by" => $added_by,
                    "is_inactivate_account" => $is_inactivate_account,
                    "created_at" => $created_at,
                );
    
                if($this->Employee_exit_model->add($exitadd)){
                    
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
        $id = $this->security->xss_clean($data->exit_id);

        if($this->Employee_exit_model->delete_record($id)){
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
        
        if(isset($data->exit_id) || isset($data->company_id)  || isset($data->employee_id) || isset($data->exit_date) || isset($data->exit_type_id) || isset($data->exit_interview) || isset($data->reason)  || isset($data->added_by) || isset($data->status)  || isset($data->created_at)){
            $exit_id = $data->exit_id;
            
            $update_complain = array(
                
                "company_id" => $data->company_id,
                "employee_id" => $data->employee_id,
                "exit_date" => $data->exit_date,
                "exit_type_id" => $data->exit_type_id,
                "exit_interview" => $data->exit_interview,
                "reason" => $data->reason,
                "added_by" => $data->added_by,
                "status" => $data->status,
                "created_at" => $data->created_at,

            );

            if($this->Employee_exit_model->update_record($update_complain, $exit_id)){
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