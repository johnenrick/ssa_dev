<script>
    /*global systemApplication, systemUtility*/
var paymentHistory = {};
paymentHistory.sectionList = [];
paymentHistory.paymentHistoryListFilter = {};
$(document).ready(function(){
    systemUtility.addAcademicYearSelectOption($("#paymentHistoryListFilterAcademicYear"));
    //section list page nav
    $("#paymentHistoryListPreviousPage").click(function(){
        if($("#paymentHistoryListCurrentPage").text()*1 -1){
            $("#paymentHistoryListCurrentPage").text($("#paymentHistoryListCurrentPage").text()*1 -1);
            paymentHistory.paymentList();
        }
    });
	
    $("#paymentHistoryListNextPage").click(function(){
        $("#paymentHistoryListCurrentPage").text($("#paymentHistoryListCurrentPage").text()*1 + 1);
        paymentHistory.paymentList();
    });
     
    //list filter and sorting
    $(".paymentHistoryListSorter").click(function(){
        $(".paymentHistoryListSorter").attr("sorted",0);
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
        
        paymentHistory.paymentList();
    });
    $("#paymentHistoryListFilterSearch").click(function(){
        paymentHistory.paymentList();
    });
	paymentHistory.listCashier();
//    $("#paymentHistoryListFilterStartDatetime").datepicker({dateFormat: "dd/mm/yy"});
//    $("#paymentHistoryListFilterEndDatetime").datepicker({dateFormat: "dd/mm/yy"});
    //pagination
    $("#paymentHistoryListPreviousPage").click(function(){
        if($("#paymentHistoryListCurrentPage").text()*1 -1){
            $("#paymentHistoryListCurrentPage").text($("#paymentHistoryListCurrentPage").text()*1);
            paymentHistory.paymentList();
        }
     });
     $("#paymentHistoryListNextPage").click(function(){
         $("#paymentHistoryListCurrentPage").text($("#paymentHistoryListCurrentPage").text()*1);
         paymentHistory.paymentList();
     });
     $("#paymentHistoryListBody").on("click", ".paymentHistoryListOrderReceiptNumber", function(){
         
         paymentHistory.viewPaymentDetail($(this).text());
     });
     paymentHistory.paymentList();
     //printing
     $("#paymentHistoryListPrintReport").click(function(){
         paymentHistory.paymentList(1)
     });
     $("#paymentHistoryListExcelReport").click(function(){
       
        paymentHistory.paymentList(2);
     });
	 paymentHistory.changeParticularAssessmentItem();
         paymentHistory.listSection();
});
paymentHistory.viewPaymentDetail = function(ID){
    $.post("<?=api_url()?>c_account_payment/retrieveAccountPayment", {order_receipt_number:ID}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#paymentHistoryDetailStatus").text((response["data"]["status"]*1 === 3) ? "TRANSACTION VOIDED" : "");
            $("#paymentHistoryDetailOrderReceiptNumber").text(response["data"]["order_receipt_number"]);
            $("#paymentHistoryDetailCashierFullName").text((
                    response["data"]["cashier_last_name"]
                    +", "
                    +response["data"]["cashier_first_name"]
                    +" "
                    +response["data"]["cashier_middle_name"]
                    ).toUpperCase());
            var payerPrefix = (typeof (response["data"]["payer_last_name"]+response["data"]["payer_first_name"]+response["data"]["payer_middle_name"]).length === "undefined") ? "no_account_" : "";
            $("#paymentHistoryDetailPayerFullName").text((
                    response["data"][payerPrefix+"payer_last_name"]
                    +", "
                    +response["data"][payerPrefix+"payer_first_name"]
                    +" "
                    +response["data"][payerPrefix+"payer_middle_name"]
                    ).toUpperCase());
            var payeePrefix = (typeof (response["data"]["payee_last_name"]+response["data"]["payee_first_name"]+response["data"]["payee_middle_name"]).length === "undefined") ? "no_account_" : "";
            $("#paymentHistoryDetailPayeeFullName").text((
                    response["data"][payeePrefix+"payee_last_name"]
                    +", "
                    +response["data"][payeePrefix+"payee_first_name"]
                    +" "
                    +response["data"][payeePrefix+"payee_middle_name"]
                    ).toUpperCase());
            switch(response["data"]["payee_last_name"]*1){
                case 1 : $("#paymentHistoryDetailPaymentMode").text("Cash");
                    break;
                case 2 : $("#paymentHistoryDetailPaymentMode").text("Check");
                    break;
                case 3 : $("#paymentHistoryDetailPaymentMode").text("Credit");
                    break;
                case 4 : $("#paymentHistoryDetailPaymentMode").text("Debit");
                    break;
            }
            $("#paymentHistoryDetailRemarks").text(response["data"]["remarks"]);
            //assessment items
            $("#paymentHistoryDetailAssessmentList").empty();
            var totalAmount = 0;
            if(response["data"]["payment_assessment_item"]){
                for(var x = 0; x < response["data"]["payment_assessment_item"].length; x++){
                    var newRow = $(".prototype").find(".paymentHistoryDetailAssessmentListRow").clone();
                    newRow.find(".paymentHistoryDetailAssessmentListDescription").text(response["data"]["payment_assessment_item"][x]["description"]);
                    newRow.find(".paymentHistoryDetailAssessmentListAmount").text(systemUtility.insertDecimalPoints((response["data"]["payment_assessment_item"][x]["amount"]*1).toFixed(2)));
                    newRow.find(".paymentHistoryDetailAssessmentListRemarks").text(response["data"]["payment_assessment_item"][x]["remarks"]);
                    totalAmount += response["data"]["payment_assessment_item"][x]["amount"]*1;
                    $("#paymentHistoryDetailAssessmentList").append(newRow);
                }
            }
            $("#paymentHistoryDetailAssessmentListTotalAmount").text(systemUtility.insertDecimalPoints((totalAmount).toFixed(2)));
            $("#paymentHistoryDetail").modal("toggle");
        }else{
            systemUtility.showErrorMessage("#paymentHistoryListMessage", response["error"]);
        }
    });
};
paymentHistory.requestList = false;
paymentHistory.paymentList = function(toExport){
    $("#paymentHistoryListFilterSearch").button("loading");
    $("#paymentHistoryListPreviousPage").button("loading");
    $("#paymentHistoryListNextPage").button("loading");
    var dataFilter = {
        retrieve_type : 1,
        retrieve_total_amount : 1
        //cashier_ID : systemApplication.userInformation.userID
//        limit : 20,
//        offset : ((($("#paymentHistoryListCurrentPage").text()*1 - 1) > 0) ? ($("#paymentHistoryListCurrentPage").text()*1 - 1) : 0) * 20
    };
    if(typeof toExport === "undefined"){
        dataFilter.limit = 20;
        dataFilter.offset = ((($("#paymentHistoryListCurrentPage").text()*1 - 1) > 0) ? ($("#paymentHistoryListCurrentPage").text()*1 - 1) : 0) * 20;
    }
    if($("#paymentHistoryListFilterCashier").val()*1 !== 0){
            dataFilter.cashier_ID = $("#paymentHistoryListFilterCashier").val();
    }
    if($("#paymentHistoryListFilterSection").val()*1 !== 0){
            dataFilter.payee_class_section_ID = $("#paymentHistoryListFilterSection").val();
    }
    var currentDate =new Date();
    currentDate = (new Date((currentDate.getMonth()+1)+","+(currentDate.getDate())+","+(currentDate.getFullYear()))).getTime()/1000;
    ($("#paymentHistoryListFilterAssessmentItem").val()*1 > 0) ? dataFilter.assessment_item_ID = $("#paymentHistoryListFilterAssessmentItem").val() : null;
    dataFilter.start_datetime = (((new Date($("#paymentHistoryListFilterStartDatetime").val())) != "Invalid Date") ? (new Date($("#paymentHistoryListFilterStartDatetime").val())).getTime()/1000 : currentDate)*1;
    dataFilter.end_datetime = (((new Date($("#paymentHistoryListFilterEndDatetime").val())) != "Invalid Date") ? (new Date($("#paymentHistoryListFilterEndDatetime").val())).getTime()/1000 : currentDate)*1;

//    ($("#tellerTransactionUserLedgerDescriptionFilter").val() !== "") ? dataFilter.assessment_item_description = $("#tellerTransactionUserLedgerDescriptionFilter").val() : null;
//    ($("#tellerTransactionUserLedgerAmountFilter").val() !== "") ? dataFilter.amount = $("#tellerTransactionUserLedgerAmountFilter").val() : null;
//    ($("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() !== "") ? dataFilter.order_receipt_number = $("#tellerTransactionUserLedgerOrderReceiptNumberFilter").val() : null;
     
    if(dataFilter.end_datetime > dataFilter.start_datetime){
        dataFilter.end_datetime -= 28800;
    }
	if(dataFilter.end_datetime === dataFilter.start_datetime){
		dataFilter.end_datetime -= 28800;
	}
	dataFilter.start_datetime -= 7200; //starts at 6am
    if($(".paymentHistoryListSorter[sorted=1]").length){
        dataFilter.sort = {};
        switch($(".paymentHistoryListSorter[sorted=1]").attr("sorter_name")){
            case "payee_full_name" :
                dataFilter.sort.payee_account_basic_information__last_name = $(".paymentHistoryListSorter[sorted=1]").attr("sort")*1;
                dataFilter.sort.payee_account_basic_information__first_name = $(".paymentHistoryListSorter[sorted=1]").attr("sort")*1;
                dataFilter.sort.payee_account_basic_information__middle_name = $(".paymentHistoryListSorter[sorted=1]").attr("sort")*1;
                break;
            default:
                dataFilter.sort[$(".paymentHistoryListSorter[sorted=1]").attr("sorter_name")] = $(".paymentHistoryListSorter[sorted=1]").attr("sort")*1;
                break;
        }
    }
    if($("#paymentHistoryListFilterIDNumber").val() !== ""){
            dataFilter.identification_number = $("#paymentHistoryListFilterIDNumber").val();
    }
    if($("#paymentHistoryListFilterORNumber").val() !== ""){
            dataFilter.order_receipt_number = $("#paymentHistoryListFilterORNumber").val();
    }
    if($("#paymentHistoryListFilterAmount").val() !== ""){
            dataFilter.amount = $("#paymentHistoryListFilterAmount").val();
    }
    if($("#paymentHistoryListFilterYearLevel").val()*1 !== 0){
            dataFilter.payee_account_year_level = $("#paymentHistoryListFilterYearLevel").val();
    }
    if($("#paymentHistoryListFilterAcademicYear").val()*1 !== 0){
            dataFilter.payment_academic_year = $("#paymentHistoryListFilterAcademicYear").val();
    }
    if(paymentHistory.requestList){
        paymentHistory.requestList.abort();
    }
    paymentHistory.requestList = $.post("<?=api_url()?>c_account_payment/retrieveAccountLedger", dataFilter, function(data){
        paymentHistory.requestList = false;
        var response = JSON.parse(data);
        var totalPages = Math.ceil(response["result_count"]/20);
        $("#paymentHistoryListTotalResult").text(response["result_count"]);
        $("#paymentHistoryListTotalPage").text(totalPages);
        $("#paymentHistoryListTotalAmount").text(systemUtility.insertDecimalPoints((response["total_amount"]*1).toFixed(2)));
        if($("#paymentHistoryListCurrentPage").text()*1 <= totalPages){
            $("#paymentHistoryListBody").empty();
        }
        if(!response["error"].length){
            if($("#paymentHistoryListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".paymentHistoryListRow").clone();
                    newRow.attr("payment_assessment_item_ID", response["data"][x]["ID"]);
                    
                    var paymentDate = new Date((response["data"][x]["datetime"])*1000);//28800
                    newRow.find(".paymentHistoryListDatetime").attr("datetime", response["data"][x]["datetime"]);
                    newRow.find(".paymentHistoryListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
                    
                    newRow.find(".paymentHistoryListOrderReceiptNumber").append(response["data"][x]["order_receipt_number"]);
                    var payeePrefix = (typeof (response["data"][x]["payee_last_name"]+response["data"][x]["payee_first_name"]+response["data"][x]["payee_middle_name"]).length === "undefined") ? "no_account_" : "";
                    
                    newRow.find(".paymentHistoryListPayeeFullName").text(
                        response["data"][x][payeePrefix+"payee_last_name"]
                        + ", " +
                        response["data"][x][payeePrefix+"payee_first_name"]
                        +" "+
                        response["data"][x][payeePrefix+"payee_middle_name"]
                    );
                    newRow.find(".paymentHistoryListAssessmentItem").text(response["data"][x]["description"]);
                    newRow.find(".paymentHistoryListAmount").text(systemUtility.insertDecimalPoints((response["data"][x]["amount"]*1).toFixed(2)));
                    newRow.find(".paymentHistoryListRemarks").html(((response["data"][x]["status"]*1 === 3 ) ? "<strong>Voided. </strong>" : "") +((response["data"][x]["remarks"]!=="") ? response["data"][x]["remarks"]:response["data"][x]["payment_remarks"]));
                    
                    $("#paymentHistoryListBody").append(newRow);
                }
            }
        }
        if(toExport === 1){
            $("#paymentHistoryListTable").css("font-family", "arial");
            $("#paymentHistoryListTable").css("font-size","8px");
            $("#paymentHistoryListTable").css("letter-spacing","2");
            $("#paymentHistoryListTable").css("line-height","9px");
            $("#paymentHistoryListTable td").css("padding-top","0px");
            $("#paymentHistoryListTable td").css("padding-bottom","0px");
            $("#paymentHistoryListTable").find(".tablePagination").hide();
            $("#paymentHistoryListTablePrint").print();
            $("#paymentHistoryListTable").css("letter-spacing","");
            $("#paymentHistoryListTable").css("font-size","");
            $("#paymentHistoryListTable").css("font-family", "");
            $("#paymentHistoryListTable").find(".tablePagination").show();
            paymentHistory.paymentList();
        }else if(toExport === 2) {
            fnExcelReport();
            paymentHistory.paymentList();
        }
        $("#paymentHistoryListFilterSearch").button("reset");
        $("#paymentHistoryListPreviousPage").button("reset");
        $("#paymentHistoryListNextPage").button("reset");
        $("#paymentHistoryListCurrentPage").text( ($("#paymentHistoryListCurrentPage").text()*1 > $("#paymentHistoryListTotalPage").text()*1) ?  $("#paymentHistoryListTotalPage").text()*1 : $("#paymentHistoryListCurrentPage").text()*1);
    });
};
function fnExcelReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    var tab = document.getElementById('paymentHistoryListTable'); // id of table
    for(j = 2 ; j < tab.rows.length-1 ; j++) 
    {
        tab_text=tab_text+"<tr>"+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }
    
    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
    return (sa);
}
paymentHistory.changeParticularAssessmentItem = function(){
    $.post("<?=base_url()?>api/c_assessment_item/retrieveAssessmentItem", {tellering : 1}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $("#paymentHistoryListFilterAssessmentItem").empty();
            $("#paymentHistoryListFilterAssessmentItem").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentHistoryListFilterAssessmentItem").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
       }
   });
 
};
paymentHistory.listCashier = function(){
	$.post("<?=base_url()?>api/c_account/retrieveAccountBasicInformation", {account_type_ID : 5}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $("#paymentHistoryListFilterCashier").empty();
            $("#paymentHistoryListFilterCashier").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentHistoryListFilterCashier").append("<option value='"+response["data"][x]["account_ID"]+"' >"+response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+"</option>");
            }
       }
   });
}
paymentHistory.listSection = function(){
	$.post("<?=base_url()?>api/c_section/retrieveSection", {account_type_ID : 5}, function(data){
       var response = JSON.parse(data);
       if(!response["error"].length){
            $("#paymentHistoryListFilterSection").empty();
            $("#paymentHistoryListFilterSection").append("<option value='0' >None</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#paymentHistoryListFilterSection").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
       }
   });
}
</script>