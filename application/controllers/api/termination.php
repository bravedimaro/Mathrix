<?php
require APPPATH.'libraries/REST_Controller.php';

class termination extends REST_Controller{
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Termination_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $terminate = $this->Termination_model->get_termination();

        if(count($terminate) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Announcement found",
                "data" => $terminate
            ), REST_Controller::HTTP_OK);

        }else{
      
            $this->response(array(
                "status" => 0,
                "message" => "No Announcement found",
                "data" => $terminate
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    public function index_post(){

        $termination_type_id = $this->security->xss_clean($this->input->post("termination_type_id"));
        $terminated_by = $this->security->xss_clean($this->input->post("terminated_by"));
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $termination_date = $this->security->xss_clean($this->input->post("termination_date"));
        $notice_date = $this->security->xss_clean($this->input->post("notice_date"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $attachment = $this->security->xss_clean($this->input->post("attachment"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $termination_type_id = $this->form_validation->set_rules("termination_type_id", "termination_type_id", "required");
        $terminated_by = $this->form_validation->set_rules("terminated_by", "terminated_by", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $termination_date = $this->form_validation->set_rules("termination_date", "termination_date", "required");
        $notice_date = $this->form_validation->set_rules("notice_date", "notice_date", "required");
        $status = $this->form_validation->set_rules("status", "status", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $attachment = $this->form_validation->set_rules("attachment", "attachment", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run()=== FALSE){

            $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($termination_type_id) && !empty($terminated_by) && !empty($department_id) && !empty($employee_id) && !empty($company_id) && !empty($termination_date) && !empty($notice_date) && !empty($status) && !empty($description) && !empty($attachment) &&  !empty($created_at)){
                
                $terminate_add = array(
                    "termination_type_id" => $termination_type_id,
                    "terminated_by" => $terminated_by,
                    "employee_id" => $employee_id,
                    "company_id" => $company_id,
                    "termination_date" => $termination_date,
                    "department_id" => $department_id,
                    "notice_date" => $notice_date,
                    "status" => $status,
                    "description" => $description,
                    "attachment" => $attachment,
                    "created_at" => $created_at
                );

                if($this->Termination_model->add($terminate_add)){
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
        if(isset($data->termination_id) || isset($data->termination_type_id) || isset($data->terminated_by) || isset($data->employee_id) || isset($data->company_id) || isset($data->termination_date) || isset($data->notice_date) || isset($data->status) || isset($data->description) || isset($data->attachment)  || isset($data->created_at)){
            $id = $data->termination_id;

            $update_terminate = array(

                "termination_type_id" => $data->termination_type_id,
                "terminated_by" => $data->terminated_by,
                "employee_id" => $data->employee_id,
                "company_id" => $data->company_id,
                "termination_date" => $data->termination_date,
                "department_id" => $data->department_id,
                "notice_date" => $data->notice_date,
                "status" => $data->status,
                "description" => $data->description,
                "attachment" => $data->attachment,
                "created_at" => $data->created_at
            );

            if($this->Termination_model->update_record($update_terminate, $id)){
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
        $termination_id = $this->security->xss_clean($data->termination_id);

        if($this->Termination_model->delete_record($termination_id)){
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
