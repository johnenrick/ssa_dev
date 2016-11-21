<script>
    /*global systemUtility, systemApplication*/
    var gradingSystem = {};
    var grading_active = 1;
    var subjectid = 0;
    var max = [];
    var weights = [];
    var totalcomponents = 0;
    var section = "";
    var boys_count = 0;
    var girls_count = 0;
    
    $(document).ready(function () {
        gradingSystem.retrieveClassList();
        $("#gclassListBody").on("click", ".gclassListViewButton", function () {
            var classrow = $(this).parent().parent();
            $("#studentListGradingSystem").show();
            gradingSystem.retrieveStudentList(classrow);
            
        });
        $("#studentListGradingSystem").on("change", ".subjectComponentHPS input", function(){
            var component = $(this);
            if($(this).parent().attr("subject_schedule_component_hps")*1){//update
                var updatedData = {
                    ID : component.parent().attr("subject_schedule_component_hps"),
                    highest_possible_score : $(this).val()
                };
                $.post("<?=api_url()?>c_subject_schedule_component_hps/updateSubjectScheduleComponentHPS", updatedData, function(data){
                    var response = JSON.parse(data); 
                    if(!response["error"].length){
                        
                    }else{
                        console.log(response);
                    }
                });
            }else{//create
                var newData = {
                    subject_schedule_ID : $("#studentListGradingSystem").find("table").attr("subject_schedule_id"),
                    subject_component_ID : $(this).parent().attr("component_id"),
                    grading : $("#gradingSystemGrading").val(),
                    highest_possible_score : $(this).val()
                };
                $.post("<?= api_url() ?>c_subject_schedule_component_hps/createSubjectScheduleComponentHPS", newData, function(data){
                   var response = JSON.parse(data);
                   if(!response["error"].length){
                       component.parent().attr("subject_schedule_component_hps", response["data"]);
                   }
                });
            }
        });
        $("#studentListGradingSystem").on("change", ".totalScore input", function(){
            //Save Grade
            var totalScore = $(this).parent();
            if(totalScore.attr("student_subject_component_ID")*1 > 0){
                
                var updatedData = {
                    ID : totalScore.attr("student_subject_component_ID"),
                    score : totalScore.find("input").val()
                };
                $.post("<?=  api_url()?>c_student_subject_component_score/updateStudentSubjectComponentScore", updatedData, function(data){
                    var response = JSON.parse(data);
                    console.log(response);
                    if(!response["error"].length){
                        gradingSystem.calculateComponent(totalScore);
                    }else{
                        totalScore.find("input").val("0");
                    }
                });
            }else{//create
                var totalScoreIndex = totalScore.index();
                var totalScoreIndexCount = ((totalScoreIndex+1) / 3) - 1;
                var componentIndex = (totalScoreIndex-1) - (totalScoreIndexCount*2)+totalScoreIndexCount;
                var componentElement = $("#gradingSystemHeader2 td").eq(componentIndex);
                var newData = {
                    account_ID : totalScore.parent().attr("account_id"),
                    subject_schedule_component_hps_ID : componentElement.attr("subject_schedule_component_hps"),
                    score : totalScore.find("input").val()
                };
                $.post("<?=api_url()?>c_student_subject_component_score/createStudentSubjectComponentScore", newData, function(data){
                    var response = JSON.parse(data);
                    console.log(response);
                    if(!response["error"].length){
                        gradingSystem.calculateComponent(totalScore);
                    }else{
                        totalScore.find("input").val("0");
                    }
                });
            }
            
        });
        $("#studentListGradingSystem").on("change", ".subjectComponentHPS input", function(){
            $("#studentListGradingSystem").find(".totalScore").each(function(){
                gradingSystem.calculateComponent($(this));
            });
        });
        
        $("#gradingSystemGrading").change(function(){
            gradingSystem.gradingComponentHPS();
        });
    });
    gradingSystem.calculateComponent = function(totalScore){
        /*  Partial = (TotalScore / HPS)*100
            Weight = Partial*(Component Weight/100)
            Initial = SUM(Weight)
            Transmuted = VlookUp(Initial)   */
        var totalScoreIndex = totalScore.index();
        var totalScoreIndexCount = ((totalScoreIndex+1) / 3) - 1;
        var componentIndex = (totalScoreIndex-1) - (totalScoreIndexCount*2)+totalScoreIndexCount;
        var componentElement = $("#gradingSystemHeader2 td").eq(componentIndex);
        var componentHPS = componentElement.find("input").val()*1;
        var componentWeight = componentElement.next().text()*1;
        var partialScore = totalScore.find("input").val()/componentHPS * 100;
        partialScore = (isNaN(partialScore) || !isFinite(partialScore)) ? 0 : partialScore;
        totalScore.next().text(Math.round(partialScore));//partial
        totalScore.next().next().text((partialScore * (componentWeight/100)).toFixed(2));
        var studentRow = totalScore.parent();
        var totalPartial =0;
        studentRow.find(".weightedScore").each(function(){
            totalPartial += ($(this).text()*1);
        });
        studentRow.find(".initialGrade").text((totalPartial).toFixed(3));
        studentRow.find(".transmutedGrade").text(gradingSystem.transmuteGrade((totalPartial).toFixed(3)));
    };
    gradingSystem.transmuteGrade = function(initialGrade){
        var lookUpKey = [0,4,8,12,16,20,24,28,32,36,40,44,48,52,56,60,61.6,63.2,64.8,66.4,68,69.6,71.2,72.8,74.4,76,77.6,79.2,80.8,82.4,84,85.6,87.2,88.8,90.4,92,93.6,95.2,96.8,98.4,100];
        var lookUpValue = [60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100];
        for(var x = lookUpKey.length-1; x >=0 ;x--){
            if((initialGrade >= lookUpKey[lookUpKey.length-1]) || (x === 0) || (initialGrade < lookUpKey[x] && initialGrade >= lookUpKey[x-1])){
                return lookUpValue[(x === 0) ? 0 : ((initialGrade >= lookUpKey[lookUpKey.length-1]) ? lookUpKey.length-1 : x-1)];
            }
        }
    };
    gradingSystem.retrieveClassList = function () {
        var days = {
            "M": 32,
            "T": 16,
            "W": 8,
            "TH": 4,
            "F": 2,
            "S": 1
        };
        var filter = {
            teacher_ID: systemApplication.userInformation.userID,
            school_year: systemUtility.getCurrentAcademicYear()
        };
        $.post("<?= api_url() ?>c_schedule/retrieveSchedule", filter, function (data) {
            var scheduleDays = "";
            var response = JSON.parse(data);
            if (!response["error"].length) {
                $("#gclassListBody").empty();
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
                    var newRow = $(".prototype-gschedule").find(".gclassListRow").clone();
                    (response["data"][x]["section_ID"]) ? newRow.find(".gclassListSection").addClass("gclassListSection-" + response["data"][x]["section_ID"]) : "";
                    newRow.attr("schedule_ID", response["data"][x]["ID"]);
                    newRow.attr("type_ID", response["data"][x]["type_ID"]);
                    newRow.attr("section_ID", response["data"][x]["section_ID"]);
                    newRow.attr("subject_ID", response["data"][x]["subject_ID"]);
                    var year_level = (response["data"][x]["year_level"] * 1 !== 101) ? "Grade " + response["data"][x]["year_level"] : "CLUB";
                    newRow.find(".gclassListGrade").text(year_level);
                    //var section = (response["data"][x]["section_ID"] != 0)? response["data"][x]["section_name"] : "";
                    //newRow.find(".gclassListSection").text(section);
                    newRow.find(".gclassListDescriprion").text(response["data"][x]["subject_name"]);
                    newRow.find(".gclassListSchedule").text(formatAMPM(time_start) + " - " + formatAMPM(time_end) + " " + scheduleDays);
                    newRow.find(".gclassListRoom").text(response["data"][x]["room_name"]);//"Bldg. "+response["data"][x]["building_ID"]+" "+
                    scheduleDays = "";
                    $("#gclassListBody").append(newRow);

                    if (response["data"][x]["section_ID"] * 1 !== 0) {
                        //section = "";
                        $.post("<?= api_url() ?>c_section/retrieveSection", {ID: response["data"][x]["section_ID"]}, function (data1) {
                            var response1 = JSON.parse(data1);
                            $(".gclassListSection-" + response1["data"]["ID"]).text(response1["data"]["description"]);
                        });

                    }
                }
            }
        });
    };

    gradingSystem.retrieveStudentList = function (row, grade) {
        $("#gradingSystemHeader1").find(".subjectComponentDescription").remove();
        $("#gradingSystemHeader2").find(".subjectComponentHPS").remove();
        $("#gradingSystemHeader2").find(".subjectComponentWeight").remove();
        $("#gradingSystemHeader3").find("td:not(:first-child)").remove();
        
        $("#gradingSystemStudentList").empty();
        $(".prototype-studentlist").find(".scheduleStudent .totalScore").remove();
        $(".prototype-studentlist").find(".scheduleStudent .partialScore").remove();
        $(".prototype-studentlist").find(".scheduleStudent .weightedScore").remove();
        $(".prototype-studentlist").find(".scheduleStudent .initialGrade").remove();
        $(".prototype-studentlist").find(".scheduleStudent .transmutedGrade").remove();
        
        
        
        subjectid = row.attr("subject_id") * 1;
        var componentfilter = {
            subject_ID: subjectid
//            academic_year: systemUtility.getCurrentAcademicYear(),
//            subject_schedule_ID : row.attr("schedule_id"),
//            grading : $("#gradingSystemGrading").val()
        };
        
        $("#studentListGradingSystem").find("table").attr("subject_schedule_ID", row.attr("schedule_id"));
        var studentfilter = {
            type_ID: row.attr("type_id"),
            subject_ID: subjectid,
            grading: $("#gradingSystemGrading").val(),
            retrieveOptions: 1,
            school_year: systemUtility.getCurrentAcademicYear()
        };
        $("#gradingSystemSubjectDescription").text(row.find(".gclassListDescriprion").text());
        $("#gradingSystemClassDescription").text(row.find(".gclassListSection").text());
        $.post("<?= api_url() ?>c_subject/retrieveComponent", componentfilter, function (data) {
            var response = JSON.parse(data);
            if (!response["error"].length) {
                for(var x=0; x < response["data"].length; x++){
                    var subjectComponentDescription = $(".prototype-studentlist").find(".subjectComponentDescription").clone();
                    subjectComponentDescription.attr("component_id",response["data"][x]["ID"]);
                    subjectComponentDescription.text(response["data"][x]["description"]);
                    $("#gradingSystemSubjectDescription").after(subjectComponentDescription);
                    
                    var subjectComponentHPS = $(".prototype-studentlist").find(".subjectComponentHPS").clone();
//                    subjectComponentHPS.find("input").val(response["data"][x]["highest_possible_score"]);
//                    subjectComponentHPS.attr("subject_schedule_component_hps", response["data"][x]["subject_schedule_component_hps_ID"]*1);
                    subjectComponentHPS.attr("component_id", response["data"][x]["ID"]*1);
                    var subjectComponentWeight = $(".prototype-studentlist").find(".subjectComponentWeight").clone();
                    subjectComponentWeight.text(response["data"][x]["percentage"]);
                    $("#gradingSystemClassDescription").after(subjectComponentWeight);
                    $("#gradingSystemClassDescription").after(subjectComponentHPS);
                    
                    $("#gradingSystemHeader3").append("<td>Score</td><td>Partial</td><td>Weight</td>");
                    
                    var totalScore = $(".prototype-studentlist").find(".columnP .totalScore").clone();
                    var partialScore = $(".prototype-studentlist").find(".columnP .partialScore").clone();
                    var weightedScore = $(".prototype-studentlist").find(".columnP .weightedScore").clone();
                    
                    $(".prototype-studentlist").find(".scheduleStudent").append(totalScore);
                    $(".prototype-studentlist").find(".scheduleStudent").append(partialScore);
                    $(".prototype-studentlist").find(".scheduleStudent").append(weightedScore);
                }
                var initialGrade = $(".prototype-studentlist").find(".initialGrade").clone();
                var transmutedGrade = $(".prototype-studentlist").find(".transmutedGrade").clone();
                $(".prototype-studentlist").find(".scheduleStudent").append(initialGrade);
                $(".prototype-studentlist").find(".scheduleStudent").append(transmutedGrade);
                //STUDENT
                if(row.attr("type_id") * 1 === 1){
                    studentfilter.section_ID = row.attr("section_id") * 1;
                }else{
                    studentfilter.schedule_ID = row.attr("schedule_id") * 1;
                }
                (row.attr("type_id")*1 === 1) ? studentfilter.section_ID = row.attr("section_id") * 1 : "";
                var link = (row.attr("type_id")*1 === 1)? "<?= api_url() ?>c_class_section/retrieveClassSection" : "<?= api_url() ?>c_club_member/retrieveClubMember";
                $.post(link, studentfilter, function (data) {
                    var response = JSON.parse(data);
                    if (!response["error"].length) {
                        console.log(response);
                        var maleList = [];
                        var femaleList = [];
                        for(var x =0; x < response["data"].length;x++){
                            console.log(response["data"][x]["account_ID"])
                            var newRow = $(".prototype-studentlist").find(".scheduleStudent").clone();
                            newRow.attr("account_id", response["data"][x]["account_ID"]);
                            newRow.find(".scheduleStudentLastName").text(response["data"][x]["last_name"]);
                            newRow.find(".scheduleStudentFirstName").text(response["data"][x]["first_name"]);
                            if(response["data"][x]["gender"]*1 === 1){
                                maleList.push(newRow);
                            }else{
                                femaleList.push(newRow);
                            }
                            $("#gradingSystemStudentList").append(maleList);
                            $("#gradingSystemStudentList").append(newRow);
                        }
                        
                    }
                    gradingSystem.gradingComponentHPS();
                });
                
                $("#gradingSystemHeader3").append("<td colspan='2'></td>");
            }
        });
        

    };
    gradingSystem.gradingComponentHPS = function(){
        //component
        $("#gradingSystemHeader2 .subjectComponentHPS").attr("subject_schedule_component_hps", 0);
        $("#gradingSystemHeader2 .subjectComponentHPS input").val("");
        //student
        $(".scheduleStudent .totalScore input").val("");
        $(".scheduleStudent .totalScore").attr("student_subject_component_id", 0);
        $(".scheduleStudent .partialScore").text("");
        $(".scheduleStudent .weightedScore").text("");
        $(".scheduleStudent .initialGrade").text("");
        $(".scheduleStudent .transmutedGrade").text("");
        
        var filter = {
            subject_schedule_ID : $("#studentListGradingSystem table").attr("subject_schedule_id"),
            grading : $("#gradingSystemGrading").val()
        };
        $.post("<?=  api_url()?>c_subject_schedule_component_hps/retrieveSubjectScheduleComponentHPS", filter, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                for(var x = 0; x < response["data"].length; x++){
                    $(".subjectComponentHPS[component_id="+response["data"][x]["subject_component_ID"]+"]").attr("subject_schedule_component_hps",response["data"][x]["ID"] );
                    
                    $(".subjectComponentHPS[component_id="+response["data"][x]["subject_component_ID"]+"] input").val(response["data"][x]["highest_possible_score"]);
                }
                gradingSystem.retrieveStudentComponentScore();
            }
            
        });
        
    };
    gradingSystem.retrieveStudentComponentScore = function(){
        var filter = {
            subject_schedule_ID : $("#studentListGradingSystem table").attr("subject_schedule_id"),
            grading : $("#gradingSystemGrading").val()
        };
        
        $.post("<?=  api_url()?>c_student_subject_component_score/retrieveStudentSubjectComponentScore", filter, function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                //calculating indices
                var componentIndex = {};
                $("#gradingSystemHeader1").find(".subjectComponentDescription").each(function(){
                    var currentIndex = $(this).index()*1;
                    var studentComponentIndex = currentIndex + 1 + ((currentIndex-1)*2)
                    componentIndex[$(this).attr("component_id")] = studentComponentIndex;
                    
                });
                for(var x = 0; x < response["data"].length; x++){
                    var totalComponentScore = $("#gradingSystemStudentList tr[account_id="+response["data"][x]["account_ID"]+"] td").eq(componentIndex[response["data"][x]["subject_component_ID"]]);
                    totalComponentScore.find("input").val(response["data"][x]["score"]);
                    gradingSystem.calculateComponent(totalComponentScore);
                    totalComponentScore.attr("student_subject_component_ID", response["data"][x]["ID"]);
                }
            }
        });
    };
    function format24Hour(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = (hours < 10 ? '0' : '') + hours + ':' + minutes + ":00";
        return strTime;
    }
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