<script>
    /* global systemApplication, systemUtility */
var noAccountPayee = {};
noAccountPayee.noAccountPayeeList = false;
noAccountPayee.noAccountList = false;
noAccountPayee.sectionList = [];
$(document).ready(function(){
    noAccountPayee.noAccountPayeeList = $("#noAccountPayeeListTable").apipagination({
        apiUrl : systemApplication.url.apiUrl+"c_account_payment/retrievePaymentNoAccount",
        customFilterGenerator : function(){
            return {
                type : 1
            };
        },
        tableSorter : {
            1 : "payment__order_receipt_number",
            2 : "payment_no_account__last_name",
            3 : "payment_no_account__first_name",
            4 : "payment_no_account__middle_name",
        },
        tableFilter : {
            order_receipt_number : "OR",
            first_name : "First Name",
            middle_name : "Middle Name",
            last_name : "Last Name"
        },
        responseCallback : noAccountPayee.showNoAccountPayeeList
    });
    noAccountPayee.noAccountPayeeList.showPage();
    
    $("#noAccountPayeeListTable").on("click", ".noAccountPayeeListRow", function(){
        $("#noAccountPayeeAccount").modal("toggle");
        $("#noAccountPayeeAccountTable").attr("payment_no_account_ID", $(this).attr("payment_no_account_ID"));
    });
    noAccountPayee.noAccountList = $("#noAccountPayeeAccountTable").apipagination({
        apiUrl : systemApplication.url.apiUrl+"c_account/retrieveAccountBasicInformation",
        customFilterGenerator : function(){
            return {
                retrieve_type : 1
            };
        },
        tableSorter : {
            1 : "account_basic_information__identification_number",
            2 : "account_basic_information__last_name",
            3 : "account_basic_information__first_name",
            4 : "account_basic_information__middle_name",
        },
        tableFilter : {
            identification_number : "ID Number",
            first_name : "First Name",
            middle_name : "Middle Name",
            last_name : "Last Name"
        },
        responseCallback : noAccountPayee.showNoAccountList
    });
    $("#noAccountPayeeAccountTable").on("click", ".noAccountPayeeAccountListSelectButton", function(){
        $(this).parent().find(".noAccountPayeeListConfirmSelect").show();
        $(this).parent().find(".noAccountPayeeAccountListSelectButton").hide();
    });
    $("#noAccountPayeeAccountTable").on("click", ".noAccountPayeeAccountListNoSelectButton", function(){
        $(this).parent().parent().find(".noAccountPayeeListConfirmSelect").hide();
        $(this).parent().parent().find(".noAccountPayeeAccountListSelectButton").show();
    });
    $("#noAccountPayeeAccountTable").on("click", ".noAccountPayeeAccountListYesSelectButton", function(){
        $(this).parent().parent().find(".noAccountPayeeListConfirmSelect").hide();
        $(this).parent().parent().find(".noAccountPayeeAccountListSelectButton").show();
        var newData = {
            account_ID : $(this).parent().parent().parent().attr("account_ID"),
            payment_ID : $("#noAccountPayeeListTable").find("tr[payment_no_account_ID="+$("#noAccountPayeeAccountTable").attr("payment_no_account_ID")+"]").find(".noAccountPayeeListOrderReceiptNumber").attr("payment_ID"),
            first_name : $("#noAccountPayeeListTable").find("tr[payment_no_account_ID="+$("#noAccountPayeeAccountTable").attr("payment_no_account_ID")+"]").find(".noAccountPayeeListFirstName").text(),
            last_name : $("#noAccountPayeeListTable").find("tr[payment_no_account_ID="+$("#noAccountPayeeAccountTable").attr("payment_no_account_ID")+"]").find(".noAccountPayeeListLastName").text(),
            middle_name : $("#noAccountPayeeListTable").find("tr[payment_no_account_ID="+$("#noAccountPayeeAccountTable").attr("payment_no_account_ID")+"]").find(".noAccountPayeeListMiddleName").text()
        };
        $.post(systemApplication.url.apiUrl+"c_account_payment/noAccountToAccount",newData, function(data){
          
           var response = JSON.parse(data);
           if(!response["error"].length){
               noAccountPayee.noAccountPayeeList.showPage();
               $("#noAccountPayeeAccount").modal("toggle");
               noAccountPayee.noAccountList.showPage();
           }
        });
    });
});
noAccountPayee.showNoAccountPayeeList = function(response){
    if(!response["error"].length){
        for(var x = 0; x < response["data"].length; x++){
            var newRow = $(".prototype").find(".noAccountPayeeListRow").clone();
            newRow.attr("payment_no_account_ID", response["data"][x]["ID"]);
            newRow.find(".noAccountPayeeListOrderReceiptNumber").text(response["data"][x]["order_receipt_number"]);
            newRow.find(".noAccountPayeeListOrderReceiptNumber").attr("payment_ID", response["data"][x]["payment_ID"]);
            newRow.find(".noAccountPayeeListFirstName").text(response["data"][x]["first_name"]);
            newRow.find(".noAccountPayeeListLastName").text(response["data"][x]["last_name"]);
            newRow.find(".noAccountPayeeListMiddleName").text(response["data"][x]["middle_name"]);
            $("#noAccountPayeeListTable").find("tbody").append(newRow);
        }
    }
};
noAccountPayee.showNoAccountList = function(response){
    if(!response["error"].length){
        for(var x = 0; x < response["data"].length; x++){
            var newRow = $(".prototype").find(".noAccountPayeeAccountListRow").clone();
            newRow.attr("account_ID", response["data"][x]["account_ID"]);
            newRow.find(".noAccountPayeeAccountIdentificationNumber").text(response["data"][x]["identification_number"]);
            newRow.find(".noAccountPayeeAccountFirstName").text(response["data"][x]["first_name"]);
            newRow.find(".noAccountPayeeAccountLastName").text(response["data"][x]["last_name"]);
            newRow.find(".noAccountPayeeAccountMiddleName").text(response["data"][x]["middle_name"]);
            $("#noAccountPayeeAccountTable").find("tbody").append(newRow);
        }
    }
};
</script>