<?php
require APPPATH.'libraries/REST_Controller.php';

class chat extends REST_controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Chat_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
    
        $chat = $this->Chat_model->get_message();

        if(count($chat) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $chat
            ), REST_Controller::HTTP_OK);
    
        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Message found",
                "data" => $chat
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post(){
        
        $from_id = $this->security->xss_clean($this->input->post("from_id"));
        $to_id = $this->security->xss_clean($this->input->post("to_id"));
        $message_from = $this->security->xss_clean($this->input->post("message_from"));
        $message_content = $this->security->xss_clean($this->input->post("message_content"));
        $message_type = $this->security->xss_clean($this->input->post("message_type"));
        $department_id = $this->security->xss_clean($this->input->post("department_id"));
        $location_id = $this->security->xss_clean($this->input->post("location_id"));

        $from_id = $this->form_validation->set_rules("from_id", "From_id", "required");
        $to_id = $this->form_validation->set_rules("to_id", "To_id", "required");
        $message_from = $this->form_validation->set_rules("message_from", "Message_From", "required");
        $message_content = $this->form_validation->set_rules("message_content", "Message_content", "required");
        $message_type = $this->form_validation->set_rules("message_type", "Message_type", "required");
        $department_id = $this->form_validation->set_rules("department_id", "Department_id", "required");
        $location_id = $this->form_validation->set_rules("location_id", "Message_content", "required");

        if($this->form_validation->run() === FALSE){

            // we have some errors
            $this->response(array(
              "status" => 0,
              "message" => "All fields are needed"
            ) , REST_Controller::HTTP_NOT_FOUND);

        } else {
            if(!empty($from_id) && !empty($to_id) && !empty($message_from) && !empty($message_content)  && !empty($message_type) && !empty($department_id) && !empty($location_id)){
                
                $chat = array(
    
                    "from_id" => $from_id,
                    "to_id" => $to_id,
                    "message_from" => $message_from,
                    "message_content" => $message_content,
                    "message_type" => $message_type,
                    "department_id" => $department_id,
                    "location_id" => $location_id
                );
    
                if($this->Chat_model->add_chat($chat)){
                    
                    $this->response(array(
                        "status" => 1,
                        "message" => "Message created" ,    
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
                    "Message" => "fields cannot be empty"
                ), REST_Controller::HTTP_NOT_FOUND);

            }
        }


    }

    public function index_delete(){
        $data = json_decode(file_get_contents("php://input"));
        $id = $this->security->xss_clean($data->message_id);

        if($this->Chat_model->delete_chat($id)){
            $this->response(array(
                "status" => 1,
                "message" => "chat deleted"
              ) , REST_Controller::HTTP_OK );

        } else {
            $this->response(array(
                "status" => 0,
                "message" => "failed to delete message"
              ) , REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_put(){

        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->message_id) || isset($data->from_id) || isset($data->to_id) || isset($data->message_from) || isset($data->message_content) && isset($data->message_type) || isset($data->department_id) || isset($data->location_id)){
            $message_id = $data->message_id;
            
            $update_chat = array(
                "from_id"=>$data->from_id,
                "to_id"=>$data->to_id,
                "message"=>$data->message_from,
                "message_content"=>$data->message_content,
                "message_type"=>$data->message_type,
                "department_id"=>$data->department_id,
                "location_id"=>$data->location_id 
            );

            if($this->Chat_model->update_chat($message_id, $update_chat)){
                $this->response(array(
                    "status" => 1,
                    "message" => "Chat updated successfully"
                ), REST_Controller::HTTP_OK);

            } else{

                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update chat"
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