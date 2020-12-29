<?php
require APPPATH.'libraries/REST_Controller.php';

class complaints extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Complaints_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $complains = $this->Complaints_model->get_complain();

        if(count($complains) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $complains
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Assets found",
                "data" => $complains
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function index_post(){
        
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $complaint_from = $this->security->xss_clean($this->input->post("complaint_from"));
        $title = $this->security->xss_clean($this->input->post("title"));
        $complaint_date = $this->security->xss_clean($this->input->post("complaint_date"));
        $complaint_against = $this->security->xss_clean($this->input->post("complaint_against"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $attachment = $this->security->xss_clean($this->input->post("attachment"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $complaint_from = $this->form_validation->set_rules("complaint_from", "complaint_from", "required");
        $title = $this->form_validation->set_rules("title", "company_assets_code", "required");
        $complaint_date = $this->form_validation->set_rules("complaint_date", "Name", "required");
        $complaint_against = $this->form_validation->set_rules("complaint_against", "complaint_against", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $attachment = $this->form_validation->set_rules("attachment", "Manufacturer", "required");
        $status = $this->form_validation->set_rules("status", "Serial_number", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($company_id) && !empty($complaint_from) && !empty($title)  && !empty($complaint_date) && !empty($complaint_against) && !empty($description) && !empty($attachment) && !empty($status) && !empty($description) && !empty($asset_note) && !empty($asset_image) && !empty($is_working) && !empty($created_at)){
                
                $complainadd = array(
    
                    "company_id" => $company_id,
                    "complaint_from" => $complaint_from,
                    "title" => $title,
                    "complaint_date" => $complaint_date,
                    "complaint_against" => $complaint_against,
                    "description" => $description,
                    "attachment" => $attachment,
                    "status" => $status,
                    "created_at" => $created_at,
                );
    
                if($this->Complaints_model->add($complainadd)){
                    
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
        $id = $this->security->xss_clean($data->complaint_id);

        if($this->Complaints_model->delete_record($id)){
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
        
        if(isset($data->complaint_id) || isset($data->company_id)  || isset($data->complaint_from) || isset($data->title) || isset($data->complaint_date) || isset($data->complaint_against) || isset($data->description)  || isset($data->attachment) || isset($data->status)  || isset($data->created_at)){
            $complaint_id = $data->complaint_id;
            
            $update_complain = array(
                
                "company_id" => $data->company_id,
                "complaint_from" => $data->complaint_from,
                "title" => $data->title,
                "complaint_date" => $data->complaint_date,
                "complaint_against" => $data->complaint_against,
                "description" => $data->description,
                "attachment" => $data->attachment,
                "status" => $data->status,
                "created_at" => $data->created_at,

            );

            if($this->Complaints_model->update_record($update_complain, $complaint_id)){
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