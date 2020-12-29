<?php
require APPPATH.'libraries/REST_Controller.php';

class tax extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Tax_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $tax = $this->Tax_model->get_all_taxes();
       
        if(count($tax) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $tax
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $tax
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->tax_id) || isset($data->name) || isset($data->rate) || isset($data->type) || isset($data->description) || isset($data->created_at)){
            $id = $data->tax_id;

            $update_tax = array(
                "rate" =>$data->rate,
                "name" =>$data->name,
                "type"=>$data->type,
                "description" =>$data->description,
                "created_at"=> $data->created_at
            );

            if($this->Tax_model->update_tax_record($update_tax, $id)){

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
        $rate = $this->security->xss_clean($this->input->post("rate"));
        $type = $this->security->xss_clean($this->input->post("type"));
        $description = $this->security->xss_clean($this->input->post("description"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $name = $this->form_validation->set_rules("name", "name", "required");
        $rate = $this->form_validation->set_rules("rate", "rate", "required");
        $type = $this->form_validation->set_rules("type", "type", "required");
        $description = $this->form_validation->set_rules("description", "description", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($name) && !empty($rate) && !empty($type) && !empty($description) && !empty($created_at)){
           
                $data = array(
                    "rate" => $rate,
                    "name" =>$name,
                    "type" =>$type,
                    "description" => $description,
                    "created_at" => $created_at                  
                );

                if($this->Tax_model->add_tax_record($data)){
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
        $tax_id = $this->security->xss_clean($data->tax_id);

        if($this->Tax_model->delete_tax_record($tax_id)){
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

