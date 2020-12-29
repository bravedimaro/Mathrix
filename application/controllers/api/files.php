<?php
require APPPATH.'libraries/REST_Controller.php';

class files extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Files_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $files = $this->Files_model->get_file();
       
        if(count($files) > 0 ){
            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $files
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $files
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->file_id) || isset($data->user_id) || isset($data->department_id) || isset($data->file_name) || isset($data->file_size) || isset($data->file_extension) || isset($data->created_at)){
            $id = $data->file_id;

            $update_files = array(
                "department_id" =>$data->department_id,
                "user_id" =>$data->user_id,
                "file_name"=>$data->file_name,
                "file_size" =>$data->file_size,
                "file_extension" =>$data->file_extension,
                "created_at"=> $data->created_at
            );

            if($this->Files_model->update_record($update_files, $id)){

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
        
        $user_id = $this->security->xss_clean($this->input->post("user_id"));
        $department_id = $this->security->xss_clean($this->input->post("department_id"));
        $file_name = $this->security->xss_clean($this->input->post("file_name"));
        $file_size = $this->security->xss_clean($this->input->post("file_size"));
        $file_extension = $this->security->xss_clean($this->input->post("file_extension"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $user_id = $this->form_validation->set_rules("user_id", "user_id", "required");
        $department_id = $this->form_validation->set_rules("department_id", "department_id", "required");
        $file_name = $this->form_validation->set_rules("file_name", "file_name", "required");
        $file_size = $this->form_validation->set_rules("file_size", "file_size", "required");
        $file_extension = $this->form_validation->set_rules("file_extension", "file_extension", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($user_id) && !empty($department_id) && !empty($file_name) && !empty($file_size) && !empty($file_extension) && !empty($created_at)){
           
                $data = array(
                    "department_id" => $department_id,
                    "user_id" =>$user_id,
                    "file_name" =>$file_name,
                    "file_size" => $file_size,
                    "file_extension" => $file_extension,
                    "created_at" => $created_at                  
                );

                if($this->Files_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "Company created" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to create message" ,    
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
        $file_id = $this->security->xss_clean($data->file_id);

        if($this->Files_model->delete_record($file_id)){
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

