<script>
    /*global systemApplication, systemUtility*/
    var studentAttendance = {};
    $(document).ready(function () {
        studentAttendance.studentAttendanceTable = $("#studentAttendanceTable").apipagination({
            apiUrl: systemApplication.url.apiUrl + "c_class_section/retrieveClassSection",
            customFilterGenerator: function () {
                
                return {
                    type: 1,
                    school_year : systemUtility.getCurrentAcademicYear()
                };
            },
            tableSorter: {
                1: "account_basic_information__last_name"
            },
            tableFilter: {
                account_name: "Student Name",
                year_level : "Grade",
                section_description : "Section"
            },
            pageLimit : 20,
            responseCallback: studentAttendance.showPortalReport
        });
        studentAttendance.studentAttendanceTable.showPage();
        $("#studentAttendanceTable").on("click", "tbody tr", function(){
            var year = (systemApplication.academic_year_label[systemUtility.getCurrentAcademicYear()]).split("-");
            $("#studentAttendanceModal").modal("show");
            var filter = {
                start_datetime :(new Date(year[0], 5, 1, 0,0,0)).getTime()/1000,//june first day am
                end_datetime : (new Date("20"+year[1], 4, 0, 23,59,0)).getTime()/1000,//april last day pm
                account_ID: $(this).attr("account_ID"),
                location : 1
            };
            $("#studentAttendanceModal .monthlyAttendance .panel-body .row").empty();
            
            $.post(systemApplication.url.apiUrl + "c_student_log/retrieveStudentLog", filter, function(data){
                var response = JSON.parse(data);
                if(!response["error"].length){
                    for(var x = 0; x < response["data"].length; x++){
                        var log = new Date(response["data"][x]["datetime"]);
                        var monthly = $("#studentAttendanceModal .monthlyAttendance[month="+(log.getMonth()+1)+"]");
                        var daily = (monthly.find(".dailyAttendance[day="+log.getDate()+"]").length) ?  monthly.find(".dailyAttendance[day="+log.getDate()+"]") : ($(".prototype .dailyAttendance").clone());
                        daily.attr("day", log.getDate());
                        daily.find(".day").text(log.getDate());
                        
                        var inout = (response["data"][x]["in_out"]*1 === 1) ? ".in" : ".out";
                        if(daily.find(inout).text() === "none"){
                            daily.find(inout).text("");
                        }
                        daily.find(inout).append((daily.find(inout).text() !== "" ? "<br>":"" )+systemUtility.formatAMPM(log));
                        monthly.find(".panel-body .row").append(daily);
                    }
                }
            });
        });
        $("#openAttendanceSummary").click(function(){
            console.log("hello");
            $("#attendanceSummaryModal").modal("show");
            var filter = {
                school_year : systemUtility.getCurrentAcademicYear()
            };
            ($("input[name=account_name]").val() !== "") ? filter.account_name = $("input[name=account_name]").val() : null;
            ($("input[name=year_level]").val() !== "") ? filter.year_level = $("input[name=year_level]").val() : null;
            ($("input[name=section_description]").val() !== "") ? filter.section_description = $("input[name=section_description]").val() : null;
            $("#exportAttendance").hide();
            $("#attendanceSummaryTable tbody").empty();
            $("#attendanceSummaryTable tbody").append("<tr><td>Loading</td></tr>")
            $.post(systemApplication.url.apiUrl + "c_class_section/retrieveClassSection", filter,function(data){
                var response = JSON.parse(data);
                $("#attendanceSummaryTable tbody").empty();
                if(!response["error"].length){
                    var studentIDs = [];
                    for(var x = 0; x < response["data"].length; x++){
                        var row = $(".prototype .attendanceSummaryTableRow").clone();
                        row.attr("account_ID", response["data"][x]["account_ID"]);
                        row.find(".lastName").text(response["data"][x]["last_name"]);
                        row.find(".firstName").text(response["data"][x]["first_name"]+" "+(response["data"][x]["middle_name"]).charAt(0)+".")
                        $("#attendanceSummaryTable tbody").append(row);
                        studentIDs.push(response["data"][x]["account_ID"]);
                    }
                    
                    var year = (systemApplication.academic_year_label[systemUtility.getCurrentAcademicYear()]).split("-");
                    var filter = {
                        start_datetime :(new Date(year[0], 5, 1, 0,0,0)).getTime()/1000,//june first day am
                        end_datetime : (new Date("20"+year[1], 4, 0, 23,59,0)).getTime()/1000,//april last day pm
                        block_student_ID : studentIDs,
                        in_out : 1,
                        location : 1
                        
                    };
                    $.post(systemApplication.url.apiUrl+"c_student_log/retrieveStudentLog", filter, function(data){
                       var response = JSON.parse(data); 
                       if(!response["error"].length){
                           var attendanceList = {};
                           for(var x = 0; x < response["data"].length; x++){
                                var log = new Date(response["data"][x]["datetime"]);
                                if(typeof attendanceList[response["data"][x]["account_ID"]] === "undefined"){
                                    attendanceList[response["data"][x]["account_ID"]] = {6:[], 7:[], 8:[], 9:[], 10:[], 11:[], 12:[], 1:[], 2:[], 3:[], 4:[]};
                                }
                                if($.inArray(log.getDate(), attendanceList[response["data"][x]["account_ID"]][log.getMonth()+1]) === -1){
                                    attendanceList[response["data"][x]["account_ID"]][log.getMonth()+1].push(log.getDate());
                                }
                            }
                            for(var accountID in attendanceList){
                                for(var month in attendanceList[accountID]){
                                    var monthCol = $("#attendanceSummaryTable tbody tr[account_ID="+accountID+"] .month"+month);
                                    monthCol.text(attendanceList[accountID][month].length);
                                }
                            }
                       }
                    });
                    $("#exportAttendance").show();
                }
            });
        });
        $("#exportAttendance").click(function(){
            var filename = $("#studentAttendanceTable tbody tr:first-child td:last-child").text();
            systemUtility.exportTable("attendanceSummaryTable", filename);
        });
    });
    studentAttendance.showPortalReport = function(response){
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var row = $(".prototype .studentAttendanceRow").clone();
                row.attr("account_ID", response["data"][x]["account_ID"]);
                row.find(".accountFullName").text(response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"])
                row.find(".accountGrade").text(response["data"][x]["year_level"]);
                row.find(".accountSection").text(response["data"][x]["section_description"]);
                $("#studentAttendanceTable tbody").append(row);
            }
            $("#openAttendanceSummary").show();
        }else{
            $("#openAttendanceSummary").hide();
        }
    };
</script>