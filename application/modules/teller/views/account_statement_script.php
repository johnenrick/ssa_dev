<script>
    /* global systemUtility, systemApplication*/
var accountStatement = {};
accountStatement.sectionList = [];
accountStatement.studentList = 0;
$(document).ready(function(){
    //filter
    accountStatement.refreshOptions();
    $("#accountStatementSchoolYearFilter").val(systemUtility.getCurrentAcademicYear());
    $("#accountStatementCourseFilter").change(function(){
        accountStatement.retrieveSection($("#accountStatementCourseFilter").val()*1, $("#accountStatementYearLevelFilter").val());
    });
    $("#accountStatementYearLevelFilter").change(function(){
        accountStatement.retrieveSection($("#accountStatementCourseFilter").val()*1, $("#accountStatementYearLevelFilter").val());
    });
    $(".accountStatementFilter").change(function(){
        $("#accountStatementStudentListBody").empty();
    });
    //form action buttons
    $("#accountStatementSectionFilter").change(function(){
        accountStatement.retrieveClassSectionList();
    });
    //section list page nav
    $("#accountStatementStudentListPreviousPage").click(function(){
        if($("#accountStatementStudentListCurrentPage").text()*1 -1){
            $("#accountStatementStudentListCurrentPage").text($("#accountStatementStudentListCurrentPage").text()*1 -1);
            accountStatement.retrieveClassSectionList();
        } 
    });
    $("#accountStatementStudentListNextPage").click(function(){
        $("#accountStatementStudentListCurrentPage").text($("#accountStatementStudentListCurrentPage").text()*1 + 1);
        accountStatement.retrieveClassSectionList();
    });
     //class section list action
    $("#accountStatementStudentListBody").on("click", ".accountStatementStudentListViewStatementButton", function(){
        $("#accountStatementAccountStatementPrint .accountStatementAccountStatementPrintList").empty();
        accountStatement.showAccountStatement($(this).parent().parent().attr("account_id"),$(this).parent().parent().find(".accountStatementStudentListName").text());
    });
    $("#accountStatementAccountStatementPrintButton").click(function(){
        $("#accountStatementAccountStatementPrint").css("font-family", "arial");
        $("#accountStatementAccountStatementPrint").css("letter-spacing","4");
        $("#accountStatementAccountStatementPrint").css("line-height","9px");
        $("#accountStatementAccountStatementPrint").print();
        $("#accountStatementAccountStatementPrint").css("letter-spacing","");
        $("#accountStatementAccountStatementPrint").css("font-size","");
        $("#accountStatementAccountStatementPrint").css("font-family", "");
    });
    $("#accountStatementAccountStatementBatchPrintButton").click(function(){
        $("#accountStatementAccountStatementBatchPrintButton").button("loading");
        accountStatement.retrieveClassSectionList(1);
    });
});
accountStatement.retrieveClassSectionList = function(toPrint){
    toPrint = (typeof toPrint === "undefined") ? false : true;
    var filter = {
        
        offset : ((($("#accountStatementStudentListCurrentPage").text()*1 - 1) > 0) ? ($("#accountStatementStudentListCurrentPage").text()*1 - 1) : 0) * 20,
        school_year : $("#accountStatementSchoolYearFilter").val()*1,
        section_ID : $("#accountStatementSectionFilter").val(),
        year_level : $("#accountStatementYearLevelFilter").val()
    };
    filter.limit = (toPrint) ? 0 : 20;
    var link = ($("#accountStatementSectionFilter").val()*1) ? "<?=api_url()?>c_class_section/retrieveClassSection" : "<?=api_url()?>c_class_section/retrieveNoClassSection";
    $.post(link, filter, function(data){
        var response = JSON.parse(data);
        $("#accountStatementStudentListTotalResult").text("0");
        if(!response["error"].length){
            if(toPrint){
               accountStatement.studentListLength = 0;
               $("#accountStatementAccountStatementPrint .accountStatementAccountStatementPrintList").empty();
               for(var x = 0; x < response["data"].length; x++) {
                    accountStatement.studentListLength++;
                    accountStatement.showAccountStatement(
                            response["data"][x]["account_ID"],
                            (   response["data"][x]["last_name"]
                                + ", " +
                                response["data"][x]["first_name"]
                                + " " +
                                response["data"][x]["middle_name"]).toUpperCase(),
                            1,
                            response["data"][x]["section_description"]
                            );
                }
            }else{
                var totalPages = Math.ceil(response["result_count"]/20);
                $("#accountStatementStudentListTotalResult").text(response["result_count"]);
                $("#accountStatementStudentListTotalPage").text(totalPages);
                if($("#accountStatementStudentListCurrentPage").text()*1 <= totalPages){
                    $("#accountStatementStudentListBody").empty();
                    for(var x = 0; x < response["data"].length; x++){
                        var newRow = $(".prototype").find(".accountStatementStudentListRow").clone();
                        newRow.attr("class_section_id", ($("#accountStatementSectionFilter").val()*1) ? response["data"][x]["ID"] : 0);
                        newRow.attr("account_id", response["data"][x]["account_ID"]);
                        newRow.find(".accountStatementStudentListName").text((
                                response["data"][x]["last_name"] 
                                + ", " +
                                response["data"][x]["first_name"]
                                +" "+
                                response["data"][x]["middle_name"]).toUpperCase());
                        newRow.find(".accountStatementStudentListIDNumber").text(response["data"][x]["identification_number"]);

                        accountStatement.refreshStudentListOption(newRow);
                        $("#accountStatementStudentListBody").append(newRow);

                    }
                }
            }
        }else{
            $("#accountStatementAccountStatementBatchPrintButton").button("reset");
        }
        $("#accountStatementStudentListCurrentPage").text( ($("#accountStatementStudentListCurrentPage").text()*1 > $("#accountStatementStudentListTotalPage").text()*1) ?  $("#accountStatementStudentListTotalPage").text()*1 : $("#accountStatementStudentListCurrentPage").text()*1);
    });
};

