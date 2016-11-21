<script>
var clubManagement = {};
clubManagement.sectionList = [];
var currentDate = new Date();
$(document).ready(function(){
    //filter
    clubManagement.refreshOptions();
    $("#clubManagementSchoolYearFilter").val(systemUtility.getCurrentAcademicYear());
    $("#clubManagementSchoolYearFilter").change(function(){
        clubManagement.retrieveClubList();
    });
    $(".clubManagementFilter").change(function(){
        $("#clubManagementStudentListBody").empty();
    });
    //form action buttons
    $("#clubManagementCreateFormButton").click(function(){
        clubManagement.changeFormAction(1);
        clubManagement.refreshOptions();
        $("#clubManagementForm").trigger("reset");
        $("#clubManagementForm").attr("action", "<?=api_url()?>c_section/createSection");
        $("#clubManagementDiv").slideDown();
        var currentDate = new Date();
        $("#clubManagementSchoolYear").val((new Date("1,1,"+currentDate.getFullYear()).getTime())/1000);
    });
    $("#clubManagementSubmitFormButton").click(function(){
        $("#clubManagementForm").submit();
    });
    $("#clubManagementCloseFormButton").click(function(){
        clubManagement.changeFormAction(2);
    });
    $("#clubManagementForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                clubManagement.retrieveClubList();
                clubManagement.changeFormAction(2);
            }else{
                $("#clubManagementMessage").html(response["error"][0]["message"]);
                $("#clubManagementMessage").show();
                setTimeout(function(){
                    $("#clubManagementMessage").html("");
                    $("#clubManagementMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    $("#clubManagementSectionFilter").change(function(){
        clubManagement.retrieveClubList();
    });
    //section list page nav
    $("#clubManagementStudentListPreviousPage").click(function(){
        if($("#clubManagementStudentListCurrentPage").text()*1 -1){
            $("#clubManagementStudentListCurrentPage").text($("#clubManagementStudentListCurrentPage").text()*1 -1);
            clubManagement.retrieveClubList();
        } 
     });
     $("#clubManagementStudentListNextPage").click(function(){
         $("#clubManagementStudentListCurrentPage").text($("#clubManagementStudentListCurrentPage").text()*1 + 1);
         clubManagement.retrieveClubList();
     });
     //class section list action
     $("#clubManagementStudentListBody").on("click", ".clubManagementStudentListRemoveButton", function(){
        $(this).parent().find(".confirmButton").show();
        $(this).parent().find(".actionButton").hide();
     });
     $("#clubManagementStudentListBody").on("click", ".clubManagementStudentListNoRemoveButton", function(){
        $(this).parent().find(".confirmButton").hide();
        $(this).parent().find(".actionButton").show();
        $(this).parent().find(".confirmChangeButton").hide();
     });
     $("#clubManagementStudentListBody").on("click", ".clubManagementStudentListYesRemoveButton", function(){
        clubManagement.removeSectionList($(this).parent().parent());
     });
     
     $("#clubManagementStudentListBody").on("change", ".clubManagementStudentListSection", function(){
        if($(this).val()*1 !== $(this).attr("default_value")*1){
            
             $(this).parent().parent().find(".confirmChangeButton").show();
             $(this).parent().parent().find(".clubManagementStudentListRemoveButton").hide();
             $(this).parent().parent().find(".confirmButton").hide();
         }else{
             $(this).parent().parent().find(".confirmChangeButton").hide();
             $(this).parent().parent().find(".clubManagementStudentListRemoveButton").show();
             $(this).parent().parent().find(".confirmButton").hide();
         }
     });
     $("#clubManagementStudentListBody").on("click", ".clubManagementStudentListYesChangeButton", function(){
        clubManagement.saveClubList($(this).parent().parent());
     });
     //add new student
     $("#clubManagementViewNewStudent").click(function(){
         clubManagement.viewNewStudent();
     });
     $("#clubManagementAddStudentButton").click(function(){
         clubManagement.addNewStudent();
     });
     
});
clubManagement.viewSctionInformation = function(sectionID){
    $("#clubManagementForm").trigger("reset");
    clubManagement.refreshOptions();
    $.post("<?=  api_url()?>c_section/retrieveSection",{ID : sectionID},function(data){
        
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#clubManagementForm").trigger("reset");
            $("#clubManagementID").val(response["data"]["ID"]*1);
            $("#clubManagementDescription").val(response["data"]["description"]);
            $("#clubManagementCourse").val(response["data"]["course_ID"]);
            $("#clubManagementYearLevel").val(response["data"]["year_level"]);
            
            $("#clubManagementForm").attr("action", "<?=api_url()?>c_section/updateSection");
            $("#clubManagementDiv").slideDown();
        }
    });
}
clubManagement.removeSectionList = function(row){
    $.post("<?=  api_url()?>c_club/deleteClub",{ID : row.attr("class_section_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            clubManagement.retrieveClubList();
        }
    });
}
clubManagement.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#clubManagementCloseFormButton").show();
            $("#clubManagementSubmitFormButton").show();
            $("#clubManagementCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#clubManagementDiv").slideUp();
            $("#clubManagementCloseFormButton").hide();
            $("#clubManagementSubmitFormButton").hide();
            $("#clubManagementCreateFormButton").show();
            break;
    }
};
clubManagement.retrieveClubList = function(){
    var filter = {
        limit : 20,
        offset : ((($("#clubManagementStudentListCurrentPage").text()*1 - 1) > 0) ? ($("#clubManagementStudentListCurrentPage").text()*1 - 1) : 0) * 20,
        school_year : $("#clubManagementSchoolYearFilter").val(),
        subject_ID : $("#clubManagementSectionFilter").val()
        
    };
    console.log(filter);
    var link = ($("#clubManagementSectionFilter").val()*1) ? "<?=api_url()?>c_club/retrieveClub" : "<?=api_url()?>c_club/retrieveNoClub";
    
    $.post(link, filter, function(data){
        var response = JSON.parse(data);
        $("#clubManagementStudentListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#clubManagementStudentListTotalResult").text(response["result_count"]);
            $("#clubManagementStudentListTotalPage").text(totalPages);
            if($("#clubManagementStudentListCurrentPage").text()*1 <= totalPages){
                $("#clubManagementStudentListBody").empty();
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".clubManagementStudentListRow").clone();
                    newRow.attr("class_section_id", ($("#clubManagementSectionFilter").val()*1) ? response["data"][x]["ID"] : 0);
                    newRow.attr("account_id", response["data"][x]["account_ID"]);
                    newRow.find(".clubManagementStudentListName").text(
                            response["data"][x]["last_name"] 
                            + ", " +
                            response["data"][x]["first_name"]
                            +" "+
                            response["data"][x]["middle_name"]);
                    newRow.find(".clubManagementStudentListIDNumber").text(response["data"][x]["identification_number"]);

                    newRow.find(".confirmButton").hide();
                    newRow.find(".confirmChangeButton").hide();
                    clubManagement.refreshStudentListOption(newRow);
                    newRow.find(".clubManagementStudentListSection").val(response["data"][x]["section_ID"]);
                    newRow.find(".clubManagementStudentListSection").attr("default_value", ($("#clubManagementSectionFilter").val()*1) ? response["data"][x]["section_ID"] : 0);
                    $("#clubManagementStudentListBody").append(newRow);
                }
                
            }
        }
        $("#clubManagementStudentListCurrentPage").text( ($("#clubManagementStudentListCurrentPage").text()*1 > $("#clubManagementStudentListTotalPage").text()*1) ?  $("#clubManagementStudentListTotalPage").text()*1 : $("#clubManagementStudentListCurrentPage").text()*1);
    });
};
clubManagement.viewNewStudent = function(){
    if($("#clubManagementIdentificationNumber").val()*1 === 0){
        return false;
    }
    $.post("<?=api_url()?>c_account/retrieveAccountBasicInformation", {identification_number : $("#clubManagementIdentificationNumber").val()}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#clubManagementNewStudentFullName").attr("account_id", response["data"]["account_ID"]);
            $("#clubManagementNewStudentFullName").text(
                              response["data"]["last_name"] 
                              + ", " +
                              response["data"]["first_name"]
                              +" "+
                              response["data"]["middle_name"]);
            $("#clubManagementNewStudentIdentificationNumber").text(response["data"]["identification_number"]);
            $("#clubManagementAddStudentButton").show();
        }else{
            $("#clubManagementNewStudentFullName").text("Student Not Found");
            $("#clubManagementNewStudentIdentificationNumber").text("");
        }
    });
};
clubManagement.refreshOptions = function(){
    //school year
    $("#clubManagementSchoolYearFilter").empty();
    /*for(var x = 1990; x <= 2040; x++){
        $().append("<option value='"+(new Date("1,1,"+x).getTime())/1000+"' >"+x+"</option>");
    }*/
    systemUtility.addAcademicYearSelectOption("#clubManagementSchoolYearFilter");
    //courses
    setTimeout(function(){
       clubManagement.retrieveSubjectClub(); 
    }, 10);
    
};
clubManagement.refreshStudentListOption = function(row){
    
    row.find(".clubManagementStudentListSection").empty();
    row.find(".clubManagementStudentListSection").append("<option value='0' >None</option>");
    for(var x = 0; x < clubManagement.sectionList.length; x++){
        
        row.find(".clubManagementStudentListSection").append("<option value='"+clubManagement.sectionList[x]["ID"]+"' >"+clubManagement.sectionList[x]["description"]+"</option>");
    }
}
clubManagement.retrieveSubjectClub = function(){
    $("#clubManagementSectionFilter").empty();
    var filter = {
        year_level : 101, 
        school_year : $("#clubManagementSchoolYearFilter").val() //(new Date("1,1,"+currentDate.getFullYear()).getTime())/1000
    };
    console.log(filter);
    $.post("<?=api_url()?>c_subject/retrieveSubject", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);
        clubManagement.sectionList = [];
        if(!response["error"].length){
            $("#clubManagementSectionFilter").append("<option value='0' >None</option>");
            clubManagement.sectionList = response["data"];
            for(var x = 0; x < response["data"].length; x++){
                 $("#clubManagementSectionFilter").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
            clubManagement.retrieveClubList();
        }else{
            
        }
    });
};
clubManagement.addNewStudent = function(){
    if($("#clubManagementNewStudentFullName").attr("account_id")*1 === 0){
        return false;
    }
    var newData = {
        subject_ID : $("#clubManagementSectionFilter").val(),
        account_ID : $("#clubManagementNewStudentFullName").attr("account_id")*1,
        school_year : $("#clubManagementSchoolYearFilter").val()
    };
    $.post("<?=api_url()?>c_club/createclub", newData, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            clubManagement.retrieveClubList();
        }else{
            
            $("#clubManagementNewStudentFullName").text(response["error"][0]["message"]);
            setTimeout(function(){
                $("#clubManagementNewStudentIdentificationNumber").text("");
                $("#clubManagementNewStudentFullName").text("");
            }, 1000);
        }
        $("#clubManagementNewStudentFullName").attr("account_id",0);
        $("#clubManagementAddStudentButton").hide();
    });
};
clubManagement.saveClubList = function(row){
    var newData = {
        previous_subject_ID : row.attr("class_section_id"),
        subject_ID : row.find(".clubManagementStudentListSection").val(),
        account_ID : row.attr("account_id"),
        school_year : $("#clubManagementSchoolYearFilter").val()
    };
    $.post("<?=api_url()?>c_club/createClub", newData, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            //clubManagement.retrieveClubList();
        }else{
            
        }
    });
};
</script>