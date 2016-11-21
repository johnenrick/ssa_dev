<!--
<link href="<?=load_asset()?>css/bootstrap-tagsinput.css" rel="stylesheet">
<script type="text/javascript" src="<?=load_asset()?>js/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="<?=load_asset()?>js/bootstrap-tagsinput.min.map"></script>
<script type="text/javascript" src="<?=load_asset()?>js/bootstrap3-typeahead.min.js"></script>
-->

<script>
var cataloging = {};
cataloging.sectionList = [];
cataloging.assessentTypeListFilter = {};
$(document).ready(function(){

/*
    $('input#catalogingSubject').tagsinput({
      typeahead: {
        source: ['Amsterdam', 'Washington', 'Sydney', 'Beijing', 'Cairo']
      }
    });
    $('input').tagsinput({
      typeahead: {
        source: function(query) {
          return $.get('http://someservice.com');
        }
      }
    });
*/

    //cataloging.retrieveList();
    //form action buttons
    $("#catalogingCreateFormButton").click(function(){
        cataloging.changeFormAction(1);
        $("#catalogingForm").trigger("reset");
        $("#catalogingForm").attr("action", "<?=api_url()?>c_cataloging/createMaterial");
        $("#catalogingDiv").slideDown();
    });
    $("#catalogingSubmitFormButton").click(function(){
        $("#catalogingForm").submit();
    });
    $("#catalogingCloseFormButton").click(function(){
        cataloging.changeFormAction(2);
    });
    $("#catalogingForm").ajaxForm({
        success : function(data){
            var response = JSON.parse(data);
            if(!response["error"].length){
                cataloging.retrieveList();
                $("#catalogingForm").trigger("reset");
                $("#catalogingDiv").slideUp();
                cataloging.changeFormAction(2);
            }else{
                $("#catalogingMessage").html(response["error"][0]["message"]);
                $("#catalogingMessage").show();
                setTimeout(function(){
                    $("#catalogingMessage").html("");
                    $("#catalogingMessage").hide();
                },3000 * response["error"].length);
            }
        }
    });
    //section list page nav
    $("#catalogingListPreviousPage").click(function(){
        if($("#catalogingListCurrentPage").text()*1 -1){
            $("#catalogingListCurrentPage").text($("#catalogingListCurrentPage").text()*1 -1);
            cataloging.retrieveList();
        }
     });
    $("#catalogingListNextPage").click(function(){
        $("#catalogingListCurrentPage").text($("#catalogingListCurrentPage").text()*1 + 1);
        cataloging.retrieveList();
    });
     //class section list action
    $("#catalogingListBody").on("click", ".catalogingListRemoveButton", function(){
       $(this).parent().find(".confirmButton").show();
       $(this).parent().find(".actionButton").hide();
    });
    $("#catalogingListBody").on("click", ".catalogingListNoRemoveButton", function(){
       $(this).parent().find(".confirmButton").hide();
       $(this).parent().find(".actionButton").show();
       $(this).parent().find(".confirmChangeButton").hide();
    });
    $("#catalogingListBody").on("click", ".catalogingListYesRemoveButton", function(){
       cataloging.removeList($(this).parent().parent());
    });

    $("#catalogingListBody").on("click", ".catalogingListYesChangeButton", function(){
       cataloging.saveList($(this).parent().parent());
    });
    //list filter and sorting
    $(".catalogingListSorter").click(function(){
        $(".catalogingListSorter").attr("sorted",0);
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

        cataloging.retrieveList();
    });
    $("#catalogingListFilterSelect").change(function(){
        $("#catalogingListFilterInput").attr("placeholder",$(this).val());
    });

    $("#catalogingListFilterInput").keypress(function(e){
        if(e.which == 13){
            $("#catalogingListFilterSearch").click();
            return false;
        }
    });

    $("#catalogingListFilterSearch").click(function(){
        $("#catalogingListBody").empty();
        cataloging.assessentTypeListFilter = {};
        switch($("#catalogingListFilterSelect").val()){
            case "Keyword":
                    cataloging.assessentTypeListFilter.keyword = $("#catalogingListFilterInput").val();
                break;
            case "Title":
                    cataloging.assessentTypeListFilter.title = $("#catalogingListFilterInput").val();
                break;
            case "Author":
                    cataloging.assessentTypeListFilter.author = $("#catalogingListFilterInput").val();
                break;
            case "Subject":
                    cataloging.assessentTypeListFilter.subject = $("#catalogingListFilterInput").val();
                break;
            case "Call No.":
                    cataloging.assessentTypeListFilter.callNumber = $("#catalogingListFilterInput").val();
                break;
            case "ISBN":
                    cataloging.assessentTypeListFilter.isbn = $("#catalogingListFilterInput").val();
                break;
            case "Notes":
                    cataloging.assessentTypeListFilter.notes = $("#catalogingListFilterInput").val();
                break;
        }
        /*
        ($(".catalogingListFilterAccessionNumber").val() !== "") ? cataloging.assessentTypeListFilter.accessionNumber = $(".catalogingListFilterAccessionNumber").val() : null;
        ($(".catalogingListFilterCallNumber").val() !== "") ? cataloging.assessentTypeListFilter.callNumber = $(".catalogingListFilterCallNumber").val() : null;
        ($(".catalogingListFilterTitle").val() !== "") ? cataloging.assessentTypeListFilter.title = $(".catalogingListFilterTitle").val() : null;
        ($(".catalogingListFilterAuthor").val() !== "") ? cataloging.assessentTypeListFilter.author = $(".catalogingListFilterAuthor").val() : null;
        */
        cataloging.retrieveList({retrieve_type : 1});
    });
    //entry
    $("#catalogingListBody").on("click", ".catalogingListViewButton", function(){
        cataloging.viewTuitionFee($(this).parent().parent().attr("assment_type_id"));
    });
});
cataloging.viewTuitionFee = function(courseAnnualFeeID){
    $("#catalogingForm").trigger("reset");
    $.post("<?=  api_url()?>c_cataloging/retrieveMaterial",{ID : courseAnnualFeeID},function(data){
        var response = JSON.parse(data);
        var dt;
        console.log(response);
        if(!response["error"].length){
            $("#catalogingForm").trigger("reset");
            $("#catalogingID").val(response["data"]["ID"]*1);
            $("#catalogingTitle").val(response["data"]["title"]);
            $("#catalogingAuthor").val(response["data"]["author"]);
            $("#catalogingAccessionNumber").val(response["data"]["accession_number"]);
            $("#catalogingCallNumber").val(response["data"]["call_number"]);
            $("#catalogingPublisher").val(response["data"]["publisher"]);
            $("#catalogingCopyright").val(response["data"]["copyright"]);
            $("#catalogingEdition").val(response["data"]["edition"]);
            $("#catalogingPhysicalDescription").val(response["data"]["physical_description"]);
            $("#catalogingControlNumber").val(response["data"]["control_number"]);
            $("#catalogingTypeOfMaterial").val(response["data"]["type_of_material"]);
            $("#catalogingPublisherAddress").val(response["data"]["publisher_address"]);
            $("#catalogingCopyright").val(response["data"]["copyright"]);
            $("#catalogingSeriesTitle").val(response["data"]["series_title"]);
            $("#catalogingISBN").val(response["data"]["isbn"]);
            $("#catalogingNotes").val(response["data"]["notes"]);
            $("#catalogingSubject1").val(response["data"]["subject1"]);
            $("#catalogingSubject2").val(response["data"]["subject2"]);
            $("#catalogingSubject3").val(response["data"]["subject3"]);
            $("#catalogingSubject4").val(response["data"]["subject4"]);
            $("#catalogingSubject5").val(response["data"]["subject5"]);
            $("#catalogingAddedEntryTitle").val(response["data"]["added_entry_title"]);
            $("#catalogingAEAuthor1").val(response["data"]["ae_author1"]);
            $("#catalogingAEAuthor2").val(response["data"]["ae_author2"]);
            $("#catalogingAEAuthor3").val(response["data"]["ae_author3"]);
            $("#catalogingLocation").val(response["data"]["location"]);
            $('#catalogingLibraryMaterialAccessControl option[value="'+response["data"]["library_material_access_control_ID"]+'"]').prop('selected', true);
            $("#catalogingQuantity").val(response["data"]["quantity"]);
            dt = new Date((response["data"]["date_acquired"])*1000);
            $("#catalogingDateAcquired").val(dt.getFullYear()+"-"+("0"+(dt.getMonth()+1)).slice(-2)+"-"+("0"+dt.getDate()).slice(-2));
            $("#catalogingSupplier").val(response["data"]["supplier"]);
            cataloging.changeFormAction(1);
            $("#catalogingForm").attr("action", "<?=api_url()?>c_cataloging/updateMaterial");
            $("#catalogingDiv").slideDown();
        }
    });
}
cataloging.removeList = function(row){
    $.post("<?=  api_url()?>c_cataloging/deleteMaterial",{ID : row.attr("assment_type_id")},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            row.remove();
            cataloging.retrieveList();
        }
    });
}
cataloging.changeFormAction = function(status){
    switch(status*1){
        case 1://Create Button
            $("#catalogingCloseFormButton").show();
            $("#catalogingSubmitFormButton").show();
            $("#catalogingCreateFormButton").hide();
            break;
        case 2://Close Button
            $("#catalogingDiv").slideUp();
            $("#catalogingCloseFormButton").hide();
            $("#catalogingSubmitFormButton").hide();
            $("#catalogingCreateFormButton").show();
            break;
    }
};
cataloging.retrieveList = function(filter){
    $("#catalogingListFilterSearch").button("loading");
    filter = (typeof filter === "undefined") ? {} : filter;
    for(x in cataloging.assessentTypeListFilter){
        filter[x] = cataloging.assessentTypeListFilter[x];
    }
    console.log(filter);
    filter.limit = 20;
    filter.retrieve_type = 1;
    filter.offset = ((($("#catalogingListCurrentPage").text()*1 - 1) > 0) ? ($("#catalogingListCurrentPage").text()*1 - 1) : 0) * 20;
    if($(".catalogingListSorter[sorted=1]").length){
        filter.sort= {};
        if($(this).attr("sorter_name") === "course_year_level"){
            filter.sort.year_level = $(".catalogingListSorter[sorted=1]").attr("sort")*1;
            filter.sort.course_ID = $(".catalogingListSorter[sorted=1]").attr("sort")*1;
        }else if($(".catalogingListSorter[sorted=1]").attr("sorter_name") === "description"){
            filter.sort["description"] = $(".catalogingListSorter[sorted=1]").attr("sort")*1;
        }else{
            filter.sort[$(".catalogingListSorter[sorted=1]").attr("sorter_name")] = $(".catalogingListSorter[sorted=1]").attr("sort")*1;
        }
    }
    $.post("<?=api_url()?>c_cataloging/retrieveMaterial", filter, function(data){
        var response = JSON.parse(data);
        console.log(response);

        $("#catalogingListTotalResult").text("0");
        if(!response["error"].length){
            var totalPages = Math.ceil(response["result_count"]/20);
            $("#catalogingListBody").empty();
            $("#catalogingListTotalResult").text(response["result_count"]);
            $("#catalogingListTotalPage").text(totalPages);

            if($("#catalogingListCurrentPage").text()*1 <= totalPages){
                for(var x = 0; x < response["data"].length; x++){
                    var newRow = $(".prototype").find(".catalogingListRow").clone();
                    newRow.attr("assment_type_id", response["data"][x]["ID"]);
                    newRow.find(".catalogingListTitle").text(response["data"][x]["title"]);
                    newRow.find(".catalogingListAuthor").text(response["data"][x]["author"]);
                    //newRow.find(".catalogingListAccessionNumber").text(response["data"][x]["accession_number"]);
                    newRow.find(".catalogingListCallNumber").text(response["data"][x]["call_number"]);
                    newRow.find(".catalogingListYear").text(response["data"][x]["copyright"]);
                    newRow.find(".catalogingListStatus").html((parseInt(response["data"][x]["loaned"]))?"<span style='color:blue'>Borrowed</span>":"<span style='color:green'>Available</span>");
                    newRow.find(".confirmButton").hide();


                    $("#catalogingListBody").append(newRow);
                }
            }
        }
        $("#catalogingListFilterSearch").button("reset");
        $("#catalogingListCurrentPage").text( ($("#catalogingListCurrentPage").text()*1 > $("#catalogingListTotalPage").text()*1) ?  $("#catalogingListTotalPage").text()*1 : $("#catalogingListCurrentPage").text()*1);
    });

    //Material Access Control
    $.post("<?=  api_url()?>c_material_access_control/retrieveMaterialAccessControl",{},function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            $("select[name='materialAccessControl']").empty();
            $("select[name='materialAccessControl']").append("<option disabled>none</option>");

            for(var x = 0; x < response["data"].length; x++){
                $("select[name='materialAccessControl']").append("<option value='"+response["data"][x]["ID"]+"' >"+response["data"][x]["description"]+"</option>")
            }
        }
    });
};
</script>
