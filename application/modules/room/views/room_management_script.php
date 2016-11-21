<script>
var roomManagement = {};
$(document).ready(function(){
	roomManagement.retrieveRoomList();
	//form action buttons
    $("#roomManagementCreateFormButton").click(function(){
        roomManagement.changeFormAction(1);
        $("#roomManagementForm").trigger("reset");
        $("#roomManagementForm").attr("action", "<?=api_url()?>c_room/createRoom");
    });
    $("#roomManagementCloseFormButton").click(function(){
        roomManagement.changeFormAction(2);
    });
    $("#roomManagementSubmitFormButton").click(function(){
        $("#roomManagementForm").submit();
    });
    $("#roomManagementForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            console.log(response);
            if(!response["error"].length){
                roomManagement.retrieveRoomList();
                roomManagement.changeFormAction(2);
            }else{
                $("#roomManagementMessage").html(response["error"][0]["message"]);
                $("#roomManagementMessage").removeClass("hide").addClass("alert-danger");
                setTimeout(function(){
                    $("#roomManagementMessage").html("");
                    $("#roomManagementMessage").removeClass("alert-danger").addClass("hide");
                },3000 * response["error"].length);
            }
        }
    });
    //room list page nav
    $("#roomManagementListPreviousPage").click(function(){
        if($("#roomManagementListCurrentPage").text()*1 -1){
            $("#roomManagementListCurrentPage").text($("#roomManagementListCurrentPage").text()*1 -1);
            roomManagement.retrieveRoomList();
        } 
     });
     $("#roomManagementListNextPage").click(function(){
        $("#roomManagementListCurrentPage").text($("#roomManagementListCurrentPage").text()*1 + 1);
        roomManagement.retrieveRoomList();
     });
     //room list action
     $("#roomManagementListBody").on("click", ".roomManagementListRemoveButton", function(){
        $(this).parent().find(".confirmButton").show();
        $(this).parent().find(".actionButton").hide();
     });
     $("#roomManagementListBody").on("click", ".roomManagementListNoRemoveButton", function(){
        $(this).parent().find(".confirmButton").hide();
        $(this).parent().find(".actionButton").show();
     });
     $("#roomManagementListBody").on("click", ".roomManagementListYesRemoveButton", function(){
        roomManagement.removeRoomList($(this).parent().parent());
     });
     $("#roomManagementListBody").on("click", ".roomManagementListViewButton", function(){
        roomManagement.viewRoomInformation($(this).parent().parent().attr("room_ID"));
        $("#roomManagementForm").attr("action", "<?=api_url()?>c_room/createRoom");
        roomManagement.changeFormAction(1);
     });
});
roomManagement.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#roomManagementCloseFormButton").show();
            $("#roomManagementSubmitFormButton").show();
            $("#roomManagementCreateFormButton").hide();
        	$("#roomManagementDiv").slideDown();
            break;
        case 2://Close Button
            $("#roomManagementDiv").slideUp();
            $("#roomManagementCloseFormButton").hide();
            $("#roomManagementSubmitFormButton").hide();
            $("#roomManagementCreateFormButton").show();
            break;
    }
}
roomManagement.viewRoomInformation = function(roomID){
    $("#roomManagementForm").trigger("reset");
    roomManagement.refreshFormOptions();
    $.post("<?=  api_url()?>c_room/retrieveRoom",{ID : roomID},function(data){
        
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#roomManagementForm").trigger("reset");
            $("#roomManagementID").val(response["data"]["ID"]*1);
            $("#roomManagementDescription").val(response["data"]["description"]);
            $("#roomManagementBuilding").val(response["data"]["building_ID"]);
            $("#roomManagementCapacity").val(response["data"]["capacity"]);
            
            $("#roomManagementForm").attr("action", "<?=api_url()?>c_room/updateroom");
            $("#roomManagementDiv").slideDown();
        }
    });
}
roomManagement.retrieveRoomList = function(){
    var filter = {
        limit : 20,
        offset : ((($("#roomManagementListCurrentPage").text()*1 - 1) > 0) ? ($("#roomManagementListCurrentPage").text()*1 - 1) : 0) * 20
    };
    $.post("<?=api_url()?>c_room/retrieveRoom", filter, function(data){
       
        var response = JSON.parse(data);
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
              $("#roomManagementListTotalPage").text(totalPages);
              if($("#roomManagementListCurrentPage").text()*1 <= totalPages){
                  $("#roomManagementListBody").empty();
                  for(var x = 0; x < response["data"].length; x++){
                      var newRow = $(".prototype").find(".roomManagementListRow").clone();
                      newRow.attr("room_ID", response["data"][x]["ID"]);
                      newRow.find(".roomManagementListDescriprion").text("Building " + response["data"][x]["building_ID"] + ", " +response["data"][x]["description"]);
                      newRow.find(".roomManagementListCapacity").text(response["data"][x]["capacity"]);
                     
                      newRow.find(".confirmButton").hide();
                      $("#roomManagementListBody").append(newRow);
                  }
              }
        }
        $("#roomManagementListCurrentPage").text( ($("#roomManagementListCurrentPage").text()*1 > $("#roomManagementListTotalPage").text()*1) ?  $("#roomManagementListTotalPage").text()*1 : $("#roomManagementListCurrentPage").text()*1);
    });
}
roomManagement.removeRoomList = function(row){
    $.post("<?=  api_url()?>c_room/deleteRoom",{ID : row.attr("room_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            roomManagement.retrieveRoomList();
        }
    });
}
roomManagement.refreshFormOptions = function(){
    //courses
    $.post("<?=api_url()?>c_course/retrieveCourse", {}, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            var currentValue = $("#roomManagementCourse").val();
            $("#roomManagementCourse").empty();
            for(var x = 0; x < response["data"].length; x++){
                $("#roomManagementCourse").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
            }
            $("#roomManagementCourse").val(currentValue);
        }
        
    });
}
</script>