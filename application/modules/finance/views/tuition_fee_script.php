<script>
    /*global systemUtility*/
var tuitionFeeManagement = {};
tuitionFeeManagement.sectionList = [];
tuitionFeeManagement.tuitionFeeListFilter = {};
$(document).ready(function(){
    tuitionFeeManagement.retrieveTuitionFeeList();
    //form
    tuitionFeeManagement.refreshOptions();
    $("#tuitionFeeManagementCourseFilter").change(function(){
        $("#tuitionFeeManagementYearLevelFilter").empty();
        $("#tuitionFeeManagementYearLevelFilter").append("<option value='0'>All</option>");
        for(var x = 1; x <= $(this).find("option[value='"+$(this).val()+"']").attr("level_number")*1; x++){
            $("#tuitionFeeManagementYearLevelFilter").append("<option value='"+x+"'>"+x+"</option>");
        }
    });
    $("#tuitionFeeManagementAssessmentType").change(function(){
        $.post("<?=api_url()?>c_assessment_item/retrieveAssessmentItem", { assessment_type_ID : $(this).val() }, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                var currentValue = $("#tuitionFeeManagementAssessmentItem").attr("viewing_value");
                console.log(currentValue);
                $("#tuitionFeeManagementAssessmentItem").empty();

                for(var x = 0; x < response["data"].length; x++){
                    $("#tuitionFeeManagementAssessmentItem").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
                }
                (currentValue*1) ? $("#tuitionFeeManagementAssessmentItem").val(currentValue): null;
                var currentValue = $("#tuitionFeeManagementAssessmentItem").attr("viewing_value", 0);
                $("#tuitionFeeManagementAssessmentItem").trigger("change");
            }
        });
    });
	$("#tuitionFeeManagementSchoolYearFilter").change(function(){
		if($("#tuitionFeeManagementID").val()*1){
			$("#tuitionFeeManagementSaveAsFormButton").show();
			$("#tuitionFeeManagementSaveAsFormButton span").text($("#tuitionFeeManagementSchoolYearFilter option:selected").text());
		}
	});
    //form action buttons
    $("#tuitionFeeManagementCreateFormButton").click(function(){
        tuitionFeeManagement.changeFormAction(1);
        tuitionFeeManagement.refreshOptions();
        $("#tuitionFeeManagementForm").trigger("reset");
		$("#tuitionFeeManagementID").val("0")
        $("#tuitionFeeManagementForm").attr("action", "<?=api_url()?>c_course_annual_fee/createCourseAnnualFee");
        $("#tuitionFeeManagementDiv").slideDown();
        $("#tuitionFeeManagementSchoolYearFilter").val(systemUtility.getCurrentAcademicYear());
    });
    $("#tuitionFeeManagementSubmitFormButton").click(function(){ 
        $("#tuitionFeeManagementForm").submit();
    });
	$("#tuitionFeeManagementSaveAsFormButton").click(function(){
	$("#tuitionFeeManagementForm").attr("action", "<?=api_url()?>c_course_annual_fee/createCourseAnnualFee");
		$("#tuitionFeeManagementForm").submit();
	});
    $("#tuitionFeeManagementCloseFormButton").click(function(){
        tuitionFeeManagement.changeFormAction(2);
    });
    $("#tuitionFeeManagementForm").ajaxForm({
        success : function(data){
            console.log(data);
            var response = JSON.parse(data);
            if(!response["error"].length){
                tuitionFeeManagement.retrieveTuitionFeeList();
                $("#tuitionFeeManagementForm").trigger("reset");
                $("#tuitionFeeManagementDiv").slideUp();
                tuitionFeeManagement.changeFormAction(2);
            }else{
                $("#tuitionFeeManagementMessage").html(response["error"][0]["message"]);
                $("#tuitionFeeManagementMessage").show();
                setTimeout(function(){
                    $("#tuitionFeeManagementMessage").html("");
                    $("#tuitionFeeManagementMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#tuitionFeeManagementTuitionFeeListPreviousPage").click(function(){
        if($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 -1){
            $("#tuitionFeeManagementTuitionFeeListCurrentPage").text($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 -1);
            tuitionFeeManagement.retrieveTuitionFeeList();
        } 
     });
    $("#tuitionFeeManagementTuitionFeeListNextPage").click(function(){
        $("#tuitionFeeManagementTuitionFeeListCurrentPage").text($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 + 1);
        tuitionFeeManagement.retrieveTuitionFeeList();
    });
     //class section list action
    $("#tuitionFeeManagementTuitionFeeListBody").on("click", ".tuitionFeeManagementTuitionFeeListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#tuitionFeeManagementTuitionFeeListBody").on("click", ".tuitionFeeManagementTuitionFeeListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#tuitionFeeManagementTuitionFeeListBody").on("click", ".tuitionFeeManagementTuitionFeeListYesRemoveButton", function(){
       tuitionFeeManagement.removeTuitionFeeList($(this).parent().parent());
    });

    $("#tuitionFeeManagementTuitionFeeListBody").on("click", ".tuitionFeeManagementTuitionFeeListYesChangeButton", function(){
       tuitionFeeManagement.saveTuitionFeeList($(this).parent().parent());
    });
    //list filter and sorting
    $(".tuitionFeeManagementTuitionFeeListSorter").click(function(){ 
        $(".tuitionFeeManagementTuitionFeeListSorter").attr("sorted",0);
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
        
        tuitionFeeManagement.retrieveTuitionFeeList();
    });
    $("#tuitionFeeManagementTuitionFeeListFilterSearch").click(function(){
      
        tuitionFeeManagement.tuitionFeeListFilter = {};
        if($("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val()){
            tuitionFeeManagement.tuitionFeeListFilter.course_ID = ($("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val() - $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val()%100)/100;
            tuitionFeeManagement.tuitionFeeListFilter.year_level =($("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val()%100)+"";
        }
        ($("#tuitionFeeManagementTuitionFeeListFilterDescription").val() !== "") ? tuitionFeeManagement.tuitionFeeListFilter.description = $("#tuitionFeeManagementTuitionFeeListFilterDescription").val() : null;
        ($("#tuitionFeeManagementTuitionFeeListFilterAssessmentItem").val() !== "") ? tuitionFeeManagement.tuitionFeeListFilter.assessment_item_description = $("#tuitionFeeManagementTuitionFeeListFilterAssessmentItem").val() : null;
        ($("#tuitionFeeManagementTuitionFeeListAmount").val() !== "") ? tuitionFeeManagement.tuitionFeeListFilter.amount = $("#tuitionFeeManagementTuitionFeeListAmount").val() : null;
        tuitionFeeManagement.tuitionFeeListFilter.type = $("#tuitionFeeManagementTuitionFeeListFilterType").val();
        $("#tuitionFeeManagementTuitionFeeListBody").empty();
        tuitionFeeManagement.retrieveTuitionFeeList({retrieve_type : 1});
    });
    //entry
    $("#tuitionFeeManagementTuitionFeeListBody").on("click", ".tuitionFeeManagementTuitionFeeListViewButton", function(){
        tuitionFeeManagement.viewTuitionFee($(this).parent().parent().attr("course_annual_fee_id"));
    });
	$("#tuitionFeeManagementTuitionFeeListFilterSchoolYearFilter").change(function(){
		tuitionFeeManagement.retrieveTuitionFeeList();
	});
});
tuitionFeeManagement.viewTuitionFee = function(courseAnnualFeeID){
    $("#tuitionFeeManagementForm").trigger("reset");
    //tuitionFeeManagement.refreshOptions();
    $.post("<?=  api_url()?>c_course_annual_fee/retrieveCourseAnnualFee",{ID : courseAnnualFeeID},function(data){
        console.log(data);
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#tuitionFeeManagementForm").trigger("reset");
            $("#tuitionFeeManagementID").val(response["data"]["ID"]*1);
            $("#tuitionFeeManagementSchoolYearFilter").val(response["data"]["academic_year"]);
            $("#tuitionFeeManagementCourseFilter").val(response["data"]["course_ID"]);
            $("#tuitionFeeManagementYearLevelFilter").val(response["data"]["year_level"]);
            $("#tuitionFeeManagementAssessmentType").val(response["data"]["assessment_type_ID"]);
            $("#tuitionFeeManagementTellering").val(response["data"]["tellering"]);
            $("#tuitionFeeManagementAssessmentItem").attr("viewing_value",response["data"]["assessment_item_ID"]);
            $("#tuitionFeeManagementAssessmentType").trigger("change");
            $("#tuitionFeeManagementDescription").val(response["data"]["description"]);
            $("#tuitionFeeManagementAmount").val(response["data"]["amount"]);
            $("#tuitionFeeManagementType").val(response["data"]["type"]);
            tuitionFeeManagement.changeFormAction(1);
            $("#tuitionFeeManagementForm").attr("action", "<?=api_url()?>c_course_annual_fee/updateCourseAnnualFee");
            $("#tuitionFeeManagementDiv").slideDown();
        }
    });
}
tuitionFeeManagement.removeTuitionFeeList = function(row){
    $.post("<?=  api_url()?>c_course_annual_fee/deleteCourseAnnualFee",{ID : row.attr("course_annual_fee_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            tuitionFeeManagement.retrieveTuitionFeeList();
        }
    });
}
tuitionFeeManagement.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#tuitionFeeManagementCloseFormButton").show();
            $("#tuitionFeeManagementSubmitFormButton").show();
            $("#tuitionFeeManagementCreateFormButton").hide();
			$("#tuitionFeeManagementSaveAsFormButton").hide();
            break;
        case 2://Close Button
            $("#tuitionFeeManagementDiv").slideUp();
            $("#tuitionFeeManagementCloseFormButton").hide();
            $("#tuitionFeeManagementSubmitFormButton").hide();
            $("#tuitionFeeManagementCreateFormButton").show();
			$("#tuitionFeeManagementSaveAsFormButton").hide();
            break;
    }
};
tuitionFeeManagement.retrieveTuitionFeeList = function(filter){
    $("#tuitionFeeManagementTuitionFeeListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(var x in tuitionFeeManagement.tuitionFeeListFilter){
        filter[x] = tuitionFeeManagement.tuitionFeeListFilter[x];
    }
    filter.limit = 20;
    filter.retrieve_type = 1;
	filter.academic_year = $("#tuitionFeeManagementTuitionFeeListFilterSchoolYearFilter").val();
    filter.offset = ((($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 - 1) > 0) ? ($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").length){
        filter.sort = {};
        if($(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sort")*1;
        }else if($(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sorter_name")] = $(".tuitionFeeManagementTuitionFeeListSorter[sorted=1]").attr("sort")*1;
        }
    }
    
    $.post("<?=api_url()?>c_course_annual_fee/retrieveCourseAnnualFee", filter, function(data){
        var response = JSON.parse(data);
        var totalPages = Math.ceil(response["result_count"]/20);
        $("#tuitionFeeManagementTuitionFeeListTotalResult").text(response["result_count"]);
        $("#tuitionFeeManagementTuitionFeeListTotalPage").text(totalPages);
        if(!response["error"].length){
            $("#tuitionFeeManagementTuitionFeeListBody").empty();
            if($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".tuitionFeeManagementTuitionFeeListRow").clone();
                    newRow.attr("course_annual_fee_id", response["data"][x]["ID"]);
                    newRow.find(".tuitionFeeManagementTuitionFeeListCourseYear").text((response["data"][x]["year_level"]*1) ? response["data"][x]["course_description"] + " " + response["data"][x]["year_level"] : "All "+ response["data"][x]["course_description"]);
                    newRow.find(".tuitionFeeManagementTuitionFeeListDescription").text(response["data"][x]["description"]);
                    newRow.find(".tuitionFeeManagementTuitionFeeListAssessmentItem").text(response["data"][x]["assessment_item_description"]);
                    newRow.find(".tuitionFeeManagementTuitionFeeListAssessmentType").text((response["data"][x]["type"]*1 === 1) ? "All" : "Selected");
                    newRow.find(".tuitionFeeManagementTuitionFeeListAmount").text((response["data"][x]["amount"]*1).toFixed(2));
                    newRow.find(".confirmButton").hide();
                    $("#tuitionFeeManagementTuitionFeeListBody").append(newRow);
                }
            }
        }
        $("#tuitionFeeManagementTuitionFeeListFilterSearch").button("reset");
        $("#tuitionFeeManagementTuitionFeeListCurrentPage").text( ($("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1 > $("#tuitionFeeManagementTuitionFeeListTotalPage").text()*1) ?  $("#tuitionFeeManagementTuitionFeeListTotalPage").text()*1 : $("#tuitionFeeManagementTuitionFeeListCurrentPage").text()*1);
    });
};
tuitionFeeManagement.refreshOptions = function(){
    //school year
    $("#tuitionFeeManagementSchoolYearFilter").empty();
    var currentDate = new Date();
//    for(var x = 1990; x <= currentDate.getFullYear(); x++){
//        $("#tuitionFeeManagementSchoolYearFilter").append("<option value='"+(new Date("1,1,"+x).getTime())/1000+"' >"+x+"</option>");
//    }
    systemUtility.addAcademicYearSelectOption("#tuitionFeeManagementSchoolYearFilter");
	systemUtility.addAcademicYearSelectOption("#tuitionFeeManagementTuitionFeeListFilterSchoolYearFilter");
	$("#tuitionFeeManagementTuitionFeeListFilterSchoolYearFilter").val(systemUtility.getCurrentAcademicYear());
    //courses
    $.post("<?=api_url()?>c_course/retrieveCourse", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#tuitionFeeManagementCourseFilter").val();
            var currentFilterValue = $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val();
            $("#tuitionFeeManagementCourseFilter").empty();
            $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").empty();
            $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").append("<option value='' year_level='"+0+"' >All</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#tuitionFeeManagementCourseFilter").append("<option value='"+response["data"][x]["ID"]+"' level_number='"+response["data"][x]["level_number"]+"' >"+response["data"][x]["description"]+"</option>");
                $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").append("<option value='"+((response["data"][x]["ID"]*100)+0)+"' year_level='"+0+"' >"+response["data"][x]["description"]+"</option>");
                for(var y = 1; y <= response["data"][x]["level_number"]; y++){
                    $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").append("<option value='"+((response["data"][x]["ID"]*100)+y)+"' year_level='"+y+"' >"+response["data"][x]["description"]+" "+y+"</option>");
                }
            }
            (currentValue*1) ? $("#tuitionFeeManagementCourseFilter").val(currentValue): null;
            (currentFilterValue*1) ? $("#tuitionFeeManagementTuitionFeeListFilterCourseLevel").val(currentFilterValue): null;
            $("#tuitionFeeManagementCourseFilter").trigger("change");
        }
    });
    $.post("<?=api_url()?>c_assessment_type/retrieveAssessmentType", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#tuitionFeeManagementAssessmentType").val();
            $("#tuitionFeeManagementAssessmentType").empty();
            
            for(var x = 0; x < response["data"].length; x++){
                $("#tuitionFeeManagementAssessmentType").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
            (currentValue*1) ? $("#tuitionFeeManagementAssessmentType").val(currentValue): null;
            $("#tuitionFeeManagementAssessmentType").trigger("change");
        }
    });
};
tuitionFeeManagement.saveTuitionFeeList = function(row){
    var newData = {
        previous_section_ID : row.attr("class_section_id"),
        section_ID : row.find(".tuitionFeeManagementTuitionFeeListSection").val(),
        account_ID : row.attr("account_id"),
        school_year : $("#tuitionFeeManagementSchoolYearFilter").val()
    };
    $.post("<?=api_url()?>c_course_annual_fee/createCourseAnnualFee", newData, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            //tuitionFeeManagement.retrieveTuitionFeeList();
        }else{
            
        }
    });
};
</script>