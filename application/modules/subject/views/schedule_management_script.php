<script>
var scheduleManagement = {};
var currentDate = new Date();

$(document).ready(function(){
	scheduleManagement.retrieveScheduleList();
    
    $("#scheduleManagementSchoolYear").val(systemUtility.getCurrentAcademicYear());
	//form action buttons
    $("#scheduleManagementYearLevel").change(function(){
        if($("#scheduleManagementCourse").val()){
            var filter = {
                course_ID   : 1,
                subject_type : ($("#scheduleManagementSubjectType").val())*1,
                year_level  : $(this).val()
            };
            $.post("<?=api_url()?>c_subject/retrieveSubject", filter, function(data){
                var response = JSON.parse(data);
                $("#scheduleManagementSubject").empty();
                $("#scheduleManagementSubject").append("<option value disabled selected style='display:none' >Please choose</option>")
                for(var x = 0; x < response["data"].length; x++){
                    $("#scheduleManagementSubject").append("<option value='"+response["data"][x]["ID"]+"' "+((response["data"][x]["ID"] === $("#scheduleManagementSubject").attr("default_value")) ? "selected" : "")+" >"+response["data"][x]["description"]+"</option>")
                }
            });
        }else{
            $("#scheduleManagementCourseContainer").addClass("has-error");
            setTimeout(function(){
                $("#scheduleManagementCourseContainer").removeClass("has-error");
            }, 1500);
        }
    });
    $("#scheduleManagementSubject").change(function(){
        $.post("<?=api_url()?>c_subject/retrieveSubject", {ID: $(this).val()}, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                $("#scheduleManagementClub").val(response["data"]["type_ID"]);
            }
        });
    });
    $("#scheduleManagementSubjectType").change(function(){
        $("#scheduleManagementYearCourse").show();
        $("#scheduleManagementSubject").empty();
        if($(this).val()*1 == 2){
            $("#scheduleManagementCourse").hide();
            $("#scheduleManagementYearLevel").hide();
            $("#scheduleManagementCourseIDLabel").hide();
            var filter = {
                course_ID   : 1,
                year_level  : 101
            };
            $.post("<?=api_url()?>c_subject/retrieveSubject", filter, function(data){
                var response = JSON.parse(data);
                $("#scheduleManagementSubject").append("<option value disabled selected style='display:none' >Please choose</option>")
                for(var x = 0; x < response["data"].length; x++){
                    $("#scheduleManagementSubject").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
                }
            });
        }else{
            if($("#scheduleManagementYearLevel").val()*1 != 101){
                $("#scheduleManagementYearLevel").change();
            }
            $("#scheduleManagementCourse").show();
            $("#scheduleManagementYearLevel").show();
            $("#scheduleManagementCourseIDLabel").show();
        }
    });
    $("#scheduleManagementShowScheduleButton").click(function(){
        if($("#scheduleManagementRoomList").val()){
            var days = {
                "1"  : 32,
                "2"  : 16,
                "3"  : 8,
                "4"  : 4,
                "5"  : 2,
                "6"  : 1
            };
            var filter = {
                room_ID : $("#scheduleManagementRoomList").val(),
                school_year : systemUtility.getCurrentAcademicYear()
            };
            $("#scheduleManagementShowScheduleButton").button("loading");
            $.post("<?=api_url()?>c_schedule/retrieveSchedule", filter, function(data){
                var response = JSON.parse(data);
                var event = [];
                var y = 0;
                if(!response["error"].length){
                    $("#room").fullCalendar("removeEvents");
                    for(var x = 0; x < response["data"].length; x++){
                        var totaldayscount = response["data"][x]["day"];
                        for(var key in days){
                            if(totaldayscount >= days[key]){
                                var time_start = new Date(response["data"][x]["time_start"]*1000);
                                var time_end   = new Date(response["data"][x]["time_end"]*1000);
                                event[y] =
                                    {
                                        id: x+y,
                                        title: response["data"][x]["subject_name"],
                                        start: "2015-06-0"+key+"T"+formatTime(time_start)+":00",
                                        end: "2015-06-0"+key+"T"+formatTime(time_end)+":00"
                                    };
                                scheduleManagement.addFullCalendar(1);
                                totaldayscount -= days[key];
                                y++;
                            }
                        }
                    }
                    $("#room").fullCalendar('addEventSource',{ 
                        events : event
                    });
                }else{
                    $("#room").fullCalendar("removeEvents");
                }
                $("#room").show();
                $("#scheduleManagementShowScheduleButton").button("reset");
            });
        }else{
            $("#scheduleManagementRoomListContainer").addClass("has-error");
            setTimeout(function(){
                $("#scheduleManagementRoomListContainer").removeClass("has-error");
            }, 1500);
        }
    });
    $("#scheduleManagementRoomList").change(function(){
        $("#scheduleManagementShowScheduleButton").button("reset");
    });
    $("#scheduleManagementCreateFormButton").click(function(){
        scheduleManagement.changeFormAction(1);
        scheduleManagement.refreshFormOptions();
        $("#scheduleManagementForm").trigger("reset");
        $("#scheduleManagementForm").attr("action", "<?=api_url()?>c_schedule/createSchedule");
    });
    $("#scheduleManagementCloseFormButton").click(function(){
        scheduleManagement.changeFormAction(2);
        $("#room").hide();
    });
    $("#scheduleManagementSubmitFormButton").click(function(){
        $("#scheduleManagementForm").submit();
    });
    $("#scheduleManagementForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            console.log(response);
            if(!response["error"].length){
                scheduleManagement.retrieveScheduleList();
                scheduleManagement.changeFormAction(2);
                $("#room").hide();
            }else{
                $("#scheduleManagementMessage").html(response["error"][0]["message"]);
                $("#scheduleManagementMessage").removeClass("hide").addClass("alert-danger");
                setTimeout(function(){
                    $("#scheduleManagementMessage").html("");
                    $("#scheduleManagementMessage").removeClass("alert-danger").addClass("hide");
                },3000 * response["error"].length);
            }
        }
    });
    //schedule list page nav
    $("#scheduleManagementListPreviousPage").click(function(){
        if($("#scheduleManagementListCurrentPage").text()*1 -1){
            $("#scheduleManagementListCurrentPage").text($("#scheduleManagementListCurrentPage").text()*1 -1);
            scheduleManagement.retrieveScheduleList();
        } 
     });
     $("#scheduleManagementListNextPage").click(function(){
        $("#scheduleManagementListCurrentPage").text($("#scheduleManagementListCurrentPage").text()*1 + 1);
        scheduleManagement.retrieveScheduleList();
     });
     //schedule list action
     $("#scheduleManagementListBody").on("click", ".scheduleManagementListRemoveButton", function(){
        $(this).parent().find(".confirmButton").show();
        $(this).parent().find(".actionButton").hide();
     });
     $("#scheduleManagementListBody").on("click", ".scheduleManagementListNoRemoveButton", function(){
        $(this).parent().find(".confirmButton").hide();
        $(this).parent().find(".actionButton").show();
     });
     $("#scheduleManagementListBody").on("click", ".scheduleManagementListYesRemoveButton", function(){
        scheduleManagement.removeScheduleList($(this).parent().parent());
     });
     $("#scheduleManagementListBody").on("click", ".scheduleManagementListViewButton", function(){
        scheduleManagement.viewScheduleInformation($(this).parent().parent().attr("schedule_ID"));
        scheduleManagement.changeFormAction(1);
     });
     $("#scheduleManagementListBody").on("change", ".scheduleManagementListTeacherFilter", function(){
        if($(this).val()*1 !== $(this).attr("default_value")*1){
            $(this).parent().parent().find(".actionButton").hide();
            $(this).parent().parent().find(".confirmChangeButton").show();
        }else{
            $(this).parent().parent().find(".actionButton").show();
            $(this).parent().parent().find(".confirmChangeButton").hide();
        }
     });
     $("#scheduleManagementListBody").on("click", ".scheduleManagementTeacherListYesChangeButton", function(){
        scheduleManagement.saveScheduleTeacher($(this).parent().parent());
     });
});
scheduleManagement.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#scheduleManagementCloseFormButton").show();
            $("#scheduleManagementSubmitFormButton").show();
            $("#scheduleManagementCreateFormButton").hide();
        	$("#scheduleManagementDiv").slideDown();
            break;
        case 2://Close Button
            $("#scheduleManagementDiv").slideUp();
            $("#scheduleManagementCloseFormButton").hide();
            $("#scheduleManagementSubmitFormButton").hide();
            $("#scheduleManagementCreateFormButton").show();
            break;
    }
}
scheduleManagement.viewScheduleInformation = function(scheduleID){
    $("#scheduleManagementForm").trigger("reset");
    scheduleManagement.refreshFormOptions();
    $.post("<?=  api_url()?>c_schedule/retrieveSchedule",{ID : scheduleID},function(data){
        
        var response = JSON.parse(data);
        if(!response["error"].length){
            var totaldayscount = response["data"]["day"];
            var time_start = new Date(response["data"]["time_start"]*1000);
            var time_end = new Date(response["data"]["time_end"]*1000);
            $("#scheduleManagementForm").trigger("reset");
            $("#scheduleManagementID").val(response["data"]["ID"]*1);
            $("#scheduleManagementSubjectType").val(response["data"]["type_ID"]).change();
            $("#scheduleManagementCourse").val(response["data"]["course_ID"]*1);
            $("#scheduleManagementSubject").attr("default_value", response["data"]["subject_ID"]);
            $("#scheduleManagementYearLevel").val(response["data"]["year_level"]*1).change();
            $("#scheduleManagementTimeStart").val(formatTime(time_start));
            $("#scheduleManagementTimeEnd").val(formatTime(time_end));
            $(".scheduleManagementCheckbox").each(function(){
                if(totaldayscount >= $(this).val()){
                    totaldayscount -= $(this).val();
                    $(this).prop("checked", true);
                }
            });
            setTimeout(function(){
                $("#scheduleManagementRoomList").val(response["data"]["room_ID"]*1).change();
            },100);
            
            $("#scheduleManagementShowScheduleButton").click();
            $("#scheduleManagementForm").attr("action", "<?=api_url()?>c_schedule/updateSchedule");
            $("#scheduleManagementDiv").slideDown();
        }
    });
}
scheduleManagement.retrieveScheduleList = function(){
    var days = {
        "M"  : 32,
        "T"  : 16,
        "W"  : 8,
        "TH" : 4,
        "F"  : 2,
        "S"  : 1
    };
    var filter = {
        limit : 20,
        offset : ((($("#scheduleManagementListCurrentPage").text()*1 - 1) > 0) ? ($("#scheduleManagementListCurrentPage").text()*1 - 1) : 0) * 20,
        school_year : systemUtility.getCurrentAcademicYear()
    };
    console.log(filter)
    $.post("<?=api_url()?>c_schedule/retrieveSchedule", filter, function(data){
        var scheduleDays = "";
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
              $("#scheduleManagementListTotalPage").text(totalPages);
              if($("#scheduleManagementListCurrentPage").text()*1 <= totalPages){
                  $("#scheduleManagementListBody").empty();
                  
                  for(var x = 0; x < response["data"].length; x++){
                      var totaldayscount = response["data"][x]["day"];
                      for(var key in days){
                        if(totaldayscount >= days[key]){
                            scheduleDays += key;
                            totaldayscount -= days[key];
                        }
                      }
                      var time_start = new Date(response["data"][x]["time_start"]*1000);
                      var time_end   = new Date(response["data"][x]["time_end"]*1000);
                      var newRow = $(".prototype").find(".scheduleManagementListRow").clone();
                      newRow.find(".scheduleManagementListTeacherFilter").addClass("scheduleManagementListTeacherFilter"+response["data"][x]["ID"]);
                      newRow.attr("schedule_ID", response["data"][x]["ID"]);
                      newRow.find(".scheduleManagementListDescriprion").text(response["data"][x]["subject_name"]+" ("+response["data"][x]["ID"]+")");
                      newRow.find(".scheduleManagementListSchedule").text(formatAMPM(time_start)+" - " + formatAMPM(time_end)+" "+scheduleDays);
                      scheduleManagement.retrieveTeacherlist(newRow, response["data"][x]["teacher_ID"]);
                      newRow.find(".scheduleManagementListTeacherFilter").attr("default_value", response["data"][x]["teacher_ID"]);
                      //console.log(response["data"][x]["teacher_ID"]);
                      //newRow.find(".scheduleManagementListTeacher").html(tearcherHtml);
                      //$("#scheduleManagementListTeacherFilter").change(response["data"][x]["teacher_ID"]);
                      newRow.find(".scheduleManagementListRoom").text("Bldg. "+response["data"][x]["building_ID"]+" "+response["data"][x]["room_name"]);
                      scheduleDays = "";
                      newRow.find(".confirmButton").hide();
                      newRow.find(".confirmChangeButton").hide();
                      console.log(newRow.find(".scheduleManagementListTeacherFilter"+response["data"][x]["ID"]));
                      
                      $(".scheduleManagementListTeacherFilter"+response["data"][x]["ID"]).val(response["data"][x]["teacher_ID"]*1).change();
                      $("#scheduleManagementListBody").append(newRow);
                  }
              }
        }
        $("#scheduleManagementListCurrentPage").text( ($("#scheduleManagementListCurrentPage").text()*1 > $("#scheduleManagementListTotalPage").text()*1) ?  $("#scheduleManagementListTotalPage").text()*1 : $("#scheduleManagementListCurrentPage").text()*1);
    });
}
scheduleManagement.removeScheduleList = function(row){
    $.post("<?=  api_url()?>c_schedule/deleteSchedule", {ID : row.attr("schedule_id")}, function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            row.remove();
            scheduleManagement.retrieveScheduleList();
        }
    });
}
scheduleManagement.refreshFormOptions = function(){
    //courses
    $.post("<?=api_url()?>c_course/retrieveCourse", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#scheduleManagementCourse").val();
            $("#scheduleManagementCourse").empty();
            for(var x = 0; x < response["data"].length; x++){
                $("#scheduleManagementCourse").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
            }
            $("#scheduleManagementCourse").val(1).change();
        }      
    });
    $.post("<?=api_url()?>c_room/retrieveRoom", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#scheduleManagementRoomList").val();
            $("#scheduleManagementRoomList").empty();
            for(var x = 0; x < response["data"].length; x++){
                $("#scheduleManagementRoomList").append("<option value='"+response["data"][x]["ID"]+"' >"+"Bldg. "+response["data"][x]["building_ID"]+", "+response["data"][x]["description"]+"</option>")
            }
            $("#scheduleManagementRoomList").val(currentValue).change();
        }
    });
}
scheduleManagement.retrieveTeacherlist = function(row, teacherID){
    row.find(".scheduleManagementListTeacherFilter").empty();
    row.find(".scheduleManagementListTeacherFilter").append("<option value='0' >None</option>");
    $.post("<?=  api_url()?>c_schedule/retrieveTeacherList", {}, function(data){
        var response = JSON.parse(data);
        //console.log(response);
        if(!response["error"].length){
           for(var x = 0; x < response["data"].length; x++){
                var selected = (response["data"][x]["ID"] == teacherID)? "selected" : "";
                row.find(".scheduleManagementListTeacherFilter").append("<option "+selected+" value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]+"</option>");
            }
            //$("#scheduleManagementListTeacherFilter").change(teacherID);
        }
    });
}
scheduleManagement.saveScheduleTeacher = function(row){
    var data = {
        ID: row.attr("schedule_id")*1,
        teacher_ID: row.find(".scheduleManagementListTeacherFilter").val()*1
    };
    console.log(data);
    $.post("<?=  api_url()?>c_schedule/updateSectionSchedule", data, function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            row.find(".actionButton").show();
            row.find(".confirmChangeButton").hide();
        }
    });
}
scheduleManagement.addFullCalendar = function(roomID){
    $("#room").fullCalendar({
        header: {
            left: '',
            center: '',
            right: ''
        },
        defaultView: 'agendaWeek',
        weekNumbers : false,
        columnFormat : "ddd",
        height : "auto",
        editable: false,
        maxTime : '22:00:00',
        minTime : '7:00:00',
        hiddenDays : [0],
        defaultDate: '2015-06-01',
        allDaySlot : false,
    });
}
function format24Hour(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();        
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = (hours < 10 ? '0':'') +  hours + ':' + minutes + ":00";
    return strTime;
}
function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}
function formatTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes;
    return strTime;
}
</script>