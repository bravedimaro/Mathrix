<?php
require APPPATH.'libraries/REST_Controller.php';

class performance extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Performance_appraisal_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $performance = $this->Performance_appraisal_model->get_performance_appraisals();
       
        if(count($performance) > 0 ){

            $this->response(array(
                "status" => 1,
                "message" => "Record found",
                "data" => $performance
            ), REST_Controller::HTTP_OK);

        } else {

            $this->response(array(
                "status" => 0,
                "message" => "No Record found",
                "data" => $performance
            ), REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function index_single_get(){
        // $isbn = $this->get
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->performance_appraisal_id) || isset($data->company_id) || isset($data->employee_id) || isset($data->appraisal_year_month) || isset($data->customer_experience) || isset($data->marketing) || isset($data->management) || isset($data->administration) || isset($data->presentation_skill) || isset($data->quality_of_work) || isset($data->efficiency) || isset($data->integrity) || isset($data->professionalism) || isset($data->team_work) || isset($data->critical_thinking) || isset($data->conflict_management) || isset($data->attendence) || isset($data->ability_to_meet_deadline) || isset($data->remarks) || isset($data->added_by) || isset($data->created_at)){
            $id = $data->performance_appraisal_id;

            $update_performance = array(
                
                "employee_id" =>$data->employee_id,
                "company_id" =>$data->company_id,
                "appraisal_year_month"=>$data->appraisal_year_month,
                "customer_experience" =>$data->customer_experience,
                "marketing" =>$data->marketing,
                "management" =>$data->management,
                "administration" =>$data->administration,
                "presentation_skill" =>$data->presentation_skill,
                "quality_of_work" =>$data->quality_of_work,
                "efficiency" =>$data->efficiency,
                "integrity" =>$data->integrity,
                "professionalism" =>$data->professionalism,
                "team_work" =>$data->team_work,
                "critical_thinking" =>$data->critical_thinking,
                "conflict_management" =>$data->conflict_management,
                "attendance" =>$data->attendance,
                "ability_to_meet_deadline" =>$data->ability_to_meet_deadline,
                "remarks" =>$data->remarks,
                "added_by" =>$data->added_by,
                "created_at"=> $data->created_at
            );

            if($this->Performance_appraisal_model->update_record($update_performance, $id)){

                $this->response(array(
                    "status" => 1,
                    "message" => "Record updated successfully"
                ), REST_Controller::HTTP_OK);

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => "Failed to update Record"
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
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $appraisal_year_month = $this->security->xss_clean($this->input->post("appraisal_year_month"));
        $customer_experience = $this->security->xss_clean($this->input->post("customer_experience"));
        $marketing = $this->security->xss_clean($this->input->post("marketing"));
        $management = $this->security->xss_clean($this->input->post("management"));
        $administration = $this->security->xss_clean($this->input->post("administration"));
        $presentation_skill = $this->security->xss_clean($this->input->post("presentation_skill"));
        $quality_of_work = $this->security->xss_clean($this->input->post("quality_of_work"));
        $efficiency = $this->security->xss_clean($this->input->post("efficiency"));
        $integrity = $this->security->xss_clean($this->input->post("integrity"));
        $professionalism = $this->security->xss_clean($this->input->post("professionalism"));
        $team_work = $this->security->xss_clean($this->input->post("team_work"));
        $critical_thinking = $this->security->xss_clean($this->input->post("critical_thinking"));
        $conflict_management = $this->security->xss_clean($this->input->post("conflict_management"));
        $attendance = $this->security->xss_clean($this->input->post("attendance"));
        $ability_to_meet_deadline = $this->security->xss_clean($this->input->post("ability_to_meet_deadline"));
        $remarks = $this->security->xss_clean($this->input->post("remarks"));
        $added_by = $this->security->xss_clean($this->input->post("added_by"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $appraisal_year_month = $this->form_validation->set_rules("appraisal_year_month", "appraisal_year_month", "required");
        $marketing = $this->form_validation->set_rules("marketing", "marketing", "required");
        $management = $this->form_validation->set_rules("management", "management", "required");
        $presentation_skill = $this->form_validation->set_rules("presentation_skill", "presentation_skill", "required");
        $quality_of_work = $this->form_validation->set_rules("quality_of_work", "quality_of_work", "required");
        $efficiency = $this->form_validation->set_rules("efficiency", "efficiency", "required");
        $integrity = $this->form_validation->set_rules("integrity", "integrity", "required");
        $professionalism = $this->form_validation->set_rules("professionalism", "professionalism", "required");
        $team_work = $this->form_validation->set_rules("team_work", "team_work", "required");
        $critical_thinking = $this->form_validation->set_rules("critical_thinking", "critical_thinking", "required");
        $conflict_management = $this->form_validation->set_rules("conflict_management", "conflict_management", "required");
        $attendance = $this->form_validation->set_rules("attendance", "attendance", "required");
        $ability_to_meet_deadline = $this->form_validation->set_rules("ability_to_meet_deadline", "ability_to_meet_deadline", "required");
        $remarks = $this->form_validation->set_rules("remarks", "remarks", "required");
        $added_by = $this->form_validation->set_rules("added_by", "added_by", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($company_id) && !empty($employee_id) && !empty($appraisal_year_month) && !empty($customer_experience) && !empty($marketing) && !empty($management) && !empty($administration) && !empty($presentation_skill) && !empty($quality_of_work) && !empty($efficiency) && !empty($integrity) && !empty($professionalism) && !empty($team_work) && !empty($critical_thinking) && !empty($conflict_management) && !empty($attendance) && !empty($ability_to_meet_deadline) && !empty($remarks) && !empty($added_by) && !empty($created_at)){
           
                $data = array(
                    "employee_id" => $employee_id,
                    "company_id" =>$company_id,
                    "appraisal_year_month" =>$appraisal_year_month,
                    "customer_experience" => $customer_experience,
                    "marketing" => $marketing,
                    "management" => $management,
                    "administration" => $administration,
                    "presentation_skill" => $presentation_skill,
                    "quality_of_work" => $quality_of_work,
                    "efficiency" => $efficiency,
                    "integrity" => $integrity,
                    "professionalism" => $professionalism,
                    "team_work" => $team_work,
                    "critical_thinking" => $critical_thinking,
                    "conflict_management" => $conflict_management,
                    "attendance" => $attendance,
                    "ability_to_meet_deadline" => $ability_to_meet_deadline,
                    "remarks" => $remarks,
                    "added_by" => $added_by,
                    "created_at" => $created_at                  
                );

                if($this->Performance_appraisal_model->add($data)){
                    $this->response(array(
                        "status" => 1,
                        "message" => "New Request Added" ,    
                    ), REST_Controller::HTTP_OK);
    
                } else {
                    $this->response(array(
                        "status" => 0,
                        "message" => "Failed to add new Request" ,    
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
        $performance_appraisal_id = $this->security->xss_clean($data->performance_appraisal_id);

        if($this->Performance_appraisal_model->delete_record($performance_appraisal_id)){
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

