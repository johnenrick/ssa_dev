<script>
    /*global syssystemApplication, systemUtility*/
var classList = {};
var currentDate = new Date();
var active_section = 0;

$(document).ready(function(){
	classList.retrieveClassList();
	$("#classListBody").on("click", ".classListViewButton", function(){
		classList.retrieveStudentList($(this).parent().parent());
	});
	$(".classListStudentListBody-Boys-Girls").on("blur", ".attendanceP", function(){
		console.log($(this).parent().parent().attr("account_id"));
		console.log($(this).val());
		console.log($(this).attr("month"));
		console.log(active_section);
	});
	$(".classListStudentListBody-Boys-Girls").on("blur", ".attendanceL", function(){
		console.log($(this).parent().parent().attr("account_id"));
		console.log($(this).val());
		console.log($(this).attr("month"));
		console.log(active_section);
	});
});
classList.retrieveClassList = function(){
	var days = {
        "M"  : 32,
        "T"  : 16,
        "W"  : 8,
        "TH" : 4,
        "F"  : 2,
        "S"  : 1
    };
	var filter = {
		teacher_ID: systemApplication.userInformation.userID,
		school_year: systemUtility.getCurrentAcademicYear()
	};
	console.log(filter);
	$.post("<?=api_url()?>c_schedule/retrieveSchedule", filter, function(data){
		var scheduleDays = "";
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
			$("#classListBody").empty();
			for(var x = 0; x < response["data"].length; x++){
				var totaldayscount = response["data"][x]["day"];
				for(var key in days){
					if(totaldayscount >= days[key]){
						scheduleDays += key;
						totaldayscount -= days[key];
					}
				}
				var time_start = new Date(response["data"][x]["time_start"]*1000);
				var time_end   = new Date(response["data"][x]["time_end"]*1000);
				var newRow = $(".prototype-schedule").find(".classListRow").clone();
				(response["data"][x]["section_ID"])? newRow.find(".classListSection").addClass("classListSection-"+response["data"][x]["section_ID"]) : "";
				(response["data"][x]["type_ID"]*1 == 1)? newRow.attr("section_ID", response["data"][x]["section_ID"]) : newRow.attr("subject_ID", response["data"][x]["subject_ID"]);
				newRow.attr("schedule_ID", response["data"][x]["ID"]);
				newRow.attr("type_ID", response["data"][x]["type_ID"]);
				var year_level = (response["data"][x]["year_level"] != 101)? "Grade "+response["data"][x]["year_level"] : "CLUB";
				newRow.find(".classListGrade").text(year_level);
				newRow.find(".classListDescription").text(response["data"][x]["subject_name"]);
				newRow.find(".classListSchedule").text(formatAMPM(time_start)+" - " + formatAMPM(time_end)+" "+scheduleDays);
				newRow.find(".classListRoom").text(response["data"][x]["room_name"]);
				scheduleDays = "";
				$("#classListBody").append(newRow);

				if(response["data"][x]["section_ID"] != 0){
					//section = "";
					$.post("<?=api_url()?>c_section/retrieveSection",{ID: response["data"][x]["section_ID"]}, function(data1){
						var response1 = JSON.parse(data1);
				        $(".classListSection-"+response1["data"]["ID"]).text(response1["data"]["description"]);
				        //console.log(section.name);
					});
					
				}
			}
        }
	});
}
classList.retrieveStudentList = function(row){
	$("#classListSubjectName").text(row.find(".classListDescription").text());
	$("#classListGradeSectionName").text(row.find(".classListGrade").text()+" - "+row.find(".classListSection").text());
	var filter = {
            school_year: systemUtility.getCurrentAcademicYear()
	};
	active_section = row.attr("section_id")*1;
        if(row.attr("type_id") * 1 === 1){
            filter.section_ID = row.attr("section_id") * 1;
        }else{
            filter.schedule_ID = row.attr("schedule_id") * 1;
        }
        
        var link = (row.attr("type_id")*1 === 1)? "<?= api_url() ?>c_class_section/retrieveClassSection" : "<?= api_url() ?>c_club_member/retrieveClubMember";
        $.post(link, filter, function (data) {
            var response = JSON.parse(data);
            $("#classListStudentListBody-Boys").empty();
                $("#classListStudentListBody-Girls").empty();
            if (!response["error"].length) {
                
                for(var x =0; x < response["data"].length; x++){
                    var student = $(".prototype .studentListRow").clone();
                    student.find(".studentLastName").text(response["data"][x]["last_name"]);
                    student.find(".studentFirstName").text(response["data"][x]["first_name"]+" "+(response["data"][x]["middle_name"]).charAt(0));
                    if(response["data"][x]["gender"]*1 === 1){
                        $("#classListStudentListBody-Boys").append(student);
                    }else{
                        $("#classListStudentListBody-Girls").append(student);
                    }
                }

                $("#studentListClassList").show();
            }
        });
	
}
function format24Hour(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();        
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = (hours < 10 ? '0':'') +  hours + ':' + minutes + ":00";
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