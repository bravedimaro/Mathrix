<?php
require APPPATH.'libraries/REST_Controller.php';

class company extends REST_Controller{
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Company_model"));
        // $this->load->library(array("form_validation"));
        // $this->load->helper("security");
      }

      function index_get(){
        $announcement = $this->Company_model->get_companies();

        // print_r($query->result());

        if(count($announcement) > 0){

            $this->response(array(
                "status" => 1,
                "message" => "Company found",
                "data" => $announcement
            ), REST_Controller::HTTP_OK);

        }else{
      
            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $announcement
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    function get_company_types(){
        $company = $this->Company_model->get_company_types();

        
        $this->response(array(
            "status" => 1,
            "message" => "Students found",
            "data" => $company
        ), REST_Controller::HTTP_OK);
    }

    }
?>