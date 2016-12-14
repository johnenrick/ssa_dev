<script>
    /*global systemApplication*/
    var portalReport = {};
    $(document).ready(function () {
        portalReport.portalReportTable = $("#portalReportTable").apipagination({
            apiUrl: systemApplication.url.apiUrl + "c_student_log/retrieveStudentLog",
            customFilterGenerator: function () {
            var currentDate =new Date();
    currentDate = (new Date((currentDate.getMonth()+1)+"/"+(currentDate.getDate())+"/"+(currentDate.getFullYear())+" 00:00:01")).getTime()/1000;
        var start_datetime = (((new Date($("[name=start_datetime_dummy]").val())) != "Invalid Date") ? (new Date($("[name=start_datetime_dummy]").val()+" 00:00:01")).getTime()/1000 : currentDate)*1;
        var end_datetime = (((new Date($("[name=end_datetime_dummy]").val())) != "Invalid Date") ? (new Date($("[name=end_datetime_dummy]").val()+" 00:00:01")).getTime()/1000 : currentDate)*1;
                
                return {
                    start_datetime : start_datetime,
                    end_datetime : end_datetime,
                    type: 1
                };
            },
            tableSorter: {
                1: "student_log__account_full_name",
                2: "student_log__datetime",
                3: "student_log__in_out"
            },
            tableFilter: {
                account_name: "Student Name",
                start_datetime_dummy: "Start Date",
                end_datetime_dummy: "End Date"
            },
            pageLimit : 20,
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