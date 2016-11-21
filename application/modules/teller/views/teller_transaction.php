<link href="<?=load_asset()?>css/teller.css" rel="stylesheet" media="print">
<script src="<?=load_asset()?>js/print.js"></script>
<script src="<?=load_asset()?>js/autoresize.jquery.js"></script>
<script src="<?=load_asset()?>js/jquery.tablesorter.js"></script>
<h1>Teller</h1>
<div class="row">
    <div id="tellerTransactionMessage">
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-8">
        <div class="form-inline pull-right">
            <label>Date : </label>
            <input id="tellerTransactionDatetime" type="datetime" class="form-control input-sm" style="text-align: right; margin-bottom: 5px;">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-inline">
            <label>Academic Year : </label>
            <select id="tellerTransactionAcademicYear" class="form-control input-sm">
            </select>
        </div>
    </div>
    
    <div class="col-md-6 " style="padding-bottom:10px;">
        <div class="form-group">
            <button class="btn btn-xs btn-warning pull-right" id="tellerTransactionModifyButton">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Modify Transaction
            </button>
            <button class="btn btn-xs btn-danger pull-right" id="tellerTransactionVoidButton" style="margin-right:5px;">
                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Void Transaction
            </button>
        </div>
    </div>
    
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Payer</label>
            <br>
            <button class="btn btn-xs btn-primary" id="tellerTransactionSelectPayer" >
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Payer :
            </button> 
            <span class="fontInfo" >
                <span id="tellerTransactionPayerIdentificationNumber" account_id="0" style="font-weight:bold">Select Payer</span>
                <br>
                <div id="tellerTransactionPayerFullName" style="padding-left:70px"></div> 
                <div style="padding-left:70px">
                    <span id="tellerTransactionPayerFirstName" ></span> 
                    <span id="tellerTransactionPayerMiddleName"></span>
                    <span id="tellerTransactionPayerLastName"></span>
                </div> 
                <div id="tellerTransactionPayerInformation" style="padding-left:70px"></div> 
            </span>
            
        </div>
        <div class="form-group">
            <label>Payee</label>
            <br>
            <button class="btn btn-xs btn-primary" id="tellerTransactionSelectPayee" >
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Payee :
            </button>
            <span class="fontInfo">
                <span id="tellerTransactionPayeeIdentificationNumber" account_id="0" style="font-weight:bold">Select Payee</span>
                <br>
                <div id="tellerTransactionPayeeFullName" style="padding-left:70px"></div>
                <div style="padding-left:70px">
                    <span id="tellerTransactionPayeeFirstName" ></span>
                    <span id="tellerTransactionPayeeMiddleName"></span>
                    <span id="tellerTransactionPayeeLastName"></span>
                </div>
                <div id="tellerTransactionPayeeInformation" style="padding-left:70px"></div>
            </span>
        </div>
        <div class="form-group">
            <label>Payment Mode</label>
            <br>
            <select id="tellerTransactionPaymentMode" class=" form-control " type="text" placeholder="payment mode" style="width:200px;margin-left:70px">
                <option value="1">CASH</option>
                <option value="2">CHECK</option>
                <option value="3">CREDIT</option>
                <option value="4">DEBIT</option>
            </select>
        </div>
        <div class="form-group">
            <label>Amount Tendered</label>
            <br>
            <input id="tellerTransactionAmountTendered" class=" form-control " type="text" placeholder="Amount Tendered" name="" style="width:200px;margin-left:70px;text-align:right">
        </div>
       
    </div>
    <div class="col-md-6 ">
        
        <div class="form-group">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 ">
                    <label>Order Receipt</label>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <input id="tellerTransactionOrderReceiptNumber" current_value="0" payment_id="0" class=" form-control " type="text" placeholder="Order Receipt Number" name="" style="text-align:right;color:red">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Cashier</label>
            <br>
            <span class="fontInfo"> <span id="tellerTransactionCashierFullName" style="padding-left:20px"></span> </span>
            <input type="hidden" id="tellerTransactionCashierID" >
        </div>
        <div class="form-group">
            <label>Remarks</label>
            <br>
            <textarea class="form-control" style="resize:vertical" id="tellerTransactionRemarks"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-xs btn-primary" id="tellerTransactionAccountStatementButton" data-loading-text="Please wat...">
                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Statement of Account
            </button>
            <button class="btn btn-xs btn-primary" id="tellerTransactionLedgerButton" data-loading-text="Please wat...">
                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Ledger
            </button>
            <button class="btn btn-xs btn-primary" id="tellerTransactionPaymentSummaryButton" data-loading-text="Please wat...">
                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Payment Summary
            </button>
        </div>
        <div class="form-group" >
            <label>Adjustments/Privileges:</label>
            <button class="btn btn-xs btn-default" id="tellerTransactionPaymentAdjustmentButton" data-loading-text="Please wat...">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Change
            </button>
            <div style="padding-left:10px;font-style: oblique;font-weight:bold" id="tellerTransactionAdjustments">
                
            </div>
        </div>
    </div>
    

