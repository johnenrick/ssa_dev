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
                1: "student_log__account_full_name"
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
    });
    studentAttendance.showPortalReport = function(response){
        console.log(response)
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var row = $(".prototype .studentAttendanceRow").clone();
                row.find(".accountFullName").text(response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"])
                row.find(".accountGrade").text(response["data"][x]["year_level"]);
                row.find(".accountSection").text(response["data"][x]["section_description"]);
                $("#studentAttendanceTable tbody").append(row);
            }
        }
    };
</script>