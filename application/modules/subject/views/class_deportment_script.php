<script>
classDeportment = {};
var active_section = 0;

$(document).ready(function(){
	classDeportment.retrieveClassSections();
	$("#classDeportmentBody").on("click", ".classDeportmentViewButton", function(){
		classDeportment.retrieveStudentList($(this).parent().parent());
	});

	$(".classDeportmentStudentList-Boys-Girls").on("blur", ".deportment", function(){
		var link = (($(this).attr("prev_val"))*1 > 0)? "<?=api_url() ?>c_class_deportment/updateClassDeportment" : "<?=api_url() ?>c_class_deportment/createClassDeportment";
		var deportment = {
			deportment_ID: $(this).attr("deportment"),
			account_ID: $(this).parent().parent().attr("account_id"),
			section_ID: active_section,
			value: $(this).val(),
			school_year: systemUtility.getCurrentAcademicYear()
		}
		var this_ID = $(this).attr("id");
		$.post(link, deportment, function(data){
			var response = JSON.parse(data);
			$("#"+this_ID).attr("prev_val", deportment.value);
		});
	});

	$(".table-bordered").on("keydown", "input", function(e){
		var s_id = $(this).attr("id").split("-");
		var next = $(this).attr("next");
		var prev = $(this).attr("prev");
	    if (e.keyCode == 38) { // arrow up
	    	$("#"+s_id[0]+"-"+s_id[1]+"-"+prev+"-"+s_id[3]).focus();
	    }    
	    if (e.keyCode == 40) { // arrow down
	    	$("#"+s_id[0]+"-"+s_id[1]+"-"+next+"-"+s_id[3]).focus();
	    }
	});
});
classDeportment.retrieveClassSections = function(){
	var filter = {
		adviser: systemApplication.userInformation.userID,
		school_year: systemUtility.getCurrentAcademicYear()
	};
	$.post("<?=api_url()?>c_section/retrieveSection", filter, function(data){
		var response = JSON.parse(data);
		if(!response["error"].length){
			$("#classDeportmentBody").empty();
			for(var x in response["data"]){
				var newRow = $(".prototype-section").find(".classDeportmentRow").clone();
				newRow.attr("section_id", response["data"][x]["ID"]);
				newRow.find(".classDeportmentGrade").text("Grade "+response["data"][x]["year_level"]);
				newRow.find(".classDeportmentSection").text(response["data"][x]["description"]);

				$("#classDeportmentBody").append(newRow);
			}
		}
	});
}