</div>
<!--###### Particular Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Particulars</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="text-align:center">Assessment Item</th>
                    <th style="text-align:center;display:none">Remaining Amount</th>
                    <th style="text-align:center">Remarks</th>
                    <th style="text-align:center">Amount</th>
                    <th style="text-align:center" >Action</th>
                </tr>
            </thead>
            <tbody id="tellerTransactionParticularListBody"></tbody>
            <tfoot>
                
                <tr>
                    <td class="small" style="text-align:right; font-weight:bold">
                        <span class="pull-left">
                            <span id="tellerTransactionParticularListTotalResult">0</span> Item(s)
                        </span>
                        Total:
                    </td>
                    
                    <td id="tellerTransactionParticularListTotalRemainingAmount" colspan="1" style="text-align:right;display:none" >
                       0.00
                    </td>
                    <td style="text-align:right" id="tellerTransactionParticularListTotalAmount">
                       0.00
                    </td>
                    <td style="text-align:right">
                       <button class="btn btn-xs btn-primary  actionButton " id="tellerTransactionParticularListAddButton" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Particular</button>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                    </td>
                    <td class="small" style="text-align:right; font-weight:bold">
                        Change :
                    </td>
                    
                    <td id="tellerTransactionParticularListTotalRemainingAmount" colspan="1" style="text-align:right;display:none" >
                       0.00
                    </td>
                    <td style="text-align:right;float:right" id="tellerTransactionChange">
                        0.00
                    </td>
                    <td style="text-align:right">
                       
                    </td>
                </tr>
            </tfoot>
        </table>
        
    </div>
</div>
<div class="row" >
    <div class="col-md-12" style="text-align:center">
        <button class="btn  btn-success" data-loading-text="Processing Transaction..." id="tellerTransactionFinishTransaction" type="button">TRANSACT</button>
        <button class="btn  btn-warning" data-loading-text="Processing Transaction..." id="tellerTransactionModifyTransaction" type="button" style="display:none">SAVE CHANGES</button>
        <button class="btn  btn-primary" data-loading-text="" id="tellerTransactionCancelModifyTransaction" type="button" style="display:none">CANCEL</button>
    </div>
