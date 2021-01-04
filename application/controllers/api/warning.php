<?php
require APPPATH.'libraries/REST_Controller.php';

class warning extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Warning_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $warning = $this->Warning_model->all_warnings();
       
        if(count($warning) > 0 ){
            $this->response(array(
                "status" => 1,
                "message" => "Records found",
                "data" => $warning
            ), REST_Controller::HTTP_OK);

        } else {
            $this->response(array(
                "status" => 0,
                "message" => "No Records found",
                "data" => $warning
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->warning_id) || isset($data->warning_to) || isset($data->company_id) || isset($data->warning_by) || isset($data->warning_date) || isset($data->warning_type_id) || isset($data->attachment) || isset($data->subject) || isset($data->description) || isset($data->status) || isset($data->created_at)){
            $id = $data->warning_id;

            $update_warning = array(
                "company_id" =>$data->company_id,
                "warning_to" =>$data->warning_to,
                "warning_by"=>$data->warning_by,
                "warning_date" =>$data->warning_date,
                "warning_type_id" =>$data->warning_type_id,
                "attachment" =>$data->attachment,
                "subject" =>$data->subject,
                "description" => $data->description,
                "status" =>$data->status,
                "created_at"=> $data->created_at
            );

            if($this->Warning_model->update_record($update_warning, $id)){

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
        
        $warning_to = $this->security->xss_clean($this->input->post("warning_to"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $warning_by = $this->security->xss_clean($this->input->post("warning_by"));
        $warning_date = $this->security->xss_clean($this->input->post("warning_date"));
        $warning_type_id = $this->security->xss_clean($this->input->post("warning_type_id"));
        $attachment = $this->security->xss_clean($this->input->post("attachment"));
        $subject = $this->security->xss_clean($this->input->post("subject"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $warning_to = $this->form_validation->set_rules("warning_to", "warning_to", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $warning_by = $this->form_validation->set_rules("warning_by", "warning_by", "required");
        $warning_date = $this->form_validation->set_rules("warning_date", "warning_date", "required");
        $warning_type_id = $this->form_validation->set_rules("warning_type_id", "warning_type_id", "required");
        $attachment = $this->form_validation->set_rules("attachment", "attachment", "required");
        $subject = $this->form_validation->set_rules("subject", "subject", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $status = $this->form_validation->set_rules("status", "status", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($warning_to) && !empty($company_id) && !empty($warning_by) && !empty($warning_date) && !empty($warning_type_id) && !empty($attachment) && !empty($subject) && !empty($description) && !empty($status) && !empty($created_at)){
           
                $data = array(
                    "company_id" => $company_id,
                    "warning_to" =>$warning_to,
                    "warning_by" =>$warning_by,
                    "warning_date" => $warning_date,
                    "warning_type_id" => $warning_type_id,
                    "attachment" =>$attachment,
                    "subject" => $subject,
                    "description" => $description,
                    "status" => $status,
                    "created_at" => $created_at                  
                );

                if($this->Warning_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "Record created" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create record" ,    
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
        $warning_id = $this->security->xss_clean($data->warning_id);

        if($this->Warning_model->delete_record($warning_id)){
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

