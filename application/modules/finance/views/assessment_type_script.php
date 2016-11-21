<script>
var assessmentType = {};
assessmentType.sectionList = [];
assessmentType.assessentTypeListFilter = {};
$(document).ready(function(){
    assessmentType.retrieveList();
    //form action buttons
    $("#assessmentTypeCreateFormButton").click(function(){
        assessmentType.changeFormAction(1);
        $("#assessmentTypeForm").trigger("reset");
        $("#assessmentTypeForm").attr("action", "<?=api_url()?>c_assessment_type/createAssessmentType");
        $("#assessmentTypeDiv").slideDown();
    });
    $("#assessmentTypeSubmitFormButton").click(function(){ 
        $("#assessmentTypeForm").submit();
    });
    $("#assessmentTypeCloseFormButton").click(function(){
        assessmentType.changeFormAction(2);
    });
    $("#assessmentTypeForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                assessmentType.retrieveList();
                $("#assessmentTypeForm").trigger("reset");
                $("#assessmentTypeDiv").slideUp();
                assessmentType.changeFormAction(2);
            }else{
                $("#assessmentTypeMessage").html(response["error"][0]["message"]);
                $("#assessmentTypeMessage").show();
                setTimeout(function(){
                    $("#assessmentTypeMessage").html("");
                    $("#assessmentTypeMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#assessmentTypeListPreviousPage").click(function(){
        if($("#assessmentTypeListCurrentPage").text()*1 -1){
            $("#assessmentTypeListCurrentPage").text($("#assessmentTypeListCurrentPage").text()*1 -1);
            assessmentType.retrieveList();
        } 
     });
    $("#assessmentTypeListNextPage").click(function(){
        $("#assessmentTypeListCurrentPage").text($("#assessmentTypeListCurrentPage").text()*1 + 1);
        assessmentType.retrieveList();
    });
     //class section list action
    $("#assessmentTypeListBody").on("click", ".assessmentTypeListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#assessmentTypeListBody").on("click", ".assessmentTypeListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#assessmentTypeListBody").on("click", ".assessmentTypeListYesRemoveButton", function(){
       assessmentType.removeList($(this).parent().parent());
    });

    $("#assessmentTypeListBody").on("click", ".assessmentTypeListYesChangeButton", function(){
       assessmentType.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".assessmentTypeListSorter").click(function(){ 
        $(".assessmentTypeListSorter").attr("sorted",0);
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
        
        assessmentType.retrieveList();
    });
    $("#assessmentTypeListFilterSearch").click(function(){
        $("#assessmentTypeListBody").empty();
        assessmentType.assessentTypeListFilter = {};
        ($("#assessmentTypeListFilterDescription").val() !== "") ? assessmentType.assessentTypeListFilter.description = $("#assessmentTypeListFilterDescription").val() : null;
        ($("#assessmentTypeListFilterCode").val() !== "") ? assessmentType.assessentTypeListFilter.code = $("#assessmentTypeListFilterCode").val() : null;
        assessmentType.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#assessmentTypeListBody").on("click", ".assessmentTypeListViewButton", function(){
        assessmentType.viewTuitionFee($(this).parent().parent().attr("assment_type_id"));
    });
});
assessmentType.viewTuitionFee = function(courseAnnualFeeID){
    $("#assessmentTypeForm").trigger("reset");
    $.post("<?=  api_url()?>c_assessment_type/retrieveAssessmentType",{ID : courseAnnualFeeID},function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            $("#assessmentTypeForm").trigger("reset");
            $("#assessmentTypeID").val(response["data"]["ID"]*1);
            $("#assessmentTypeDescription").val(response["data"]["description"]);
            $("#assessmentTypeCode").val(response["data"]["code"]);
            assessmentType.changeFormAction(1);
            $("#assessmentTypeForm").attr("action", "<?=api_url()?>c_assessment_type/updateAssessmentType");
            $("#assessmentTypeDiv").slideDown();
        }
    });
}
assessmentType.removeList = function(row){
    $.post("<?=  api_url()?>c_assessment_type/deleteAssessmentType",{ID : row.attr("assment_type_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            assessmentType.retrieveList();
        }
    });
}
assessmentType.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#assessmentTypeCloseFormButton").show();
            $("#assessmentTypeSubmitFormButton").show();
            $("#assessmentTypeCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#assessmentTypeDiv").slideUp();
            $("#assessmentTypeCloseFormButton").hide();
            $("#assessmentTypeSubmitFormButton").hide();
            $("#assessmentTypeCreateFormButton").show();
            break;
    }
};
assessmentType.retrieveList = function(filter){
    $("#assessmentTypeListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in assessmentType.assessentTypeListFilter){
        filter[x] = assessmentType.assessentTypeListFilter[x];
    }
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#assessmentTypeListCurrentPage").text()*1 - 1) > 0) ? ($("#assessmentTypeListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".assessmentTypeListSorter[sorted=1]").length){
        filter.sort = {};
        if($(".assessmentTypeListSorter[sorted=1]").attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".assessmentTypeListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".assessmentTypeListSorter[sorted=1]").attr("sort")*1;
        }else if($(".assessmentTypeListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".assessmentTypeListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".generalLedgerListSorter[sorted=1]").attr("sorter_name")] = $(".assessmentTypeListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_assessment_type/retrieveAssessmentType", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);
        
        $("#assessmentTypeListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#assessmentTypeListBody").empty();
            $("#assessmentTypeListTotalResult").text(response["result_count"]);
            $("#assessmentTypeListTotalPage").text(totalPages);
            
            if($("#assessmentTypeListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".assessmentTypeListRow").clone();
                    newRow.attr("assment_type_id", response["data"][x]["ID"]);
                    newRow.find(".assessmentTypeListDescription").text(response["data"][x]["description"]);
                    newRow.find(".assessmentTypeListCode").text(response["data"][x]["code"]);
                    newRow.find(".confirmButton").hide();
                    
                    
                    $("#assessmentTypeListBody").append(newRow);
                }
            }
        }
        $("#assessmentTypeListFilterSearch").button("reset");
        $("#assessmentTypeListCurrentPage").text( ($("#assessmentTypeListCurrentPage").text()*1 > $("#assessmentTypeListTotalPage").text()*1) ?  $("#assessmentTypeListTotalPage").text()*1 : $("#assessmentTypeListCurrentPage").text()*1);
    });
};
</script>