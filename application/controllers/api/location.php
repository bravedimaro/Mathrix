<?php
require APPPATH.'libraries/REST_Controller.php';

class location extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Location_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $location = $this->Location_model->all_office_locations();
       
        if(count($location) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Location found",
                "data" => $location
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Location found",
                "data" => $location
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->location_id) || isset($data->company_id) || isset($data->location_head) || isset($data->location_manager) || isset($data->location_name) || isset($data->email) || isset($data->phone) || isset($data->fax) || isset($data->address_1) || isset($data->address_2) || isset($data->city) || isset($data->state) || isset($data->zipcode) || isset($data->country) || isset($data->added_by) || isset($data->status) || isset($data->created_at)){
            $id = $data->location_id;

            $update_location = array(
                
                "location_head" =>$data->location_head,
                "company_id" =>$data->company_id,
                "location_manager"=>$data->location_manager,
                "location_name" =>$data->location_name,
                "email" =>$data->email,
                "phone" =>$data->phone,
                "fax" =>$data->fax,
                "address_1" =>$data->address_1,
                "address_2" =>$data->address_2,
                "city" =>$data->city,
                "state" =>$data->state,
                "zipcode" =>$data->zipcode,
                "country" =>$data->country,
                "added_by" =>$data->added_by,
                "status" =>$data->status,
                "created_at"=> $data->created_at
            );

            if($this->Location_model->update_record($update_location, $id)){

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
        $location_head = $this->security->xss_clean($this->input->post("location_head"));
        $location_manager = $this->security->xss_clean($this->input->post("location_manager"));
        $location_name = $this->security->xss_clean($this->input->post("location_name"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $phone = $this->security->xss_clean($this->input->post("phone"));
        $fax = $this->security->xss_clean($this->input->post("fax"));
        $address_1 = $this->security->xss_clean($this->input->post("address_1"));
        $address_2 = $this->security->xss_clean($this->input->post("address_2"));
        $city = $this->security->xss_clean($this->input->post("city"));
        $state = $this->security->xss_clean($this->input->post("state"));
        $zipcode = $this->security->xss_clean($this->input->post("zipcode"));
        $country = $this->security->xss_clean($this->input->post("country"));
        $added_by = $this->security->xss_clean($this->input->post("added_by"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $location_head = $this->form_validation->set_rules("location_head", "location_head", "required");
        $location_manager = $this->form_validation->set_rules("location_manager", "location_manager", "required");
        $email = $this->form_validation->set_rules("email", "email", "required");
        $phone = $this->form_validation->set_rules("phone", "phone", "required");
        $fax = $this->form_validation->set_rules("fax", "fax", "required");
        $address_1 = $this->form_validation->set_rules("address_1", "address_1", "required");
        $address_2 = $this->form_validation->set_rules("address_2", "address_2", "required");
        $city = $this->form_validation->set_rules("city", "city", "required");
        $state = $this->form_validation->set_rules("state", "state", "required");
        $zipcode = $this->form_validation->set_rules("zipcode", "zipcode", "required");
        $country = $this->form_validation->set_rules("country", "country", "required");
        $added_by = $this->form_validation->set_rules("added_by", "added_by", "required");
        $status = $this->form_validation->set_rules("status", "status", "");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($company_id) && !empty($location_head) && !empty($location_manager) && !empty($location_name) && !empty($email) && !empty($phone) && !empty($fax) && !empty($address_1) && !empty($address_2) && !empty($city) && !empty($state) && !empty($zipcode) && !empty($country) && !empty($added_by) && !empty($status) && !empty($created_at)){
           
                $data = array(
                    "location_head" => $location_head,
                    "company_id" =>$company_id,
                    "location_manager" =>$location_manager,
                    "location_name" => $location_name,
                    "email" => $email,
                    "phone" => $phone,
                    "fax" => $fax,
                    "address_1" => $address_1,
                    "address_2" => $address_2,
                    "city" => $city,
                    "state" => $state,
                    "zipcode" => $zipcode,
                    "country" => $country,
                    "added_by" => $added_by,
                    "created_at" => $created_at                  
                );

                if($this->Location_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "New Location Added" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to add new Location" ,    
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
        $location_id = $this->security->xss_clean($data->location_id);

        if($this->Location_model->delete_record($location_id)){
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

