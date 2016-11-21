<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class c_course_annual_fee extends API_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("m_course_annual_fee");
        $this->load->model("m_change_log");
    }

    public function createCourseAnnualFee() {
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), API_Controller::STUDENT_MANAGEMENT)) { //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('type', 'type', 'required');
            $this->form_validation->set_rules('assessment_item_ID', 'Assessment Item', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required|is_unique[course_annual_fee.description]');
            $this->form_validation->set_rules('amount', 'amount', 'required');

            if (1) {
                $result = $this->m_course_annual_fee->createCourseAnnualFee(
                        $this->input->post("type"), $this->input->post("course_ID"), $this->input->post("year_level"), $this->input->post("academic_year"), $this->input->post("assessment_item_ID"), $this->input->post("description"), $this->input->post("amount")
                );
                if ($result) {
                    $response["data"] = $result;
                } else {
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create"
                    );
                }
            } else {
                $response["error"][] = array(
                    "status" => 2,
                    "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }

    public function retrieveCourseAnnualFee() {
        $response = $this->generateResponse();
        $result = $this->m_course_annual_fee->retrieveCourseAnnualFee(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"), $this->input->post("offset"), $this->input->post("sort"), $this->input->post("ID"), $this->input->post("type"), $this->input->post("course_ID"), $this->input->post("year_level"), $this->input->post("academic_year"), $this->input->post("assessment_item_ID"), $this->input->post("description"), $this->input->post("amount"), $this->input->post("assessment_item_description"), $this->input->post("assessment_type_ID")
        );
        if ($this->input->post("limit")) {
            $response["result_count"] = count($this->m_course_annual_fee->retrieveCourseAnnualFee(
                            $this->input->post("retrieve_type"), // 1 - search, 0 - match
                            0, 0, $this->input->post("sort"), $this->input->post("ID"), $this->input->post("type"), $this->input->post("course_ID"), $this->input->post("year_level"), $this->input->post("academic_year"), $this->input->post("assessment_item_ID"), $this->input->post("description"), $this->input->post("amount"), $this->input->post("assessment_item_description"), $this->input->post("assessment_type_ID")
            ));
        }
        if ($result) {
            $response["data"] = $result;
        } else {
            $response["error"][] = array(
                "status" => 3,
                "message" => "No result"
            );
        }
        echo json_encode($response);
    }

    public function updateCourseAnnualFee() {
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), API_Controller::STUDENT_MANAGEMENT)) { //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Acount Level ID', 'required');

            if ($this->form_validation->run()) {
                $result = $this->m_course_annual_fee->updateCourseAnnualFee(
                        $this->input->post("ID"), $this->input->post("type"), $this->input->post("course_ID"), $this->input->post("year_level"), $this->input->post("academic_year"), $this->input->post("assessment_item_ID"), $this->input->post("description"), $this->input->post("amount")
                );
                if ($result) {

                    $response["data"] = $result;
                } else {
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to update"
                    );
                }
            } else {
                $response["error"][] = array(
                    "status" => 2,
                    "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }

    public function deleteCourseAnnualFee() {
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), API_Controller::STUDENT_MANAGEMENT)) { // if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $result = $this->m_course_annual_fee->deleteCourseAnnualFee($this->input->post("ID"));
            if ($result) {
                $response["data"] = $result;
            } else {
                $response["error"][] = array(
                    "status" => 1,
                    "message" => "Failed to delete"
                );
            }
        }
        echo json_encode($response);
    }

    public function retrieveAccountAdjusment() {
        $response = $this->generateResponse();
        $result = $this->m_course_annual_fee->retrieveAccountAdjustment(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"), $this->input->post("offset"), $this->input->post("sort"), $this->input->post("ID"), $this->input->post("identification_number"), $this->input->post("last_name"), $this->input->post("first_name"), $this->input->post("middle_name"), $this->input->post("course_annual_fee_ID")
        );
        if ($this->input->post("limit")) {
            $response["result_count"] = count($this->m_course_annual_fee->retrieveAccountAdjustment(
                            $this->input->post("retrieve_type"), // 1 - search, 0 - match
                            0, 0, $this->input->post("sort"), $this->input->post("ID"), $this->input->post("identification_number"), $this->input->post("last_name"), $this->input->post("first_name"), $this->input->post("middle_name"), $this->input->post("course_annual_fee_ID")
            ));
        }
        if ($result) {
            $response["data"] = $result;
        } else {
            $response["error"][] = array(
                "status" => 3,
                "message" => "No result"
            );
        }
        echo json_encode($response);
    }

    public function createCourseAnnualFeeSelectedAccount() {
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), API_Controller::STUDENT_MANAGEMENT)) { //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('course_annual_fee_ID', 'Course annual fee ID', 'required');
            $this->form_validation->set_rules('account_ID', 'Account ID', 'required|is_unique[course_annual_fee.description]');

            if (1) {
                $result = $this->m_course_annual_fee->createCourseAnnualFeeSelectedAccount(
                        $this->input->post("course_annual_fee_ID"), $this->input->post("account_ID"), $this->input->post("academic_year")
                );
                if ($result) {
                    $response["data"] = $result;
                } else {
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create"
                    );
                }
            } else {
                $response["error"][] = array(
                    "status" => 2,
                    "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }

    public function deleteCourseAnnualFeeSelectedAccount() {
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), API_Controller::STUDENT_MANAGEMENT)) { // if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $result = $this->m_course_annual_fee->deleteCourseAnnualFeeSelectedAccount(
                    $this->input->post("account_ID"), $this->input->post("course_annual_fee_ID")
            );
            if ($result) {
                $response["data"] = $result;
            } else {
                $response["error"][] = array(
                    "status" => 1,
                    "message" => "Failed to delete"
                );
            }
        }
        echo json_encode($response);
    }

    public function retrieveCourseAnnualFeeSelectedAccount() {
        $response = $this->generateResponse();
        $result = $this->m_course_annual_fee->retrieveCourseAnnualFeeSelectedAccount(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"), $this->input->post("offset"), $this->input->post("sort"), $this->input->post("ID"), $this->input->post("account_ID"), $this->input->post("course_annual_fee_ID"), $this->input->post("academic_year")
        );
        if ($this->input->post("limit")) {
            $response["result_count"] = count($this->m_course_annual_fee->retrieveCourseAnnualFeeSelectedAccount(
                            $this->input->post("retrieve_type"), // 1 - search, 0 - match
                            0, 0, $this->input->post("sort"), $this->input->post("ID"), $this->input->post("account_ID"), $this->input->post("course_annual_fee_ID"), $this->input->post("academic_year")
            ));
        }
        if ($result) {
            $response["data"] = $result;
        } else {
            $response["error"][] = array(
                "status" => 3,
                "message" => "No result"
            );
        }
        echo json_encode($response);
    }

    public function retrieveAccountStatement() {
        $response = $this->generateResponse();
        //account information
        $this->load->model("m_account_level");
        $this->load->model("m_account_payment");
        $accountDetail = $this->m_account_level->retrieveAccountLevel(0, 0, false, NULL, $this->input->post("account_ID"), NULL, $this->input->post("academic_year"));
        $response["data"]["general_fee"] = array();
        $response["data"]["adjustment_fee"] = array();
        if ($accountDetail) {
            $accountDetail = $accountDetail[0];
            //school fee
            $schoolFees = array();
            $response["debug"][] = $accountDetail;
            $response["debug"][] = $this->input->post("academic_year");
            $schoolFees1 = $this->m_course_annual_fee->retrieveCourseAnnualFee(
                    0, // 1 - search, 0 - match
                    0, 
                    0, 
                    $this->input->post("sort"), 
                    NULL, 
                    1, 
                    $accountDetail["course_ID"], 
                    $accountDetail["year_level"] * 1 != 11 ? array(0, $accountDetail["year_level"] * 1) : $accountDetail["year_level"], 
                    $this->input->post("academic_year"), 
                    NULL, NULL, NULL, NULL, $this->input->post("assessment_type_ID")
            );
            $schoolFees2 = false; /* /*$this->m_course_annual_fee->retrieveCourseAnnualFee(
              0, // 1 - search, 0 - match
              0,
              0,
              $this->input->post("sort"),
              NULL,
              1,
              $accountDetail["course_ID"],
              "0",
              $this->input->post("academic_year"),
              NULL,
              NULL,
              NULL,
              NULL,
              $this->input->post("assessment_type_ID")
              ); */
            if ($schoolFees1 && $schoolFees2) {
                $schoolFees = array_merge($schoolFees1, $schoolFees2);
            } else if ($schoolFees1) {
                $schoolFees = $schoolFees1;
            } else if ($schoolFees2) {
                $schoolFees = $schoolFees2;
            }
            $selectedFees = $this->m_course_annual_fee->retrieveCourseAnnualFeeSelectedAccount(
                    0, 0, 0, $this->input->post("sort"), NULL, $this->input->post("account_ID"), NULL, $this->input->post("academic_year")
            );
            $response["data"]["general_fee"] = $schoolFees;
            $response["data"]["adjustment_fee"] = $selectedFees;
        } else {
            
        }
        $response["data"]["ledger"] = $this->m_account_payment->retrieveAccountPaymentAssessmentItem(
                NULL, NULL, NULL, NULL, NULL, $this->input->post("account_ID"), NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, $this->input->post("start_datetime"), $this->input->post("end_datetime"), NULL, $this->input->post("ledger_assessment_type_ID"), NULL, $this->input->post("payment_academic_year")
        );
        if (!$accountDetail && !$response["data"]["ledger"]) {
            $response["error"][] = array(
                "status" => 3,
                "message" => "No result" . $accountDetail
            );
        }
        echo json_encode($response);
    }

}
