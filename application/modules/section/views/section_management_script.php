<script>
    /*global systemUtility*/
    var sectionManagement = {};
    $(document).ready(function () {
        
        systemUtility.addAcademicYearSelectOption("#sectionManagementAcademicYearFilter");
        $("#sectionManagementAcademicYear").val(systemUtility.getCurrentAcademicYear());
        //form action buttons
        $("#sectionManagementCreateFormButton").click(function () {
            sectionManagement.changeFormAction(1);
            sectionManagement.refreshFormOptions();
            $("#sectionManagementForm").trigger("reset");
            $("#sectionManagementForm").attr("action", "<?= api_url() ?>c_section/createSection");
            $("#sectionManagementDiv").slideDown();
            var currentDate = new Date();
            $("#sectionManagementAcademicYear").val($("#sectionManagementAcademicYearFilter").val());
        });
        $("#sectionManagementAcademicYearFilter").change(function(){
            $("#sectionManagementAcademicYear").val($(this).val());
            sectionManagement.retrieveSectionList();
        })
        $("#sectionManagementSubmitFormButton").click(function () {
            $("#sectionManagementForm").submit();
        });
        $("#sectionManagementCloseFormButton").click(function () {
            sectionManagement.changeFormAction(2);
        });
        $("#sectionManagementForm").ajaxForm({
            success: function (data) {
                console.log(data);
                var response = JSON.parse(data);
                if (!response["error"].length) {
                    sectionManagement.retrieveSectionList();
                    sectionManagement.changeFormAction(2);
                } else {
                    $("#sectionManagementMessage").html(response["error"][0]["message"]);
                    $("#sectionManagementMessage").show();
                    setTimeout(function () {
                        $("#sectionManagementMessage").html("");
                        $("#sectionManagementMessage").hide();
                    }, 3000 * response["error"].length);
                }
            }
        });
        //section list page nav
        $("#sectionManagementListPreviousPage").click(function () {
            if ($("#sectionManagementListCurrentPage").text() * 1 - 1) {
                $("#sectionManagementListCurrentPage").text($("#sectionManagementListCurrentPage").text() * 1 - 1);
                sectionManagement.retrieveSectionList();
            }
        });
        $("#sectionManagementListNextPage").click(function () {
            $("#sectionManagementListCurrentPage").text($("#sectionManagementListCurrentPage").text() * 1 + 1);
            sectionManagement.retrieveSectionList();
        });
        //section list action
        $("#sectionManagementListBody").on("click", ".sectionManagementListRemoveButton", function () {
            $(this).parent().find(".confirmButton").show();
            $(this).parent().find(".actionButton").hide();
        });
        $("#sectionManagementListBody").on("click", ".sectionManagementListNoRemoveButton", function () {
            $(this).parent().find(".confirmButton").hide();
            $(this).parent().find(".actionButton").show();
        });
        $("#sectionManagementListBody").on("click", ".sectionManagementListYesRemoveButton", function () {
            sectionManagement.removeSectionList($(this).parent().parent());
        });
        $("#sectionManagementListBody").on("click", ".sectionManagementListViewButton", function () {
            sectionManagement.viewSctionInformation($(this).parent().parent().attr("section_ID"));
            sectionManagement.changeFormAction(1);
        });
        $("#sectionManagementListBody").on("change", ".sectionManagementListAdviser", function () {
            if ($(this).val() * 1 !== $(this).attr("default_value") * 1) {
                $(this).parent().parent().find(".actionButton").hide();
                $(this).parent().parent().find(".confirmChangeButton").show();
            } else {
                $(this).parent().parent().find(".actionButton").show();
                $(this).parent().parent().find(".confirmChangeButton").hide();
            }
        });
        $("#sectionManagementListBody").on("click", ".sectionManagementAdviserListYesChangeButton", function () {
            sectionManagement.saveAdviser($(this).parent().parent());
        });
        $("#sectionManagementListBody").on("click", ".sectionManagementAdviserLtistNoRemoveButton", function () {
            $(this).parent().parent().find(".actionButton").show();
            $(this).parent().parent().find(".confirmChangeButton").hide();
        });
        sectionManagement.retrieveSectionList();
    });
    sectionManagement.viewSctionInformation = function (sectionID) {
        $("#sectionManagementForm").trigger("reset");
        sectionManagement.refreshFormOptions();
        $.post("<?= api_url() ?>c_section/retrieveSection", {ID: sectionID}, function (data) {
            console.log(data);
            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#sectionManagementForm").trigger("reset");
                $("#sectionManagementID").val(response["data"]["ID"] * 1);
                $("#sectionManagementDescription").val(response["data"]["description"]);
                $("#sectionManagementCourse").val(response["data"]["course_ID"]);
                $("#sectionManagementYearLevel").val(response["data"]["year_level"]);

                $("#sectionManagementForm").attr("action", "<?= api_url() ?>c_section/updateSection");
                $("#sectionManagementDiv").slideDown();
            }
        });
    }
    sectionManagement.removeSectionList = function (row) {
        $.post("<?= api_url() ?>c_section/deleteSection", {ID: row.attr("section_id")}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                row.remove();
                sectionManagement.retrieveSectionList();
            }
        });
    }
    sectionManagement.changeFormAction = function (status) {
        switch (status * 1) {
            case 1://Create Button
                $("#sectionManagementCloseFormButton").show();
                $("#sectionManagementSubmitFormButton").show();
                $("#sectionManagementCreateFormButton").hide();
                break;
            case 2://Close Button
                $("#sectionManagementDiv").slideUp();
                $("#sectionManagementCloseFormButton").hide();
                $("#sectionManagementSubmitFormButton").hide();
                $("#sectionManagementCreateFormButton").show();
                break;
        }
    }
    sectionManagement.retrieveSectionList = function () {
        var filter = {
            limit: 20,
            offset: ((($("#sectionManagementListCurrentPage").text() * 1 - 1) > 0) ? ($("#sectionManagementListCurrentPage").text() * 1 - 1) : 0) * 20,
            academic_year : $("#sectionManagementAcademicYearFilter").val()
        };
        $.post("<?= api_url() ?>c_section/retrieveSection", filter, function (data) {

            var response = JSON.parse(data);
            console.log(response);
            if (!response["error"].length) {
                var totalPages = Math.ceil(response["result_count"] / 20);
                $("#sectionManagementListTotalPage").text(totalPages);
                if ($("#sectionManagementListCurrentPage").text() * 1 <= totalPages) {
                    $("#sectionManagementListBody").empty();
                    for (var x = 0; x < response["data"].length; x++) {
                        var newRow = $(".prototype").find(".sectionManagementListRow").clone();
                        newRow.attr("section_ID", response["data"][x]["ID"]);
                        newRow.find(".sectionManagementListDescriprion").text(response["data"][x]["description"]);
                        newRow.find(".sectionManagementListAdviser").attr("section_adviser_id", response["data"][x]["section_adviser_ID"]*1);
                        sectionManagement.retrieveTeacherlist(newRow, response["data"][x]["adviser_account_ID"]);
                        newRow.find(".sectionManagementListAdviser").attr("default_value", response["data"][x]["adviser"]);
                        newRow.find(".sectionManagementListYearLevel").text(response["data"][x]["course_description"] + " " + response["data"][x]["year_level"]);

                        newRow.find(".confirmButton").hide();
                        $("#sectionManagementListBody").append(newRow);
                    }
                }
            }
            $("#sectionManagementListCurrentPage").text(($("#sectionManagementListCurrentPage").text() * 1 > $("#sectionManagementListTotalPage").text() * 1) ? $("#sectionManagementListTotalPage").text() * 1 : $("#sectionManagementListCurrentPage").text() * 1);
        });
    };
    sectionManagement.refreshFormOptions = function () {
        //courses
        $.post("<?= api_url() ?>c_course/retrieveCourse", {}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                var currentValue = $("#sectionManagementCourse").val();
                $("#sectionManagementCourse").empty();
                for (var x = 0; x < response["data"].length; x++) {
                    $("#sectionManagementCourse").append("<option value='" + response["data"][x]["ID"] + "' >" + response["data"][x]["description"] + "</option>")
                }
                $("#sectionManagementCourse").val(currentValue);
            }

        });
    };
    sectionManagement.retrieveTeacherlist = function (row, teacherID) {
        row.find(".sectionManagementListAdviser").empty();
        row.find(".sectionManagementListAdviser").append("<option value='0' >None</option>");
        $.post("<?= api_url() ?>c_schedule/retrieveTeacherList", {}, function (data) {
            var response = JSON.parse(data);
            //console.log(response);
            if (!response["error"].length) {
                for (var x = 0; x < response["data"].length; x++) {
                    var selected = (response["data"][x]["ID"]*1 === teacherID*1) ? "selected" : "";
                    row.find(".sectionManagementListAdviser").append("<option " + selected + " value='" + response["data"][x]["ID"] + "' >" + response["data"][x]["last_name"] + ", " + response["data"][x]["first_name"] + " " + response["data"][x]["middle_name"] + "</option>");
                }
                //$("#scheduleManagementListTeacherFilter").change(teacherID);
            }
        });
    };
    sectionManagement.saveAdviser = function (row) {
        console.log(row.find(".sectionManagementListAdviser").attr("section_adviser_id"))
        if(row.find(".sectionManagementListAdviser").attr("section_adviser_id")*1){
            var data = {
                ID: row.find(".sectionManagementListAdviser").attr("section_adviser_id"),
                adviser_account_ID: row.find(".sectionManagementListAdviser").val() * 1
            };
            $.post("<?= api_url() ?>c_section/updateSectionAdviser", data, function (data) {
                var response = JSON.parse(data);
                console.log(response);
                if (!response["error"].length) {
                    row.find(".actionButton").show();
                    row.find(".confirmChangeButton").hide();
                }
            });
        }else{
            var data = {
                adviser_account_ID: row.find(".sectionManagementListAdviser").val() * 1,
                academic_year : $("#sectionManagementAcademicYearFilter").val(),
                section_ID : row.attr("section_id")
            };
            $.post("<?= api_url() ?>c_section/createSectionAdviser", data, function (data) {
                var response = JSON.parse(data);
                console.log(response);
                if (!response["error"].length) {
                    row.find(".sectionManagementListAdviser").attr("section_adviser_id", response["data"]);
                    row.find(".actionButton").show();
                    row.find(".confirmChangeButton").hide();
                }
            });
        }
    };

</script>