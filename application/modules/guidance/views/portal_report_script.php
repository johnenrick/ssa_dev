<script>
    /*global systemApplication*/
    var portalReport = {};
    $(document).ready(function () {
        portalReport.portalReportTable = $("#portalReportTable").apipagination({
            apiUrl: systemApplication.url.apiUrl + "c_student_attendance/retrieveStudentAttendance",
            customFilterGenerator: function () {
                return {
                    type: 1
                };
            },
            tableSorter: {
                1: "student_log__account_full_name",
                2: "student_log__datetime",
                3: "student_log__in_out"
            },
            tableFilter: {
                order_receipt_number: "OR",
                first_name: "First Name",
                middle_name: "Middle Name",
                last_name: "Last Name"
            },
            pageLimit : null,
            responseCallback: portalReport.showPortalReport
        });
        portalReport.portalReportTable.showPage();
    });
    portalReport.showPortalReport = function(response){
        console.log(response)
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var row = $(".prototype .portalReportRow").clone();
               
                $("#portalReportTable tbody").append(row);
            }
        }
    };
</script>