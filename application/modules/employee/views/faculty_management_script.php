<script>
    /*global systemApplication*/
var facultyManagement = {};
facultyManagement.sectionList = [];
facultyManagement.facultyManagementListFilter = {};
$(document).ready(function(){
    facultyManagement.retrieveList();
    //form action buttons
    $("#facultyManagementCreateFormButton").click(function(){
        facultyManagement.changeFormAction(1);
        $("#facultyManagementForm").trigger("reset");
        $("#facultyManagementForm").attr("action", "<?=api_url()?>c_account/createAccount");
        $("#facultyManagementDiv").slideDown();
    });
    $("#facultyManagementSubmitFormButton").click(function(){ 
        $("#facultyManagementForm").submit();
    });
    $("#facultyManagementCloseFormButton").click(function(){
        facultyManagement.changeFormAction(2);
    });
    $("#facultyManagementBirthDate").change(function(){
       $("#facultyManagementBirthDatetime").val((new Date($("#facultyManagementBirthDate").val())).getTime()/1000);
    });
    $("#facultyManagementForm").ajaxForm({
        beforeSubmit : function(){
            $("#facultyManagementBirthDatetime").val((new Date($("#facultyManagementBirthDate").val())).getTime()/1000);
        },
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                facultyManagement.retrieveList();
                $("#facultyManagementForm").trigger("reset");
                $("#facultyManagementDiv").slideUp();
                facultyManagement.changeFormAction(2);
                $.post(systemApplication.url.apiUrl+"c_access_control_list/batchReplaceAccessContolList", {account_ID: response["data"],access_control_list : facultyManagement.retrieveAccessControlList(response["data"])}, function(data){
                    console.log(data);
                });
            }else{
                $("#facultyManagementMessage").html(response["error"][0]["message"]);
                $("#facultyManagementMessage").show();
                setTimeout(function(){
                    $("#facultyManagementMessage").html("");
                    $("#facultyManagementMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#facultyManagementListPreviousPage").click(function(){
        if($("#facultyManagementListCurrentPage").text()*1 -1){
            $("#facultyManagementListCurrentPage").text($("#facultyManagementListCurrentPage").text()*1 -1);
            facultyManagement.retrieveList();
        }
    });
    $("#facultyManagementListNextPage").click(function(){
        $("#facultyManagementListCurrentPage").text($("#facultyManagementListCurrentPage").text()*1 + 1);
        facultyManagement.retrieveList();
    });
     //class section list action
    $("#facultyManagementListBody").on("click", ".facultyManagementListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#facultyManagementListBody").on("click", ".facultyManagementListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#facultyManagementListBody").on("click", ".facultyManagementListYesRemoveButton", function(){
       facultyManagement.removeList($(this).parent().parent());
    });

    $("#facultyManagementListBody").on("click", ".facultyManagementListYesChangeButton", function(){
       facultyManagement.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".facultyManagementListSorter").click(function(){ 
        $(".facultyManagementListSorter").attr("sorted",0);
        $(this).attr("sorted",1);
        // 1 - asc, 0 - desc
        if($(this).attr("sort")*1){
            $(this).attr("sort", 0 );
            $(this).find(".sortDown").show();
            $(this).find(".sortUp").hide();
        }else{
            $(this).attr("sort", 1);
            $(this).find(".sortDown").hide();
            $(this).find(".sortUp").show();
        }
        
        facultyManagement.retrieveList();
    });
    $("#facultyManagementListFilterSearch").click(function(){
        $("#facultyManagementListBody").empty();
        facultyManagement.facultyManagementListFilter = {};
        ($("#facultyManagementListFilterIdentificationNumber").val() !== "") ? facultyManagement.facultyManagementListFilter.identification_number = $("#facultyManagementListFilterIdentificationNumber").val() : null;
        ($("#facultyManagementListFilterLastName").val() !== "") ? facultyManagement.facultyManagementListFilter.last_name = $("#facultyManagementListFilterLastName").val() : null;
        ($("#facultyManagementListFilterFirstName").val() !== "") ? facultyManagement.facultyManagementListFilter.first_name = $("#facultyManagementListFilterFirstName").val() : null;
        ($("#facultyManagementListFilterMiddleName").val() !== "") ? facultyManagement.facultyManagementListFilter.middle_name = $("#facultyManagementListFilterMiddleName").val() : null;
        ($("#facultyManagementListFilterAccountType").val() !== "0") ? facultyManagement.facultyManagementListFilter.account_type_ID = $("#facultyManagementListFilterAccountType").val()*1 : null;
        facultyManagement.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#facultyManagementListBody").on("click", ".facultyManagementListViewButton", function(){
        facultyManagement.viewDetail($(this).parent().parent().attr("account_ID"));
    });
    //access control list
    $.post(systemApplication.url.apiUrl+"c_access_control_list/retrieveModule", {parent_ID : 0, retrieve_all : 1}, function(data){
        var response = JSON.parse(data);
        
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                var newGroup = $(".prototype").find(".accessControlListGroup").clone();
                newGroup.find(".panel-title").text(response["data"][x]["description"]);
                newGroup.find(".panel-heading").attr("href", "#accessControlListGroupContainer"+response["data"][x]["ID"]);
                newGroup.find(".accessControlListGroupContainer").attr("id", "accessControlListGroupContainer"+response["data"][x]["ID"]);
                $("#facultyManagementAccessControlListTab").append(newGroup);
                var newUnit = $(".prototype").find(".accessControlListUnit").clone();
                newUnit.find("input").val(response["data"][x]["ID"]);
                newUnit.find("input").attr("parent_id", response["data"][x]["parent_ID"]);
                newUnit.find(".accessControlListUnitLabel").css("font-weight", "bold");
                newUnit.find(".accessControlListUnitLabel").text("ALL");
                newGroup.find(".accessControlListGroupContainer").find(".row").append(newUnit);
                for(var y = 0; y < response["data"][x]["sub_module"].length; y++){
                    var newUnit = $(".prototype").find(".accessControlListUnit").clone();
                    newUnit.find("input").val(response["data"][x]["sub_module"][y]["ID"]);
                    newUnit.find("input").attr("parent_id", response["data"][x]["sub_module"][y]["parent_ID"]);
                    newUnit.find(".accessControlListUnitLabel").text(response["data"][x]["sub_module"][y]["description"]);
                    newGroup.find(".accessControlListGroupContainer").find(".row").append(newUnit);
                }
            };
        }
    });
    $("#facultyManagementAccessControlListTab").on("click", ".accessControlListUnitInput", function(){
        if($(this).attr("parent_ID")*1 === 0){
            $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput[parent_ID="+$(this).val()+"]").prop("checked", false);
        }else{
            $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput[value="+$(this).attr("parent_ID")+"]").prop("checked", false);
        }
    });
    facultyManagement.refreshOptions();
});
$("#facultyManagementAccountType").change(function(){
    if($(this).val()*1 === 6){
        $("#accessControlListGroupContainer13").collapse('show');
        $(".accessControlListUnitInput[value=23]").prop("checked", true);
        $(".accessControlListUnitInput[value=26]").prop("checked", true);
        $(".accessControlListUnitInput[value=33]").prop("checked", true);
        $(".accessControlListUnitInput[value=34]").prop("checked", true);
    }
});
facultyManagement.viewDetail = function(accountID){
    $("#facultyManagementForm").trigger("reset");
    $.post("<?=  api_url()?>c_account/retrieveAccountBasicInformation",{account_ID : accountID},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("#facultyManagementForm").trigger("reset");
            facultyManagement.viewAccessControlList(accountID);
            $("#facultyManagementAccountType").val(response["data"]["account_type_ID"]);
            $("#facultyManagementAccountID").val(response["data"]["account_ID"]);
            $("#facultyManagementIdentificationNumber").val(response["data"]["identification_number"]);
            $("#facultyManagementLastName").val(response["data"]["last_name"]);
            $("#facultyManagementFirstName").val(response["data"]["first_name"]);
            $("#facultyManagementMiddleName").val(response["data"]["middle_name"]);
            $("#facultyManagementGender").val(response["data"]["gender"]);
            $("#facultyManagementBirthDatetime").val(response["data"]["birth_datetime"]);
            var birthDate = new Date(response["data"]["birth_datetime"]*1000);
            $("#facultyManagementBirthDate").val( birthDate.getFullYear() +'-'+ ("0"+(birthDate.getMonth()+1)).slice(-2) +'-'+ ("0"+(birthDate.getDate())).slice(-2));
            $("#facultyManagementBirthPlace").val(response["data"]["birth_place"]);
            $("#facultyManagementReligion").val(response["data"]["religion_maintainable_ID"]);
            $("#facultyManagementNationality").val(response["data"]["nationality_maintainable_ID"]);
            facultyManagement.changeFormAction(1);
            $("#facultyManagementForm").attr("action", "<?=api_url()?>c_account/updateAccount");
            $("#facultyManagementDiv").slideDown();
        }
    });
};
facultyManagement.retrieveAccessControlList = function(accountID){
    var newACL = [];
    $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput:checked").each(function(){
        newACL.push({
            account_ID : accountID,
            module_ID : $(this).val()
        });
    });
    return newACL;
};
facultyManagement.viewAccessControlList = function(accountID){
    $.post(systemApplication.url.apiUrl+"c_access_control_list/retrieveAccessControlList", {account_ID: accountID}, function(data){
        var response = JSON.parse(data);
        $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput").prop("checked", false);
        $("#facultyManagementAccessControlListTab").find(".accessControlListGroupContainer").removeClass("in");
        if(!response["error"].length){            
            for(var x = 0; x < response["data"].length; x++){
                $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput[value="+response["data"][x]["module_ID"]+"]").prop("checked", true);
                $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput[value="+response["data"][x]["module_ID"]+"]").parent().parent().parent().addClass("in");
            }
        }
    });
};
facultyManagement.removeList = function(row){
    $.post("<?=  api_url()?>c_account/deleteAccount",{ID : row.attr("account_ID")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            facultyManagement.retrieveList();
        }
        console.log(response);
    });
};
facultyManagement.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#facultyManagementCloseFormButton").show();
            $("#facultyManagementSubmitFormButton").show();
            $("#facultyManagementCreateFormButton").hide();
            $("#facultyManagementAccessControlListTab").find(".accessControlListUnitInput").prop("checked", false);
            break;
        case 2://Close Button
            $("#facultyManagementDiv").slideUp();
            $("#facultyManagementCloseFormButton").hide();
            $("#facultyManagementSubmitFormButton").hide();
            $("#facultyManagementCreateFormButton").show();
            break;
    }
};
facultyManagement.retrieveList = function(filter){
    $("#facultyManagementListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in facultyManagement.facultyManagementListFilter){
        filter[x] = facultyManagement.facultyManagementListFilter[x];
    }
    (filter.account_type_ID) ? null : filter.in_account_type_ID = [3,5,6];
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#facultyManagementListCurrentPage").text()*1 - 1) > 0) ? ($("#facultyManagementListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".facultyManagementListSorter[sorted=1]").length){
        filter.sort= {};
        if($(".facultyManagementListSorter[sorted=1]").attr("sorter_name") === "full_name"){
            filter.sort.last_name = $(".facultyManagementListSorter[sorted=1]").attr("sort")*1;
            filter.sort.first_name = $(".facultyManagementListSorter[sorted=1]").attr("sort")*1;
            filter.sort.middle_name = $(".facultyManagementListSorter[sorted=1]").attr("sort")*1;
        }else if($(".facultyManagementListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".facultyManagementListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".facultyManagementListSorter[sorted=1]").attr("sorter_name")] = $(".facultyManagementListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_account/retrieveAccountBasicInformation", filter, function(data){
        var response = JSON.parse(data);
        
        $("#facultyManagementListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#facultyManagementListBody").empty();
            $("#facultyManagementListTotalResult").text(response["result_count"]);
            $("#facultyManagementListTotalPage").text(totalPages);
            
            if($("#facultyManagementListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".facultyManagementListRow").clone();
                   
                    newRow.attr("account_ID", response["data"][x]["account_ID"]);
                     newRow.find(".facultyManagementListPosition").text((response["data"][x]["description"]+"").toUpperCase());
                    newRow.find(".facultyManagementListIdentificationNumber").text(response["data"][x]["identification_number"]);
                    newRow.find(".facultyManagementListFullName").text((
                            response["data"][x]["last_name"] + ", " +
                            response["data"][x]["first_name"] + " " +
                            response["data"][x]["middle_name"]
                            ).toUpperCase());
                    newRow.find(".confirmButton").hide();
                    
                    
                    $("#facultyManagementListBody").append(newRow);
                }
            }
        }
        $("#facultyManagementListFilterSearch").button("reset");
        $("#facultyManagementListCurrentPage").text( ($("#facultyManagementListCurrentPage").text()*1 > $("#facultyManagementListTotalPage").text()*1) ?  $("#facultyManagementListTotalPage").text()*1 : $("#facultyManagementListCurrentPage").text()*1);
    });
};
facultyManagement.refreshOptions = function(){
    //Nationality
    $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 2},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("select[name='nationality_maintainable_ID']").empty();
            $("select[name='nationality_maintainable_ID']").append("<option>N/A</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#facultyManagementDiv").find("select[name='nationality_maintainable_ID']").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
        }
    });
    //religion
    $.post("<?=  api_url()?>c_maintainable/retrieveMaintainable",{maintainable_type_ID : 1},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("select[name='religion_maintainable_ID']").empty();
            $("select[name='religion_maintainable_ID']").append("<option>N/A</option>");
            for(var x = 0; x < response["data"].length; x++){
                $("#facultyManagementDiv").find("select[name='religion_maintainable_ID']").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
            }
        }
    });
};
</script>