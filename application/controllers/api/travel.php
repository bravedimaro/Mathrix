<?php
require APPPATH.'libraries/REST_Controller.php';

class travel extends REST_Controller{
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Travel_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $travel = $this->Travel_model->get_travels();

        if(count($travel) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Records found",
                "data" => $travel
            ), REST_Controller::HTTP_OK);

        }else{
      
            $this->response(array(
                "status" => 0,
                "message" => "No Records found",
                "data" => $travel
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    public function index_post(){

        $start_date = $this->security->xss_clean($this->input->post("start_date"));
        $end_date = $this->security->xss_clean($this->input->post("end_date"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $visit_purpose = $this->security->xss_clean($this->input->post("visit_purpose"));
        $visit_place = $this->security->xss_clean($this->input->post("visit_place"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $travel_mode = $this->security->xss_clean($this->input->post("travel_mode"));
        $arrangement_type = $this->security->xss_clean($this->input->post("arrangement_type"));
        $expected_budget = $this->security->xss_clean($this->input->post("expected_budget"));
        $actual_budget = $this->security->xss_clean($this->input->post("actual_budget"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $added_by = $this->security->xss_clean($this->input->post("added_by"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $start_date = $this->form_validation->set_rules("start_date", "start_date", "required");
        $end_date = $this->form_validation->set_rules("end_date", "end_date", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $visit_purpose = $this->form_validation->set_rules("visit_purpose", "visit_purpose", "required");
        $visit_place = $this->form_validation->set_rules("visit_place", "visit_place", "required");
        $status = $this->form_validation->set_rules("status", "status", "required");
        $travel_mode = $this->form_validation->set_rules("travel_mode", "travel_mode", "required");
        $arrangement_type = $this->form_validation->set_rules("arrangement_type", "arrangement_type", "required");
        $expected_budget = $this->form_validation->set_rules("expected_budget", "expected_budget", "required");
        $actual_budget = $this->form_validation->set_rules("actual_budget", "actual_budget", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $added_by = $this->form_validation->set_rules("added_by", "added_by", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run()=== FALSE){

            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($start_date) && !empty($end_date) && !empty($decription) && !empty($employee_id) && !empty($company_id) && !empty($visit_purpose) && !empty($visit_place) && !empty($status) && !empty($travel_mode) && !empty($arrangement_type) && !empty($expected_budget) && !empty($actual_budget) && !empty($added_by) && !empty($created_at)){
                
                $travel_add = array(
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "employee_id" => $employee_id,
                    "company_id" => $company_id,
                    "visit_purpose" => $visit_purpose,
                    "description" => $decription,
                    "visit_place" => $visit_place,
                    "status" => $status,
                    "travel_mode" => $travel_mode,
                    "expected_budget" => $expected_budget,
                    "actual_budget" => $actual_budget,
                    "added_by" => $added_by,
                    "arrangement_type" => $arrangement_type,
                    "created_at" => $created_at
                );

                if($this->Travel_model->add($travel_add)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "Record Created",    
                    ), REST_Controller::HTTP_OK);
    
                } else {

                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create Record" ,    
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
                }


            } else {

                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to create message" ,    
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }
        }

    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->travel_id) || isset($data->start_date) || isset($data->end_date) || isset($data->employee_id) || isset($data->company_id) || isset($data->visit_purpose) || isset($data->visit_place) || isset($data->status) || isset($data->travel_mode) || isset($data->arrangement_type)  || isset($data->expected_budget)|| isset($data->actual_budget)|| isset($data->added_by) || isset($data->description) || isset($data->created_at)){
            $id = $data->travel_id;

            $update_travel = array(

                "start_date" => $data->start_date,
                "end_date" => $data->end_date,
                "employee_id" => $data->employee_id,
                "company_id" => $data->company_id,
                "visit_purpose" => $data->visit_purpose,
                "description" => $data->description,
                "visit_place" => $data->visit_place,
                "status" => $data->status,
                "travel_mode" => $data->travel_mode,
                "arrangement_type" => $data->arrangement_type,
                "expected_date" => $data->expected_date,
                "actual_budget" => $data->actual_budget,
                "added_by" => $data->added_by,
                "created_at" => $data->created_at
            );

            if($this->Travel_model->update_record($update_travel, $id)){
                $this->response(array(
                    "status" => 1,
                    "message" => "Record updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update Record"
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else{
            
            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete(){
        $data = json_decode(file_get_contents("php://input"));
        $travel_id = $this->security->xss_clean($data->travel_id);

        if($this->Travel_model->delete_record($travel_id)){
            $this->response(array(
                "status" => 1,
                "message" => "Record Deleted"
            ) , REST_Controller::HTTP_OK );

        } else{
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete Record"
            ) , REST_Controller::HTTP_NOT_FOUND);

        }
    }   
}
?>
