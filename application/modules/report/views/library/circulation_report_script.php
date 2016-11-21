<script>
    /*global  systemUtility, systemApplication*/
var assessmentItemReport = {};
assessmentItemReport.sectionList = [];
assessmentItemReport.assessmentItemReportListFilter = {};
assessmentItemReport.assessmentItemDetail = false;
assessmentItemReport.assessmentItemDetailTable = false;
$(document).ready(function(){

    //list filter and sorting
    $(".assessmentItemReportListSorter").click(function(){
        $(".assessmentItemReportListSorter").attr("sorted",0);
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

        assessmentItemReport.paymentList();
    });
    $("#assessmentItemReportListFilterSearch").click(function(){
        assessmentItemReport.paymentList();
    });
    //pagination
    //item list
    $("#assessmentItemReportListPreviousPage").click(function(){
        if($("#assessmentItemReportListCurrentPage").text()*1 -1){
            $("#assessmentItemReportListCurrentPage").text($("#assessmentItemReportListCurrentPage").text()*1 -1);
            assessmentItemReport.paymentList();
        }
    });
    $("#assessmentItemReportListNextPage").click(function(){
        $("#assessmentItemReportListCurrentPage").text($("#assessmentItemReportListCurrentPage").text()*1 + 1);
        assessmentItemReport.paymentList();
    });
    //item detail list
    $("#assessmentItemReportAssessmentItemDetailPreviousPage").click(function(){
       if($("#assessmentItemReportAssessmentItemDetailCurrentPage").text()*1 -1){
           $("#assessmentItemReportAssessmentItemDetailCurrentPage").text($("#assessmentItemReportAssessmentItemDetailCurrentPage").text()*1 -1);
           assessmentItemReport.viewItemDetail();
       }
    });
    $("#assessmentItemReportAssessmentItemDetailNextPage").click(function(){
        $("#assessmentItemReportAssessmentItemDetailCurrentPage").text($("#assessmentItemReportAssessmentItemDetailCurrentPage").text()*1 + 1);
        assessmentItemReport.viewItemDetail();
    });
    $("#assessmentItemReportListBody").on("click", ".assessmentItemReportListOrderReceiptNumber", function(){
        assessmentItemReport.viewPaymentDetail($(this).text());
    });
    //assessmentItemReport.paymentList();
    //printing
    $("#assessmentItemReportListPrintReport").click(function(){
        assessmentItemReport.paymentList(1);
    });
    $("#assessmentItemReportListBody").on("click", ".assessmentItemReportListRow", function(){
       $("#assessmentItemReportAssessmentItemDetail").modal("toggle");
       $("#assessmentItemReportAssessmentItemDetailBody").empty();
       $("#assessmentItemReportAssessmentItemDetailTable").attr("payment_assessment_item_ID", $(this).attr("payment_assessment_item_ID"));
       if(assessmentItemReport.assessmentItemDetailTable){
           assessmentItemReport.assessmentItemDetailTable.showPage();
       }
    });

    assessmentItemReport.assessmentItemDetailTable = $("#assessmentItemReportAssessmentItemDetailTable").apipagination({
        apiUrl : systemApplication.url.apiUrl+"c_account_payment/retrievePaymentAssessmentItem",
        tableSorter : {
            2 : "payment__order_receipt_number",
            3 : "payee_account_basic_information__last_name,payee_account_basic_information__first_name,payee_account_basic_information__middle_name"
        },
        tableFilter : {order_receipt_number : "OR Number"},
        responseCallback : assessmentItemReport.itemDetailResponseCallback,
        customFilterGenerator : function(){
            var currentDate =new Date();
            currentDate = (new Date((currentDate.getMonth()+1)+","+(currentDate.getDate())+","+(currentDate.getFullYear()))).getTime()/1000;
            var filter = {
                retrieve_total_amount : 1,
                assessment_item_ID : $("#assessmentItemReportAssessmentItemDetailTable").attr("payment_assessment_item_ID"),
                start_datetime : ((new Date($("#assessmentItemReportListFilterStartDatetime").val())) != "Invalid Date") ? (new Date($("#assessmentItemReportListFilterStartDatetime").val())).getTime()/1000 : currentDate,
                end_datetime : ((new Date($("#assessmentItemReportListFilterEndDatetime").val())) != "Invalid Date") ? (new Date($("#assessmentItemReportListFilterEndDatetime").val())).getTime()/1000 : currentDate
            };
            return filter;
        }
    });
});
assessmentItemReport.itemDetailResponseCallback = function(response){

    ("total_amount" in response) ? $("#assessmentItemReportAssessmentItemDetailTotalAmount").text(systemUtility.insertDecimalPoints((response["total_amount"]*1).toFixed(2))) : 0;
    if(response["data"]){
        for(var x = 0; x < response["data"].length; x++){
            var newRow = $(".prototype").find(".assessmentItemReportAssessmentItemDetailRow").clone();
            newRow.attr("payment_assessment_item_ID", response["data"][x]["payment_assessment_item_ID"]);
            var datetime = new Date(response["data"][x]["datetime"]*1000);
            newRow.find(".assessmentItemReportAssessmentItemDetailDatetime").text((datetime.getMonth()+1)+"/"+datetime.getDate()+"/"+datetime.getFullYear());
            newRow.find(".assessmentItemReportAssessmentItemDetailOrderReceiptNumber").text(response["data"][x]["order_receipt_number"]);
            newRow.find(".assessmentItemReportAssessmentItemDetailPayeeFullName").text(response["data"][x]["payee_last_name"]+", "+(response["data"][x]["payee_first_name"]+"").charAt(0)+" "+(response["data"][x]["payee_middle_name"]+"").charAt(0)+".");
            newRow.find(".assessmentItemReportAssessmentItemDetailDetail").text((response["data"][x]["payment_remarks"]==="") ? response["data"][x]["remarks"] : response["data"][x]["payment_remarks"]);
            newRow.find(".assessmentItemReportAssessmentItemDetailAmount").text(systemUtility.insertDecimalPoints((response["data"][x]["amount"]*1).toFixed(2)));
            $("#assessmentItemReportAssessmentItemDetailTable").find("tbody").append(newRow);
        }
    }
};
assessmentItemReport.paymentList = function(toPrint){

    $("#assessmentItemReportListFilterSearch").button("loading");
    var dataFilter = {
        retrieve_type : 1,
        retrieve_total_amount : 1
    };
    if(typeof toPrint === "undefined"){
        dataFilter.limit = 20;
        dataFilter.offset = ((($("#assessmentItemReportListCurrentPage").text()*1 - 1) > 0) ? ($("#assessmentItemReportListCurrentPage").text()*1 - 1) : 0) * dataFilter.limit;
    }
    var currentDate =new Date();
    currentDate = (new Date((currentDate.getMonth()+1)+","+(currentDate.getDate())+","+(currentDate.getFullYear()))).getTime()/1000;
    dataFilter.start_datetime = ((new Date($("#assessmentItemReportListFilterStartDatetime").val())) != "Invalid Date") ? (new Date($("#assessmentItemReportListFilterStartDatetime").val())).getTime()/1000 : currentDate;
    dataFilter.end_datetime = ((new Date($("#assessmentItemReportListFilterEndDatetime").val())) != "Invalid Date") ? (new Date($("#assessmentItemReportListFilterEndDatetime").val())).getTime()/1000 : currentDate;
    //sorting
    if($(".assessmentItemReportListSorter[sorted=1]").length){
        dataFilter.sort = {};
        switch($(".assessmentItemReportListSorter[sorted=1]").attr("sorter_name")){
            default:
                dataFilter.sort[$(".assessmentItemReportListSorter[sorted=1]").attr("sorter_name")] = $(".assessmentItemReportListSorter[sorted=1]").attr("sort")*1;
                break;
        }
    }
    $.post(systemApplication.url.apiUrl+"c_circulation/retrieveCirculationList", dataFilter, function(data){
        var response = JSON.parse(data);
        var total = 0;
        console.log(response);
        var totalPages = 0;//Math.ceil(response["result_count"]/20);
        //$("#assessmentItemReportListTotalResult").text(response["result_count"]);
        //$("#assessmentItemReportListTotalPage").text(totalPages);
        //$("#assessmentItemReportListTotalAmount").text(systemUtility.insertDecimalPoints((response["total_amount"]*1).toFixed(2)));
        $("#assessmentItemReportListBody").empty();
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var newRow = $(".prototype").find(".assessmentItemReportListRow").clone();
                newRow.find(".assessmentItemReportListAssessmentTypeCode").append((response["data"][x]["description"]=="student")?"Grade "+response["data"][x]["year_level"]:"Faculty & Staff");
                newRow.find(".assessmentItemReportListAssessmentItem").text(response["data"][x]["total"]);
                ///newRow.find(".assessmentItemReportListAmount").text(systemUtility.insertDecimalPoints((response["data"][x]["total_amount"]*1).toFixed(2)));
                if(response["data"][x]["description"]!="student" || (response["data"][x]["description"]=="student" && response["data"][x]["year_level"] != null))
                {
                    $("#assessmentItemReportListBody").append(newRow);
                    total += parseInt(response["data"][x]["total"]);
                }
            }
        }
        $("#assessmentItemReportListTotalAmount").text(total);
        if(toPrint){
            $("#assessmentItemReportListTable").find(".tablePagination").hide();
            $("#assessmentItemReportListTablePrint").print();
            $("#assessmentItemReportListTable").find(".tablePagination").show();
            assessmentItemReport.paymentList();
        }
        $("#assessmentItemReportListFilterSearch").button("reset");
        $("#assessmentItemReportListCurrentPage").text( ($("#assessmentItemReportListCurrentPage").text()*1 > $("#assessmentItemReportListTotalPage").text()*1) ?  $("#assessmentItemReportListTotalPage").text()*1 : $("#assessmentItemReportListCurrentPage").text()*1);
    });
};
</script>