accountStatement.refreshOptions = function(){
    //school year
    $("#accountStatementSchoolYearFilter").empty();
    systemUtility.addAcademicYearSelectOption("#accountStatementSchoolYearFilter");
    //courses
    $.post("<?=api_url()?>c_course/retrieveCourse", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#accountStatementCourseFilter").val();
            $("#accountStatementCourseFilter").empty();
             
            for(var x = 0; x < response["data"].length; x++){
                $("#accountStatementCourseFilter").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
            (currentValue*1) ? $("#accountStatementCourseFilter").val(currentValue) : "";
            accountStatement.retrieveSection($("#accountStatementCourseFilter").val()*1, $("#accountStatementYearLevelFilter").val());
        }
    });
    
};
accountStatement.refreshStudentListOption = function(row){
    row.find(".accountStatementStudentListSection").empty();
    row.find(".accountStatementStudentListSection").append("<option value='0' >None</option>");
    for(var x = 0; x < accountStatement.sectionList.length; x++){
        row.find(".accountStatementStudentListSection").append("<option value='"+accountStatement.sectionList[x]["ID"]+"' >"+accountStatement.sectionList[x]["description"]+"</option>");
    }
};
accountStatement.retrieveSection = function(courseID, yearLevel){
    $("#accountStatementSectionFilter").empty();
    $.post("<?=api_url()?>c_section/retrieveSection", {course_ID : courseID, year_level : yearLevel}, function(data){
        var response = JSON.parse(data);
        accountStatement.sectionList = [];
        if(!response["error"].length){
            $("#accountStatementSectionFilter").append("<option value='0' >None</option>");
            accountStatement.sectionList = response["data"];
            for(var x = 0; x < response["data"].length; x++){
                 $("#accountStatementSectionFilter").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
            accountStatement.retrieveClassSectionList();
        }
    });
};
accountStatement.showAccountStatement = function(accountID, fullName, batchPrint, sectionDescription){
    batchPrint = (typeof batchPrint === "undefined") ? false : true;
    $("#accountStatementAccountStatementButton").button("loading");
    var dataFilter = {
        account_ID:accountID,
        academic_year : $("#accountStatementSchoolYearFilter").val()
    };
    if(($("#accountStatementSchoolYearFilter").val()*1 <= 1428336000)){//if school year 2015-16
        dataFilter.start_datetime = $("#accountStatementSchoolYearFilter").val();
        dataFilter.end_datetime = $("#accountStatementSchoolYearFilter").find("option:nth-child("+($("#accountStatementSchoolYearFilter").find("option:selected").index()+2)+")").val();
    }else{
        dataFilter.payment_academic_year = $("#accountStatementSchoolYearFilter").val();
    }
    dataFilter.ledger_assessment_type_ID = 133;//tuition only
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var academicDate = new Date(systemUtility.getCurrentAcademicYear()*1000);
            accountStatement.addAccountStatement(accountID, fullName, academicDate.getFullYear(), response["data"]["general_fee"], response["data"]["adjustment_fee"], response["data"]["ledger"], sectionDescription);
            if(batchPrint && ($("#accountStatementAccountStatementPrint .accountStatementAccountStatementPrintList").find(".accountStatementAccountStatementRow").length === accountStatement.studentListLength)){
                $("#accountStatementAccountStatement").modal("show");
                $("#accountStatementAccountStatementBatchPrintButton").button("reset");
            }
        }
        if(!batchPrint){
            $("#accountStatementAccountStatement").modal("show");
            $("#accountStatementAccountStatementButton").button("reset");
        }
        
    });
    
    
   
};

