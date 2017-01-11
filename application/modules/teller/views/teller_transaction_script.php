<script>
    /* global systemApplication, systemUtility */
var tellerTransaction = {};
tellerTransaction.assessmentTypeList = [];
tellerTransaction.particularRemoveList = [];
tellerTransaction.currentAcademicYear;
$(document).ready(function(){
    systemUtility.addAcademicYearSelectOption("#tellerTransactionAcademicYear");
    $("#tellerTransactionAcademicYear option:nth-child(2)").attr("default", "true");
    tellerTransaction.currentAcademicYear = $("#tellerTransactionAcademicYear option:nth-child(3)").val()*1;
    $("#tellerTransactionAcademicYear").val(tellerTransaction.currentAcademicYear);
    $("#tellerTransactionAcademicYear").change(function(){
        $("#tellerTransactionUserSelectionSchoolYear").val($(this).val());
    });
    var currentDate = new Date();
    $("#tellerTransactionDatetime").val(currentDate.getFullYear() +"-"+ systemUtility.pad(currentDate.getMonth()+1, 2)+"-"+currentDate.getDate());
    $.post(systemApplication.url.apiUrl+"c_access_control_list/retrieveAccessControlList", {account_ID : systemApplication.userInformation.userID, retrieve_all : 1, module_ID : 24}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#tellerTransactionModifyButton").show();
        }else{
            $("#tellerTransactionModifyButton").hide();
        }
    });
    $("#tellerTransactionCashierID").val(systemApplication.userInformation.userID);
    $("#tellerTransactionCashierFullName").text((
            systemApplication.userInformation.lastName + 
            ", " +
            systemApplication.userInformation.firstName +
            " " +
            systemApplication.userInformation.middleName
            ).toUpperCase());
    tellerTransaction.refreshAssessmentTypeList();
    // particular list
    $("#tellerTransactionParticularListAddButton").click(function(){
        tellerTransaction.addNewParticular();
    });
    $("#tellerTransactionModifyButton").click(function(){
        $("#tellerTransactionModification").modal("toggle");
    });
    $("#tellerTransactionParticularListBody").on("click", ".tellerTransactionParticularListRemoveButton", function(){
        ($(this).parent().parent().attr("particular_id")*1) ? tellerTransaction.particularRemoveList.push($(this).parent().parent().attr("particular_id")) : "";
        $(this).parent().parent().remove();
        tellerTransaction.calculateParticularListAmount();
    });
    $("#tellerTransactionParticularListBody").on("change", ".tellerTransactionParticularListAssessmentItem", function(){
        if($(this).val()*1 && !isNaN($(this).parent().parent().find(".tellerTransactionParticularListAmount").val()*1) && $(this).parent().parent().find(".tellerTransactionParticularListAmount").val()*1){
            $(this).parent().parent().removeClass("alert-danger");
        }
       
    });
    $("#tellerTransactionParticularListBody").on("click", ".tellerTransactionParticularListAmount", function(){
        if($(this).val()*1 <= 0){
            $(this).val("");
        }
    });
    $("#tellerTransactionParticularListBody").on("keyup", ".tellerTransactionParticularListAmount", function(){
        if(!isNaN($(this).val()*1) && $(this).val()*1 ){
            tellerTransaction.calculateParticularListAmount();
            if($(this).parent().parent().find(".tellerTransactionParticularListAssessmentItem").val()*1){
                $(this).parent().parent().removeClass("alert-danger");
            }
        }else{  
            if(isNaN($(this).val()*1)){
                $(this).val($(this).val().substring(0, $(this).val().length - 1));
            }
        }
        
    });
    
    // account selection
    $("#tellerTransactionSelectPayer").click(function(){
        $("#tellerTransactionUserSelection").modal("toggle");
        $("#tellerTransactionUserSelection").attr("selection_mode", 1);//1 - payer
        $("#tellerTransactionUserList").slideDown();
        $("#tellerTransactionUserLedger").slideUp();
    });
    $("#tellerTransactionSelectPayee").click(function(){
        $("#tellerTransactionUserSelection").modal("toggle");
        $("#tellerTransactionUserSelection").attr("selection_mode", 2);//1 - payee
        $("#tellerTransactionUserList").slideDown();
        $("#tellerTransactionUserLedger").slideUp();
    });
    $("#tellerTransactionUserSelectionSearchFilter").ajaxForm({
        beforeSubmit : function(){
            $("#tellerTransactionUserList").slideDown();
            $("#tellerTransactionUserLedger").slideUp();
            $("#tellerTransactionUserSelectionSearchFilterSearchButton").button("loading");
        },
        success : function(data){
            tellerTransaction.addUserSelectionList(data);
            $("#tellerTransactionUserSelectionSearchFilterSearchButton").button("reset");
        }
    });
    //account ledger
    $("#tellerTransactionUserSelectionListBody").on("click", ".tellerTransactionUserSelectionLisLedger", function(){
        $(this).button("loading");
        $("#tellerTransactionUserLedgerDescriptionFilter").val("");
        $("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val("");
        $("#tellerTransactionUserLedgerAmountFilter").val("");
        $("#tellerTransactionUserLedgerListBody").attr("account_id",$(this).parent().parent().attr("account_id"));
        tellerTransaction.showStudentLedger();
        $("#tellerTransactionUserList").slideUp();
        $("#tellerTransactionUserLedger").slideDown();
        $(this).button("reset");
        
    });
    $("#tellerTransactionUserLedgerBack").click(function(){
        $("#tellerTransactionUserList").slideDown();
        $("#tellerTransactionUserLedger").slideUp();
    });
    $("#tellerTransactionUserLedgerFilterButton").click(function(){
        tellerTransaction.showStudentLedger();
    });
    //other selection
    $("#tellerTransactionUserSelectionOthersBackButton").click(function(){
        $("#tellerTransactionUserSelectionSearchForm").slideDown();
        $("#tellerTransactionUserSelectionOthersForm").slideUp();
    });
    $("#tellerTransactionUserSelectionOthersButton").click(function(){
        $("#tellerTransactionUserSelectionSearchForm").slideUp();
        $("#tellerTransactionUserSelectionOthersForm").slideDown();
    });
    //$("#tellerTransactionUserLedger").find("table").tablesorter({headers:{4:{sorter:false},5:{sorter:false},6:{sorter:false},7:{sorter:false},8:{sorter:false}}});
    //payment voiding
    $("#tellerTransactionVoidForm").ajaxForm({
        beforeSubmit : function(){
            $("#tellerTransactionVoidSubmitButton").button("loading");
        },
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $("#tellerTransactionVoid").modal("toggle");
            }else{
                $("#tellerTransactionVoidMessage").show();
                $("#tellerTransactionVoidMessage").html(response["error"][0]["message"]);
                setTimeout(function(){
                    $("#tellerTransactionVoidMessage").hide();
                }, 1500);
            }
            $("#tellerTransactionVoidSubmitButton").button("reset");
        }
    });
    $("#tellerTransactionUserSelectionListBody").on("click", ".tellerTransactionParticularListSelectButton", function(){
        var selector = ($("#tellerTransactionUserSelection").attr("selection_mode")*1 === 1) ? "r" : "e";
        $("#tellerTransactionPaye"+selector+"IdentificationNumber").attr("account_id", $(this).parent().parent().attr("account_id"));
        $("#tellerTransactionPaye"+selector+"IdentificationNumber").text($(this).parent().parent().find(".tellerTransactionUserSelectionListIdentificationNumber").text());
        $("#tellerTransactionPaye"+selector+"FullName").text($(this).parent().parent().find(".tellerTransactionUserSelectionListFullName").text());
        $("#tellerTransactionPaye"+selector+"Information").text($(this).parent().parent().attr("account_information"));
        
        $("#tellerTransactionPayerLastName").text("");
        $("#tellerTransactionPayerFirstName").text("");
        $("#tellerTransactionPayerMiddleName").text("");
        
        if(($("#tellerTransactionUserSelection").attr("selection_mode")*1 === 1)){
            $("#tellerTransactionPayeeIdentificationNumber").attr("account_id", $(this).parent().parent().attr("account_id"));
            $("#tellerTransactionPayeeIdentificationNumber").text($(this).parent().parent().find(".tellerTransactionUserSelectionListIdentificationNumber").text());
            $("#tellerTransactionPayeeFullName").text($(this).parent().parent().find(".tellerTransactionUserSelectionListFullName").text());
            $("#tellerTransactionPayeeInformation").text($(this).parent().parent().attr("account_information"));
            $("#tellerTransactionPayeeLastName").text("");
            $("#tellerTransactionPayeeFirstName").text("");
            $("#tellerTransactionPayeeMiddleName").text("");
            
        }
        tellerTransaction.showPrivilege($(this).parent().parent().attr("account_id"));
        $("#tellerTransactionUserSelection").modal("toggle");
    });
    $("#tellerTransactionUserSelectionSelectOthers").click(function(){
        var selector = ($("#tellerTransactionUserSelection").attr("selection_mode")*1 === 1) ? "r" : "e";
        $("#tellerTransactionPaye"+selector+"IdentificationNumber").attr("account_id", -1);
        $("#tellerTransactionPaye"+selector+"IdentificationNumber").text("Others");
        $("#tellerTransactionPaye"+selector+"Information").text("");
        $("#tellerTransactionPaye"+selector+"FullName").text("");
        
        $("#tellerTransactionPaye"+selector+"LastName").text($("#tellerTransactionUserSelectionOthersLastName").val().toUpperCase());
        $("#tellerTransactionPaye"+selector+"FirstName").text($("#tellerTransactionUserSelectionOthersFirstName").val().toUpperCase());
        $("#tellerTransactionPaye"+selector+"MiddleName").text($("#tellerTransactionUserSelectionOthersMiddleName").val().toUpperCase());
        
        if(($("#tellerTransactionUserSelection").attr("selection_mode")*1 === 1)){
            $("#tellerTransactionPayeeIdentificationNumber").attr("account_id",-1);
            $("#tellerTransactionPayeeIdentificationNumber").text("Others");
            $("#tellerTransactionPayeeInformation").text("");
            $("#tellerTransactionPayeeFullName").text("");
            
            $("#tellerTransactionPayeeLastName").text($("#tellerTransactionUserSelectionOthersLastName").val().toUpperCase());
            $("#tellerTransactionPayeeFirstName").text($("#tellerTransactionUserSelectionOthersFirstName").val().toUpperCase());
            $("#tellerTransactionPayeeMiddleName").text($("#tellerTransactionUserSelectionOthersMiddleName").val().toUpperCase());
        }
        $("#tellerTransactionUserSelection").modal("toggle");
    });
    $("#tellerTransactionRemarks").autoResize({extraSpace:2, animate: {duration:1}});
    
    //amount payment
    $("#tellerTransactionAmountTendered").keyup(function(){
        if(!isNaN($(this).val()*1)){
            $("#tellerTransactionChange").text( ($(this).val()*1 - $("#tellerTransactionParticularListTotalAmount").text()*1).toFixed(2));
        }else{
             $(this).val($(this).val().substring(0, $(this).val().length - 1));
        }
    });
    //transaction
    $("#tellerTransactionFinishTransaction").click(function(){
        if($("#tellerTransactionAcademicYear").val()*1 !== tellerTransaction.currentAcademicYear){
                $.confirm({
                        cancelButton : "Cancel",
                        confirmButton: 'Proceed',
                        title: 'Academic Year changed!',
                        content: 'You are trying to transact for the academic year <b>'+ $("#tellerTransactionAcademicYear option:selected").text()+"</b>",
                        confirm: function(){
                                tellerTransaction.finishTransaction();
                        },
                        cancel: function(){
                        }
                });
        }else{
                tellerTransaction.finishTransaction();
        }
        
    });
    $("#tellerTransactionModifyTransaction").click(function(){
        tellerTransaction.finishTransaction();
    });
    $("#tellerTransactionCancelModifyTransaction").click(function(){
        tellerTransaction.refreshTransaction();
        $("#tellerTransactionFinishTransaction").show();
        $("#tellerTransactionCancelModifyTransaction").hide();
        $("#tellerTransactionModifyTransaction").hide();
        $("#tellerTransactionModificationOrderReceiptNumber").val("");
        $("#tellerTransactionOrderReceiptNumber").val($("#tellerTransactionOrderReceiptNumber").attr("current_value"));
        $("#tellerTransactionOrderReceiptNumber").attr("current_value", 0);
    });
    
    //transaction voiding
    $("#tellerTransactionVoidButton").click(function(){
        $("#tellerTransactionVoid").modal("toggle");
    });
    $("#tellerTransactionAccountStatementButton").click(function(){
        tellerTransaction.showAccountStatement($("#tellerTransactionPayeeIdentificationNumber").attr("account_id"));
    });
    $("#tellerTransactionLedgerButton").click(function(){
        tellerTransaction.showAccountLedger($("#tellerTransactionPayeeIdentificationNumber").attr("account_id"));
    });
    $("#tellerTransactionPaymentSummaryButton").click(function(){
        tellerTransaction.showPaymentSummary($("#tellerTransactionPayeeIdentificationNumber").attr("account_id"));
    });
    $("#tellerTransactionAccountStatementPrintButton").click(function(){
        $("#tellerTransactionAccountStatementTellerList").parent().hide();
        $("#tellerTransactionAccountStatementPrint").print();
        $("#tellerTransactionAccountStatementTellerList").parent().show();
    });
    $("#tellerTransactionPaymentSummaryListPrintButton").click(function(){
        $("#tellerTransactionPaymentSummaryListPrint").print();
    });
    $("#tellerTransactionLedgerPrintButton").click(function(){
       $("#tellerTransactionLedgerPrint").print(); 
    });
    $("#tellerTransactionPaymentAdjustmentButton").click(function(){
        $("#tellerTransactionPaymentAdjustmentButton").button("loading");
        tellerTransaction.showAdjustment($("#tellerTransactionPayeeIdentificationNumber").attr("account_id"));
        
    });
    $("#tellerTransactionAdjustmentListBody").on("click", ".tellerTransactionAdjustmentListSelectAction", function(){
        var selectedRow = $(this).parent().parent();
        var newData = {
            course_annual_fee_ID : $(this).parent().parent().attr("course_annual_fee_ID"),
            account_ID : $(this).parent().parent().attr("account_ID"),
            academic_year : $("#tellerTransactionAcademicYear").val()
        };
        
        $.post(systemApplication.url.apiUrl+"c_course_annual_fee/createCourseAnnualFeeSelectedAccount", newData, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                selectedRow.find(".tellerTransactionAdjustmentListSelectAction").hide();
                selectedRow.find(".tellerTransactionAdjustmentListRemoveAction").show();
            }else{
                alert(response["error"][0]["message"]);
            };
            
            tellerTransaction.showPrivilege(newData.account_ID);
         });
    });
    $("#tellerTransactionAdjustmentListBody").on("click", ".tellerTransactionAdjustmentListRemoveAction", function(){
        var selectedRow = $(this).parent().parent();
        var newData = {
            course_annual_fee_ID : $(this).parent().parent().attr("course_annual_fee_ID"),
            account_ID : $(this).parent().parent().attr("account_ID")
        };
        $.post(systemApplication.url.apiUrl+"c_course_annual_fee/deleteCourseAnnualFeeSelectedAccount", newData, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                selectedRow.find(".tellerTransactionAdjustmentListSelectAction").show();
                selectedRow.find(".tellerTransactionAdjustmentListRemoveAction").hide();
            }else{
                alert(response["error"][0]["message"]);
            };
            
            tellerTransaction.showPrivilege(newData.account_ID);
         });
    });
    //Options
    tellerTransaction.refreshOptions();
    //$("#tellerTransactionUserSelectionSchoolYear").val($("#tellerTransactionAcademicYear option:nth-child(2)").val());
    $("#tellerTransactionModificationButton").click(function(){
        tellerTransaction.showTransaction($("#tellerTransactionModificationOrderReceiptNumber").val());
    });
    $("#tellerTransactionAcademicYear").trigger("change");
});
$(document).one("ready", function(){
	//tellerTransaction.refreshTransaction();
})
tellerTransaction.showTransaction = function(orderReceiptNumber){
    if(orderReceiptNumber*1 === 0){
        return false;
    }
    $("#tellerTransactionModificationButton").button("loading");
    $("#tellerTransactionFinishTransaction").hide();
    $("#tellerTransactionModifyTransaction").show();
    $("#tellerTransactionCancelModifyTransaction").show();
    if($("#tellerTransactionOrderReceiptNumber").attr("current_value")*1 === 0 ){
        $("#tellerTransactionOrderReceiptNumber").attr("current_value", $("#tellerTransactionOrderReceiptNumber").val());
    }
    tellerTransaction.refreshTransaction();
    $.post(systemApplication.url.apiUrl+"c_account_payment/retrieveAccountPayment", {order_receipt_number : orderReceiptNumber}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentDate = new Date(response["data"]["datetime"]*1000);
            if(response["data"]["academic_year"]*1){
                $("#tellerTransactionAcademicYear").val(response["data"]["academic_year"]);
            }else{
                response["data"]["datetime"] = response["data"]["datetime"]*1;
                if(response["data"]["datetime"] >= 1396828800 && response["data"]["datetime"] < 1428336000){//2014
                    $("#tellerTransactionAcademicYear").val(1396828800);
                }else if(response["data"]["datetime"] >= 1428336000 && response["data"]["datetime"] < 1459958400){//2015
                    $("#tellerTransactionAcademicYear").val(1428336000);
                }else if(response["data"]["datetime"] >= 1459958400 && response["data"]["datetime"] < 1491523200){//2015
                    $("#tellerTransactionAcademicYear").val(1459958400);
                }else if(response["data"]["datetime"] >= 1491523200){//2015
                    $("#tellerTransactionAcademicYear").val(1491523200);
                }
            }
            $("#tellerTransactionDatetime").val(currentDate.getFullYear()+"-"+systemUtility.pad(currentDate.getMonth()+1,2)+"-"+systemUtility.pad(currentDate.getDate(),2)+" "+currentDate.getHours()+":0"+currentDate.getMinutes()+":"+currentDate.getSeconds());
            //$("#tellerTransactionDatetime").val(currentDate.getFullYear() +"-"+ systemUtility.pad(currentDate.getMonth()+1, 2)+"-"+currentDate.getDate());
            $("#tellerTransactionOrderReceiptNumber").attr("payment_id", response["data"]["ID"]);
            $("#tellerTransactionOrderReceiptNumber").val(response["data"]["order_receipt_number"]);
            $("#tellerTransactionRemarks").text(response["data"]["remarks"]);
            
            if(response["data"]["payer_account_ID"]*1>-1){
                $("#tellerTransactionPayerIdentificationNumber").attr("account_id",response["data"]["payer_account_ID"]);
                $("#tellerTransactionPayerIdentificationNumber").text(response["data"]["payer_identification_number"]);
                $("#tellerTransactionPayerFirstName").text(response["data"]["payer_first_name"].toUpperCase());
                $("#tellerTransactionPayerMiddleName").text(response["data"]["payer_middle_name"].toUpperCase());
                $("#tellerTransactionPayerLastName").text(response["data"]["payer_last_name"].toUpperCase());
            }else{
                $("#tellerTransactionPayerIdentificationNumber").attr("account_id",-1);
                $("#tellerTransactionPayerIdentificationNumber").text("Others");
                $("#tellerTransactionPayerFirstName").text(response["data"]["no_account_payer_first_name"].toUpperCase());
                $("#tellerTransactionPayerMiddleName").text(response["data"]["no_account_payer_middle_name"].toUpperCase());
                $("#tellerTransactionPayerLastName").text(response["data"]["no_account_payer_last_name"].toUpperCase());
            }
            
            
            if(response["data"]["payee_account_ID"]*1>-1){
                $("#tellerTransactionPayeeIdentificationNumber").attr("account_id",response["data"]["payee_account_ID"]);
                $("#tellerTransactionPayeeIdentificationNumber").text(response["data"]["payee_identification_number"]);
                $("#tellerTransactionPayeeFirstName").text(response["data"]["payee_first_name"].toUpperCase());
                $("#tellerTransactionPayeeMiddleName").text(response["data"]["payee_middle_name"].toUpperCase());
                $("#tellerTransactionPayeeLastName").text(response["data"]["payee_last_name"].toUpperCase());
            }else{
                $("#tellerTransactionPayeeIdentificationNumber").attr("account_id",-1);
                $("#tellerTransactionPayeeIdentificationNumber").text("Others");
                $("#tellerTransactionPayeeFirstName").text(response["data"]["no_account_payee_first_name"].toUpperCase());
                $("#tellerTransactionPayeeMiddleName").text(response["data"]["no_account_payee_middle_name"].toUpperCase());
                $("#tellerTransactionPayeeLastName").text(response["data"]["no_account_payee_last_name"].toUpperCase());
            }
            $("#tellerTransactionPaymentMode").val(response["data"]["mode"]);
            $("#tellerTransactionAmountTendered").val((response["data"]["total_amount"]*1).toFixed(2));
            $("#tellerTransactionRemarks").val(response["data"]["remarks"]);
            $("#tellerTransactionRemarks").trigger("change");
            $("#tellerTransactionParticularListBody").empty();
            if(response["data"]["payment_assessment_item"]){
                for(var x = 0 ; x < response["data"]["payment_assessment_item"].length; x++){
                    //ID, assessmentTypeID, assessmentItemID, remainingAmount, amount, remarks
                    tellerTransaction.addNewParticular(response["data"]["payment_assessment_item"][x]["ID"], response["data"]["payment_assessment_item"][x]["assessment_item_ID"], 0, response["data"]["payment_assessment_item"][x]["amount"], response["data"]["payment_assessment_item"][x]["remarks"]);
                }
            }
            
            tellerTransaction.showPrivilege(response["data"]["payee_account_ID"]);
        }else{
           
            systemUtility.showErrorMessage($("#tellerTransactionModificationMessage"), response["error"]);
        }
        $("#tellerTransactionModificationButton").button("reset");
    });
};
tellerTransaction.showPrivilege = function(accountID){
    
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveCourseAnnualFeeSelectedAccount", {account_ID:accountID, academic_year:$("#tellerTransactionAcademicYear").val() }, function(data){
        var response = JSON.parse(data);
        $("#tellerTransactionAdjustments").empty();
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                $("#tellerTransactionAdjustments").append((response["data"][x]["description"]).toUpperCase()+"<br>");
            }
        }
    });
};
tellerTransaction.showAdjustment = function(accountID){
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveCourseAnnualFee", {assessment_type_ID: 136,type:2, academic_year:$("#tellerTransactionAcademicYear").val()/*, year_level : [0,7]*/, sort: {description : "ASC"}}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#tellerTransactionAdjustmentListBody").empty();
            for(var x = 0; x < response["data"].length; x++){
                var newRow = $(".prototype").find(".tellerTransactionAdjustmentListRow").clone();
                newRow.attr("course_annual_fee_ID", response["data"][x]["ID"]);
                newRow.attr("account_id", accountID);
                newRow.find(".tellerTransactionAdjustmentListDescription").text((response["data"][x]["description"]).toUpperCase());
                newRow.find(".tellerTransactionAdjustmentListAmount").text((response["data"][x]["amount"]*-1).toFixed(2));
                $("#tellerTransactionAdjustmentListBody").append(newRow);
            }
            $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveCourseAnnualFeeSelectedAccount", {account_ID:accountID, academic_year:systemUtility.getCurrentAcademicYear()}, function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    for(var x = 0; x < response["data"].length; x++){
                        $("#tellerTransactionAdjustmentListBody").find("[course_annual_fee_ID="+response["data"][x]["course_annual_fee_ID"]+"]").find(".tellerTransactionAdjustmentListSelectAction").hide();
                        $("#tellerTransactionAdjustmentListBody").find("[course_annual_fee_ID="+response["data"][x]["course_annual_fee_ID"]+"]").find(".tellerTransactionAdjustmentListRemoveAction").show();
                    }
                    
                }
                $("#tellerTransactionAdjustment").modal("toggle");
                $("#tellerTransactionPaymentAdjustmentButton").button("reset");
                
            });
        }else{
            $("#tellerTransactionPaymentAdjustmentButton").button("reset");
        }
    });
};
tellerTransaction.showAccountStatement = function(accountID){
    $("#tellerTransactionAccountStatementButton").button("loading");
    $("#tellerTransactionAccountStatementTotalAmountPaid").text("0.00");
    $("#tellerTransactionAccountStatementTotalAnnualFee").text("0.00");
    $("#tellerTransactionAccountStatementTotalRemainingBalance").text("0.00");
    $("#tellerTransactionAccountStatementFullName").text($("#tellerTransactionPayeeFullName").text());
    var academicDate = new Date($("#tellerTransactionAcademicYear").val()*1000);
    $("#tellerTransactionAccountStatementList").empty();
    $("#tellerTransactionAccountStatementAcademicYear").text(academicDate.getFullYear());
    var dataFilter = {
        account_ID : accountID,
        academic_year : systemUtility.getCurrentAcademicYear()
    };
    if(($("#tellerTransactionAcademicYear").val()*1 <= 1428336000)){//if school year 2015-16
            dataFilter.start_datetime = $("#tellerTransactionAcademicYear").val();
            dataFilter.end_datetime = $("#tellerTransactionAcademicYear").find("option:nth-child("+($("#tellerTransactionAcademicYear").find("option:selected").index()+2)+")").val();
    }else{
            dataFilter.payment_academic_year = $("#tellerTransactionAcademicYear").val();
    }
    dataFilter.ledger_assessment_type_ID = 133;//tuition only
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var totalAccountStatementListAmount = 0;
            var total2 = 0;
            //general
            for(var x = 0; x < response["data"]["general_fee"].length;x++){
                totalAccountStatementListAmount += response["data"]["general_fee"][x]["amount"]*1;
                total2+=response["data"]["general_fee"][x]["amount"]*1;
            }
            //selected
            if(response["data"]["adjustment_fee"]){
                for(var x = 0; x < response["data"]["adjustment_fee"].length;x++){
                    totalAccountStatementListAmount += response["data"]["adjustment_fee"][x]["amount"]*1;
                }
            }
            $("#tellerTransactionAccountStatementTotalAnnualFee").text(systemUtility.insertDecimalPoints((totalAccountStatementListAmount).toFixed(2)));
            $("#tellerTransactionAccountStatementTotalRemainingBalance").text(($("#tellerTransactionAccountStatementTotalRemainingBalance").text()*1 + totalAccountStatementListAmount).toFixed(2));
            //ledger
            var totalAmountPaid = 0;
			
            if(response["data"]["ledger"]){
                var newRow = false;
                for(var x = 0; x < response["data"]["ledger"].length; x++){
                    newRow = $(".prototype").find(".tellerTransactionAccountStatementListRow").clone();
                    newRow.attr("id", "tellerTransactionAccountStatementList"+response["data"]["ledger"][x]["assessment_item_ID"]);
                    var paymentDate = new Date(response["data"]["ledger"][x]["datetime"]*1000);
                    newRow.find(".tellerTransactionAccountStatementListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                    newRow.find(".tellerTransactionAccountStatementListOR").append(response["data"]["ledger"][x]["order_receipt_number"]);
                    newRow.find(".tellerTransactionAccountStatementListAmount").append(systemUtility.insertDecimalPoints((response["data"]["ledger"][x]["amount"]*1).toFixed(2)));
                    newRow.find(".tellerTransactionAccountStatementListRemarks").append((response["data"]["ledger"][x]["remarks"] !== "") ? response["data"]["ledger"][x]["remarks"] : response["data"]["ledger"][x]["payment_remarks"]);
                    $("#tellerTransactionAccountStatementList").append(newRow);
                    totalAmountPaid += response["data"]["ledger"][x]["amount"]*1;
                }
            }
            $("#tellerTransactionAccountStatementTotalAmountPaid").text(systemUtility.insertDecimalPoints((totalAmountPaid).toFixed(2)));
            $("#tellerTransactionAccountStatementTotalRemainingBalance").text(systemUtility.insertDecimalPoints(($("#tellerTransactionAccountStatementTotalRemainingBalance").text()*1 + (totalAmountPaid*-1)).toFixed(2)));
        }
        $("#tellerTransactionAccountStatement").modal("show");
        $("#tellerTransactionAccountStatementButton").button("reset");
    });
    var dataFilter2 = {
        status : 2,
        sort : {
        },
        payee_account_ID: accountID*1,
		exclude_assessment_type_ID : [133]
    };
