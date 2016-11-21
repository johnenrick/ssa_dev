<script>
    /* global systemApplication, systemUtility */
var paymentList = {};
paymentList.paymentListList = false;
paymentList.noAccountList = false;
paymentList.sectionList = [];
paymentList.isSearchingPayee = false;
$(document).ready(function(){
    paymentList.paymentListList = $("#paymentListListTable").apipagination({
        apiUrl : systemApplication.url.apiUrl+"c_account_payment/retrieveAccountPayment",
        customFilterGenerator : function(){
            return {
                type : 1
            };
        },
        tableSorter : {
            1 : "payment__order_receipt_number"
        },
        tableFilter : {
            order_receipt_number : "OR",
            order_receipt_number_range : "OR range",
            first_name : "First Name",
            middle_name : "Middle Name",
            last_name : "Last Name"
           
        },
        responseCallback : paymentList.showPaymentListList
    });
    $("#paymentListAccountTable").on("click", ".paymentListAccountListYesSelectButton", function(){
        $(this).parent().parent().find(".paymentListListConfirmSelect").hide();
        $(this).parent().parent().find(".paymentListAccountListSelectButton").show();
        var newData = {
            account_ID : $(this).parent().parent().parent().attr("account_ID"),
            payment_ID : $("#paymentListListTable").find("tr[payment_no_account_ID="+$("#paymentListAccountTable").attr("payment_no_account_ID")+"]").find(".paymentListListOrderReceiptNumber").attr("payment_ID"),
            first_name : $("#paymentListListTable").find("tr[payment_no_account_ID="+$("#paymentListAccountTable").attr("payment_no_account_ID")+"]").find(".paymentListListFirstName").text(),
            last_name : $("#paymentListListTable").find("tr[payment_no_account_ID="+$("#paymentListAccountTable").attr("payment_no_account_ID")+"]").find(".paymentListListLastName").text(),
            middle_name : $("#paymentListListTable").find("tr[payment_no_account_ID="+$("#paymentListAccountTable").attr("payment_no_account_ID")+"]").find(".paymentListListMiddleName").text()
        };
        $.post(systemApplication.url.apiUrl+"c_account_payment/noAccountToAccount",newData, function(data){
          
           var response = JSON.parse(data);
           if(!response["error"].length){
               paymentList.paymentListList.showPage();
               $("#paymentListAccount").modal("toggle");
               paymentList.noAccountList.showPage();
           }
        });
    });
    //searching events
    $("#paymentListListTable").on("keydown", ".paymentListFullName", function(e){
        var suggestionContainer = $(this).parent().find(".paymentListPayeeSuggestion");
        var searchInput = $(this);
        switch(e.keyCode){
            case 13 ://enter
                if(suggestionContainer.attr("account_id")*1 !== 0){
                    var newData = {
                        payee_account_ID : suggestionContainer.attr("account_id"),
                        payer_account_ID : suggestionContainer.attr("account_id"),
                        ID : $(this).attr("transaction_id")
                    };
                    $.post(systemApplication.url.apiUrl+"c_account_payment/updateAccountPayment",newData, function(data){
                        var response = JSON.parse(data);
                        if(!response["error"].length){
                            searchInput.val(suggestionContainer.find(".paymentListPayeeSuggestionOption[account_id='"+suggestionContainer.attr("account_id")+"']").text());
                            suggestionContainer.empty();
                            suggestionContainer.attr("account_id",0);
                        }
                    });
                }else{
                    
                }
                break;
            case 40 ://keydown
                //$(this).trigger("blur");
                
                suggestionContainer.children().css("background-color","");
                if(suggestionContainer.children().length){
                    if(suggestionContainer.attr("account_id")*1){
                        suggestionContainer.find(".paymentListPayeeSuggestionOption[account_id='"+suggestionContainer.attr("account_id")+"']").index();
                        
                        if(suggestionContainer.find(".paymentListPayeeSuggestionOption[account_id='"+suggestionContainer.attr("account_id")+"']").next().index()!== -1){
                            suggestionContainer.attr("account_id", suggestionContainer.find(".paymentListPayeeSuggestionOption[account_id='"+suggestionContainer.attr("account_id")+"']").next().attr("account_id"));
                        }else{
                            suggestionContainer.attr("account_id", suggestionContainer.children().eq(0).attr("account_id"));
                        }
                        suggestionContainer.find(".paymentListPayeeSuggestionOption[account_id='"+suggestionContainer.attr("account_id")+"']").css("background-color","skyblue");
                        
                    }else{
                        suggestionContainer.attr("account_id", suggestionContainer.children().eq(0).attr("account_id"));
                        suggestionContainer.children().eq(0).css("background-color","skyblue");
                    }
                }else{
                    searchInput.trigger("blur");
                    searchInput.parent().parent().next().find(".paymentListFullName").focus();
                }
                //$(this).trigger("focus");
                break;
            case 38 ://keyup
                $(this).trigger("blur");
                $(this).trigger("focus");
                break;
        }
    });
    $("#paymentListListTable").on("keyup", ".paymentListFullName", function(e){
        switch(e.keyCode){
            case 13 ://enter
                break;
            case 40 ://keydown
                break;
            case 38 ://keydown
                break;
            default ://others
                paymentList.searchPayee($(this));
                break;
        }
    });
    $("#paymentListListTable").on("focus", ".paymentListFullName", function(e){
        var suggestionContainer = $(this).parent().find(".paymentListPayeeSuggestion");
        suggestionContainer.empty();
        suggestionContainer.show();
        suggestionContainer.attr("account_id",0);
    })
    $("#paymentListListTable").on("blur", ".paymentListFullName", function(e){
        var suggestionContainer = $(this).parent().find(".paymentListPayeeSuggestion");
        suggestionContainer.empty();
        suggestionContainer.attr("account_id",0);
        suggestionContainer.hide();
    })
    
});
paymentList.showPaymentListList = function(response){
    if(!response["error"].length){
        for(var x = 0; x < response["data"].length; x++){
            var newRow = $(".prototype").find(".paymentListListRow").clone();
            newRow.find(".paymentListOrderReceiptNumber").text(response["data"][x]["order_receipt_number"]);
            newRow.find(".paymentListFullName").val(response["data"][x]["payee_last_name"]+", "+response["data"][x]["payee_first_name"]+" "+response["data"][x]["payee_middle_name"]);
            newRow.find(".paymentListFullName").attr("account_id", response["data"][x]["payee_account_ID"]);
            newRow.find(".paymentListFullName").attr("transaction_id", response["data"][x]["ID"]);
            newRow.find(".paymentListTotalAmount").text(response["data"][x]["total_amount"]);
            newRow.find(".paymentListRemarks").text(response["data"][x]["remarks"]);
            
            $("#paymentListListTable").find("tbody").append(newRow);
        }
    }
};
paymentList.searchPayee = function(searchInput){
    if(paymentList.isSearchingPayee){
        paymentList.isSearchingPayee.abort();
        paymentList.isSearchingPayee = false;
    }
    var suggestionContainer = searchInput.parent().find(".paymentListPayeeSuggestion");
    suggestionContainer.attr("account_id",0);
    suggestionContainer.empty();
    searchInput.attr("account_id", 0);
    var filterData = {
        retrieve_type : 1,
        full_name : searchInput.val(),
        limit : 3
    };
    paymentList.isSearchingPayee = $.post(systemApplication.url.apiUrl+"c_account/retrieveAccountBasicInformation", filterData, function(data){
        var response = JSON.parse(data);
        if(!response["error"].length){
            for(var x = 0; x < response["data"].length; x++){
                suggestionContainer.append("<div account_id='"+response["data"][x]["account_ID"]+"' class='paymentListPayeeSuggestionOption'>"+response["data"][x]["last_name"]+", "+response["data"][x]["first_name"]+" "+response["data"][x]["middle_name"]+"</div>")
            }
        }
    });
};
</script>