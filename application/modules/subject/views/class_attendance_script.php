<script>
    /*global systemApplication, systemUtility */
    classAttendance = {};
    var active_section = 0;

    $(document).ready(function () {
        classAttendance.retrieveClassSections();
        $("#classAttendanceBody").on("click", ".classAttendanceViewButton", function () {
            classAttendance.retrieveStudentList($(this).parent().parent());
        });
        $("#classAttendanceStudentListBody").on("blur", "input", function () {
            var input = $(this);
            var  link;
            var attendance = {
            };
            ($(this).attr("attendance")*1 === 1) ? attendance.present = $(this).val()*1 : attendance.late = $(this).val()*1;
            if(($(this).attr("student_attendance_id")) * 1 > 0){
                link = "<?= api_url() ?>c_student_attendance/updateStudentAttendance";
                attendance.ID = $(this).attr("student_attendance_id");
            }else{
                link = "<?= api_url() ?>c_student_attendance/createStudentAttendance";
                attendance.account_ID = $(this).parent().parent().attr("account_id");
                attendance.section_ID = active_section;
                attendance.month = $(this).attr("month");
                attendance.school_year = systemUtility.getCurrentAcademicYear();
            }
            console.log(attendance);
            $.post(link, attendance, function (data) {
                var response = JSON.parse(data);
                if(!response["error"].length){
                    if((input.attr("student_attendance_id")) * 1 === 0){
                        $("#classAttendanceStudentListBody tr[account_id="+input.parent().parent().attr("account_id")+"] input[month="+input.attr("month")+"]").attr("student_attendance_id", response["data"]);
                    }
                }
            });
        });
    });

    classAttendance.retrieveClassSections = function () {
        var filter = {
            adviser: systemApplication.userInformation.userID,
            academic_year: systemUtility.getCurrentAcademicYear()
        };
        $.post("<?= api_url() ?>c_section/retrieveSection", filter, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#classAttendanceBody").empty();
                for (var x in response["data"]) {
                    var newRow = $(".prototype-section").find(".classAttendanceRow").clone();
                    newRow.attr("section_id", response["data"][x]["ID"]);
                    newRow.find(".classAttendanceGrade").text("Grade " + response["data"][x]["year_level"]);
                    newRow.find(".classAttendanceSection").text(response["data"][x]["description"]);

                    $("#classAttendanceBody").append(newRow);
                }
            }
        });
    }; 

    classAttendance.retrieveStudentList = function (row) {
        $("#classAttendanceGradeSectionName").text(row.find(".classAttendanceGrade").text() + " - " + row.find(".classAttendanceSection").text());
        var filter = {
            section_ID: row.attr("section_id") * 1,
            type_ID: 1,
            school_year: systemUtility.getCurrentAcademicYear()
        };
        active_section = row.attr("section_id") * 1;
        $.post("<?= api_url() ?>c_class_section/retrieveClassSection", filter, function (data) {
            var response = JSON.parse(data);
            $("#classAttendanceStudentListBody").empty();
            if (!response["error"].length) {
                var maleList = [];
                var femaleList = [];
                for(var x = 0; x < response["data"].length; x++){
                    var studentRow = $(".prototype-studentlist .classAttendanceStudentRow").clone();
                    studentRow.attr("account_id", response["data"][x]["account_ID"]);
                    studentRow.find(".classAttendanceStudentLastName").text(response["data"][x]["last_name"]);
                    studentRow.find(".classAttendanceStudentFirstName").text(response["data"][x]["first_name"]);
                    if(response["data"][x]["gender"]*1 === 1){
                        maleList.push(studentRow);
                    }else{
                        femaleList.push(studentRow);
                    }
                }
                
                var htmlboys = "<tr>" +
                    "<td colspan='4' style='text-align: center; padding: 0;'>Boys</td>" +
                    "<td colspan='20'></td>" +
                    "</tr>";
                $("#classAttendanceStudentListBody").append(htmlboys);
                $("#classAttendanceStudentListBody").append(maleList);
                var htmlgirls = "<tr>" +
                    "<td colspan='4' style='text-align: center; padding: 0;'>Girls</td>" +
                    "<td colspan='20'></td>" +
                    "</tr>";
                $("#classAttendanceStudentListBody").append(htmlgirls);
                $("#classAttendanceStudentListBody").append(femaleList);
                classAttendance.retrieveStudentAttendance(row.attr("section_id") * 1);
                
            }
        });
    };

    classAttendance.retrieveStudentAttendance = function (sectionID) {
        var dataFilter = {
            section_ID : sectionID,
            school_year : systemUtility.getCurrentAcademicYear()
        };
        $.post("<?=  api_url()?>c_student_attendance/retrieveStudentAttendance", dataFilter, function(data){
            var response = JSON.parse(data);
            console.log(response);
            if(!response["error"].length){
                for(var x = 0; x < response["data"].length; x++){
                    $("#classAttendanceStudentListBody tr[account_id="+response["data"][x]["account_ID"]+"] input[month="+response["data"][x]["month"]+"]").attr("student_attendance_id", response["data"][x]["ID"]);
                    $("#classAttendanceStudentListBody tr[account_id="+response["data"][x]["account_ID"]+"] input[month="+response["data"][x]["month"]+"][attendance=1] ").val(response["data"][x]["present"]);
                    $("#classAttendanceStudentListBody tr[account_id="+response["data"][x]["account_ID"]+"] input[month="+response["data"][x]["month"]+"][attendance=2] ").val(response["data"][x]["late"]);
                }
            }
            $("#studentListclassAttendance").show();
        });
    };

</script>