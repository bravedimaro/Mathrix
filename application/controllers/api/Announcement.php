<?php
require APPPATH.'libraries/REST_Controller.php';

class announcement extends REST_Controller{
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Announcement_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $announcement = $this->Announcement_model->get_new_announcements();

        if(count($announcement) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Announcement found",
                "data" => $announcement
            ), REST_Controller::HTTP_OK);

        }else{
      
            $this->response(array(
                "status" => 0,
                "message" => "No Announcement found",
                "data" => $announcement
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    public function index_post(){

        $title = $this->security->xss_clean($this->input->post("title"));
        $start_date = $this->security->xss_clean($this->input->post("start_date"));
        $end_date = $this->security->xss_clean($this->input->post("end_date"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $location_id = $this->security->xss_clean($this->input->post("location_id"));
        $published_by = $this->security->xss_clean($this->input->post("published_by"));
        $summary = $this->security->xss_clean($this->input->post("summary"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $is_active = $this->security->xss_clean($this->input->post("is_active"));
        $is_notify = $this->security->xss_clean($this->input->post("is_notify"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));
        $department_id = $this->security->xss_clean($this->input->post("department_id"));

        $title = $this->form_validation->set_rules("title", "title", "required");
        $start_date = $this->form_validation->set_rules("start_date", "start_date", "required");
        $end_date = $this->form_validation->set_rules("end_date", "end_date", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $location_id = $this->form_validation->set_rules("location_id", "location_id", "required");
        $published_by = $this->form_validation->set_rules("published_by", "published_by", "required");
        $summary = $this->form_validation->set_rules("summary", "summary", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $is_active = $this->form_validation->set_rules("is_active", "is_active", "required");
        $is_notify = $this->form_validation->set_rules("is_notify", "is_notify", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");
        $department_id = $this->form_validation->set_rules("department_id", "department_id", "required");

        if($this->form_validation->run()=== FALSE){

            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($title) && !empty($start_date) && !empty($department_id) && !empty($end_date) && !empty($company_id) && !empty($location_id) && !empty($published_by) && !empty($summary) && !empty($description) && !empty($is_active) && !empty($is_notify) && !empty($created_at)){
                
                $announce = array(
                    "title" => $title,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "company_id" => $company_id,
                    "location_id" => $location_id,
                    "department_id" => $department_id,
                    "published_by" => $published_by,
                    "summary" => $summary,
                    "description" => $description,
                    "is_active" => $is_active,
                    "is_notify" => $is_notify,
                    "created_at" => $created_at
                );

                if($this->Announcement_model->add($announce)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "Announcement Created",    
                    ), REST_Controller::HTTP_OK);
    
                } else {

                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create message" ,    
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
        if(isset($data->announcement_id) || isset($data->title) || isset($data->start_date) || isset($data->end_date) || isset($data->company_id) || isset($data->location_id) || isset($data->department_id) || isset($data->published_by) || isset($data->summary) || isset($data->description) || isset($data->is_active) || isset($data->is_notify) || isset($data->created_at)){
            $id = $data->announcement_id;

            $update_announce = array(

                "title" => $data->title,
                "start_date" => $data->start_date,
                "end_date" => $data->end_date,
                "company_id" => $data->company_id,
                "location_id" => $data->location_id,
                "department_id" => $data->department_id,
                "published_by" => $data->published_by,
                "summary" => $data->summary,
                "description" => $data->description,
                "is_active" => $data->is_active,
                "is_notify" => $data->is_notify,
                "created_at" => $data->created_at
            );

            if($this->Announcement_model->update_record($update_announce, $id)){
                $this->response(array(
                    "status" => 1,
                    "message" => "Announcement updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update announcement"
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
        $announcement_id = $this->security->xss_clean($data->announcement_id);

        if($this->Announcement_model->delete_record($announcement_id)){
            $this->response(array(
                "status" => 1,
                "message" => "Announcement Deleted"
            ) , REST_Controller::HTTP_OK );

        } else{
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete Announcement"
            ) , REST_Controller::HTTP_NOT_FOUND);

        }
    }   
}
?>
