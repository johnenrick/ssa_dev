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
                account_ID: $(this).attr("account_ID")
            };
            $("#studentAttendanceModal .monthlyAttendance .panel-body .row").empty();
            
            $.post(systemApplication.url.apiUrl + "c_student_log/retrieveStudentLog", filter, function(data){
                var response = JSON.parse(data);
                console.log(response);
                if(!response["error"].length){
                    for(var x = 0; x < response["data"].length; x++){
                        var log = new Date(response["data"][x]["datetime"]);
                        var monthly = $("#studentAttendanceModal .monthlyAttendance[month="+(log.getMonth()+1)+"]");
                        var daily = (monthly.find(".dailyAttendance[day="+log.getDate()+"]").length) ?  monthly.find(".dailyAttendance[day="+log.getDate()+"]") : ($(".prototype .dailyAttendance").clone());
                        console.log(monthly.find(".dailyAttendance[day="+log.getDate()+"]"));
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
        }
    };
</script>