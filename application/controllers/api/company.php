<?php
require APPPATH.'libraries/REST_Controller.php';

class company extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Company_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $company = $this->Company_model->get_companies();
       
        if(count($company) > 0 ){
            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $company
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $company
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->company_id) || isset($data->name) || isset($data->type_id) || isset($data->trading_id) || isset($data->username) || isset($data->password) || isset($data->registration_no) || isset($data->government_tax) || isset($data->email) || isset($data->logo) || isset($data->contact_number) || isset($data->website_url) && isset($data->address_1) || isset($data->address_2) || isset($data->city) || isset($data->state) || isset($data->zipcode) || isset($data->country) || isset($data->created_by) || isset($data->created_at)){
            $id = $data->company_id;

            $updatecompany = array(
                "type_id" =>$data->type_id,
                "name" =>$data->name,
                "trading_name"=>$data->trading_id,
                "username" =>$data->username,
                "password" =>$data->password,
                "registration_no" =>$data->registration_no,
                "government_tax" =>$data->government_tax,
                "email" => $data->email,
                "logo" =>$data->logo,
                "contact_number" =>$data->contact_number,
                "website_url" =>$data->website_url,
                "address_1" =>$data->address_1,
                "address_2" =>$data->address_2,
                "city" =>$data->city,
                "state" =>$data->state,
                "zipcode" =>$data->zipcode,
                "country" =>$data->country,
                "added_by" =>$data->created_by,
                "created_at"=> $data->created_at
            );

            if($this->Company_model->update_record($updatecompany, $id)){

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
        
        $name = $this->security->xss_clean($this->input->post("name"));
        $type_id = $this->security->xss_clean($this->input->post("type_id"));
        $trading_id = $this->security->xss_clean($this->input->post("trading_id"));
        $username = $this->security->xss_clean($this->input->post("username"));
        $password = $this->security->xss_clean($this->input->post("password"));
        $registration_no = $this->security->xss_clean($this->input->post("registration_no"));
        $government_tax = $this->security->xss_clean($this->input->post("government_tax"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $logo = $this->security->xss_clean($this->input->post("logo"));
        $contact_number = $this->security->xss_clean($this->input->post("contact_number"));
        $website_url = $this->security->xss_clean($this->input->post("website_url"));
        $address_1 = $this->security->xss_clean($this->input->post("address_1"));
        $address_2 = $this->security->xss_clean($this->input->post("address_2"));
        $city = $this->security->xss_clean($this->input->post("city"));
        $state = $this->security->xss_clean($this->input->post("state"));
        $zipcode = $this->security->xss_clean($this->input->post("zipcode"));
        $country = $this->security->xss_clean($this->input->post("country"));
        $created_by = $this->security->xss_clean($this->input->post("added_by"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));
        // $is_active = $this->security->xss_clean($this->input->post("is_active"));

        $name = $this->form_validation->set_rules("name", "Name", "required");
        $type_id = $this->form_validation->set_rules("type_id", "Type_id", "required");
        $trading_id = $this->form_validation->set_rules("trading_id", "Trading_id", "required");
        $username = $this->form_validation->set_rules("username", "Username", "required");
        $password = $this->form_validation->set_rules("password", "Password", "required");
        $registration_no = $this->form_validation->set_rules("registration_no", "Registration_no", "required");
        $government_tax = $this->form_validation->set_rules("government_tax", "government_tax", "required");
        $email = $this->form_validation->set_rules("email", "email", "required");
        $logo = $this->form_validation->set_rules("logo", "logo", "required");
        $contact_number = $this->form_validation->set_rules("contact_number", "contact_number", "required");
        $website_url = $this->form_validation->set_rules("website_url", "website_url", "required");
        $address_1 = $this->form_validation->set_rules("address_1", "address_1", "required");
        $address_2 = $this->form_validation->set_rules("address_2", "address_2", "required");
        $city = $this->form_validation->set_rules("city", "city", "required");
        $state = $this->form_validation->set_rules("state", "state", "required");
        $zipcode = $this->form_validation->set_rules("zipcode", "zipcode", "required");
        $country = $this->form_validation->set_rules("country", "country", "required");
        $created_by = $this->form_validation->set_rules("added_by", "added_by", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");
        // $is_active = $this->form_validation->set_rules("is_active", "is_active", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($name) && !empty($type_id) && !empty($trading_id) && !empty($username) && !empty($password) && !empty($registration_no) && !empty($government_tax) && !empty($email) && !empty($logo) && !empty($contact_number) && !empty($website_url) && !empty($address_1) && !empty($address_2) && !empty($city) && !empty($state) && !empty($zipcode) && !empty($country) && !empty($created_by) && !empty($created_at)){
                $data = array(
                    "type_id" => $type_id,
                    "name" =>$name,
                    "trading_name" =>$trading_id,
                    "username" => $username,
                    "password" => $password,
                    "registration_no" =>$registration_no,
                    "government_tax" => $government_tax,
                    "email" => $email,
                    "logo" => $logo,
                    "contact_number" => $contact_number,
                    "website_url" => $website_url,
                    "address_1" => $address_1,
                    "address_2" => $address_2,
                    "city" => $city,
                    "state" => $state,
                    "zipcode" => $zipcode,
                    "country" => $country,
                    // "is_active" => $is_active,
                    "added_by" =>$added_by,
                    "created_at" => $created_at                  
                );

                if($this->Company_model->add($data)){
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
        $company_id = $this->security->xss_clean($data->company_id);

        if($this->Company_model->delete_record($company_id)){
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

