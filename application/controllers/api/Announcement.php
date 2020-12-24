<?php
require APPPATH.'libraries/REST_Controller.php';

class announcement extends REST_Controller{
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Announcement_model"));
        // $this->load->library(array("form_validation"));
        // $this->load->helper("security");
      }

    function index_get(){
        $announcement = $this->Announcement_model->get_new_announcements();

        if(count($announcement) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Announcement found",
                "data" => $announcement
            ), REST_Controller::HTTP_OK);

        }else{
      
            $this->response(array(
                "status" => 0,
                "message" => "No Announcement found",
                "data" => $announcement
            ), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    function index_post(){
        echo "security";
    }
    // function 
}
?>