//    dataFilter2.assessment_type_ID = 1;//tellering
//    dataFilter2.start_datetime = $("#tellerTransactionAcademicYear").val();
//    dataFilter2.end_datetime = $("#tellerTransactionAcademicYear").find("option:nth-child("+($("#tellerTransactionAcademicYear").find("option:selected").index()+2)+")").val();
    if($("#tellerTransactionAcademicYear").val()*1 <= 1428336000 || ($("#tellerTransactionAcademicYear").val()*1 === 1396828800)){//if school year 2015-16
            dataFilter2.start_datetime = $("#tellerTransactionAcademicYear").val();
            dataFilter2.end_datetime = $("#tellerTransactionAcademicYear").find("option:nth-child("+($("#tellerTransactionAcademicYear").find("option:selected").index()+2)+")").val();
    }else{
            dataFilter2.payment_academic_year = $("#tellerTransactionAcademicYear").val();
    }
    $.post("<?=api_url()?>c_account_payment/retrieveAccountLedger", dataFilter2, function(data){
        var response = JSON.parse(data);
        $("#tellerTransactionAccountStatementTellerList").empty();
        $("#tellerTransactionAccountStatementTellerTotalAmountPaid").html("0.00");
        var totalAmount = 0;
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var newRow = $(".prototype").find(".tellerTransactionAccountStatementListRow").clone();
                var paymentDate = new Date(response["data"][x]["datetime"]*1000);
                newRow.find(".tellerTransactionAccountStatementListDatetime").attr("datetime", response["data"][x]["datetime"]);
                newRow.find(".tellerTransactionAccountStatementListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                newRow.find(".tellerTransactionAccountStatementListRemarks").append((response["data"][x]["remarks"]!=="") ? response["data"][x]["remarks"] : response["data"][x]["payment_remarks"]);
                newRow.find(".tellerTransactionAccountStatementListOR").append(response["data"][x]["order_receipt_number"]);
                newRow.find(".tellerTransactionAccountStatementListAmount").append(systemUtility.insertDecimalPoints((response["data"][x]["amount"]*1).toFixed(2)));
                $("#tellerTransactionAccountStatementTellerList").append(newRow);
                totalAmount += response["data"][x]["amount"]*1;
            }
            
        }else{
            systemUtility.showErrorMessage("#tellerTransactionAccountStatementListMessage" ,response["error"]);
        }
        $("#tellerTransactionAccountStatementTellerTotalAmountPaid").html(systemUtility.insertDecimalPoints(totalAmount.toFixed(2)));
    });
    
    
};
tellerTransaction.showPaymentSummary = function(accountID){
    $("#tellerTransactionPaymentSummaryButton").button("loading");
    $("#tellerTransactionPaymentSummaryListTotalAmountPaid").text("0.00");
    $("#tellerTransactionPaymentSummaryListTotalAnnualFee").text("0.00");
    $("#tellerTransactionPaymentSummaryListTotalRemainingBalance").text("0.00");
    var dataFilter = {
        payee_account_ID:accountID,
        status : 2,
        account_ID : accountID, 
        academic_year : systemUtility.getCurrentAcademicYear()
    };
	
    if($("#tellerTransactionAcademicYear").val()*1 <= 1428336000 ){//if school year 2015-16
        dataFilter.start_datetime = $("#tellerTransactionAcademicYear").val();
        dataFilter.end_datetime = $("#tellerTransactionAcademicYear").find("option:nth-child("+($("#tellerTransactionAcademicYear").find("option:selected").index()+2)+")").val();
    }else{
        dataFilter.payment_academic_year = $("#tellerTransactionAcademicYear").val();
    }
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){
        var response = JSON.parse(data);
        $("#tellerTransactionPaymentSummaryList").empty();
        if(!response["error"].length){
            var totalAccountStatementAmount = 0;
            //general
            var newRow ;
            for(var x = 0; x < response["data"]["general_fee"].length;x++){
                totalAccountStatementAmount += response["data"]["general_fee"][x]["amount"]*1;
            }
            //selected
            if(response["data"]["adjustment_fee"]){
                for(var x = 0; x < response["data"]["adjustment_fee"].length;x++){
                    totalAccountStatementAmount += response["data"]["adjustment_fee"][x]["amount"]*1;
                }
            }
            $("#tellerTransactionPaymentSummaryListTotalAnnualFee").text((totalAccountStatementAmount).toFixed(2));
        }
        var totalAmountPaid = 0;
        if(response["data"]["ledger"]){
            var newRow = false;
            for(var x = 0; x < response["data"]["ledger"].length; x++){
                if($("#tellerTransactionPaymentSummaryList").find("#tellerTransactionPaymentSummary"+response["data"]["ledger"][x]["assessment_item_ID"]).length){
                    newRow.find(".tellerTransactionPaymentSummaryListAmount").text((newRow.find(".tellerTransactionPaymentSummaryListAmount").text()*1+response["data"]["ledger"][x]["amount"]*1).toFixed(2));
                }else{
                    newRow = $(".prototype").find(".tellerTransactionPaymentSummaryListRow").clone();
                    newRow.attr("id", "tellerTransactionPaymentSummary"+response["data"]["ledger"][x]["assessment_item_ID"]);
                    var paymentDate = new Date(response["data"]["ledger"][x]["datetime"]*1000);
                    newRow.find(".tellerTransactionPaymentSummaryListDate").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                    newRow.find(".tellerTransactionPaymentSummaryListDescription").append(response["data"]["ledger"][x]["description"]);
                    newRow.find(".tellerTransactionPaymentSummaryListAmount").append((response["data"]["ledger"][x]["amount"]*1).toFixed(2));
                    $("#tellerTransactionPaymentSummaryList").append(newRow);
                }
                totalAmountPaid += response["data"]["ledger"][x]["amount"]*1;
            }
        }
        $("#tellerTransactionPaymentSummaryListTotalAmountPaid").text((totalAmountPaid).toFixed(2));
        $("#tellerTransactionPaymentSummaryListTotalRemainingBalance").text(($("#tellerTransactionPaymentSummaryListTotalRemainingBalance").text()*1 + (totalAmountPaid*-1)).toFixed(2));
        $("#tellerTransactionPaymentSummary").modal("show");
        $("#tellerTransactionPaymentSummaryButton").button("reset");
    });
    
};
tellerTransaction.showAccountLedger = function(accountID){
    $("#tellerTransactionLedgerButton").button("loading");
    var dataFilter = {
        retrieve_type : 1,
        status : 2,
        sort : {
        },
        payee_account_ID: accountID*1,
        exclude_assessment_type_ID : [133]
    };
    var academicDate = new Date($("#tellerTransactionAcademicYear").val()*1000);
    $("#tellerTransactionLedgerFullName").text($("#tellerTransactionPayeeFullName").text());
        $("#tellerTransactionLedgerAcademicYear").text(academicDate.getFullYear());
//    dataFilter.start_datetime = systemUtility.getCurrentAcademicYear();
//    dataFilter.end_datetime = systemUtility.getNextAcademicYear(dataFilter.start_datetime*1000);
    if($("#tellerTransactionAcademicYear").val()*1 <= 1428336000){//if school year 2015-16
            dataFilter.start_datetime = $("#tellerTransactionAcademicYear").val();
            dataFilter.end_datetime = $("#tellerTransactionAcademicYear").find("option:nth-child("+($("#tellerTransactionAcademicYear").find("option:selected").index()+2)+")").val();
    }else{
            dataFilter.payment_academic_year = $("#tellerTransactionAcademicYear").val();
    }
    $.post("<?=api_url()?>c_account_payment/retrieveAccountLedger", dataFilter, function(data){
        var response = JSON.parse(data);
        $("#tellerTransactionLedgerList").empty();
        $("#tellerTransactionLedgerListTotalAmount").html("0.00");
        $("#tellerTransactionLedger").modal("toggle");
        var totalAmount = 0;
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var newRow = $(".prototype").find(".tellerTransactionLedgerListRow").clone();
                var paymentDate = new Date(response["data"][x]["datetime"]*1000);
                newRow.find(".tellerTransactionLedgerListDatetime").attr("datetime", response["data"][x]["datetime"]);
                newRow.find(".tellerTransactionLedgerListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                newRow.find(".tellerTransactionLedgerListDescription").append(response["data"][x]["description"]);
                newRow.find(".tellerTransactionLedgerListOR").append(response["data"][x]["order_receipt_number"]);
                newRow.find(".tellerTransactionLedgerListAmount").append((response["data"][x]["amount"]*1).toFixed(2));
                newRow.find(".tellerTransactionLedgerListRemarks").append(response["data"][x]["remarks"]);
                $("#tellerTransactionLedgerList").append(newRow);
                totalAmount += response["data"][x]["amount"]*1;
            }
            
        }else{
            systemUtility.showErrorMessage("#tellerTransactionLedgerListMessage" ,response["error"]);
        }
        $("#tellerTransactionLedgerListTotalAmount").html(totalAmount.toFixed(2));
        $("#tellerTransactionLedgerButton").button("reset");
    });
};
tellerTransaction.showStudentLedger = function(row){
    $("#tellerTransactionUserLedgerFilterButton").button("loading");
    var dataFilter = {
        retrieve_type : 1,
        status : 2,
        sort : {
        },
        payee_account_ID: $("#tellerTransactionUserLedgerListBody").attr("account_id")*1
    };
    if($("#tellerTransactionUserLedgerAcademicYearFilter").val() !== "0") {
		if($("#tellerTransactionUserLedgerAcademicYearFilter").val()*1 <= 1428336000 ){//if school year 2015-16
			dataFilter.start_datetime = $("#tellerTransactionUserLedgerAcademicYearFilter").val();
			dataFilter.end_datetime = $("#tellerTransactionUserLedgerAcademicYearFilter").find("option:nth-child("+($("#tellerTransactionUserLedgerAcademicYearFilter").find("option:selected").index()+2)+")").val();
		}else{
			dataFilter.payment_academic_year = $("#tellerTransactionUserLedgerAcademicYearFilter").val();
		}
    }
    ($("#tellerTransactionUserLedgerDescriptionFilter").val() !== "") ? dataFilter.assessment_item_description = $("#tellerTransactionUserLedgerDescriptionFilter").val() : null;
    ($("#tellerTransactionUserLedgerAmountFilter").val() !== "") ? dataFilter.amount = $("#tellerTransactionUserLedgerAmountFilter").val() : null;
    ($("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() !== "") ? dataFilter.order_receipt_number = $("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() : null;
    $.post("<?=api_url()?>c_account_payment/retrieveAccountLedger", dataFilter, function(data){
        var response = JSON.parse(data);
        $("#tellerTransactionUserLedgerListBody").empty();
        $("#tellerTransactionUserLedgerTotalAmount").html("0.00");
        var totalAmount = 0;
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var newRow = $(".prototype").find(".tellerTransactionUserLedgerListRow").clone();
                var paymentDate = new Date(response["data"][x]["datetime"]*1000);
                newRow.find(".tellerTransactionUserLedgerListDatetime").attr("datetime", response["data"][x]["datetime"]);
                newRow.find(".tellerTransactionUserLedgerListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                newRow.find(".tellerTransactionUserLedgerListDescription").append(response["data"][x]["description"]);
                newRow.find(".tellerTransactionUserLedgerListOrderReceiptNumber").append(response["data"][x]["order_receipt_number"]);
                newRow.find(".tellerTransactionUserLedgerListAmount").append((response["data"][x]["amount"]*1).toFixed(2));
                newRow.find(".tellerTransactionUserLedgerListRemarks").append(response["data"][x]["remarks"]);
                $("#tellerTransactionUserLedgerListBody").append(newRow);
                totalAmount += response["data"][x]["amount"]*1;
            }
            
        }else{
            systemUtility.showErrorMessage("#tellerTransactionUserListMessage" ,response["error"]);
        }
        $("#tellerTransactionUserLedgerTotalAmount").html(totalAmount.toFixed(2));
        $("#tellerTransactionUserLedgerFilterButton").button("reset");
        
        $("#tellerTransactionUserLedger").find("table").trigger("update");
    });
};

tellerTransaction.finishTransaction = function(){
    if($("#tellerTransactionAcademicYear").val()*1 !== tellerTransaction.currentAcademicYear){
        $.confirm({
                title: 'Academic Year changed!',
                content: 'You are trying to transact for the academic year '+ $("#tellerTransactionAcademicYear option:selected").text()+". \n\nPress OK to proceed.",
                confirm: function(){

                },
                cancel: function(){
                }
        });
    }
    $("#tellerTransactionFinishTransaction").button("loading");
    $("#tellerTransactionModifyTransaction").button("loading");
    $("#tellerTransactionMessage").html("");
    var newData = {
        order_receipt_number : $("#tellerTransactionOrderReceiptNumber").val(),
        payee_account_ID : $("#tellerTransactionPayeeIdentificationNumber").attr("account_id")*1,
        payer_account_ID : $("#tellerTransactionPayerIdentificationNumber").attr("account_id")*1,
        mode : $("#tellerTransactionPaymentMode").val(),
        payment_assessment_item : tellerTransaction.retrieveParticular(),
        total_amount : $("#tellerTransactionParticularListTotalAmount").text(),
        remarks : $("#tellerTransactionRemarks").val(),
        academic_year : $("#tellerTransactionAcademicYear").val()
    };
    var numberOfError = 0,
        errorMessage = "";
    if(newData.order_receipt_number === "0" || newData.order_receipt_number === ""){
        errorMessage += "<p>Please Specify OR Number</p>";
        numberOfError++;
    }
    if(newData.payee_account_ID === 0){
        errorMessage += "<p>Select Payee</p>";
        numberOfError++;
    }else if(newData.payee_account_ID === -1){
        newData.payee_last_name = $("#tellerTransactionPayeeLastName").text();
        newData.payee_first_name = $("#tellerTransactionPayeeFirstName").text();
        newData.payee_middle_name = $("#tellerTransactionPayeeMiddleName").text();
    }
    
    if(newData.payer_account_ID === 0){
        errorMessage += "<p>Select Payer</p>";
        numberOfError++;
    }else if(newData.payer_account_ID === -1){
        newData.payer_last_name = $("#tellerTransactionPayerLastName").text();
        newData.payer_first_name = $("#tellerTransactionPayerFirstName").text();
        newData.payer_middle_name = $("#tellerTransactionPayerMiddleName").text();
    }
    if(newData.payment_assessment_item.length === 0 && !$("#tellerTransactionOrderReceiptNumber").attr("payment_id")*1){
        errorMessage += "<p>Please Review Particular</p>";
        numberOfError++;
    }
    if($("#tellerTransactionChange").text()*1 < 0){
        errorMessage += "<p>Amount tendered is not enough</p>";
        numberOfError++;
    }
    if(numberOfError){
        systemUtility.showMessage("#tellerTransactionMessage" ,errorMessage, "warning", numberOfError*3000);
        $("#tellerTransactionFinishTransaction").button("reset");
        $("#tellerTransactionModifyTransaction").button("reset");
    }else{
        var link = "<?=api_url()?>";
        /*If updating*/
        if($("#tellerTransactionOrderReceiptNumber").attr("payment_id")*1){
            newData.ID = $("#tellerTransactionOrderReceiptNumber").attr("payment_id");
            if(tellerTransaction.particularRemoveList.length){
                newData.removed_payment_assessment_item = tellerTransaction.particularRemoveList;
            }
            var oldParticularList = tellerTransaction.retrieveParticular(1);
            if(oldParticularList.length){
                newData.updated_payment_assessment_item = oldParticularList;
            }
            newData.modification_remarks = $("#tellerTransactionModificationRemarks").val();
            newData.datetime = (new Date($("#tellerTransactionDatetime").val())).getTime()/1000;
            link += "c_account_payment/updateAccountPayment";
        }else{
            link += "c_account_payment/createAccountPayment";
        }
        $.post(link, newData, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                //messageSelector ,message, messageType, duration
                systemUtility.showMessage("#tellerTransactionMessage" ,"Transaction Success!", "success", 2000);
                if($("#tellerTransactionOrderReceiptNumber").attr("payment_id")*1 === 0){
                    tellerTransaction.printReceipt();
                    tellerTransaction.refreshTransaction();
                }else{
                     $("#tellerTransactionCancelModifyTransaction").trigger("click");
                }
                $("#tellerTransactionUserSelectionListBody").empty();
                
            }else{
                systemUtility.showErrorMessage( "#tellerTransactionMessage", response["error"]);
            }
            $("#tellerTransactionModifyTransaction").button("reset");
            $("#tellerTransactionFinishTransaction").button("reset");
        });
    }
};
tellerTransaction.printReceipt = function(){
    
    $("#tellerTransactionReceiptPayee").html(($("#tellerTransactionPayeeIdentificationNumber").attr("account_id")*1 !== -1)  ? $("#tellerTransactionPayeeFullName").text()+" <br> " +$("#tellerTransactionPayerInformation").text() : $("#tellerTransactionPayeeFirstName").text() + $("#tellerTransactionPayeeMiddleName").text() + $("#tellerTransactionPayeeLastName").text());
    var cashierFullName = $("#tellerTransactionCashierFullName").text().split(", ");
    
    $("#tellerTransactionReceiptParticularCashier").text(cashierFullName[0]+", "+cashierFullName[1].charAt(0)+".");
    var currentDate = new Date();
    $("#tellerTransactionReceiptDate").text((currentDate.getMonth()+1)+"/"+currentDate.getDate()+"/"+currentDate.getFullYear());
    $("#tellerTransactionDatetime").text((currentDate.getMonth()+1)+"/"+currentDate.getDate()+"/"+currentDate.getFullYear());
    $("#tellerTransactionReceiptParticularTotalAmount").text(systemUtility.insertDecimalPoints($("#tellerTransactionParticularListTotalAmount").text()));
    $("#tellerTransactionReceiptParticularDescription").empty();
    $("#tellerTransactionReceiptParticularAmount").empty();
    $("#tellerTransactionParticularListBody").find("tr").each(function(){
        if($(this).find(".tellerTransactionParticularListAssessmentItem").val()*1){
            $("#tellerTransactionReceiptParticularDescription").append("<div>"+$(this).find(".tellerTransactionParticularListAssessmentItem").find("option:selected").text()+"</div>");
            $("#tellerTransactionReceiptParticularAmount").append("<div>"+systemUtility.insertDecimalPoints(($(this).find(".tellerTransactionParticularListAmount").val()*1).toFixed(2))+"</div>");
        }
        if($(this).find(".tellerTransactionParticularListRemarks").val() !== ""){
            $("#tellerTransactionReceiptParticularDescription").append("<div> &nbsp;&nbsp;&nbsp;<strong>Remarks: </strong> "+$(this).find(".tellerTransactionParticularListRemarks").val()+"</div>");
        }
        
    });
    if($("#tellerTransactionRemarks").val() !== ""){
        $("#tellerTransactionReceiptParticularDescription").append("<div style='font-style:italic;text-align:center'>(<strong>Remarks: </strong>"+$("#tellerTransactionRemarks").val()+")</div>");
    }
    var amount = ($("#tellerTransactionParticularListTotalAmount").text()).split(".");
    var wordAmount = tellerTransaction.numberToWord(amount[0])+" PESOS "+ ((amount[1]*1)?  " AND "+amount[1]+" CENTS" :"");
    $("#tellerTransactionReceiptParticularDescriptionFooter").append("<div style='font-weight:bold;font-style:italic;text-align:center'>"+wordAmount.toUpperCase()+"</div>");
    $("#tellerTransactionReceiptDiv").print();
    
};
tellerTransaction.retrieveParticular = function(old){
    old = (typeof old === "undefined") ? 0 : old;
    var particulars = [];
    $("#tellerTransactionParticularListBody").find(".tellerTransactionParticularListRow").each(function(){
        if($(this).find(".tellerTransactionParticularListAssessmentItem").val()*1){
            if($(this).find(".tellerTransactionParticularListAmount").val()*1){
                
                if((!old && ($(this).attr("particular_id")*1 === 0)) || (old && $(this).attr("particular_id")*1)){
                    particulars.push({
                        ID : $(this).attr("particular_id"),
                        assessment_item_ID : $(this).find(".tellerTransactionParticularListAssessmentItem").val(),
                        amount : $(this).find(".tellerTransactionParticularListAmount").val(),
                        remarks : $(this).find(".tellerTransactionParticularListRemarks").val()
                    });
                }
            }else{
               
                systemUtility.showMessage("#tellerTransactionMessage" ,"Enter amount in Particular List", "danger", 3000);
                return [];
            }
        }else{
            systemUtility.showMessage("#tellerTransactionMessage" ,"Select Assessment Item in Particular List", "danger", 3000);
            return [];
        }
    });
    return particulars;
};
tellerTransaction.refreshTransaction = function(){
    tellerTransaction.particularRemoveList = [];
    var currentDate = new Date();
    $("#tellerTransactionAcademicYear").val(systemUtility.getCurrentAcademicYear())
    $("#tellerTransactionDatetime").val(currentDate.getFullYear() +"-"+ systemUtility.pad(currentDate.getMonth()+1, 2)+"-"+currentDate.getDate());
    $("#tellerTransactionPayerIdentificationNumber").text("Select Payer");
    $("#tellerTransactionPayerIdentificationNumber").attr("account_id", 0);
    $("#tellerTransactionPayerFullName").text("");
    $("#tellerTransactionPayeeIdentificationNumber").text("Select Payee");
    $("#tellerTransactionPayeeIdentificationNumber").attr("account_id", 0);
    $("#tellerTransactionPayeeFullName").text("");
    $("#tellerTransactionOrderReceiptNumber").attr("payment_id", 0);
    $("#tellerTransactionOrderReceiptNumber").val((($("#tellerTransactionOrderReceiptNumber").val()*1) ? $("#tellerTransactionOrderReceiptNumber").val()*1 +1 : ""));
    $("#tellerTransactionParticularListBody").empty();
    $("#tellerTransactionAmountTendered").val("");
    $("#tellerTransactionAmountTendered").trigger("change");
    $("#tellerTransactionRemarks").val("");
    $("#tellerTransactionReceiptParticularDescriptionFooter").empty();
    tellerTransaction.particularRemoveList = [];
    tellerTransaction.addNewParticular();
    $("#tellerTransactionPayerFirstName").text("");
    $("#tellerTransactionPayerMiddleName").text("");
    $("#tellerTransactionPayerLastName").text("");
    $("#tellerTransactionPayeeFirstName").text("");
    $("#tellerTransactionPayeeMiddleName").text("");
    $("#tellerTransactionPayeeLastName").text("");
    $("#tellerTransactionPayeeInformation").text("");
    $("#tellerTransactionPayerInformation").text("");
    $("#tellerTransactionAdjustments").empty();
};
tellerTransaction.addUserSelectionList = function(data){
    var response = JSON.parse(data);
    $("#tellerTransactionUserSelectionListBody").empty();
    if(!response["error"].length){
        for(var x = 0; x < response["data"].length; x++ ){
            var newRow = $(".prototype").find(".tellerTransactionUserSelectionListRow").clone();
            newRow.attr("account_id", response["data"][x]["account_ID"]);
            newRow.attr("account_information", (response["data"][x]["account_type_ID"]*1 === 4 && response["data"][x]["section_ID"]*1 !== 0) ? (response["data"][x]["course_code"]+" "+response["data"][x]["section_year_level"]+" - "+response["data"][x]["secion_description"]).toUpperCase() : (response["data"][x]["account_type_description"]).toUpperCase());
            newRow.find(".tellerTransactionUserSelectionListIdentificationNumber").text(response["data"][x]["identification_number"]);
            newRow.find(".tellerTransactionUserSelectionListFullName").text((
                    response["data"][x]["last_name"] +
                    ", " +
                    response["data"][x]["first_name"] +
                    " " + 
                    response["data"][x]["middle_name"] 
                    ).toUpperCase());
            $("#tellerTransactionUserSelectionListBody").append(newRow);
        }
    }else{
        $("#tellerTransactionUserSelectionListBody").append("<tr><td colspan='3' style='text-align:center' >No Result</td></tr>");
    }
};
tellerTransaction.changeParticularAssessmentItem = function(){
    $.post("<?=base_url()?>api/c_assessment_item/retrieveAssessmentItem", {tellering : 1}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $(".tellerTransactionParticularListAssessmentItem").empty();
            $(".tellerTransactionParticularListAssessmentItem").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $(".tellerTransactionParticularListAssessmentItem").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
       }
   });
};
tellerTransaction.addNewParticular = function(ID, assessmentItemID, remainingAmount, amount, remarks){
    ID = (typeof ID === "undefined") ? 0 : ID;
    assessmentItemID = (typeof assessmentItemID === "undefined") ? 0 : assessmentItemID;
    remainingAmount = (typeof remainingAmount === "undefined") ? 0 : remainingAmount;
    remarks = (typeof remarks === "undefined") ? "" : remarks;
    amount = (typeof amount === "undefined") ? "": amount;
    var newRow = $(".prototype").find(".tellerTransactionParticularListRow").clone();
    newRow.attr("particular_id", ID);
    //tellerTransaction.changeParticularAssessmentType(newRow);
    newRow.find(".tellerTransactionParticularListAssessmentRemainingAmount").text(remainingAmount.toFixed(2));
    
    $("#tellerTransactionParticularListBody").append(newRow);
    newRow.find(".tellerTransactionParticularListRemarks").autoResize({extraSpace:1, animate: {duration:1}});
    newRow.find(".tellerTransactionParticularListAssessmentItem").val(assessmentItemID);
    newRow.find(".tellerTransactionParticularListRemarks").val(remarks);
    newRow.find(".tellerTransactionParticularListRemarks").trigger("change");
    newRow.find(".tellerTransactionParticularListAmount").val((amount*1).toFixed(2));
    tellerTransaction.calculateParticularListAmount();
    
    
};
tellerTransaction.calculateParticularListAmount = function(){
    var totalRemainingAmount = 0;
    var totalAmount = 0;
    var totalItemCount = 0;
    $("#tellerTransactionParticularListBody").find(".tellerTransactionParticularListRow").each(function(){
        totalItemCount++;
        totalRemainingAmount += $(this).find(".tellerTransactionParticularListAssessmentRemainingAmount").text()*1;
        totalAmount += $(this).find(".tellerTransactionParticularListAmount").val()*1;
       
    });
    $("#tellerTransactionParticularListTotalResult").text(totalItemCount);
    $("#tellerTransactionParticularListTotalRemainingAmount").text(totalRemainingAmount.toFixed(2));
    $("#tellerTransactionParticularListTotalAmount").text(totalAmount.toFixed(2));
    $("#tellerTransactionChange").text( ($("#tellerTransactionAmountTendered").val()*1 - $("#tellerTransactionParticularListTotalAmount").text()*1).toFixed(2));
};
tellerTransaction.refreshAssessmentTypeList = function(){
    $.post("<?=base_url()?>api/c_assessment_type/retrieveAssessmentType", {}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
           tellerTransaction.assessmentTypeList  = response["data"];
           tellerTransaction.refreshAssessmentTypeOption();
       }
    });
};
tellerTransaction.refreshAssessmentTypeOption = function(){
    var previousValues = [];
    $(".tellerTransactionAssessmentTypeOption").each(function(){
        previousValues.push($(this).val()*1);
    });
    $(".tellerTransactionAssessmentTypeOption").empty();
    $(".tellerTransactionAssessmentTypeOption").append("<option value='0' >None</option>");
    for(var x = 0; x < tellerTransaction.assessmentTypeList.length; x++){
        $(".tellerTransactionAssessmentTypeOption").append("<option value='"+tellerTransaction.assessmentTypeList[x]["ID"]+"' >"+tellerTransaction.assessmentTypeList[x]["description"]+"</option>");
    }
    $(".tellerTransactionAssessmentTypeOption").each(function(index){
        $(this).val(previousValues[index]);
    });
};
tellerTransaction.refreshOptions = function(){
    $("#tellerTransactionUserLedgerAcademicYearFilter").empty();
    systemUtility.addAcademicYearSelectOption("#tellerTransactionUserLedgerAcademicYearFilter");
    $("#tellerTransactionUserLedgerAcademicYearFilter").val(systemUtility.getCurrentAcademicYear());
    tellerTransaction.changeParticularAssessmentItem();
};
tellerTransaction.numberToWord = function(n){
      var string = n.toString(), units, tens, scales, start, end, chunks, chunksLen, chunk, ints, i, word, words, and = 'and';

    /* Is number zero? */
    if( parseInt( string ) === 0 ) {
        return 'zero';
    }

    /* Array of units as words */
    units = [ '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen' ];

    /* Array of tens as words */
    tens = [ '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety' ];

    /* Array of scales as words */
    scales = [ '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion', 'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quatttuor-decillion', 'quindecillion', 'sexdecillion', 'septen-decillion', 'octodecillion', 'novemdecillion', 'vigintillion', 'centillion' ];

    /* Split user arguemnt into 3 digit chunks from right to left */
    start = string.length;
    chunks = [];
    while( start > 0 ) {
        end = start;
        chunks.push( string.slice( ( start = Math.max( 0, start - 3 ) ), end ) );
    }

    /* Check if function has enough scale words to be able to stringify the user argument */
    chunksLen = chunks.length;
    if( chunksLen > scales.length ) {
        return '';
    }

    /* Stringify each integer in each chunk */
    words = [];
    for( i = 0; i < chunksLen; i++ ) {

        chunk = parseInt( chunks[i] );

        if( chunk ) {

            /* Split chunk into array of individual integers */
            ints = chunks[i].split( '' ).reverse().map( parseFloat );

            /* If tens integer is 1, i.e. 10, then add 10 to units integer */
            if( ints[1] === 1 ) {
                ints[0] += 10;
            }

            /* Add scale word if chunk is not zero and array item exists */
            if( ( word = scales[i] ) ) {
                words.push( word );
            }

            /* Add unit word if array item exists */
            if( ( word = units[ ints[0] ] ) ) {
                words.push( word );
            }

            /* Add tens word if array item exists */
            if( ( word = tens[ ints[1] ] ) ) {
                words.push( word );
            }

            /* Add 'and' string after units or tens integer if: */
            if( ints[0] || ints[1] ) {

                /* Chunk has a hundreds integer or chunk is the first of multiple chunks */
                if( ints[2] || ! i && chunksLen ) {
                    words.push( and );
                }

            }

            /* Add hundreds word if array item exists */
            if( ( word = units[ ints[2] ] ) ) {
                words.push( word + ' hundred' );
            }

        }

    }

    return words.reverse().join( ' ' );
};
</script>