</div>
<div class="modal fade " id="tellerTransactionUserSelection" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content "  >
            <div class="modal-body">
                <div class="row" id="tellerTransactionUserSelectionOthersForm" style="display:none">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1 center">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 center">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input id="tellerTransactionUserSelectionOthersLastName" class="form-control" type="text" placeholder="Last Name" name="last_name" >
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 center">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input id="tellerTransactionUserSelectionOthersFirstName" class="form-control" type="text" placeholder="First Name" name="first_name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 center">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input id="tellerTransactionUserSelectionOthersMiddleName" class="form-control" type="text" placeholder="Middle Name" name="middle_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1 center">
                            <button class="btn  btn-primary pull-right" data-loading-text="Searching..." id="tellerTransactionUserSelectionOthersBackButton">
                                <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span> Back
                            </button>
                            <button class="btn  btn-success pull-right" data-loading-text="Searching..." id="tellerTransactionUserSelectionSelectOthers" style="margin-right:5px">
                                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Select
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" id="tellerTransactionUserSelectionSearchForm">
                    <div class="col-xs-12">
                        <form id="tellerTransactionUserSelectionSearchFilter" method="POST" action="<?=api_url()?>/c_account/retrieveAccountBasicInformation">
                            <input type="hidden" name="limit" value="20">
                            <input id="tellerTransactionUserSelectionSchoolYear" type="hidden" name="with_class_section" value="1">
                            <input type="hidden" name="retrieve_type" value="1">
                            <div class="row" >
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 ">
                                    <div class="form-group">
                                        <label>ID Number</label>
                                        <input class="form-control" type="text" placeholder="ID Number" name="identification_number" >
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 center">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input class="form-control" type="text" placeholder="Last Name" name="last_name" >
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 center">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input class="form-control" type="text" placeholder="First Name" name="first_name">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 center">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input class="form-control" type="text" placeholder="Middle Name" name="middle_name">
                                    </div>
                                </div>

                            </div>
                            <div class="row" >
                                <div class="col-md-12" style="text-align:center">
                                    <button id="tellerTransactionUserSelectionSearchFilterSearchButton" class="btn  btn-primary" data-loading-text="Searching..." type="submit" >
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                                    </button>
                                    <button id="tellerTransactionUserSelectionOthersButton" class="btn btn-warning pull-right"  type="button" >
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Others
                                    </button>
                                </div>
                            </div>
                        </form>


                        <div class="row" >
                            <div class="alert" id="tellerTransactionUserListMessage" style="text-align:center">

                            </div>
                        </div>
                        <div class="row" id="tellerTransactionUserList">
                            <div class="col-xs-12">
                                <table  class="table table-hover" style="margin-right:auto;margin-left:auto">
                                    <thead class="small">
                                        <tr>
                                            <th style="text-align:center">ID Number</th>
                                            <th style="text-align:center">Full Name</th>
                                            <th style="text-align:center" >Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tellerTransactionUserSelectionListBody">
                                        <tr><td colspan='3' style='text-align:center' >No Result</td></tr>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="row" id="tellerTransactionUserLedger" style="display:none" >
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table  class="table table-hover" style="margin-right:auto;margin-left:auto">
                                            <thead class="small">
                                                <tr>
                                                    <th style="text-align:center">Date</th>
                                                    <th style="text-align:center;width:10%" >OR</th>
                                                    <th style="text-align:center">Description</th>
                                                    <th style="text-align:center;width:15%" >Amount</th>
                                                    <th style="text-align:center" >Remarks</th>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">
                                                        <select id="tellerTransactionUserLedgerAcademicYearFilter" class="form-control">
                                                        </select>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="tellerTransactionUserLedgerOrderReceiptNumberFilter" placeholder="OR" class="form-control" type="text">
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="tellerTransactionUserLedgerDescriptionFilter" placeholder="Description" class="form-control" type="text">
                                                    </td>
                                                    <td style="text-align:center" >
                                                        <input id="tellerTransactionUserLedgerAmountFilter" placeholder="Amount" class="form-control" type="text" style="text-align:right">
                                                    </td>
                                                    <td style="text-align:center" >
                                                        <button class="btn btn-primary" id="tellerTransactionUserLedgerFilterButton" data-loading-text="Loading">
                                                            <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
                                                            Filter
                                                        </button>

                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody id="tellerTransactionUserLedgerListBody" account_id="">
                                            </tbody>
                                            <tfoot>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:right">
                                                    <label>Total</label>
                                                </td>
                                                <td id="tellerTransactionUserLedgerTotalAmount" style="text-align:right">
                                                    123.00
                                                </td>
                                                <td></td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button class="btn btn-primary pull-right" id="tellerTransactionUserLedgerBack"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tellerTransactionVoid" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <form id="tellerTransactionVoidForm" action="<?=api_url()?>c_account_payment/voidTransaction" method="POST">
                    <div class="form-group">
                        <div id="tellerTransactionVoidMessage" class="alert alert-warning" style="display:none">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Order Receipt</label>
                        <input id="tellerTransactionVoidOrderReceiptNumber" class=" form-control " type="text" name="order_receipt_number" placeholder="Order Receipt Number">
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea id="tellerTransactionVoidRemarks" class="form-control " type="text" name="remarks" placeholder="Remarks" ></textarea>
                    </div>
                    <div class="form-group">
                        <button data-loading-text="Retrieving..." type="submit" class="btn center btn-danger "  id="tellerTransactionVoidSubmitButton">
                            <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Void Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tellerTransactionModification" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content "  >
            <div class="modal-body" >
                    <div class="form-group">
                        <div id="tellerTransactionModificationMessage" class="alert alert-warning" style="display:none">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Order Receipt</label>
                        <input id="tellerTransactionModificationOrderReceiptNumber" class=" form-control " type="text" name="order_receipt_number" placeholder="Order Receipt Number">
                    </div>
                    <div class="form-group" style="display:none">
                        <label>Reason</label>
                        <textarea id="tellerTransactionModificationRemarks" class="form-control " type="text" name="remarks" placeholder="Reason for modifying" ></textarea>
                    </div>
                    <div class="form-group">
                        <button data-loading-text="Retrieving..." type="submit" class="btn center btn-warning "  id="tellerTransactionModificationButton">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> View
                        </button>
                    </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tellerTransactionAdjustment" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-xs-12">
                        <table  class="table table-hover" style="margin-right:auto;margin-left:auto">
                            <thead class="small">
                                <tr>
                                    <th style="text-align:center">Description</th>
                                    <th style="text-align:center" >Action</th>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionAdjustmentListBody">
                                <tr><td colspan='2' style='text-align:center' >No Result</td></tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--### Account Statement. DON'T DELETE###-->
