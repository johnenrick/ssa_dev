<script>
    /*global systemApplication, systemUtility*/
var paymentReport = {};
paymentReport.sectionList = [];
paymentReport.paymentReportListFilter = {};
$(document).ready(function(){
    //section list page nav
    $("#paymentReportListPreviousPage").click(function(){
        if($("#paymentReportListCurrentPage").text()*1 -1){
            $("#paymentReportListCurrentPage").text($("#paymentReportListCurrentPage").text()*1 -1);
            paymentReport.paymentList();
        }
    });
	
    $("#paymentReportListNextPage").click(function(){
        $("#paymentReportListCurrentPage").text($("#paymentReportListCurrentPage").text()*1 + 1);
        paymentReport.paymentList();
    });
     
    //list filter and sorting
    $(".paymentReportListSorter").click(function(){
        $(".paymentReportListSorter").attr("sorted",0);
        $(this).attr("sorted",1);
        // 1 - asc, 0 - desc
        if($(this).attr("sort")*1){
            $(this).attr("sort", 0 );
            $(this).find(".sortDown").show();
            $(this).find(".sortUp").hide();
        }else{
            $(this).attr("sort", 1);
            $(this).find(".sortDown").hide();
            $(this).find(".sortUp").show();
        }
        
        paymentReport.paymentList();
    });
    $("#paymentReportListFilterSearch").click(function(){
        paymentReport.paymentList();
    });
	paymentReport.listCashier();
//    $("#paymentReportListFilterStartDatetime").datepicker({dateFormat: "dd/mm/yy"});
//    $("#paymentReportListFilterEndDatetime").datepicker({dateFormat: "dd/mm/yy"});
    //pagination
    $("#paymentReportListPreviousPage").click(function(){
        if($("#paymentReportListCurrentPage").text()*1 -1){
            $("#paymentReportListCurrentPage").text($("#paymentReportListCurrentPage").text()*1);
            paymentReport.paymentList();
        }
     });
     $("#paymentReportListNextPage").click(function(){
         $("#paymentReportListCurrentPage").text($("#paymentReportListCurrentPage").text()*1);
         paymentReport.paymentList();
     });
     $("#paymentReportListBody").on("click", ".paymentReportListOrderReceiptNumber", function(){
         
         paymentReport.viewPaymentDetail($(this).text());
     });
     paymentReport.paymentList();
     //printing
     $("#paymentReportListPrintReport").click(function(){
         paymentReport.paymentList(1)
     });
	 paymentReport.changeParticularAssessmentItem();
         paymentReport.listSection();
});
paymentReport.viewPaymentDetail = function(ID){
    $.post("<?=api_url()?>c_account_payment/retrieveAccountPayment", {order_receipt_number:ID}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#paymentReportDetailStatus").text((response["data"]["status"]*1 === 3) ? "TRANSACTION VOIDED" : "");
            $("#paymentReportDetailOrderReceiptNumber").text(response["data"]["order_receipt_number"]);
            $("#paymentReportDetailCashierFullName").text((
                    response["data"]["cashier_last_name"]
                    +", "
                    +response["data"]["cashier_first_name"]
                    +" "
                    +response["data"]["cashier_middle_name"]
                    ).toUpperCase());
            var payerPrefix = (typeof (response["data"]["payer_last_name"]+response["data"]["payer_first_name"]+response["data"]["payer_middle_name"]).length === "undefined") ? "no_account_" : "";
            $("#paymentReportDetailPayerFullName").text((
                    response["data"][payerPrefix+"payer_last_name"]
                    +", "
                    +response["data"][payerPrefix+"payer_first_name"]
                    +" "
                    +response["data"][payerPrefix+"payer_middle_name"]
                    ).toUpperCase());
            var payeePrefix = (typeof (response["data"]["payee_last_name"]+response["data"]["payee_first_name"]+response["data"]["payee_middle_name"]).length === "undefined") ? "no_account_" : "";
            $("#paymentReportDetailPayeeFullName").text((
                    response["data"][payeePrefix+"payee_last_name"]
                    +", "
                    +response["data"][payeePrefix+"payee_first_name"]
                    +" "
                    +response["data"][payeePrefix+"payee_middle_name"]
                    ).toUpperCase());
            switch(response["data"]["payee_last_name"]*1){
                case 1 : $("#paymentReportDetailPaymentMode").text("Cash");
                    break;
                case 2 : $("#paymentReportDetailPaymentMode").text("Check");
                    break;
                case 3 : $("#paymentReportDetailPaymentMode").text("Credit");
                    break;
                case 4 : $("#paymentReportDetailPaymentMode").text("Debit");
                    break;
            }
            $("#paymentReportDetailRemarks").text(response["data"]["remarks"]);
            //assessment items
            $("#paymentReportDetailAssessmentList").empty();
            var totalAmount = 0;
            if(response["data"]["payment_assessment_item"]){
                for(var x = 0; x < response["data"]["payment_assessment_item"].length; x++){
                    var newRow = $(".prototype").find(".paymentReportDetailAssessmentListRow").clone();
                    newRow.find(".paymentReportDetailAssessmentListDescription").text(response["data"]["payment_assessment_item"][x]["description"]);
                    newRow.find(".paymentReportDetailAssessmentListAmount").text(systemUtility.insertDecimalPoints((response["data"]["payment_assessment_item"][x]["amount"]*1).toFixed(2)));
                    newRow.find(".paymentReportDetailAssessmentListRemarks").text(response["data"]["payment_assessment_item"][x]["remarks"]);
                    totalAmount += response["data"]["payment_assessment_item"][x]["amount"]*1;
                    $("#paymentReportDetailAssessmentList").append(newRow);
                }
            }
            $("#paymentReportDetailAssessmentListTotalAmount").text(systemUtility.insertDecimalPoints((totalAmount).toFixed(2)));
            $("#paymentReportDetail").modal("toggle");
        }else{
            systemUtility.showErrorMessage("#paymentReportListMessage", response["error"]);
        }
    });
};
paymentReport.requestList = false;
paymentReport.paymentList = function(toPrint){
    $("#paymentReportListFilterSearch").button("loading");
    $("#paymentReportListPreviousPage").button("loading");
    $("#paymentReportListNextPage").button("loading");
    var dataFilter = {
        retrieve_type : 1,
        retrieve_total_amount : 1
        //cashier_ID : systemApplication.userInformation.userID
//        limit : 20,
//        offset : ((($("#paymentReportListCurrentPage").text()*1 - 1) > 0) ? ($("#paymentReportListCurrentPage").text()*1 - 1) : 0) * 20
    };
    if(typeof toPrint === "undefined"){
        dataFilter.limit = 20;
        dataFilter.offset = ((($("#paymentReportListCurrentPage").text()*1 - 1) > 0) ? ($("#paymentReportListCurrentPage").text()*1 - 1) : 0) * 20;
    }
	if($("#paymentReportListFilterCashier").val()*1 !== 0){
		dataFilter.cashier_ID = $("#paymentReportListFilterCashier").val();
	}
        if($("#paymentReportListFilterSection").val()*1 !== 0){
		dataFilter.payee_class_section_ID = $("#paymentReportListFilterSection").val();
	}
    var currentDate =new Date();
    currentDate = (new Date((currentDate.getMonth()+1)+"/"+(currentDate.getDate())+"/"+(currentDate.getFullYear())+" 00:00:01")).getTime()/1000;
	($("#paymentReportListFilterAssessmentItem").val()*1 > 0) ? dataFilter.assessment_item_ID = $("#paymentReportListFilterAssessmentItem").val() : null;
    dataFilter.start_datetime = (((new Date($("#paymentReportListFilterStartDatetime").val())) != "Invalid Date") ? (new Date($("#paymentReportListFilterStartDatetime").val()+" 00:00:01")).getTime()/1000 : currentDate)*1;
    dataFilter.end_datetime = (((new Date($("#paymentReportListFilterEndDatetime").val())) != "Invalid Date") ? (new Date($("#paymentReportListFilterEndDatetime").val()+" 00:00:01")).getTime()/1000 : currentDate)*1;
//    ($("#tellerTransactionUserLedgerDescriptionFilter").val() !== "") ? dataFilter.assessment_item_description = $("#tellerTransactionUserLedgerDescriptionFilter").val() : null;
//    ($("#tellerTransactionUserLedgerAmountFilter").val() !== "") ? dataFilter.amount = $("#tellerTransactionUserLedgerAmountFilter").val() : null;
//    ($("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() !== "") ? dataFilter.order_receipt_number = $("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() : null;
    if($(".paymentReportListSorter[sorted=1]").length){
        dataFilter.sort = {};
        switch($(".paymentReportListSorter[sorted=1]").attr("sorter_name")){
            case "payee_full_name" :
                dataFilter.sort.payee_account_basic_information__last_name = $(".paymentReportListSorter[sorted=1]").attr("sort")*1;
                dataFilter.sort.payee_account_basic_information__first_name = $(".paymentReportListSorter[sorted=1]").attr("sort")*1;
                dataFilter.sort.payee_account_basic_information__middle_name = $(".paymentReportListSorter[sorted=1]").attr("sort")*1;
                break;
            default:
                dataFilter.sort[$(".paymentReportListSorter[sorted=1]").attr("sorter_name")] = $(".paymentReportListSorter[sorted=1]").attr("sort")*1;
                break;
        }
    }
    if(paymentReport.requestList){
        paymentReport.requestList.abort();
    }
    paymentReport.requestList = $.post("<?=api_url()?>c_account_payment/retrieveAccountLedger", dataFilter, function(data){
        paymentReport.requestList = false;
        var response = JSON.parse(data);
        var totalPages = Math.ceil(response["result_count"]/20);
        $("#paymentReportListTotalResult").text(response["result_count"]);
        $("#paymentReportListTotalPage").text(totalPages);
        $("#paymentReportListTotalAmount").text(systemUtility.insertDecimalPoints((response["total_amount"]*1).toFixed(2)));
        if($("#paymentReportListCurrentPage").text()*1 <= totalPages){
            $("#paymentReportListBody").empty();
        }
        if(!response["error"].length){
            if($("#paymentReportListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".paymentReportListRow").clone();
                    newRow.attr("payment_assessment_item_ID", response["data"][x]["ID"]);
                    
                    var paymentDate = new Date((response["data"][x]["datetime"])*1000);//28800
                    newRow.find(".paymentReportListDatetime").attr("datetime", response["data"][x]["datetime"]);
                    newRow.find(".paymentReportListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                    
                    newRow.find(".paymentReportListOrderReceiptNumber").append(response["data"][x]["order_receipt_number"]);
                    var payeePrefix = (typeof (response["data"][x]["payee_last_name"]+response["data"][x]["payee_first_name"]+response["data"][x]["payee_middle_name"]).length === "undefined") ? "no_account_" : "";
                    
                    newRow.find(".paymentReportListPayeeFullName").text(
                        response["data"][x][payeePrefix+"payee_last_name"]
                        + ", " +
                        response["data"][x][payeePrefix+"payee_first_name"]
                        +" "+
                        response["data"][x][payeePrefix+"payee_middle_name"]
                    );
                    newRow.find(".paymentReportListAssessmentItem").text(response["data"][x]["description"]);
                    newRow.find(".paymentReportListAmount").text(systemUtility.insertDecimalPoints((response["data"][x]["amount"]*1).toFixed(2)));
                    newRow.find(".paymentReportListRemarks").html(((response["data"][x]["status"]*1 === 3 ) ? "<strong>Voided. </strong>" : "") +((response["data"][x]["remarks"]!=="") ? response["data"][x]["remarks"]:response["data"][x]["payment_remarks"]));
                    
                    $("#paymentReportListBody").append(newRow);
                }
            }
        }
        if(toPrint){
            $("#paymentReportListTable").css("font-family", "arial");
            $("#paymentReportListTable").css("font-size","8px");
            $("#paymentReportListTable").css("letter-spacing","2");
            $("#paymentReportListTable").css("line-height","9px");
            $("#paymentReportListTable td").css("padding-top","0px");
            $("#paymentReportListTable td").css("padding-bottom","0px");
            $("#paymentReportListTable").find(".tablePagination").hide();
            $("#paymentReportListTablePrint").print();
            $("#paymentReportListTable").css("letter-spacing","");
            $("#paymentReportListTable").css("font-size","");
            $("#paymentReportListTable").css("font-family", "");
            $("#paymentReportListTable").find(".tablePagination").show();
            paymentReport.paymentList();
        }
        $("#paymentReportListFilterSearch").button("reset");
        $("#paymentReportListPreviousPage").button("reset");
        $("#paymentReportListNextPage").button("reset");
        $("#paymentReportListCurrentPage").text( ($("#paymentReportListCurrentPage").text()*1 > $("#paymentReportListTotalPage").text()*1) ?  $("#paymentReportListTotalPage").text()*1 : $("#paymentReportListCurrentPage").text()*1);
    });
};
paymentReport.changeParticularAssessmentItem = function(){
    $.post("<?=base_url()?>api/c_assessment_item/retrieveAssessmentItem", {tellering : 1}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $("#paymentReportListFilterAssessmentItem").empty();
            $("#paymentReportListFilterAssessmentItem").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentReportListFilterAssessmentItem").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
       }
   });
 
};
paymentReport.listCashier = function(){
	$.post("<?=base_url()?>api/c_account/retrieveAccountBasicInformation", {account_type_ID : 5}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $("#paymentReportListFilterCashier").empty();
            $("#paymentReportListFilterCashier").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentReportListFilterCashier").append("<option value='"+response["data"][x]["account_ID"]+"' >"+response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+"</option>");
            }
       }
   });
}
paymentReport.listSection = function(){
	$.post("<?=base_url()?>api/c_section/retrieveSection", {account_type_ID : 5}, function(data){
       var response = JSON.parse(data);
       console.log(response);
       if(!response["error"].length){
            $("#paymentReportListFilterSection").empty();
            $("#paymentReportListFilterSection").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentReportListFilterSection").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
       }
   });
}
</script>