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
    $("#accountStatementSchoolYearFilter").change(function(){
        $("#accountStatementSectionFilter").trigger("change");
    });
    $("#accountStatementYearLevelFilter").change(function(){
        accountStatement.retrieveSection($("#accountStatementCourseFilter").val()*1, $("#accountStatementYearLevelFilter").val());
    });
    $(".accountStatementFilter").change(function(){
        $("#accountStatementStudentListBody").empty();
        
    });
    //form action buttons
    $("#accountStatementSectionFilter").change(function(){
       if($(this).find("option:selected").text() !== "None"){
            accountStatement.retrieveClassSectionList();
        }
    });
     //class section list action
    $("#accountStatementStudentListBody").on("click", ".accountStatementStudentListViewStatementButton", function(){
        $("#accountStatementAccountStatementPrint").empty();
        accountStatement.showAccountStatement($(this).parent().parent().attr("account_id"),$(this).parent().parent().find(".accountStatementStudentListName").text());
    });
    $("#accountStatementAccountStatementPrintButton").click(function(){
        $("#accountStatementAccountStatementPrint").print();
    });
    $("#accountStatementAccountStatementBatchPrintButton").click(function(){
        $("#accountStatementAccountStatementBatchPrintButton").button("loading");
        
        
        $("#summaryTable").css("font-family", "arial");
        $("#summaryTable").css("font-size","8px");
        $("#summaryTable").css("letter-spacing","2");
        $("#summaryTable").css("line-height","9px");
        $("#summaryTable td").css("padding-top","0px");
        $("#summaryTable td").css("padding-bottom","0px");
        $("#summaryTable").parent().print();
        $("#summaryTable").css("letter-spacing","");
        $("#summaryTable").css("font-size","");
        $("#summaryTable").css("font-family", "");
            
            
        $("#accountStatementAccountStatementBatchPrintButton").button("reset");
    });
});
accountStatement.retrieveClassSectionList = function(toPrint){
    $("#accountStatementAccountStatementBatchPrintButton").button("loading");
    toPrint = (typeof toPrint === "undefined") ? false : true;
    var filter = {
        school_year : $("#accountStatementSchoolYearFilter").val()*1,
        year_level : $("#accountStatementYearLevelFilter").val()
    };
    if($("#accountStatementSectionFilter").val()*1){
        filter.section_ID = $("#accountStatementSectionFilter").val();
    }
    console.log("hey")
    var link = "<?=api_url()?>c_class_section/retrieveClassSection" ;
    $.post(link, filter, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
                $(".totatTuition").text(0);
                $(".totalSpecialFee").text(0);
                $(".totalOtherFee").text(0);
                $(".totalDiscount").text(0);
                $(".totalTotal").text(0);
                $(".totalTuitionBalance").text(0);
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
                    accountStatement.showAccountStatement(response["data"][x]["account_ID"]);
                }
        }
        $("#accountStatementAccountStatementBatchPrintButton").button("reset");
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
            accountStatement.retrieveSection();
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
            $("#accountStatementSectionFilter").append("<option value='0' >All</option>");
            accountStatement.sectionList = response["data"];
            for(var x = 0; x < response["data"].length; x++){
                 $("#accountStatementSectionFilter").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
            
        }else{
            
        }
    });
};
accountStatement.showAccountStatement = function(accountID){
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
    $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){//TUITION
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
            $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListTuitionBalance").text((totalAccountStatementListAmount).toFixed(2));
            
            var totalAmountPaid = 0;
            var tuitionLedger = response["data"]["ledger"];
            if(tuitionLedger){
                for(var x = 0; x < tuitionLedger.length; x++){
                    totalAmountPaid += tuitionLedger[x]["amount"]*1;
                }
            }
            
            $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListTuition").text(totalAmountPaid.toFixed(2));
            $(".totalTuition").text(($(".totalTuition").text()*1+totalAmountPaid).toFixed(2));
            $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListTuitionBalance").text((totalAccountStatementListAmount - totalAmountPaid).toFixed(2));
            $(".totalTuitionBalance").text(($(".totalTuitionBalance").text()*1+totalAccountStatementListAmount - totalAmountPaid).toFixed(2));
            var totalAmountAdjustment = 0;
            var adjustmentFeeList = response["data"]["adjustment_fee"];
            if(adjustmentFeeList){
                for(var x = 0; x < adjustmentFeeList.length;x++){
                    totalAmountAdjustment += adjustmentFeeList[x]["amount"]*1;
                }
            }
            $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListDiscount").text(totalAmountAdjustment.toFixed(2));
            $(".totalDiscount").text(($(".totalDiscount").text()*1+totalAmountAdjustment).toFixed(2));
            dataFilter.ledger_assessment_type_ID = 132;//Special fee only
            $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){//TUITION
                var response = JSON.parse(data);
                var totalAmountPaid1 = 0;
                if(!response["error"].length){
                    var tuitionLedger = response["data"]["ledger"];
                    if(tuitionLedger){
                        for(var x = 0; x < tuitionLedger.length; x++){
                            totalAmountPaid1 += tuitionLedger[x]["amount"]*1;
                        }
                    }
                    $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListSpecialFee").text(totalAmountPaid1.toFixed(2));
                    $(".totalSpecialFee").text(($(".totalSpecialFee").text()*1+totalAmountPaid1).toFixed(2));
                }
                dataFilter.ledger_assessment_type_ID = 1;//DF only                
                $.post(systemApplication.url.apiUrl+"c_course_annual_fee/retrieveAccountStatement", dataFilter, function(data){//TUITION
                    var response = JSON.parse(data);
                    var totalAmountPaid2 = 0;
                    if(!response["error"].length){
                        var tuitionLedger = response["data"]["ledger"];
                        if(tuitionLedger){
                            for(var x = 0; x < tuitionLedger.length; x++){
                                totalAmountPaid2 += tuitionLedger[x]["amount"]*1;
                            }
                        }
                        $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListOtherFee").text(totalAmountPaid2.toFixed(2));
                        $(".totalOtherFee").text(($(".totalOtherFee").text()*1+totalAmountPaid2).toFixed(2));
                    }
                    $(".accountStatementStudentListRow[account_id="+accountID+"] .accountStatementStudentListTotal").text((totalAmountPaid+totalAmountPaid2+totalAmountPaid1+totalAmountAdjustment).toFixed(2));
                    $(".totalTotal").text(($(".totalTotal").text()*1+totalAmountPaid+totalAmountPaid2+totalAmountPaid1+totalAmountAdjustment).toFixed(2));
                });
            });
        }
    });
    
    
    
    
   
};

accountStatement.addAccountStatement = function(accountID, fullName, academicYear, courseAnnualFeeList, adjustmentFeeList, tuitionLedger){
    var accountStatement = $(".prototype").find(".accountStatementAccountStatementRow").clone();
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
    
    accountStatement.find(".accountStatementAccountStatementTotalAmountPaid").text((totalAmountPaid).toFixed(2));
    accountStatement.find(".accountStatementAccountStatementTotalRemainingBalance").text((totalAccountStatementListAmount - totalAmountPaid).toFixed(2));
    $("#accountStatementAccountStatementPrint").append(accountStatement);
};
</script>