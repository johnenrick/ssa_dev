<script>
    /*global systemApplication, systemUtility */
    var clubMember = {};
    $(document).ready(function () {
        //retrieve Clubs

        $.post(systemApplication.url.apiUrl + "c_subject/retrieveSubject", {subject_type: 2}, function (data) {
            var response = JSON.parse(data);
            $("#clubMemberClubList").empty();
            if (!response["error"].length) {
                $("#clubMemberClubList").append("<option value='0'>Select Club</option>");
                for (var x = 0; x < response["data"].length; x++) {
                    $("#clubMemberClubList").append("<option value='" + response["data"][x]["ID"] + "'>" + response["data"][x]["description"] + "</option>");
                }
            } else {
                $("#clubMemberClubList").append("<option value='0'>No Club</option>");
            }
        });
        $("#clubMemberClubList").change(function(){
            $("#clubMemberAddMember").hide();
            $("#clubMemberListTable").parent().parent().hide();
            var scheduleFilter = {
                subject_ID : $(this).val(),
                school_year : systemUtility.getCurrentAcademicYear()
            };
            $.post(systemApplication.url.apiUrl + "c_schedule/retrieveSchedule", scheduleFilter, function (data) {
                var response = JSON.parse(data);
                $("#clubMemberClubScheduleList").empty();
                if (!response["error"].length) {
                    $("#clubMemberClubScheduleList").append("<option value='0'>Select Club</option>");
                    var days = {
                        M  : 32,
                        T  : 16,
                        W  : 8,
                        TH : 4,
                        F  : 2,
                        S  : 1
                    };
                    for (var x = 0; x < response["data"].length; x++) {
                        var time_start = new Date(response["data"][x]["time_start"]*1000);
                        var time_end   = new Date(response["data"][x]["time_end"]*1000);
                        var scheduleDays = "";
                        var totaldayscount = response["data"][x]["day"];
                        for(var key in days){
                            if(totaldayscount >= days[key]){
                                scheduleDays += key;
                                totaldayscount -= days[key];
                            }
                        }
                        $("#clubMemberClubScheduleList").append("<option value='" + response["data"][x]["ID"] + "'>" + formatAMPM(time_start)+" - " + formatAMPM(time_end)+" "+scheduleDays + "</option>");
                    }
                } else {
                    $("#clubMemberClubScheduleList").append("<option value='0'>No Club</option>");
                }
            });
            $("#clubMemberClubScheduleList").change(function(){
                clubMember.member_list.emptyPage();
                if($(this).val()*1){
                    $("#clubMemberAddMember").show();
                    $("#clubMemberListTable").parent().parent().show();
                    clubMember.member_list.showPage();
                }else{
                    $("#clubMemberAddMember").hide();
                    $("#clubMemberListTable").parent().parent().hide();
                }
            });
        });
        clubMember.member_list = $("#clubMemberListTable").apipagination({
            apiUrl: systemApplication.url.apiUrl + "c_club_member/retrieveClubMember",
            customFilterGenerator: function () {
                return {
                    retrieve_type: 1,
                    schedule_ID: $("#clubMemberClubScheduleList").val()
                };
            },
            tableSorter: {
                1: "account_basic_information__identification_number",
                2: "account_basic_information__last_name"
            },
            tableFilter: {
                identification_number: "ID Number",
                full_name: "Student Name"
            },
            pageLimit : 0,
            responseCallback: clubMember.listMember
        });
        clubMember.account_list = $("#clubMemberAddListTable").apipagination({
            apiUrl: systemApplication.url.apiUrl + "c_account/retrieveAccountBasicInformation",
            customFilterGenerator: function () {
                return {
                    retrieve_type: 1,
                    with_class_section : systemUtility.getCurrentAcademicYear()
                };
            },
            tableSorter: {
                1: "account_basic_information__identification_number",
                2: "account_basic_information__last_name"
            },
            tableFilter: {
                identification_number: "ID Number",
                full_name: "Student Name",
                grade_level : "Grade Level",
                class_section_description : "Section"
            },
            responseCallback: clubMember.listStudent
        });
        $("#clubMemberAddMemberModal").on("shown.bs.modal", function(){
            clubMember.account_list.showPage();
        });
        $("#clubMemberAddListTable").on("click", ".clubMemberStudentListAddMember", function(){
            var row = $(this).parent().parent();
            var newData = {
                schedule_ID : $("#clubMemberClubScheduleList").val(),
                account_ID : row.attr("account_ID")
            };
            $.post(systemApplication.url.apiUrl+"c_club_member/createClubMember", newData, function(data){
                var response = JSON.parse(data);
                console.log(response);
                if(!response["error"].length){
                    row.find(".clubMemberStudentListAddMember").hide();
                    row.find(".clubMemberStudentListRemoveMember").show();
                    var newRow = $(".prototype").find(".clubMemberStudentListRow").clone();
                    newRow.attr("account_ID", row.attr("account_ID"));
                    newRow.find(".clubMemberStudentListIDNumber").text(row.find(".clubMemberStudentListIDNumber").text());
                    newRow.find(".clubMemberStudentListGrade").text(row.find(".clubMemberStudentListGrade").text());
                    newRow.find(".clubMemberStudentListName").text(row.find(".clubMemberStudentListName").text());
                    newRow.find(".clubMemberStudentListRemoveMember").show();
                    $("#clubMemberListTable").find("tbody").append(newRow);
                }else{
                    console.log("failed");
                }
            });
        });
        $("#clubMemberAddListTable, #clubMemberListTable").on("click", ".clubMemberStudentListRemoveMember", function(){
            var row = $(this).parent().parent();
            var newData = {
                schedule_ID : $("#clubMemberClubScheduleList").val(),
                account_ID : row.attr("account_ID")
            };
            $.post(systemApplication.url.apiUrl+"c_club_member/deleteClubMember", newData, function(data){
                var response = JSON.parse(data);
                console.log(response);
                if(!response["error"].length){
                    row.find(".clubMemberStudentListAddMember").show();
                    row.find(".clubMemberStudentListRemoveMember").hide();
                    $("#clubMemberListTable tbody tr[account_id="+row.attr("account_ID")+"]").remove();
                }else{
                    console.log("failed");
                }
            });
        });
    });
    clubMember.listStudent = function (response) {
        if (!response["error"].length) {
            for (var x = 0; x < response["data"].length; x++) {
                var newRow = $(".prototype").find(".clubMemberStudentListRow").clone();
                newRow.attr("account_ID", response["data"][x]["account_ID"]);
                newRow.find(".clubMemberStudentListIDNumber").text(response["data"][x]["identification_number"]);
                newRow.find(".clubMemberStudentListGrade").text(response["data"][x]["section_year_level"]+" - "+response["data"][x]["secion_description"]);
                newRow.find(".clubMemberStudentListName").text(response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]);
                if($("#clubMemberListTable tbody tr[account_id="+response["data"][x]["account_ID"]+"]").length){
                    newRow.find(".clubMemberStudentListRemoveMember").show();
                }else{
                    newRow.find(".clubMemberStudentListAddMember").show();
                }
                $("#clubMemberAddListTable").find("tbody").append(newRow);
            }
        }
    };
    clubMember.listMember = function (response) {
        console.log(response);
        if (!response["error"].length) {
            for (var x = 0; x < response["data"].length; x++) {
                var newRow = $(".prototype").find(".clubMemberStudentListRow").clone();
                newRow.attr("account_ID", response["data"][x]["account_ID"]);
                newRow.find(".clubMemberStudentListIDNumber").text(response["data"][x]["identification_number"]);
                newRow.find(".clubMemberStudentListGrade").text(response["data"][x]["section_year_level"]+" - "+response["data"][x]["secion_description"]);
                newRow.find(".clubMemberStudentListName").text(response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]);
                newRow.find(".clubMemberStudentListRemoveMember").show();
                $("#clubMemberListTable").find("tbody").append(newRow);
            }
        }
    };
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
</script>