classDeportment.retrieveStudentList = function(row){
	$.post("<?=api_url() ?>c_class_deportment/retrieveClassDeportmentDescription", {},function(data){
		var response = JSON.parse(data);
		$("#classDeportmentHeader").empty();
		var header = 	"<th colspan='4' rowspan='2' id='classDeportmentGradeSectionName'style='text-align: center; vertical-align: middle;'>" +
							row.find(".classDeportmentGrade").text()+" - "+row.find(".classDeportmentSection").text() +
						"</th>"
		$("#classDeportmentHeader").append(header);
		for(var x in response["data"]){
			var header = 	"<th rowspan='2' id='classDeportmentGradeSectionName'style='text-align: center; vertical-align: middle;'>" +
								response["data"][x]["description"] +
							"</th>"
			$("#classDeportmentHeader").append(header);
		}
	});
	var filter = {
		section_ID: row.attr("section_id")*1,
		type_ID: 1,
		school_year: systemUtility.getCurrentAcademicYear()
	};
	active_section = row.attr("section_id")*1;
	$.post("<?=api_url()?>c_grading_system/retrieveStudents", filter, function(data1){
		var response1 = JSON.parse(data1);
		if(!response1["error"].length){
			var boys_count = (response1["data"]["boys"]).length - 1;
			var girls_count = (response1["data"]["girls"]).length - 1;
			$("#classDeportmentStudentList-Boys-1").empty();
			$("#classDeportmentStudentList-Girls-1").empty();
			var htmlboys = 	"<tr>" +
								"<td colspan='4' style='text-align: center; padding: 0;'>Boys</td>" +
								"<td colspan='20'></td>" +
							"</tr>";
			$("#classDeportmentStudentList-Boys-1").append(htmlboys);

			for(var x = 0; x <= boys_count; x++){
				var acc_boys = response1["data"]["boys"][x]["account_ID"];
				var next_stud = (x < boys_count)? response1["data"]["boys"][x + 1]["account_ID"] : response1["data"]["girls"][0]["account_ID"];
				var prev_stud = (x !== 0)? response1["data"]["boys"][x - 1]["account_ID"] : 0 ;
				var listBoys =	"<tr account_id="+acc_boys+">" +
									"<td colspan='1' style='padding: 0px; border-right: white solid 1px;' class='col-md-1'>"+response1["data"]["boys"][x]["last_name"]+"</td>" +
									"<td colspan='3' style='padding: 0px; border-left: white solid 1px;' class='col-md-3'>"+", "+response1["data"]["boys"][x]["first_name"]+"</td>" +
									"<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-1-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='1' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-2-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='2' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-3-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='3' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-4-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='4' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-5-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='5' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-6-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='6' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-7-"+acc_boys+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='7' /></td>" +
								"</tr>";
				$("#classDeportmentStudentList-Boys-1").append(listBoys);
				var deportment1 = {
					account_ID: acc_boys,
					section_ID: active_section,
					school_year: systemUtility.getCurrentAcademicYear()
				}
				$.ajax({
					url: "<?=api_url() ?>c_class_deportment/retrieveClassDeportment",
					type: "POST",
					data: deportment1,
					async: false,
					success: function(data){
						var temp = JSON.parse(data);
						for(var x in temp["data"]){
							$("#deportment-"+temp["data"][x]["class_deportment_ID"]+"-"+acc_boys+"-D").val(temp["data"][x]["value"]);
							$("#deportment-"+temp["data"][x]["class_deportment_ID"]+"-"+acc_boys+"-D").attr("prev_val", temp["data"][x]["value"]);
						}
					}
				});
			}
			var htmlgirls = "<tr>" +
								"<td colspan='4' style='text-align: center; padding: 0;'>Girls</td>" +
								"<td colspan='20'></td>" +
							"</tr>";
			$("#classDeportmentStudentList-Girls-1").append(htmlgirls);
			for(var x = 0; x <= girls_count; x++){
				var acc_girls = response1["data"]["girls"][x]["account_ID"];
				var next_stud = (x < girls_count)? response1["data"]["girls"][x + 1]["account_ID"] : 0;
				var prev_stud = (x !== 0)? response1["data"]["girls"][x - 1]["account_ID"] : response1["data"]["boys"][boys_count]["account_ID"];
				var listGirls =	"<tr account_id="+acc_girls+">" +
									"<td colspan='1' style='padding: 0px; border-right: white solid 1px;' class='col-md-1'>"+response1["data"]["girls"][x]["last_name"]+"</td>" +
									"<td colspan='3' style='padding: 0px; border-left: white solid 1px;' class='col-md-3'>"+", "+response1["data"]["girls"][x]["first_name"]+"</td>" +
									"<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-1-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='1' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-2-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='2' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-3-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='3' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-4-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='4' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-5-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='5' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-6-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='6' /></td>" +
				                    "<td style='text-align: center; padding: 0;'><input type='text' prev_val='0' next='"+next_stud+"' prev='"+prev_stud+"' id='deportment-7-"+acc_girls+"-D' class='deportment form-control' size=3 style='padding: 0px 2px; height: 25px;' deportment='7' /></td>" +
								"</tr>";
				$("#classDeportmentStudentList-Girls-1").append(listGirls);
				var deportment2 = {
					account_ID: acc_girls,
					section_ID: active_section,
					school_year: systemUtility.getCurrentAcademicYear()
				}
				$.ajax({
					url: "<?=api_url() ?>c_class_deportment/retrieveClassDeportment",
					type: "POST",
					data: deportment2,
					async: false,
					success: function(data1){
						var temp = JSON.parse(data1);
						for(var x in temp["data"]){
							$("#deportment-"+temp["data"][x]["class_deportment_ID"]+"-"+acc_girls+"-D").val(temp["data"][x]["value"]);
							$("#deportment-"+temp["data"][x]["class_deportment_ID"]+"-"+acc_girls+"-D").attr("prev_val", temp["data"][x]["value"]);
						}
					}
				});
			}
		}
	});

}
</script>


