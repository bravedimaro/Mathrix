<?php
require APPPATH.'libraries/REST_Controller.php';

class clients extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Clients_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $clients = $this->Clients_model->get_all_clients();
       
        if(count($clients) > 0 ){
            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $clients
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $clients
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->client_id) || isset($data->type) || isset($data->name) || isset($data->email) || isset($data->client_username) || isset($data->client_password) || isset($data->client_profile) || isset($data->contact_number) || isset($data->company_name) || isset($data->gender) || isset($data->gender) || isset($data->last_logout_date) || isset($data->address_1) || isset($data->address_2) || isset($data->city) || isset($data->state) || isset($data->zipcode) || isset($data->country) || isset($data->website_url) || isset($data->created_at) || isset($data->last_login_date) || isset($data->is_logged_in) || isset($data->last_login_ip)){
            $id = $data->client_id;

            $updateclients = array(
                "type" =>$data->type,
                "name"=>$data->name,
                "last_login_ip" =>$data->last_login_ip,
                "is_logged_in" =>$data->is_logged_in,
                "last_login_date" =>$data->last_login_date,
                "last_logout_date" =>$data->last_logout_date,
                "client_username" =>$data->client_username,
                "client_password" =>$data->client_password,
                "client_profile" =>$data->client_profile,
                "email" => $data->email,
                "company_name" =>$data->company_name,
                "contact_number" =>$data->contact_number,
                "gender" =>$data->gender,
                "is_changed" =>$data->is_changed,
                "address_1" =>$data->address_1,
                "address_2" =>$data->address_2,
                "city" =>$data->city,
                "state" =>$data->state,
                "zipcode" =>$data->zipcode,
                "country" =>$data->country,
                "website_url" =>$data->website_url,
                "created_at"=> $data->created_at
            );

            if($this->Clients_model->update_record($updateclients, $id)){

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
        
        $type = $this->security->xss_clean($this->input->post("type"));
        $name = $this->security->xss_clean($this->input->post("name"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $client_username = $this->security->xss_clean($this->input->post("client_username"));
        $client_password = $this->security->xss_clean($this->input->post("client_password"));
        $client_profile = $this->security->xss_clean($this->input->post("client_profile"));
        $company_name = $this->security->xss_clean($this->input->post("company_name"));
        $is_changed = $this->security->xss_clean($this->input->post("is_changed"));
        $gender = $this->security->xss_clean($this->input->post("gender"));
        $contact_number = $this->security->xss_clean($this->input->post("contact_number"));
        $website_url = $this->security->xss_clean($this->input->post("website_url"));
        $address_1 = $this->security->xss_clean($this->input->post("address_1"));
        $address_2 = $this->security->xss_clean($this->input->post("address_2"));
        $city = $this->security->xss_clean($this->input->post("city"));
        $state = $this->security->xss_clean($this->input->post("state"));
        $zipcode = $this->security->xss_clean($this->input->post("zipcode"));
        $country = $this->security->xss_clean($this->input->post("country"));
        $is_logged_in = $this->security->xss_clean($this->input->post("is_logged_in"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));
        $is_logged_in = $this->security->xss_clean($this->input->post("is_logged_in"));
        $last_login_ip = $this->security->xss_clean($this->input->post("last_login_ip"));
        $last_login_date = $this->security->xss_clean($this->input->post("last_login_date"));
        $last_logout_date = $this->security->xss_clean($this->input->post("last_logout_date"));
        // $is_active = $this->security->xss_clean($this->input->post("is_active"));

        $type = $this->form_validation->set_rules("type", "Name", "required");
        $name = $this->form_validation->set_rules("name", "Type_id", "required");
        $email = $this->form_validation->set_rules("email", "Trading_id", "required");
        $client_username = $this->form_validation->set_rules("client_username", "Username", "required");
        $client_password = $this->form_validation->set_rules("client_password", "Password", "required");
        $client_profile = $this->form_validation->set_rules("client_profile", "Registration_no", "required");
        $company_name = $this->form_validation->set_rules("company_name", "company_name", "required");
        $is_changed = $this->form_validation->set_rules("is_changed", "is_changed", "required");
        $gender = $this->form_validation->set_rules("gender", "gender", "required");
        $contact_number = $this->form_validation->set_rules("contact_number", "contact_number", "required");
        $website_url = $this->form_validation->set_rules("website_url", "website_url", "required");
        $address_1 = $this->form_validation->set_rules("address_1", "address_1", "required");
        $address_2 = $this->form_validation->set_rules("address_2", "address_2", "required");
        $city = $this->form_validation->set_rules("city", "city", "required");
        $state = $this->form_validation->set_rules("state", "state", "required");
        $zipcode = $this->form_validation->set_rules("zipcode", "zipcode", "required");
        $country = $this->form_validation->set_rules("country", "country", "required");
        $is_logged_in = $this->form_validation->set_rules("is_logged_in", "is_logged_in", "required");
        $last_logout_date = $this->form_validation->set_rules("last_logout_date", "last_login_date", "required");
        $last_login_date = $this->form_validation->set_rules("last_login_date", "last_login_ip", "required");
        $last_login_ip = $this->form_validation->set_rules("last_login_ip", "last_login_ip", "required");
        $is_logged_in = $this->form_validation->set_rules("is_logged_in", "is_logged_in", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");
        // $is_active = $this->form_validation->set_rules("is_active", "is_active", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($type) && !empty($name) && !empty($email) && !empty($client_username) && !empty($client_password) && !empty($client_profile) && !empty($company_name) && !empty($is_changed) && !empty($gender) && !empty($contact_number) && !empty($website_url) && !empty($address_1) && !empty($address_2) && !empty($city) && !empty($state) && !empty($zipcode) && !empty($country) && !empty($is_logged_in) && !empty($last_login_date) && !empty($last_logout_date) && !empty($last_login_ip) && !empty($created_at)){
                $data = array(
                    "name" => $name,
                    "type" =>$type,
                    "email" =>$email,
                    "client_username" => $client_username,
                    "client_password" => $client_password,
                    "client_profile" =>$client_profile,
                    "company_name" => $company_name,
                    "is_changed" => $is_changed,
                    "gender" => $gender,
                    "contact_number" => $contact_number,
                    "website_url" => $website_url,
                    "address_1" => $address_1,
                    "address_2" => $address_2,
                    "city" => $city,
                    "state" => $state,
                    "zipcode" => $zipcode,
                    "country" => $country,
                    "last_login_ip" => $last_login_ip,
                    "is_logged_in" =>$is_logged_in,
                    "last_logout_date" =>$last_logout_date,
                    "last_login_date" =>$last_login_date,

                    "created_at" => $created_at                  
                );

                if($this->Clients_model->add($data)){
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

        if($this->Clients_model->delete_record($company_id)){
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

