<?php
require APPPATH.'libraries/REST_Controller.php';

class elogin extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Elogin_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        
        $data = json_decode(file_get_contents("php://input"));
        $id = $this->security->xss_clean($data->designation_id);

        // $designation = $this->Elogin_model->get_designation();
        if($this->Elogin_model->read_setting_info($id)){
            $this->response(array(
                "status" => 1,
                "message" => "Assets found",
                "data" => $designation
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete Assets"
              ) , REST_Controller::HTTP_NOT_FOUND);

        }

    }
    
    // public function index_post(){
        
    //     $top_designation_id = $this->security->xss_clean($this->input->post("top_designation_id"));
    //     $department_id = $this->security->xss_clean($this->input->post("department_id"));
    //     $sub_department_id = $this->security->xss_clean($this->input->post("sub_department_id"));
    //     $company_id = $this->security->xss_clean($this->input->post("company_id"));
    //     $designation_name = $this->security->xss_clean($this->input->post("designation_name"));
    //     $status = $this->security->xss_clean($this->input->post("status"));
    //     $created_at = $this->security->xss_clean($this->input->post("created_at"));
    //     $added_by = $this->security->xss_clean($this->input->post("added_by"));

    //     $top_designation_id = $this->form_validation->set_rules("top_designation_id", "top_designation_id", "required");
    //     $department_id = $this->form_validation->set_rules("department_id", "department_id", "required");
    //     $sub_department_id = $this->form_validation->set_rules("sub_department_id", "company_assets_code", "required");
    //     $company_id = $this->form_validation->set_rules("company_id", "Name", "required");
    //     $designation_name = $this->form_validation->set_rules("designation_name", "designation_name$designation_name", "required");
    //     $status = $this->form_validation->set_rules("status", "status", "required");
    //     $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");
    //     $added_by = $this->form_validation->set_rules("added_by", "added_by", "required");

    //     if($this->form_validation->run() === FALSE){

    //         // we have some errors
    //         $this->response(array(
    //           "status" => 0,
    //           "message" => "All fields are needed"
    //         ) , REST_Controller::HTTP_NOT_FOUND);

    //     } else {
    //         if(!empty($top_designation_id) && !empty($department_id) && !empty($sub_department_id)  && !empty($company_id) && !empty($designation_name) && !empty($status) && !empty($added_by) && !empty($created_at)){
                
    //             $designationdd = array(
    
    //                 "top_designation_id" => $top_designation_id,
    //                 "department_id" => $department_id,
    //                 "sub_department_id" => $sub_department_id,
    //                 "company_id" => $company_id,
    //                 "designation_name" => $designation_name,
    //                 "status" => $status,
    //                 "added_by" => $added_by,
    //                 "created_at" => $created_at,
    //             );
    
    //             if($this->Elogin_model->add($designationdd)){
                    
    //                 $this->response(array(
    //                     "status" => 1,
    //                     "message" => "Asset created" ,    
    //                 ), REST_Controller::HTTP_OK);
    
    //             } else {
                    
    //                 $this->response(array(
    //                     "status" => 0,
    //                     "message" => "Failed to create Asset" ,    
    //                 ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    
    //             }
                
    //         } else {
                
    //             $this->response(array(
    //                 "status" => 0,
    //                 "Message" => "fields cannot be empty"
    //             ), REST_Controller::HTTP_NOT_FOUND);

    //         }
    //     }


    // }

    // public function index_delete(){
    //     $data = json_decode(file_get_contents("php://input"));
    //     $id = $this->security->xss_clean($data->designation_id);

    //     if($this->Elogin_model->delete_record($id)){
    //         $this->response(array(
    //             "status" => 1,
    //             "message" => "Assets deleted"
    //           ) , REST_Controller::HTTP_OK );

    //     } else {
    //         $this->response(array(
    //             "status" => 0,
    //             "message" => "failed to delete Assets"
    //           ) , REST_Controller::HTTP_NOT_FOUND);

    //     }
    // }

    // public function index_put(){

    //     $data = json_decode(file_get_contents("php://input"));
        
    //     if(isset($data->designation_id) || isset($data->top_designation_id) || isset($data->department_id) || isset($data->sub_department_id) || isset($data->company_id) || isset($data->designation_name) || isset($data->status) || isset($data->added_by) || isset($data->created_at)){
    //         $designation_id = $data->designation_id;
            
    //         $update_department = array(
                
    //             "top_designation_id" => $data->top_designation_id,
    //             "department_id" => $data->department_id,
    //             "sub_department_id" => $data->sub_department_id,
    //             "company_id" => $data->company_id,
    //             "designation_name" => $data->designation_name,
    //             "added_by" => $data->added_by,
    //             "status" => $data->status,
    //             "created_at" => $data->created_at,

    //         );

    //         if($this->Elogin_model->update_record($update_department, $designation_id)){
    //             $this->response(array(
    //                 "status" => 1,
    //                 "message" => "Awards updated successfully"
    //             ), REST_Controller::HTTP_OK);

    //         } else{

    //             $this->response(array(
    //                 "status" => 0,
    //                 "message" => "Failed to update Asset"
    //             ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    //         }

    //     } else{
    //         $this->response(array(
    //             "status" => 0,
    //             "message" => "All fields are needed"
    //         ), REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }

}

?>