<!--<div class="modal fade" id="tellerTransactionAccountStatement"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right" id="tellerTransactionAccountStatementPrintButton" data-loading-text="Please wat...">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                        </button>
                    </div>
                </div>
                    <br>
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionAccountStatementPrint">
                        <div style="font-family: Verdana, Arial, San-Serif;padding:10px;font-size:12px">
                            Statement of account of <span id="tellerTransactionAccountStatementFullName" style="font-weight:bold"></span> in academic year of <span style="font-weight:bold" id="tellerTransactionAccountStatementAcademicYear"></span>
                        </div>
                        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;">
                            <thead style="font-weight:bold;text-align:center">
                                <tr>
                                    <td>DESCRIPTION</td>
                                    <td>AMOUNT</td>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionAccountStatementList">
                               
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align:right;font-weight:bold">Total Amount : </td>
                                    <td id="tellerTransactionAccountStatementListTotalAmount" style="text-align:right;font-weight:bold"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<div class="modal fade" id="tellerTransactionAccountStatement"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionAccountStatementListMessage">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right" id="tellerTransactionAccountStatementPrintButton" data-loading-text="Please wat...">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                        </button>
                    </div>
                </div>
                    <br>
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionAccountStatementPrint">
                        <div style="font-family: Verdana, Arial, San-Serif;padding:10px;font-size:12px">
                            Account Statement of <span id="tellerTransactionAccountStatementFullName" style="font-weight:bold"></span> in academic year of <span style="font-weight:bold" id="tellerTransactionAccountStatementAcademicYear"></span>
                        </div>
                        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;margin-bottom:10px">
                            <thead style="font-weight:bold;text-align:center">
                                <tr>
                                    <td colspan="4">Statement of Account on TUITION FEES</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>OR</td>
                                    <td>Description</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionAccountStatementList">
                               
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;font-weight:bold">Total Amount Paid : </td>
                                    <td id="tellerTransactionAccountStatementTotalAmountPaid" style="text-align:right;font-weight:bold">0.00</td>
                                
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;font-weight:bold">Total Tuition Fee : </td>
                                    <td id="tellerTransactionAccountStatementTotalAnnualFee" style="text-align:right;font-weight:bold">0.00</td>
                               
                                </tr>
                                <tr >
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;font-weight:bold">Remaining Balance : </td>
                                    <td id="tellerTransactionAccountStatementTotalRemainingBalance" style="text-align:right;font-weight:bold">0.00</td>
                               
                                </tr>
                            </tfoot>
                        </table>
                        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;">
                            <thead style="font-weight:bold;text-align:center">
                                <tr>
                                    <td colspan="4">Statement of Account on Teller</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>OR</td>
                                    <td>Description</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionAccountStatementTellerList">
                               
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;font-weight:bold">Total Amount Paid : </td>
                                    <td id="tellerTransactionAccountStatementTellerTotalAmountPaid" style="text-align:right;font-weight:bold">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tellerTransactionLedger"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionLedgerListMessage"> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-right" id="tellerTransactionLedgerPrintButton" data-loading-text="Please wat...">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                        </button>
                    </div>
                </div>
                    <br>
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionLedgerPrint">
                        <div style="font-family: Verdana, Arial, San-Serif;padding:10px;font-size:12px">
                            Account ledger of <span id="tellerTransactionLedgerFullName" style="font-weight:bold"></span> in academic year of <span style="font-weight:bold" id="tellerTransactionLedgerAcademicYear"></span>
                        </div>
                        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;">
                            <thead style="font-weight:bold;text-align:center">
                                <tr>
                                    <td>Date</td>
                                    <td>OR</td>
                                    <td>Description</td>
                                    <td>Amount</td>
                                    <td>Remarks</td>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionLedgerList">
                               
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:right;font-weight:bold">Total Amount : </td>
                                    <td id="tellerTransactionLedgerListTotalAmount" style="text-align:right;font-weight:bold"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tellerTransactionPaymentSummary"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content "  >
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <button class="btn btn-primary pull-right" id="tellerTransactionPaymentSummaryListPrintButton" data-loading-text="Please wat..." style="margin-bottom:5px">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                        </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="tellerTransactionPaymentSummaryListPrint">
                        <table class="table table-hover" style="font-size:13px;width:100%;border-radius: 5px;border-style: solid;padding: 10px;font-family: Verdana, Arial, San-Serif;">
                            <thead style="font-weight:bold" style="font-weight:bold;text-align:center">
                                <tr>
                                    <td>Description</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody id="tellerTransactionPaymentSummaryList">
                               
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align:right;font-weight:bold">Total Amount Paid : </td>
                                    <td id="tellerTransactionPaymentSummaryListTotalAmountPaid" style="text-align:right;font-weight:bold">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="prototype" style="display:none;">
    <table>
        <tr class="tellerTransactionParticularListRow" >
            <td >
                <select class="form-control  tellerTransactionParticularListAssessmentItem" type="text" placeholder="section" name="section_ID">
                    <option value="0">None</option>
                </select>
                
            </td>
            <td>
                <textarea class="form-control  tellerTransactionParticularListRemarks" type="text" placeholder="Remarks" name="remarks" style="resize: none"></textarea>
            </td>
            <td style="text-align:right;display:none">
                <span class="tellerTransactionParticularListAssessmentRemainingAmount">0.00</span>
            </td>
            <td>
                <input class="form-control tellerTransactionParticularListAmount" type="text" placeholder="Amount" name="section_ID" style="text-align:right">
            </td>
            <td style="text-align:center">
                <button class="btn btn-xs btn-danger tellerTransactionParticularListRemoveButton" type="button"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove</button>
            </td>
        </tr>
        <tr class="tellerTransactionUserSelectionListRow " account_id="0">
            <td class="tellerTransactionUserSelectionListIdentificationNumber center"></td>
            <td class="tellerTransactionUserSelectionListFullName"></td>
            <td clas="center">
                <button class="btn btn-xs btn-info tellerTransactionUserSelectionLisLedger" type="button">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true" data-loading-text="Loading..."></span> Ledger
                </button>
                <button class="btn btn-xs btn-success tellerTransactionParticularListSelectButton" type="button">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Select
                </button>
            </td>
        </tr>
        <tr class="tellerTransactionUserLedgerListRow " account_id="0">
            <td class="tellerTransactionUserLedgerListDatetime"></td>
            <td class="tellerTransactionUserLedgerListOrderReceiptNumber"></td>
            <td class="tellerTransactionUserLedgerListDescription"></td>
            <td class="tellerTransactionUserLedgerListAmount" style="text-align:right"></td>
            <td class="tellerTransactionUserLedgerListRemarks" style="font-style:italic"></td>
        </tr>
        <tr class="tellerTransactionAccountStatementListRow">
