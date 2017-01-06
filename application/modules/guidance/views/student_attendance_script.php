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
           $.post(systemApplication.url.apiUrl + "c_student_log/retrieveStudentLog", {account_ID: $(this).attr("account_ID")}, function(data){
                var response = JSON.parse(data);
                if(!response["data"].length){
                    var month = {};
                    for(var x = 0; x < response["data"].length; x++){
                        var log = new Date(response["data"][x]["datetime"]);
                    }
                }
           });
        });
        
    });
    studentAttendance.showPortalReport = function(response){
        console.log(response)
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