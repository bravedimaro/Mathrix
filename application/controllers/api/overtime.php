<?php
require APPPATH.'libraries/REST_Controller.php';

class overtime extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Overtime_request_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $overtime = $this->Overtime_request_model->all_employee_overtime_requests();
       
        if(count($overtime) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Record found",
                "data" => $overtime
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Record found",
                "data" => $overtime
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_single_get(){
        // $isbn = $this->get
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->time_request_id) || isset($data->company_id) || isset($data->employee_id) || isset($data->request_date) || isset($data->request_date_request) || isset($data->request_clock_in) || isset($data->total_hours) || isset($data->request_reason) || isset($data->is_approved) || isset($data->created_at)){
            $id = $data->time_request_id;

            $update_overtime = array(
                
                "employee_id" =>$data->employee_id,
                "company_id" =>$data->company_id,
                "request_date"=>$data->request_date,
                "request_date_request" =>$data->request_date_request,
                "request_clock_in" =>$data->request_clock_in,
                "total_hours" =>$data->total_hours,
                "request_reason" =>$data->request_reason,
                "is_approved" =>$data->is_approved,
                "created_at"=> $data->created_at
            );

            if($this->Overtime_request_model->update_request_record($update_overtime, $id)){

                $this->response(array(
                    "status" => 1,
                    "message" => "Overtime updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update Overtime"
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
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $request_date = $this->security->xss_clean($this->input->post("request_date"));
        $request_date_request = $this->security->xss_clean($this->input->post("request_date_request"));
        $request_clock_in = $this->security->xss_clean($this->input->post("request_clock_in"));
        $total_hours = $this->security->xss_clean($this->input->post("total_hours"));
        $request_reason = $this->security->xss_clean($this->input->post("request_reason"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $request_date = $this->form_validation->set_rules("request_date", "request_date", "required");
        $request_clock_in = $this->form_validation->set_rules("request_clock_in", "request_clock_in", "required");
        $total_hours = $this->form_validation->set_rules("total_hours", "total_hours", "required");
        $request_reason = $this->form_validation->set_rules("request_reason", "request_reason", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($request_date) && !empty($request_date_request) && !empty($request_clock_in) && !empty($total_hours) && !empty($request_reason) && !empty($created_at)){
           
                $data = array(
                    "employee_id" => $employee_id,
                    "company_id" =>$company_id,
                    "request_date" =>$request_date,
                    "request_date_request" => $request_date_request,
                    "request_clock_in" => $request_clock_in,
                    "total_hours" => $total_hours,
                    "request_reason" => $request_reason,
                    "created_at" => $created_at                  
                );

                if($this->Overtime_request_model->add_employee_overtime_request($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "New Request Added" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to add new Request" ,    
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
        $time_request_id = $this->security->xss_clean($data->time_request_id);

        if($this->Overtime_request_model->delete_overtime_request_record($time_request_id)){
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

