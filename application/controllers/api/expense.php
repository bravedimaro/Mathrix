<?php
require APPPATH.'libraries/REST_Controller.php';

class expense extends REST_Controller{
    
    public function __construct(){

        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("Expense_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");
    }

    public function index_get(){
        $expense = $this->Expense_model->get_expense();
       
        if(count($expense) > 0 ){
            $this->response(array(
                "status" => 1,
                "message" => "Message found",
                "data" => $expense
            ), REST_Controller::HTTP_OK);
        } else {
            $this->response(array(
                "status" => 0,
                "message" => "No Company found",
                "data" => $expense
            ), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put(){
        $data = json_decode(file_get_contents("php://input"));
       
        if(isset($data->expense_id) || isset($data->employee_id) || isset($data->company_id) || isset($data->expense_type_id) || isset($data->billcopy_file) || isset($data->amount) || isset($data->purchase_date) || isset($data->remarks) || isset($data->status) || isset($data->status_remarks) || isset($data->created_at)){
            $id = $data->expense_id;

            $update_expense = array(
                "company_id" =>$data->company_id,
                "employee_id" =>$data->employee_id,
                "expense_type_id"=>$data->expense_type_id,
                "billcopy_file" =>$data->billcopy_file,
                "amount" =>$data->amount,
                "purchase_date" =>$data->purchase_date,
                "remarks" =>$data->remarks,
                "status" => $data->status,
                "status_remarks" =>$data->status_remarks,
                "created_at"=> $data->created_at
            );

            if($this->Expense_model->update_record($update_expense, $id)){

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
        
        $employee_id = $this->security->xss_clean($this->input->post("employee_id"));
        $company_id = $this->security->xss_clean($this->input->post("company_id"));
        $expense_type_id = $this->security->xss_clean($this->input->post("expense_type_id"));
        $billcopy_file = $this->security->xss_clean($this->input->post("billcopy_file"));
        $amount = $this->security->xss_clean($this->input->post("amount"));
        $purchase_date = $this->security->xss_clean($this->input->post("purchase_date"));
        $remarks = $this->security->xss_clean($this->input->post("remarks"));
        $status = $this->security->xss_clean($this->input->post("status"));
        $status_remarks = $this->security->xss_clean($this->input->post("status_remarks"));
        $created_at = $this->security->xss_clean($this->input->post("created_at"));

        $employee_id = $this->form_validation->set_rules("employee_id", "employee_id", "required");
        $company_id = $this->form_validation->set_rules("company_id", "company_id", "required");
        $expense_type_id = $this->form_validation->set_rules("expense_type_id", "expense_type", "required");
        $billcopy_file = $this->form_validation->set_rules("billcopy_file", "billcopy_file", "required");
        $amount = $this->form_validation->set_rules("amount", "amount", "required");
        $purchase_date = $this->form_validation->set_rules("purchase_date", "purchase_date", "required");
        $remarks = $this->form_validation->set_rules("remarks", "remarks", "required");
        $status = $this->form_validation->set_rules("status", "status", "required");
        $status_remarks = $this->form_validation->set_rules("status_remarks", "status_remarks", "required");
        $created_at = $this->form_validation->set_rules("created_at", "created_at", "required");

        if($this->form_validation->run() === FALSE){
            
            // we have some errors
             $this->response(array(
                "status" => 0,
                "message" => "All fields are needed"
              ) , REST_Controller::HTTP_NOT_FOUND);
  
        } else {
            if(!empty($employee_id) && !empty($company_id) && !empty($expense_type_id) && !empty($billcopy_file) && !empty($amount) && !empty($purchase_date) && !empty($remarks) && !empty($status) && !empty($status_remarks) && !empty($created_at)){
           
                $data = array(
                    "company_id" => $company_id,
                    "employee_id" =>$employee_id,
                    "expense_type_id" =>$expense_type_id,
                    "billcopy_file" => $billcopy_file,
                    "amount" => $amount,
                    "purchase_date" =>$purchase_date,
                    "remarks" => $remarks,
                    "status" => $status,
                    "status_remarks" => $status_remarks,
                    "created_at" => $created_at                  
                );

                if($this->Expense_model->add($data)){
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
        $expense_id = $this->security->xss_clean($data->expense_id);

        if($this->Expense_model->delete_record($expense_id)){
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

