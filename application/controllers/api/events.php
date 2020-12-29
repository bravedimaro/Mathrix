<?php
require APPPATH.'libraries/REST_Controller.php';

class events extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Events_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $events = $this->Events_model->get_event();

        if(count($events) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $events
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $events
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $event_title = $this->security->xss_clean($this->input->post("event_title"));
        $event_date = $this->security->xss_clean($this->input->post("event_date"));
        $event_time = $this->security->xss_clean($this->input->post("event_time"));
        $event_note = $this->security->xss_clean($this->input->post("event_note"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $event_title = $this->form_validation->set_rules("event_title", "event_title", "required");
        $event_date = $this->form_validation->set_rules("event_date", "event_date", "required");
        $event_time = $this->form_validation->set_rules("event_time", "event_time", "required");
        $event_note = $this->form_validation->set_rules("event_note", "event_note", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($event_title)  && !empty($event_date) && !empty($event_time) && !empty($event_note) && !empty($created_at)){
                
                $eventadd = array(
    
                    "company_id" => $company_id,
                    "employee_id" => $employee_id,
                    "event_title" => $event_title,
                    "event_date" => $event_date,
                    "event_time" => $event_time,
                    "event_note" => $event_note,
                    "created_at" => $created_at,
                );
    
                if($this->Events_model->add($eventadd)){
                    
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
        $id = $this->security->xss_clean($data->event_id);

        if($this->Events_model->delete_event_record($id)){
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
        
        if(isset($data->) || isset($data->event_id)  || isset($data->employee_id) || isset($data->event_title) || isset($data->event_date) || isset($data->event_time) || isset($data->event_note)  || isset($data->attachment) || isset($data->status)  || isset($data->created_at)){
            $id = $data->complaint_id;
            
            $update_events = array(
                
                "company_id" => $data->company_id,
                "employee_id" => $data->employee_id,
                "event_title" => $data->event_title,
                "event_date" => $data->event_date,
                "event_time" => $data->event_time,
                "event_note" => $data->event_note,
                "created_at" => $data->created_at
            );

            if($this->Events_model->update_record($update_events, $id)){
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