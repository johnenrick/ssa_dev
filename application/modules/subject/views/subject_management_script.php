<script>
    /*global systemUtility*/
    var subjectManagement = {};
    var currentDate = new Date();

    $(document).ready(function () {
        $('#subjectManagementAcademicYear').val(systemUtility.getCurrentAcademicYear())
        subjectManagement.retrieveSubjectList();
        //form action buttons
        $("#subjectManagementCreateFormButton").click(function () {
            subjectManagement.changeFormAction(1);
            subjectManagement.refreshFormOptions();
            $("#subjectManagementForm").trigger("reset");
            $("#subjectComponentManagementListBody").empty();
            var html = "<tr>" +
                    "<td><input type='text' class='form-control' name='component_description[]'/></td>" +
                    "<td><input type='number' class='form-control componentpercentage' name='component_percentage[]' style='width: 70px;' min='0' max='100'/></td>" +
                    "<td>" +
                    "<button class='btn btn-xs btn-info addComponent' type='button' style='margin-top: 5px'>" +
                    "<span id='addComponent' class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add" +
                    "</button>" +
                    "</td>" +
                    "</tr>";
            $("#subjectComponentManagementListBody").append(html);
            $("#subjectManagementForm").attr("action", "<?= api_url() ?>c_subject/createSubject");
        });
        $("#subjectComponentManagementListBody").on("click", ".addComponent", function () {
            var html = "<tr>" +
                    "<td><input type='text' class='form-control' required name='component_description[]'/></td>" +
                    "<td><input type='number' class='form-control componentpercentage' required name='component_percentage[]' style='width: 70px;' min='0' max='100'/></td>" +
                    "<td></td>" +
                    "</tr>";
            $("#subjectComponentManagementListBody").append(html);
        });
        $("#subjectComponentManagementListBody").on("click", ".updateComponent", function () {
            var data1 = {
                ID: $(this).parent().parent().attr("component_id"),
                description: $(this).parent().parent().find(".udescription").val(),
                percentage: $(this).parent().parent().find(".upercentage").val()
            };

            $.post("<?= api_url() ?>c_subject/updateComponent", data1, function (data) {
                var response = JSON.parse(data);
                if (!response["error"].length) {
                }
            });
        });
        $("#subjectComponentManagementListBody").on("click", ".removeComponent", function () {
            $row = $(this).parent().parent();
            $.post("<?= api_url() ?>c_subject/removeComponent", {ID: $row.attr("component_id")}, function (data) {
                var response = JSON.parse(data);
                if (!response["error"].length) {
                    $row.remove();
                }
            });
        });
        $("#subjectManagementCloseFormButton").click(function () {
            subjectManagement.changeFormAction(2);
        });
        $("#subjectManagementSubmitFormButton").click(function () {
            $("#subjectManagementForm").submit();
        });
        $("#subjectManagementForm").ajaxForm({
            success: function (data) {
                var response = JSON.parse(data);
                if (!response["error"].length) {
                    subjectManagement.retrieveSubjectList();
                    subjectManagement.changeFormAction(2);
                } else {
                    $("#subjectManagementMessage").html(response["error"][0]["message"]);
                    $("#subjectManagementMessage").removeClass("hide").addClass("alert-danger");
                    setTimeout(function () {
                        $("#subjectManagementMessage").html("");
                        $("#subjectManagementMessage").removeClass("alert-danger").addClass("hide");
                    }, 3000 * response["error"].length);
                }
            }
        });
        /*$("#subjectManagementDescription").blur(function(){
         $.post("<?= api_url() ?>c_subject/checkSubjectName", {description: $(this).val()}, function(response){
         response = JSON.parse(response);
         if(!response["error"].length){
         if(response["data"] == 1) $("#subjectManagementMessage").removeClass("hide").addClass("alert-danger").html("Subject is Already in the Database");
         setTimeout(function(){
         $("#subjectManagementMessage").html("");
         $("#subjectManagementMessage").hide();
         },2000);
         }
         });
         });*/
        //subject list page nav
        $("#subjectManagementListPreviousPage").click(function () {
            if ($("#subjectManagementListCurrentPage").text() * 1 - 1) {
                $("#subjectManagementListCurrentPage").text($("#subjectManagementListCurrentPage").text() * 1 - 1);
                subjectManagement.retrieveSubjectList();
            }
        });
        $("#subjectManagementListNextPage").click(function () {
            $("#subjectManagementListCurrentPage").text($("#subjectManagementListCurrentPage").text() * 1 + 1);
            subjectManagement.retrieveSubjectList();
        });
        //subject list action
        $("#subjectManagementListBody").on("click", ".subjectManagementListRemoveButton", function () {
            $(this).parent().find(".confirmButton").show();
            $(this).parent().find(".actionButton").hide();
        });
        $("#subjectManagementListBody").on("click", ".subjectManagementListNoRemoveButton", function () {
            $(this).parent().find(".confirmButton").hide();
            $(this).parent().find(".actionButton").show();
        });
        $("#subjectManagementListBody").on("click", ".subjectManagementListYesRemoveButton", function () {
            subjectManagement.removeSubjectList($(this).parent().parent());
        });
        $("#subjectManagementListBody").on("click", ".subjectManagementListViewButton", function () {
            subjectManagement.viewSubjectInformation($(this).parent().parent().attr("subject_ID"));
            subjectManagement.changeFormAction(1);
        });
        $("#subjectComponentManagementListBody").on("keyup", ".componentpercentage", function (e) {
            var totalP = 0;
            $(".componentpercentage").each(function (ev) {
                totalP += $(this).val() * 1;
                if (($(this).val() * 1 > 100 || totalP > 100)) {
                    ev.stopPropagation();
                }
            });
            $("#subjectComponentTotalPercentage").text(totalP);
        });
        $("#subjectManagementFilterSearch").click(function () {
            subjectManagement.retrieveSubjectList(1);
        });
        $("#subjectManagementSubjectType").change(function () {
            ($(this).val()*1 === 1) ? $("#subjectManagementYearCourse").show() : $("#subjectManagementYearCourse").hide();
        });
    });
    subjectManagement.changeFormAction = function (status) {
        switch (status * 1) {
            case 1://Create Button
                $("#subjectManagementCloseFormButton").show();
                $("#subjectManagementSubmitFormButton").show();
                $("#subjectManagementCreateFormButton").hide();
                $("#subjectManagementDiv").slideDown();
                break;
            case 2://Close Button
                $("#subjectManagementDiv").slideUp();
                $("#subjectManagementCloseFormButton").hide();
                $("#subjectManagementSubmitFormButton").hide();
                $("#subjectManagementCreateFormButton").show();
                break;
        }
    };
    subjectManagement.viewSubjectInformation = function (subjectID) {
        $("#subjectManagementForm").trigger("reset");
        subjectManagement.refreshFormOptions();
        $.post("<?= api_url() ?>c_subject/retrieveSubject", {ID: subjectID, academic_year:systemUtility.getCurrentAcademicYear(), has_component : true}, function (data) {
            var response = JSON.parse(data);
            console.log(response);
            if (!response["error"].length) {
                $("#subjectComponentManagementListBody").empty();
                if (response["data"]["component"]){
                    var totalP = 0;
                    console.log(response["data"]["component"].length)
                    for (var x = 0; x < response["data"]["component"].length; x++) {
                        totalP += response["data"]["component"][x]["percentage"] * 1;
                        var html = "<tr component_id='" + response["data"]["component"][x]["ID"] + "'>" +
                                "<td><input type='text' class='form-control udescription' name='ucomponent_description[]' value='" + response["data"]["component"][x]["description"] + "'/></td>" +
                                "<td><input type='number' class='form-control upercentage componentpercentage' name='rcomponent_percentage[]' value='" + response["data"]["component"][x]["percentage"] + "' style='width: 70px;' min='0' max='99' maxlength='2' /></td>" +
                                "<td>" +
                                "<button class='btn btn-xs btn-info updateComponent actionComponent' type='button' style='margin-top: 5px; margin-right: 2px; padding: 5px'>" +
                                "<span id='updateComponent' class='glyphicon glyphicon-check' aria-hidden='true'></span>" +
                                "</button>" +
                                "<button class='btn btn-xs btn-danger removeComponent actionComponent' type='button' style='margin-top: 5px; padding: 5px'>" +
                                "<span id='removeComponent' class='glyphicon glyphicon-trash' aria-hidden='true'></span>" +
                                "</button>" +
                                "</td>" +
                                "</tr>";
                        $("#subjectComponentManagementListBody").append(html);
                    }
                    var html = "<tr>" +
                            "<td><input type='text' class='form-control' name='component_description[]'/></td>" +
                            "<td><input type='number' class='form-control componentpercentage' name='component_percentage[]' style='width: 70px;' min='0' max='99' maxlength='2'/></td>" +
                            "<td>" +
                            "<button class='btn btn-xs btn-info addComponent' type='button' style='margin-top: 5px'>" +
                            "<span id='addComponent' class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add" +
                            "</button>" +
                            "</td>" +
                            "</tr>";
                    $("#subjectComponentManagementListBody").append(html);
                    $("#subjectManagementForm").trigger("reset");
                    $("#subjectManagementID").val(response["data"]["ID"] * 1);
                    $("#subjectManagementDescription").val(response["data"]["description"]);
                    (response["data"]["elective"] * 1 === 1) ? $("#subjectManagementElective").prop("checked", true) : $("#subjectManagementElective").prop("checked", false);
                    $("#subjectManagementSubjectType").val(response["data"]["type_ID"]);
                    (response["data"]["type_ID"] * 1 === 1) ? $("#subjectManagementYearCourse").show() : $("#subjectManagementYearCourse").hide();
                    $("#subjectManagementCourse").val(response["data"]["course_ID"]);
                    $("#subjectManagementYearLevel").val(response["data"]["year_level"]);
                    $("#subjectComponentTotalPercentage").text(totalP);

                    $("#subjectManagementForm").attr("action", "<?= api_url() ?>c_subject/updateSubject");
                    $("#subjectManagementDiv").slideDown();
                }
            }
        });
    };
    subjectManagement.retrieveSubjectList = function (retrieveType) {
        var filter = {
            limit: 20,
            offset: ((($("#subjectManagementListCurrentPage").text() * 1 - 1) > 0) ? ($("#subjectManagementListCurrentPage").text() * 1 - 1) : 0) * 20,
            year_level: $("#subjectManagementFilterYearLevel").val() * 1,
            description: $("#subjectManagementFilterDescription").val()
        };
        $.post("<?= api_url() ?>c_subject/retrieveSubject", filter, function (data) {

            var response = JSON.parse(data);
            if (!response["error"].length) {
                var totalPages = Math.ceil(response["result_count"] / 20);
                var gradelevel = "";
                $("#subjectManagementListTotalPage").text(totalPages);
                if ($("#subjectManagementListCurrentPage").text() * 1 <= totalPages) {
                    $("#subjectManagementListBody").empty();
                    for (var x = 0; x < response["data"].length; x++) {
                        var newRow = $(".prototype").find(".subjectManagementListRow").clone();
                        newRow.attr("subject_ID", response["data"][x]["ID"]);
                        newRow.find(".subjectManagementListDescriprion").text(response["data"][x]["description"]);
                        gradelevel = (response["data"][x]["year_level"]*1 === 101) ? "CLUB" : response["data"][x]["course_description"] + " " + response["data"][x]["year_level"];
                        newRow.find(".subjectManagementListYearLevel").text(gradelevel);

                        newRow.find(".confirmButton").hide();
                        $("#subjectManagementListBody").append(newRow);
                    }
                }
            }
            $("#subjectManagementListCurrentPage").text(($("#subjectManagementListCurrentPage").text() * 1 > $("#subjectManagementListTotalPage").text() * 1) ? $("#subjectManagementListTotalPage").text() * 1 : $("#subjectManagementListCurrentPage").text() * 1);
        });
    };
    subjectManagement.removeSubjectList = function (row) {
        $.post("<?= api_url() ?>c_subject/deleteSubject", {ID: row.attr("subject_id")}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                row.remove();
                subjectManagement.retrieveSubjectList();
            }
        });
    };
    subjectManagement.refreshFormOptions = function () {
        //courses
        $.post("<?= api_url() ?>c_course/retrieveCourse", {}, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#subjectManagementCourse").empty();
                for (var x = 0; x < response["data"].length; x++) {
                    $("#subjectManagementCourse").append("<option value='" + response["data"][x]["ID"] + "' >" + response["data"][x]["description"] + "</option>");
                }
                $("#subjectManagementCourse").val(1);
                $("#subjectComponentTotalPercentage").text(0);
            }

        });
    };
</script>