accountStatement.addAccountStatement = function(accountID, fullName, academicYear, courseAnnualFeeList, adjustmentFeeList, tuitionLedger, sectionDescription){
    var accountStatement = $(".prototype").find(".accountStatementAccountStatementRow").clone();
    accountStatement.find(".accountStatementAccountStatementGrade").text($("#accountStatementYearLevelFilter").val());
    accountStatement.find(".accountStatementAccountStatementSection").text(sectionDescription);
    accountStatement.find(".accountStatementAccountStatementTotalAmountPaid").text("0.00");
    accountStatement.find(".accountStatementAccountStatementTotalAnnualFee").text("0.00");
    accountStatement.find(".accountStatementAccountStatementTotalRemainingBalance").text("0.00");
    accountStatement.find(".accountStatementAccountStatementFullName").text(fullName);
    accountStatement.find(".accountStatementAccountStatementFullName").attr(accountID);
    accountStatement.find(".accountStatementAccountStatementAcademicYear").text(academicYear);
    var totalAccountStatementListAmount = 0;
    var total2 = 0;
    //general
    if(courseAnnualFeeList){
        for(var x = 0; x < courseAnnualFeeList.length;x++){
            totalAccountStatementListAmount += courseAnnualFeeList[x]["amount"]*1;
            total2+=courseAnnualFeeList[x]["amount"]*1;
        }
    }
    //selected
    if(adjustmentFeeList){
        for(var x = 0; x < adjustmentFeeList.length;x++){
            totalAccountStatementListAmount += adjustmentFeeList[x]["amount"]*1;
        }
    }
    accountStatement.find(".accountStatementAccountStatementTotalAnnualFee").text((totalAccountStatementListAmount).toFixed(2));
    var totalAmountPaid = 0;
    if(tuitionLedger){
        var newRow = false;
        for(var x = 0; x < tuitionLedger.length; x++){
            newRow = $(".prototype").find(".accountStatementAccountStatementListRow").clone();
            newRow.attr("id", "accountStatementAccountStatementList"+tuitionLedger[x]["assessment_item_ID"]);
            var paymentDate = new Date(tuitionLedger[x]["datetime"]*1000);
            newRow.find(".accountStatementAccountStatementListDatetime").append((paymentDate.getMonth()+1)+"/"+paymentDate.getDate()+"/"+paymentDate.getFullYear());
            newRow.find(".accountStatementAccountStatementListOR").append(tuitionLedger[x]["order_receipt_number"]);
            newRow.find(".accountStatementAccountStatementListAmount").append((tuitionLedger[x]["amount"]*1).toFixed(2));
            newRow.find(".accountStatementAccountStatementListRemarks").append((tuitionLedger[x]["remarks"] !== "") ? tuitionLedger[x]["remarks"] : tuitionLedger[x]["payment_remarks"]);
            accountStatement.find(".accountStatementAccountStatementList").append(newRow);
            totalAmountPaid += tuitionLedger[x]["amount"]*1;
        }
    }
    accountStatement.find(".accountStatementAccountStatementTotalAmountPaid").text((totalAmountPaid).toFixed(2));
    accountStatement.find(".accountStatementAccountStatementTotalRemainingBalance").text((totalAccountStatementListAmount - totalAmountPaid).toFixed(2));
    $("#accountStatementAccountStatementPrint .accountStatementAccountStatementPrintList").append(accountStatement);
};
</script>