<!--            <td class="tellerTransactionAccountStatementListDescription"></td>
            <td class="tellerTransactionAccountStatementListAmount" style="text-align:right"></td>-->
            <td class="tellerTransactionAccountStatementListDatetime"></td>
            <td class="tellerTransactionAccountStatementListOR"></td>
            <td class="tellerTransactionAccountStatementListRemarks"></td>
            <td class="tellerTransactionAccountStatementListAmount" style="text-align:right"></td>
            
            
        </tr>
        <tr class="tellerTransactionLedgerListRow">
            <td class="tellerTransactionLedgerListDatetime"></td>
            <td class="tellerTransactionLedgerListOR"></td>
            <td class="tellerTransactionLedgerListDescription"></td>
            <td class="tellerTransactionLedgerListAmount" style="text-align:right"></td>
            <td class="tellerTransactionLedgerListRemarks"></td>
        </tr>
        <tr class="tellerTransactionPaymentSummaryListRow">
            <td class="tellerTransactionPaymentSummaryListDescription"></td>
            <td class="tellerTransactionPaymentSummaryListAmount" style="text-align:right"></td>
        </tr>
        <tr class="tellerTransactionAdjustmentListRow">
            <td class="tellerTransactionAdjustmentListDescription"></td>
            <td class="tellerTransactionAdjustmentListAction" style="text-align:right">
                <button class="btn btn-xs btn-success tellerTransactionAdjustmentListSelectAction" type="button" data-loading-text="Loading...">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true" ></span> Select
                </button>
                <button class="btn btn-xs btn-danger tellerTransactionAdjustmentListRemoveAction" type="button" style="display:none" data-loading-text="Loading...">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true" ></span> Remove
                </button>
            </td>
        </tr>
    </table>
