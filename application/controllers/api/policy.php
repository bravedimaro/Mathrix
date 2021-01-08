<?php
require APPPATH.'libraries/REST_Controller.php';

class policy extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Policy_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $policy = $this->Policy_model->get_policies();
       
        if(count($policy) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Record found",
                "data" => $policy
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Record found",
                "data" => $policy
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->meeting_id) || isset($data->company_id) || isset($data->employee_id) || isset($data->meeting_title) || isset($data->meeting_date) || isset($data->meeting_time) || isset($data->meeting_room) || isset($data->meeting_note) || isset($data->created_at)){
            $id = $data->meeting_id;

            $update_meeting = array(
                
                "employee_id" =>$data->employee_id,
                "company_id" =>$data->company_id,
                "meeting_title"=>$data->meeting_title,
                "meeting_date" =>$data->meeting_date,
                "meeting_time" =>$data->meeting_time,
                "meeting_room" =>$data->meeting_room,
                "meeting_note" =>$data->meeting_note,
                "created_at"=> $data->created_at
            );

            if($this->Policy_model->update_record($update_meeting, $id)){

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
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $meeting_title = $this->security->xss_clean($this->input->post("meeting_title"));
        $meeting_date = $this->security->xss_clean($this->input->post("meeting_date"));
        $meeting_time = $this->security->xss_clean($this->input->post("meeting_time"));
        $meeting_room = $this->security->xss_clean($this->input->post("meeting_room"));
        $meeting_note = $this->security->xss_clean($this->input->post("meeting_note"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $meeting_title = $this->form_validation->set_rules("meeting_title", "meeting_title", "required");
        $meeting_time = $this->form_validation->set_rules("meeting_time", "meeting_time", "required");
        $meeting_room = $this->form_validation->set_rules("meeting_room", "meeting_room", "required");
        $meeting_note = $this->form_validation->set_rules("meeting_note", "meeting_note", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($meeting_title) && !empty($meeting_date) && !empty($meeting_time) && !empty($meeting_room) && !empty($meeting_note) && !empty($created_at)){
           
                $data = array(
                    "employee_id" => $employee_id,
                    "company_id" =>$company_id,
                    "meeting_title" =>$meeting_title,
                    "meeting_date" => $meeting_date,
                    "meeting_time" => $meeting_time,
                    "meeting_room" => $meeting_room,
                    "meeting_note" => $meeting_note,
                    "created_at" => $created_at                  
                );

                if($this->Policy_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "New Meeting Added" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to add new Meeting" ,    
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
        $meeting_id = $this->security->xss_clean($data->meeting_id);

        if($this->Policy_model->delete_meeting_record($meeting_id)){
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

