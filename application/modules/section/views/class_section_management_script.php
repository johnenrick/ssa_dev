<script>
    /* global systemUtility, systemApplication*/
    var classSectionManagement = {};
    classSectionManagement.sectionList = [];
    $(document).ready(function () {
        //filter
        classSectionManagement.refreshOptions();
        $("#classSectionManagementSchoolYearFilter").val(systemUtility.getCurrentAcademicYear());
        $("#classSectionManagementCourseFilter").change(function () {
            classSectionManagement.retrieveSection($("#classSectionManagementCourseFilter").val() * 1, $("#classSectionManagementYearLevelFilter").val());
        });
        $("#classSectionManagementYearLevelFilter").change(function () {
            $("#classSectionManagementStudentListFilterName").val("");
            classSectionManagement.retrieveSection($("#classSectionManagementCourseFilter").val() * 1, $("#classSectionManagementYearLevelFilter").val());
            
        });
        $(".classSectionManagementFilter").change(function () {
            $("#classSectionManagementStudentListBody").empty();
        });
        //form action buttons
        $("#classSectionManagementCreateFormButton").click(function () {
            classSectionManagement.changeFormAction(1);
            classSectionManagement.refreshOptions();
            $("#classSectionManagementForm").trigger("reset");
            $("#classSectionManagementForm").attr("action", "<?= api_url() ?>c_section/createSection");
            $("#classSectionManagementDiv").slideDown();
            $("#classSectionManagementSchoolYear").val(systemUtility.getCurrentAcademicYear());
        });
        $("#classSectionManagementSubmitFormButton").click(function () {
            $("#classSectionManagementForm").submit();
        });
        $("#classSectionManagementCloseFormButton").click(function () {
            classSectionManagement.changeFormAction(2);
        });
        $("#classSectionManagementForm").ajaxForm({
            success: function (data) {
                var response = JSON.parse(data);
                if (!response["error"].length) {
                    classSectionManagement.retrieveClassSectionList();
                    classSectionManagement.changeFormAction(2);
                } else {
                    $("#classSectionManagementMessage").html(response["error"][0]["message"]);
                    $("#classSectionManagementMessage").show();
                    setTimeout(function () {
                        $("#classSectionManagementMessage").html("");
                        $("#classSectionManagementMessage").hide();
                    }, 3000 * response["error"].length);
                }
            }
        });
        $("#classSectionManagementSectionFilter").change(function () {
            $("#classScheduleManagementListCurrentPage").text("1");
            $("#classSectionManagementStudentListFilterName").val("");
            classSectionManagement.retrieveClassSectionList();
            classSectionManagement.retrieveClassScheduleList();
        });

       
        //section list page nav
        $("#classSectionManagementStudentListPreviousPage").click(function () {
            if ($("#classSectionManagementStudentListCurrentPage").text() * 1 - 1) {
                $("#classSectionManagementStudentListCurrentPage").text($("#classSectionManagementStudentListCurrentPage").text() * 1 - 1);
                classSectionManagement.retrieveClassSectionList();
            }
        });
        $("#classSectionManagementStudentListNextPage").click(function () {
            $("#classSectionManagementStudentListCurrentPage").text($("#classSectionManagementStudentListCurrentPage").text() * 1 + 1);
            classSectionManagement.retrieveClassSectionList();
        });

        //Schedule list page nav
        $("#classScheduleManagementListPreviousPage").click(function () {
            if ($("#classSectionManagementStudentListCurrentPage").text() * 1 - 1) {
                $("#classScheduleManagementListCurrentPage").text($("#classScheduleManagementListCurrentPage").text() * 1 - 1);
                classSectionManagement.retrieveClassScheduleList();
            }
        });
        $("#classScheduleManagementListNextPage").click(function () {
            $("#classScheduleManagementListCurrentPage").text($("#classScheduleManagementListCurrentPage").text() * 1 + 1);
            classSectionManagement.retrieveClassScheduleList();
        });


        //class section list action
        $("#classSectionManagementStudentListBody").on("click", ".classSectionManagementStudentListRemoveButton", function () {
            $(this).parent().find(".confirmButton").show();
            $(this).parent().find(".actionButton").hide();
        });
        $("#classSectionManagementStudentListBody").on("click", ".classSectionManagementStudentListNoRemoveButton", function () {
            $(this).parent().find(".confirmButton").hide();
            $(this).parent().find(".actionButton").show();
            $(this).parent().find(".confirmChangeButton").hide();
        });
        $("#classSectionManagementStudentListBody").on("click", ".classSectionManagementStudentListYesRemoveButton", function () {
            classSectionManagement.removeSectionList($(this).parent().parent());
        });

        $("#classSectionManagementStudentListBody").on("change", ".classSectionManagementStudentListSection", function () {
            if ($(this).val() * 1 !== $(this).attr("default_value") * 1) {

                $(this).parent().parent().find(".confirmChangeButton").show();
                $(this).parent().parent().find(".classSectionManagementStudentListRemoveButton").hide();
                $(this).parent().parent().find(".confirmButton").hide();
            } else {
                $(this).parent().parent().find(".confirmChangeButton").hide();
                $(this).parent().parent().find(".classSectionManagementStudentListRemoveButton").show();
                $(this).parent().parent().find(".confirmButton").hide();
            }
        });
        $("#classSectionManagementStudentListBody").on("click", ".classSectionManagementStudentListYesChangeButton", function () {
            var row = $(this).parent().parent();
            if(row.attr("year_level")*1 === 0){
                console.log(row.find(".classSectionManagementStudentListSection option:selected").attr("value"));
                classSectionManagement.createAccountLevel(row.attr("account_id"), row.find(".classSectionManagementStudentListSection option:selected").attr("year_level"));
            }
            classSectionManagement.saveClassSectionList($(this).parent().parent());

        });
        $("#classSectionManagementStudentListBody").on("click", ".classSectionManagementStudentListViewGradeButton", function () {
            $("#classSectionReportCardGradeTableBody").empty();
            classSectionManagement.retrieveGrade($(this).parent().parent(), "Grade " + $("#classSectionManagementYearLevelFilter").val(), $("#classSectionManagementSectionFilter").val());
            $("#classSectionReportCard").modal("toggle");
        });
        //add new student
        $("#classSectionManagementViewNewStudent").click(function () {
            classSectionManagement.viewNewStudent();
        });
        $("#classSectionManagementAddStudentButton").click(function () {
            classSectionManagement.addNewStudent();
        });
        //class schedule list action
        $("#classScheduleManagementListBody").on("click", ".classScheduleManagementStudentListNoRemoveButton", function () {
            $(this).parent().find(".confirmButton").hide();
            $(this).parent().find(".actionButton").show();
            $(this).parent().find(".confirmChangeButton").hide();

        });

        $("#classScheduleManagementListBody").on("change", ".classSectionManagementStudentListSection", function () {
            if ($(this).val() * 1 !== $(this).attr("default_value") * 1) {
                $(this).parent().parent().find(".confirmChangeButton").show();
            }
        });
        $("#classScheduleManagementListBody").on("click", ".classScheduleManagementStudentListYesChangeButton", function() {
            classSectionManagement.saveSectionScheduleList($(this).parent().parent());
        });
        $("#classSectionManagementStudentListSearch").click(function(){
            classSectionManagement.retrieveClassSectionList();
        });
        $("#classSectionManagementStudentListPrint").click(function(){
            classSectionManagement.printClassList();
        });
    });
    classSectionManagement.createAccountLevel = function (accountID, yearLevel) {
        var data = {
            account_ID: accountID,
            year_level: yearLevel,
            academic_year: systemUtility.getCurrentAcademicYear(),
            course_ID: $("#classSectionManagementCourseFilter").val()
        };
        $.post("<?= api_url() ?>/c_account_level/createAccountLevel", data, function (data) {
            console.log(data);
        });
    };
    classSectionManagement.updateAccountLevel = function (accountID, yearLevel) {
        var data = {
            account_ID: accountID,
            year_level: yearLevel,
            academic_year: $("#classSectionManagementSchoolYearFilter").val()
        };
        $.post("<?= api_url() ?>/c_account_level/updateAccountLevel", data, function (data) {

        });
    };
    classSectionManagement.viewSctionInformation = function (sectionID) {
        $("#classSectionManagementForm").trigger("reset");
        classSectionManagement.refreshOptions();
        $.post("<?= api_url() ?>c_section/retrieveSection", {ID: sectionID}, function (data) {

            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#classSectionManagementForm").trigger("reset");
                $("#classSectionManagementID").val(response["data"]["ID"] * 1);
                $("#classSectionManagementDescription").val(response["data"]["description"]);
                $("#classSectionManagementCourse").val(response["data"]["course_ID"]);
                $("#classSectionManagementYearLevel").val(response["data"]["year_level"]);

                $("#classSectionManagementForm").attr("action", "<?= api_url() ?>c_section/updateSection");
                $("#classSectionManagementDiv").slideDown();
            }

        });
    };
    classSectionManagement.removeSectionList = function (row) {
        $.post("<?= api_url() ?>c_class_section/deleteClassSection", {ID: row.attr("class_section_id")}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                row.remove();
                classSectionManagement.retrieveClassSectionList();
            }
        });
    };
    classSectionManagement.changeFormAction = function (status) {
        switch (status * 1) {
            case 1://Create Button
                $("#classSectionManagementCloseFormButton").show();
                $("#classSectionManagementSubmitFormButton").show();
                $("#classSectionManagementCreateFormButton").hide();
                break;
            case 2://Close Button
                $("#classSectionManagementDiv").slideUp();
                $("#classSectionManagementCloseFormButton").hide();
                $("#classSectionManagementSubmitFormButton").hide();
                $("#classSectionManagementCreateFormButton").show();
                break;
        }
    };
    classSectionManagement.retrievingClassSectionList = false;
    classSectionManagement.retrieveClassSectionList = function (printClass) {
        if(classSectionManagement.retrievingClassSectionList){
            classSectionManagement.retrievingClassSectionList.abort();
            classSectionManagement.retrievingClassSectionList = false;
        }
        $("#classSectionManagementStudentListSearch").button("loading");
        var filter = {
            limit: 20 ,
            offset: ((($("#classSectionManagementStudentListCurrentPage").text() * 1 - 1) > 0) ? ($("#classSectionManagementStudentListCurrentPage").text() * 1 - 1) : 0) * 20,
            school_year: $("#classSectionManagementSchoolYearFilter").val() * 1,
            section_ID: $("#classSectionManagementSectionFilter").val(),
            year_level: $("#classSectionManagementYearLevelFilter").val(),
            sort: {
                account_basic_information__last_name: "asc",
                account_basic_information__first_name: "asc",
                account_basic_information__middle_name: "asc"
            }

        };
        if($("#classSectionManagementStudentListFilterName").val() !== ""){
            filter.full_name = $("#classSectionManagementStudentListFilterName").val();
        }

        var link = ($("#classSectionManagementSectionFilter").val() * 1) ? "<?= api_url() ?>c_class_section/retrieveClassSection" : "<?= api_url() ?>c_class_section/retrieveNoClassSection";
        
        classSectionManagement.retrievingClassSectionList = $.post(link, filter, function (data) {
            var response = JSON.parse(data);
            $("#classSectionManagementStudentListTotalResult").text("0");
            if (!response["error"].length) {
                var totalPages = Math.ceil(response["result_count"] / 20);
                $("#classSectionManagementStudentListTotalResult").text(response["result_count"]);
                $("#classSectionManagementStudentListTotalPage").text(totalPages);
                if ($("#classSectionManagementStudentListCurrentPage").text() * 1 <= totalPages) {
                    $("#classSectionManagementStudentListBody").empty();
                    for (var x = 0; x < response["data"].length; x++) {
                        var newRow = $(".prototype").find(".classSectionManagementStudentListRow").clone();
                        newRow.attr("class_section_id", ($("#classSectionManagementSectionFilter").val() * 1) ? response["data"][x]["ID"] : 0);
                        newRow.attr("account_id", response["data"][x]["account_ID"]);
                        newRow.attr("year_level", response["data"][x]["year_level"]*1);
                        newRow.attr("gender", response["data"][x]["gender"]);
                        newRow.find(".classSectionManagementStudentListName").text(
                                response["data"][x]["last_name"]
                                + ", " +
                                response["data"][x]["first_name"]
                                + " " +
                                response["data"][x]["middle_name"]);
                        newRow.find(".classSectionManagementStudentListIDNumber").text(response["data"][x]["identification_number"]);
                        newRow.find(".confirmButton").hide();
                        newRow.find(".confirmChangeButton").hide();
                        classSectionManagement.refreshStudentListOption(newRow);
                        newRow.find(".classSectionManagementStudentListSection").val(response["data"][x]["section_ID"]);
                        newRow.find(".classSectionManagementStudentListSection").attr("default_value", ($("#classSectionManagementSectionFilter").val() * 1) ? response["data"][x]["section_ID"] : 0);
                        $("#classSectionManagementStudentListBody").append(newRow);
                    }

                }
            }
            classSectionManagement.retrievingClassSectionList = false;
            $("#classSectionManagementStudentListSearch").button("reset");
            $("#classSectionManagementStudentListCurrentPage").text(($("#classSectionManagementStudentListCurrentPage").text() * 1 > $("#classSectionManagementStudentListTotalPage").text() * 1) ? $("#classSectionManagementStudentListTotalPage").text() * 1 : $("#classSectionManagementStudentListCurrentPage").text() * 1);
        });
    };
    classSectionManagement.printClassList = function(){
        $("#classSectionManagementStudentListPrintGrade").text(systemUtility.romanize($("#classSectionManagementYearLevelFilter").val())*1);
        $("#classSectionManagementStudentListPrintSection").text($("#classSectionManagementSectionFilter option:selected ").text());
        $("#classSectionManagementStudentListPrintMaleList").find("tbody").html("<tr><td colspan='2'></td></tr>");
        $("#classSectionManagementStudentListPrintFemaleList").find("tbody").html("<tr><td colspan='2'></td></tr>");
        $("#classSectionManagementStudentListPrintAdviser").text($("#classSectionManagementSectionFilter option:selected").attr("adviser_name"));
        $("#classSectionManagementStudentListSearch").button("loading");
        $("#classSectionManagementStudentListPrint").button("loading");
        
        var filter = {
            school_year: $("#classSectionManagementSchoolYearFilter").val() * 1,
            section_ID: $("#classSectionManagementSectionFilter").val(),
            year_level: $("#classSectionManagementYearLevelFilter").val(),
            sort: {
                account_basic_information__last_name: "asc",
                account_basic_information__first_name: "asc",
                account_basic_information__middle_name: "asc"
            }

        };
        if($("#classSectionManagementStudentListFilterName").val() !== ""){
            filter.full_name = $("#classSectionManagementStudentListFilterName").val();
        }

        var link = ($("#classSectionManagementSectionFilter").val() * 1) ? "<?= api_url() ?>c_class_section/retrieveClassSection" : "<?= api_url() ?>c_class_section/retrieveNoClassSection";
        $.post(link, filter, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                for (var x = 0; x < response["data"].length; x++) {
                    if(response["data"][x]["gender"]*1 === 1){ //male
                        $("#classSectionManagementStudentListPrintMaleList").find("tbody").append("<tr><td>"+($("#classSectionManagementStudentListPrintMaleList").find("tbody tr").length)+") </td><td>"+response["data"][x]["identification_number"]+"&nbsp;&nbsp;&nbsp;</td><td>"+response["data"][x]["last_name"]
                            + ", " +
                            response["data"][x]["first_name"]
                            + " " +
                            response["data"][x]["middle_name"]+"</td></tr>")

                    }else{//female
                        $("#classSectionManagementStudentListPrintFemaleList").find("tbody").append("<tr><td>"+($("#classSectionManagementStudentListPrintFemaleList").find("tbody tr").length)+") </td><td>"+response["data"][x]["identification_number"]+"&nbsp;&nbsp;&nbsp;</td><td>"+response["data"][x]["last_name"]
                            + ", " +
                            response["data"][x]["first_name"]
                            + " " +
                            response["data"][x]["middle_name"]+"</td></tr>")
                    }
                }
                $("#classSectionManagementStudentListPrintClassList").print();
            }
            $("#classSectionManagementStudentListSearch").button("reset");
            $("#classSectionManagementStudentListPrint").button("reset");
        });
        
    };
    classSectionManagement.viewNewStudent = function () {
        if ($("#classSectionManagementIdentificationNumber").val() * 1 === 0) {
            return false;
        }
        $.post("<?= api_url() ?>c_account/retrieveAccountBasicInformation", {identification_number: $("#classSectionManagementIdentificationNumber").val()}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#classSectionManagementNewStudentFullName").attr("account_id", response["data"]["account_ID"]);
                $("#classSectionManagementNewStudentFullName").text(
                        response["data"]["last_name"]
                        + ", " +
                        response["data"]["first_name"]
                        + " " +
                        response["data"]["middle_name"]);
                $("#classSectionManagementNewStudentIdentificationNumber").text(response["data"]["identification_number"]);
                $("#classSectionManagementAddStudentButton").show();
            } else {
                $("#classSectionManagementNewStudentFullName").text("Student Not Found");
                $("#classSectionManagementNewStudentIdentificationNumber").text("");
            }
        });
    };
    classSectionManagement.refreshOptions = function () {
        //school year
        $("#classSectionManagementSchoolYearFilter").empty();
        systemUtility.addAcademicYearSelectOption("#classSectionManagementSchoolYearFilter");
        //courses
        $.post("<?= api_url() ?>c_course/retrieveCourse", {}, function (data) {
            var response = JSON.parse(data);

            if (!response["error"].length) {
                var currentValue = $("#classSectionManagementCourseFilter").val();
                $("#classSectionManagementCourseFilter").empty();

                for (var x = 0; x < response["data"].length; x++) {
                    $("#classSectionManagementCourseFilter").append("<option value='" + response["data"][x]["ID"] + "' >" + response["data"][x]["description"] + "</option>");
                }
                (currentValue * 1) ? $("#classSectionManagementCourseFilter").val(currentValue) : "";
                classSectionManagement.retrieveSection();
            }

        });

    };
    classSectionManagement.refreshStudentListOption = function (row) {

        row.find(".classSectionManagementStudentListSection").empty();
        row.find(".classSectionManagementStudentListSection").append("<option value='0' >None</option>");
        for (var x = 0; x < classSectionManagement.sectionList.length; x++) {
            row.find(".classSectionManagementStudentListSection").append("<option value='" + classSectionManagement.sectionList[x]["ID"] + "' year_level='"+classSectionManagement.sectionList[x]["year_level"]+"'>" + classSectionManagement.sectionList[x]["description"] + "</option>");
        }
    };
    classSectionManagement.retrieveSection = function (courseID, yearLevel) {
        $("#classSectionManagementSectionFilter").empty();
        $.post("<?= api_url() ?>c_section/retrieveSection", {course_ID: courseID, year_level: yearLevel, academic_year : $("#classSectionManagementSchoolYearFilter").val()}, function (data) {
            var response = JSON.parse(data);
            classSectionManagement.sectionList = [];
            if (!response["error"].length) {
                $("#classSectionManagementSectionFilter").append("<option value='0' >None</option>");
                classSectionManagement.sectionList = response["data"];
                for (var x = 0; x < response["data"].length; x++) {
                    $("#classSectionManagementSectionFilter").append("<option adviser_name='"+ (response["data"][x]["adviser_first_name"] !== null ? (response["data"][x]["adviser_first_name"]+" "+response["data"][x]["adviser_middle_name"]+" "+response["data"][x]["adviser_last_name"]) : "None")+"' value='" + response["data"][x]["ID"] + "' >" + response["data"][x]["description"] + "</option>");
                    
                }
                classSectionManagement.retrieveClassSectionList();
                classSectionManagement.retrieveClassScheduleList();
            } else {

            }
        });
    };
    classSectionManagement.addNewStudent = function () {
        if ($("#classSectionManagementNewStudentFullName").attr("account_id") * 1 === 0) {
            return false;
        }
        var newData = {
            section_ID: $("#classSectionManagementSectionFilter").val(),
            account_ID: $("#classSectionManagementNewStudentFullName").attr("account_id") * 1,
            school_year: $("#classSectionManagementSchoolYearFilter").val()
        };
        $.post("<?= api_url() ?>c_class_section/createClassSection", newData, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                classSectionManagement.retrieveClassSectionList();
            } else {

                $("#classSectionManagementNewStudentFullName").text(response["error"][0]["message"]);
                setTimeout(function () {
                    $("#classSectionManagementNewStudentIdentificationNumber").text("");
                    $("#classSectionManagementNewStudentFullName").text("");
                }, 1000);
            }
            $("#classSectionManagementNewStudentFullName").attr("account_id", 0);
            $("#classSectionManagementAddStudentButton").hide();
        });
    };
    classSectionManagement.saveClassSectionList = function (row) {
        var newData = {
            previous_section_ID: row.attr("class_section_id"),
            section_ID: row.find(".classSectionManagementStudentListSection").val(),
            account_ID: row.attr("account_id"),
            school_year: $("#classSectionManagementSchoolYearFilter").val()
        };
        $.post("<?= api_url() ?>c_class_section/createClassSection", newData, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                row.remove();
                //classSectionManagement.retrieveClassSectionList();
            } else {

            }
        });
    };

    /**********************************************************************************************************/
    classSectionManagement.retrieveClassScheduleList = function () {
        var days = {
            "M": 32,
            "T": 16,
            "W": 8,
            "TH": 4,
            "F": 2,
            "S": 1
        };
        var filter = {
            limit: 20,
            offset: ((($("#classScheduleManagementListCurrentPage").text() * 1 - 1) > 0) ? ($("#classScheduleManagementListCurrentPage").text() * 1 - 1) : 0) * 20,
            school_year: $("#classSectionManagementSchoolYearFilter").val(),
            section_ID: ($("#classSectionManagementSectionFilter").val() * 1) ? $("#classSectionManagementSectionFilter").val() * 1 : 0,
            subject_type: 1,
            year_level : $("#classSectionManagementYearLevelFilter").val()*1
        };
        $.post("<?= api_url() ?>c_schedule/retrieveSchedule", filter, function (data) {
            var scheduleDays = "";
            var response = JSON.parse(data);
            if (!response["error"].length) {
                var totalPages = Math.ceil(response["result_count"] / 20);
                $("#classScheduleManagementListTotalPage").text(totalPages);
                if ($("#classScheduleManagementListCurrentPage").text() * 1 <= totalPages) {
                    $("#classScheduleManagementListBody").empty();
                    for (var x = 0; x < response["data"].length; x++) {
                        var totaldayscount = response["data"][x]["day"];
                        for (var key in days) {
                            if (totaldayscount >= days[key]) {
                                scheduleDays += key;
                                totaldayscount -= days[key];
                            }
                        }
                        var time_start = new Date(response["data"][x]["time_start"] * 1000);
                        var time_end = new Date(response["data"][x]["time_end"] * 1000);
                        var newRow = $(".prototype-schedule").find(".classScheduleManagementListRow").clone();
                        newRow.attr("schedule_ID", response["data"][x]["ID"]);
                        
                        newRow.find(".classScheduleManagementListDescriprion").text(response["data"][x]["subject_name"]);
                        newRow.find(".classScheduleManagementListSchedule").text(formatAMPM(time_start) + " - " + formatAMPM(time_end) + " " + scheduleDays);
                        newRow.find(".classScheduleManagementListRoom").text("Bldg. " + response["data"][x]["building_ID"] + " " + response["data"][x]["room_name"]);
                        scheduleDays = "";
                        classSectionManagement.refreshStudentListOption(newRow);
                        newRow.find(".classSectionManagementStudentListSection").val(response["data"][x]["section_ID"]);
                        newRow.find(".classSectionManagementStudentListSection").attr("default_value", ($("#classSectionManagementSectionFilter").val() * 1) ? response["data"][x]["section_ID"] : 0);
                        $("#classScheduleManagementListBody").append(newRow);
                    }
                }
            } else {
                $("#classScheduleManagementListBody").empty();
            }

            $("#classScheduleManagementListCurrentPage").text(($("#classScheduleManagementListCurrentPage").text() * 1 > $("#classScheduleManagementListTotalPage").text() * 1) ? $("#classScheduleManagementListTotalPage").text() * 1 : $("#classScheduleManagementListCurrentPage").text() * 1);
        });
    };
    classSectionManagement.retrieveGrade = function (row, year, section) {
        $("#classSectionReportCardGradeTableBody .classSectionReportCardGradeTableRow").remove();
        $("#classSectionReportCardGradeTableBody .classSectionReportCardGradeGeneralAverage").text(0);
        $.post("<?= api_url() ?>c_class_section/retrieveStudentInfo", {account_id: row.attr("account_id")}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                var birth_date = new Date(response["data"]["birth_datetime"] * 1000);
                var age = 2015 - birth_date.getFullYear();
                $("#classSectionReportCardStudentIdentificationNumber").text(row.find(".classSectionManagementStudentListIDNumber").text());
                $("#classSectionReportCardStudentName").text(row.find(".classSectionManagementStudentListName").text());
                $("#classSectionReportCardStudentAddress").text(response["data"]["birth_place"]);
                $("#classSectionReportCardStudentAge").text(age);
                $("#classSectionReportCardStudentSchoolYear").text(new Date(systemUtility.getCurrentAcademicYear() * 1000).getFullYear() + " - " + new Date(systemUtility.getNextAcademicYear() * 1000).getFullYear());
            }
        });
        $.post("<?= api_url() ?>c_section/retrieveSection", {ID: section}, function (data1) {
            var response1 = JSON.parse(data1);
            $("#classSectionReportCardStudentYearAndSection").text(year + " " + response1["data"]["description"]);
        });
       
        var filter = {
            section_ID : $("#classSectionManagementSectionFilter").val(),
            school_year : $("#classSectionManagementSchoolYearFilter").val()
        };
        $.post("<?=  api_url()?>c_schedule/retrieveSchedule", filter, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                for(var x = 0; x < response["data"].length; x++){
                    var subject = $(".prototype").find(".classSectionReportCardGradeTableRow").clone();
                    subject.attr("subject_id", response["data"][x]["subject_ID"]);
                    subject.find(".classSectionReportCardGradeTableLearningAreas").text(response["data"][x]["subject_name"]);
                    $("#classSectionReportCardGradeTableBody").append(subject);
                }
                 classSectionManagement.viewStudentGrade(row.attr("account_id"));
            }else{
                console.log(response);
            }
        });

        var attendance1 = {
            account_ID: row.attr("account_id"),
            section_ID: section,
            school_year: systemUtility.getCurrentAcademicYear()
        };
        $("#classSectionReportCardAttendanceTableBody tr:not(:first-child) td:not(:first-child)").text(0);
        $("#classSectionReportCardAttendanceTableBody .classSectionReportCardAttendanceDayPresentTotal").text(0);
        $("#classSectionReportCardAttendanceTableBody .classSectionReportCardAttendanceDayTardyTotal").text(0);
        $.ajax({
            url: "<?= api_url() ?>c_student_attendance/retrieveStudentAttendance",
            type: "POST",
            data: attendance1,
            async: false,
            success: function (data) {
                var temp = JSON.parse(data);
                var tardyCount = 0;
                var presentCount = 0;
                console.log(temp["data"]);
                for (var x in temp["data"]) {
                    //$("#attendance-"+temp["data"][x]["month"]+"-"+attendance1.account_ID+"-P").val(temp["data"][x]["present"]);
                    //$("#attendance-"+temp["data"][x]["month"]+"-"+attendance1.account_ID+"-L").val(temp["data"][x]["late"]);
                    $(".classSectionReportCardAttendanceDayPresent-" + temp["data"][x]["month"]).text(temp["data"][x]["present"]);
                    $(".classSectionReportCardAttendanceDayTardy-" + temp["data"][x]["month"]).text(temp["data"][x]["late"]);
                    console.log(temp["data"][x]["present"])
                    tardyCount += temp["data"][x]["late"]*1;
                    presentCount += temp["data"][x]["present"]*1;
                }
                $("#classSectionReportCardAttendanceTableBody .classSectionReportCardAttendanceDayPresentTotal").text(presentCount);
                $("#classSectionReportCardAttendanceTableBody .classSectionReportCardAttendanceDayTardyTotal").text(tardyCount);
            }
        });
        var deportment1 = {
            account_ID: row.attr("account_id"),
            section_ID: section,
            school_year: systemUtility.getCurrentAcademicYear()
        };
        $.ajax({
            url: systemApplication.url.apiUrl + "c_class_deportment/retrieveClassDeportment",
            type: "POST",
            data: deportment1,
            async: false,
            success: function (data1) {
                var temp = JSON.parse(data1);
                $("#classSectionReportCardDeportmentTableBody").empty();
                for (var x in temp["data"]) {
                    var newRow = $(".prototype").find(".classSectionReportCardDeportmentTableRow").clone();

                    newRow.find(".classSectionReportCardDeportmentTableCriteria").text(temp["data"][x]["description"]);

                    newRow.find(".classSectionReportCardDeportmentTableFirstPeriodicRating").text(temp["data"][x]["value"]);
                    newRow.find(".classSectionReportCardDeportmentTableSecondPeriodicRating").text("-");
                    newRow.find(".classSectionReportCardDeportmentTableThirdPeriodicRating").text("-");
                    newRow.find(".classSectionReportCardDeportmentTableFourthPeriodicRating").text("-");

                    $("#classSectionReportCardDeportmentTableBody").append(newRow);
                }
            }
        });


    };
    classSectionManagement.viewStudentGrade = function(accountID){
        var filter = {
            account_ID : accountID,
            academic_year :  $("#classSectionManagementSchoolYearFilter").val()
        };
        
        $.post("<?=  api_url()?>c_student_subject_component_score/retrieveStudentSubjectComponentScore", filter, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                for(var x = 0; x < response["data"].length; x++){
                    var partialScore = (response["data"][x]["score"]/response["data"][x]["highest_possible_score"])*100;
                    var weight = (partialScore * (response["data"][x]["subject_component_percentage"]/100)).toFixed(2);
                    console.log(response["data"][x]["subject_ID"]+":::"+response["data"][x]["grading"]+":::"+response["data"][x]["score"]+":::"+weight+":::"+partialScore+":::"+response["data"][x]["subject_component_percentage"]);
                    $("#classSectionReportCardGradeTableBody").find("tr[subject_id="+response["data"][x]["subject_ID"]+"] td:nth-child("+(response["data"][x]["grading"]*1+1)+")").text($("#classSectionReportCardGradeTableBody").find("tr[subject_id="+response["data"][x]["subject_ID"]+"] td:nth-child("+(response["data"][x]["grading"]*1+1)+")").text()*1 + (weight*1));
    
                }
            }
            var generalAverage = 0;
            $("#classSectionReportCardGradeTableBody .classSectionReportCardGradeTableRow").each(function(){
                var firstQuarter  = classSectionManagement.transmuteGrade($(this).find(".classSectionReportCardGradeTableFirstQuarter").text()*1);
                $(this).find(".classSectionReportCardGradeTableFirstQuarter").text(firstQuarter);
                var secondQuarter  = classSectionManagement.transmuteGrade($(this).find(".classSectionReportCardGradeTableSecondQuarter").text()*1);
                $(this).find(".classSectionReportCardGradeTableSecondQuarter").text(secondQuarter);
                var thirdQuarter  = classSectionManagement.transmuteGrade($(this).find(".classSectionReportCardGradeTableThirdQuarter").text()*1);
                $(this).find(".classSectionReportCardGradeTableThirdQuarter").text(thirdQuarter);
                var fourthQuarter  = classSectionManagement.transmuteGrade($(this).find(".classSectionReportCardGradeTableFourthQuarter").text()*1);
                $(this).find(".classSectionReportCardGradeTableFourthQuarter").text(fourthQuarter);
                var finalGrade = (firstQuarter+secondQuarter+thirdQuarter+fourthQuarter)/4;
                $(this).find(".classSectionReportCardGradeTableFinalGrade").text((finalGrade).toFixed(2));
                generalAverage += finalGrade;
            });
            $("#classSectionReportCardGradeGeneralAverage").text(generalAverage/$("#classSectionReportCardGradeTableBody .classSectionReportCardGradeTableRow").length)
        });
    };
    classSectionManagement.transmuteGrade = function(initialGrade){
        var lookUpKey = [0,4,8,12,16,20,24,28,32,36,40,44,48,52,56,60,61.6,63.2,64.8,66.4,68,69.6,71.2,72.8,74.4,76,77.6,79.2,80.8,82.4,84,85.6,87.2,88.8,90.4,92,93.6,95.2,96.8,98.4,100];
        var lookUpValue = [60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100];
        for(var x = lookUpKey.length-1; x >=0 ;x--){
            if((initialGrade >= lookUpKey[lookUpKey.length-1]) || (x === 0) || (initialGrade < lookUpKey[x] && initialGrade >= lookUpKey[x-1])){
                return lookUpValue[(x === 0) ? 0 : ((initialGrade >= lookUpKey[lookUpKey.length-1]) ? lookUpKey.length-1 : x-1)];
            }
        }
    };
    classSectionManagement.saveSectionScheduleList = function (row) {
        var newData = {
            ID: row.attr("schedule_id"),
            section_ID: row.find(".classSectionManagementStudentListSection").val()
        };
        $.post("<?= api_url() ?>c_schedule/updateSectionSchedule", newData, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                row.remove();
            } else {

            }
        });
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
    function formatTime(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes;
        return strTime;
    }
</script>