</div>

<div id="tellerTransactionReceiptDiv" style="display:none">
    <style>
        /*
		Change the margin of the receipt without resizing the paper
		@page {
			margin:0
		}
		*/
        #tellerTransactionReceipt div{
            /*border-style:solid;*/
            font-size: 16px;
            color:black;
            font-family:sans-serif;
            letter-spacing:1px;
            
        }
        #tellerTransactionReceipt{
            width:22.5cm;
            display:table;
            padding-left:2.5cm;
            padding-top: 1cm;
        }
        #tellerTransactionReceiptPayee{
            float:left;
            width: 9.6cm;
            height:0.7cm;
            text-align:center;
        }
        #tellerTransactionReceiptDate{
            float:left;
            margin-left: 5cm;
            width: 3.8cm;
            height:0.7cm;
            text-align:center;
        }
        #tellerTransactionReceiptParticularLabel{
            width: 10.1cm;
            float:left;
            margin-top:0.3cm;
            height:0.7cm;
        }
        #tellerTransactionReceiptParticularAmountLabel{
            width:3.3cm;
            float:left;
            margin-top:0.3cm;
            height:0.7cm;
        }
        #tellerTransactionReceiptParticularDescription{
            width: 11.1cm;
            float:left;
            height: 5.2cm;
        }
        #tellerTransactionReceiptParticularAmount{
            width:7cm;
            float:left;
            height: 5.2cm;
            text-align:right;
        }
        #tellerTransactionReceiptParticularDescriptionFooter{
            width: 10.1cm;
            float:left;
            height: 0.7cm;
        }
        #tellerTransactionReceiptParticularTotalAmount{
            width:8cm;
            float:left;
            height: 0.7cm;
            text-align:right;
        }
        #tellerTransactionReceiptParticularCashier{
            float:left;
            margin-top: 0.2cm;
            width:8.6cm;
            height: 0.8cm;
            text-align:center;
        }
        @media print {
                /*#tellerTransactionReceiptParticularCashier {page-break-after: always;}*/
            }
    </style>
    <div id="tellerTransactionReceipt" style="">
        <div id="tellerTransactionReceiptPayee" style="">Juan Dela Cruz</div>
        <div id="tellerTransactionReceiptDate" style="">1/2/3456</div>
        <div id="tellerTransactionReceiptParticularLabel" style=""></div>
        <div style="display: inline-flex;">
            <div id="tellerTransactionReceiptParticularDescription" style="">Printing</div>
            <div id="tellerTransactionReceiptParticularAmount" style="">1000</div>
        </div>
        <div style="display: inline-flex;">
            <div id="tellerTransactionReceiptParticularDescriptionFooter" style=""></div>
            <div id="tellerTransactionReceiptParticularTotalAmount" style="">100</div>
        </div>
        <div id="tellerTransactionReceiptParticularCashier" style="">Cashiering</div>
    </div>
</div>