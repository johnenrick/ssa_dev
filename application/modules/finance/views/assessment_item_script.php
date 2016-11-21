<script>
var assessmentItem = {};
assessmentItem.sectionList = [];
assessmentItem.assessmentItemListFilter = {};
$(document).ready(function(){
    assessmentItem.retrieveList();
    assessmentItem.refreshOptions();
    //form action buttons
    $("#assessmentItemCreateFormButton").click(function(){
        assessmentItem.changeFormAction(1);
        $("#assessmentItemForm").trigger("reset");
        $("#assessmentItemForm").attr("action", "<?=api_url()?>c_assessment_item/createAssessmentItem");
        $("#assessmentItemDiv").slideDown();
    });
    $("#assessmentItemSubmitFormButton").click(function(){ 
        $("#assessmentItemForm").submit();
    });
    $("#assessmentItemCloseFormButton").click(function(){
        assessmentItem.changeFormAction(2);
    });
    $("#assessmentItemForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                assessmentItem.retrieveList();
                $("#assessmentItemForm").trigger("reset");
                $("#assessmentItemDiv").slideUp();
                assessmentItem.changeFormAction(2);
            }else{
                $("#assessmentItemMessage").html(response["error"][0]["message"]);
                $("#assessmentItemMessage").show();
                setTimeout(function(){
                    $("#assessmentItemMessage").html("");
                    $("#assessmentItemMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#assessmentItemListPreviousPage").click(function(){
        if($("#assessmentItemListCurrentPage").text()*1 -1){
            $("#assessmentItemListCurrentPage").text($("#assessmentItemListCurrentPage").text()*1 -1);
            assessmentItem.retrieveList();
        } 
     });
    $("#assessmentItemListNextPage").click(function(){
        $("#assessmentItemListCurrentPage").text($("#assessmentItemListCurrentPage").text()*1 + 1);
        assessmentItem.retrieveList();
    });
     //class section list action
    $("#assessmentItemListBody").on("click", ".assessmentItemListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#assessmentItemListBody").on("click", ".assessmentItemListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#assessmentItemListBody").on("click", ".assessmentItemListYesRemoveButton", function(){
       assessmentItem.removeList($(this).parent().parent());
    });

    $("#assessmentItemListBody").on("click", ".assessmentItemListYesChangeButton", function(){
       assessmentItem.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".assessmentItemListSorter").click(function(){ 
        
        $(".assessmentItemListSorter").attr("sorted",0);
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
        
        assessmentItem.retrieveList();
    });
    $("#assessmentItemListFilterSearch").click(function(){
        $("#assessmentItemListBody").empty();
        assessmentItem.assessmentItemListFilter = {};
        ($("#assessmentItemListFilterAssessmentType").val() !== "") ? assessmentItem.assessmentItemListFilter.assessment_type_ID = $("#assessmentItemListFilterAssessmentType").val() : null;
        ($("#assessmentItemListFilterGeneralLedger").val() !== "") ? assessmentItem.assessmentItemListFilter.general_ledger_ID = $("#assessmentItemListFilterGeneralLedger").val() : null;
        ($("#assessmentItemListFilterDescription").val() !== "") ? assessmentItem.assessmentItemListFilter.description = $("#assessmentItemListFilterDescription").val() : null;
        assessmentItem.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#assessmentItemListBody").on("click", ".assessmentItemListViewButton", function(){
        assessmentItem.viewTuitionFee($(this).parent().parent().attr("assessment_item_id"));
    });
});
assessmentItem.viewTuitionFee = function(courseAnnualFeeID){
    $("#assessmentItemForm").trigger("reset");
    $.post("<?=  api_url()?>c_assessment_item/retrieveAssessmentItem",{ID : courseAnnualFeeID},function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            $("#assessmentItemForm").trigger("reset");
            $("#assessmentItemID").val(response["data"]["ID"]*1);
            $("#assessmentItemType").val(response["data"]["assessment_type_ID"]);
            $("#assessmentItemDescription").val(response["data"]["description"]);
            $("#assessmentItemTellering").val(response["data"]["tellering"]);
            $("#assessmentItemGeneralLedger").val(response["data"]["general_ledger_ID"]);
            assessmentItem.changeFormAction(1);
            $("#assessmentItemForm").attr("action", "<?=api_url()?>c_assessment_item/updateAssessmentItem");
            $("#assessmentItemDiv").slideDown();
        }
    });
}
assessmentItem.removeList = function(row){
    $.post("<?=  api_url()?>c_assessment_item/deleteAssessmentItem",{ID : row.attr("assessment_item_id")},function(data){
        var response = JSON.parse(data);
        console.log(response);
        if(!response["error"].length){
            row.remove();
            assessmentItem.retrieveList();
        }
    });
}
assessmentItem.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#assessmentItemCloseFormButton").show();
            $("#assessmentItemSubmitFormButton").show();
            $("#assessmentItemCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#assessmentItemDiv").slideUp();
            $("#assessmentItemCloseFormButton").hide();
            $("#assessmentItemSubmitFormButton").hide();
            $("#assessmentItemCreateFormButton").show();
            break;
    }
};
assessmentItem.refreshOptions = function(filter){
  $.post("<?=api_url()?>c_assessment_type/retrieveAssessmentType", filter, function(data){
      var response = JSON.parse(data);
      if(!response["error"].length){
          var currentValue = $("#assessmentItemType").val();
          var currentValue2 = $("#assessmentItemListFilterAssessmentType").val();
          $("#assessmentItemType").empty();
          $("#assessmentItemListFilterAssessmentType").append("<option value='' >All</option>");
          for(var x = 0; x <response["data"].length; x++){
              $("#assessmentItemType").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
              $("#assessmentItemListFilterAssessmentType").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
          }
          (currentValue*1) ? $("#assessmentItemType").val(currentValue) : null;
          (currentValue2*1) ? $("#assessmentItemListFilterAssessmentType").val(currentValue2) : null;
      }
  }); 
  $.post("<?=api_url()?>c_general_ledger/retrieveGeneralLedger", filter, function(data){
      var response = JSON.parse(data);
      if(!response["error"].length){
          var currentValue = $("#assessmentItemGeneralLedger").val();
          var currentValue2 = $("#assessmentItemListFilterGeneralLedger").val();
          $("#assessmentItemGeneralLedger").empty();
          $("#assessmentItemListFilterGeneralLedger").append("<option value='' >All</option>");
          for(var x = 0; x <response["data"].length; x++){
              $("#assessmentItemGeneralLedger").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
              $("#assessmentItemListFilterGeneralLedger").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>");
          }
          (currentValue*1) ? $("#assessmentItemGeneralLedger").val(currentValue) : null;
          (currentValue2*1) ? $("#assessmentItemListFilterGeneralLedger").val(currentValue2) : null;
      }
  }); 
};
assessmentItem.retrieveList = function(filter){
    $("#assessmentItemListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(var x in assessmentItem.assessmentItemListFilter){
        filter[x] = assessmentItem.assessmentItemListFilter[x];
    }
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#assessmentItemListCurrentPage").text()*1 - 1) > 0) ? ($("#assessmentItemListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".assessmentItemListSorter[sorted=1]").length){
        filter.sort = {};
        if($(".assessmentItemListSorter[sorted=1]").attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".assessmentItemListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".assessmentItemListSorter[sorted=1]").attr("sort")*1;
        }else if($(".assessmentItemListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".assessmentItemListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".assessmentItemListSorter[sorted=1]").attr("sorter_name")] = $(".assessmentItemListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_assessment_item/retrieveAssessmentItem", filter, function(data){
        var response = JSON.parse(data);
        $("#assessmentItemListTotalResult").text("0");
        $("#assessmentItemListTotalPage").text(1);
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#assessmentItemListBody").empty();
            $("#assessmentItemListTotalResult").text(response["result_count"]);
            $("#assessmentItemListTotalPage").text(totalPages);
            
            if($("#assessmentItemListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".assessmentItemListRow").clone();
                    newRow.attr("assessment_item_id", response["data"][x]["ID"]);
                    newRow.find(".assessmentItemListDescription").text(response["data"][x]["description"]);
                    newRow.find(".assessmentItemListAssessmentType").text(response["data"][x]["assessment_type_description"]);
                    newRow.find(".assessmentItemListGeneralLedger").text(response["data"][x]["general_ledger_description"]);
                    newRow.find(".confirmButton").hide();
                    
                    
                    $("#assessmentItemListBody").append(newRow);
                }
            }
        }
        $("#assessmentItemListFilterSearch").button("reset");
        $("#assessmentItemListCurrentPage").text( ($("#assessmentItemListCurrentPage").text()*1 > $("#assessmentItemListTotalPage").text()*1) ?  $("#assessmentItemListTotalPage").text()*1 : $("#assessmentItemListCurrentPage").text()*1);
    });
};
</script>