<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<h1>Payment History</h1>

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Payment Listsd
            
            <button id="paymentHistoryListPrintReport" class="btn btn-primary pull-right" >
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
            </button>
            <button id="paymentHistoryListExcelReport" class="btn btn-success pull-right" style="margin-right:10px">
                <span class="glyphicon glyphicon-export" aria-hidden="true"></span> Excel
            </button>
        </legend>
    </div>
    <div id="paymentHistoryListMessage" class="col-md-11 col-md-offset-1">
        
    </div>
</div>
<div class="row" >
    <div class="col-md-2 col-xs-4 ">
        <label>Start Date</label>
        <input id="paymentHistoryListFilterStartDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
    </div>
    <div class="col-md-2 col-xs-4">
        <label>End Date</label>
        <input id="paymentHistoryListFilterEndDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
    </div>
	<div class="col-md-2 col-xs-4">
        <label>Assessment Item</label><br>
        <select id="paymentHistoryListFilterAssessmentItem" class="form-control" ></select>
    </div>
	<div class="col-md-2 col-xs-4 ">
        <label>Cashier</label>
        <select id="paymentHistoryListFilterCashier" class="form-control" ></select>
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>Section</label>
        <select id="paymentHistoryListFilterSection" class="form-control" ></select>
    </div>
    <div class="col-md-2 col-xs-4" style="text-align:right">
        <label>&nbsp;</label><br>
        <button id="paymentHistoryListFilterSearch" class="btn btn-primary" >
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
        </button>
    </div>
</div>
<div class="row" >
    <div class="col-md-2 col-xs-4 ">
        <label>ID Number</label>
        <input id="paymentHistoryListFilterIDNumber" class="form-control" type="text" placeholder="ID Number">
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>OR Number</label>
        <input id="paymentHistoryListFilterORNumber" class="form-control" type="text" placeholder="OR Number">
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>Amount</label>
        <input id="paymentHistoryListFilterAmount" class="form-control" type="text" placeholder="Amount">
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>Academic Year</label>
        <select id="paymentHistoryListFilterAcademicYear" class="form-control" >
            <option value="0">None</option>
        </select>
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>Year Level</label>
        <select id="paymentHistoryListFilterYearLevel" class="form-control" >
            <option value="0">None</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
        </select>
    </div>
</div>
<div class="row" >
    <div class="col-md-12">
        <div id="paymentHistoryListTablePrint">
		<style>
			/*table,
			table tr td {
				page-break-inside: avoid;
			}
			@page {
			  margin: 30;
			}*/
                        #paymentHistoryListTable{
                            text-transform: uppercase;
                        }
                        @media print
                        {
                          table { page-break-after:auto }
                          tr    { page-break-inside:avoid; page-break-after:auto }
                          td    { page-break-inside:avoid; page-break-after:auto }
                          thead { display:table-header-group }
                          tfoot { display:table-footer-group }
                        }
		</style>
        <table class="table table-hover" id="paymentHistoryListTable">
            <thead>
                <tr>
                    <th class="paymentHistoryListSorter" sorter_name="payment__datetime" sort="0" sorted="1" style="width:10%">Date
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentHistoryListSorter" sorter_name="payment__order_receipt_number" sort="0" style="width:10%">OR
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentHistoryListSorter" sorter_name="payee_full_name" sort="0" style="width:30%">Payee
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentHistoryListSorter" sorter_name="assessment_item__description" sort="0" style="width:20%">Assessment Item
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentHistoryListSorter" sorter_name="payment_assessment_item__amount" sort="0" style="width:10%">Amount
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentHistoryListSorter" sorter_name="payment__remarks" sort="0" style="width:30%">Details
                    </th>
                </tr>
            </thead>
            <tbody id="paymentHistoryListBody">
            </tbody>
            <tfoot >
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Total : </td>
                    <td id="paymentHistoryListTotalAmount"  style="text-align:right">0.00</td>
                    <td></td>
                </tr>
                <tr class="tablePagination">
                    <td style="font-size:13px;font-weight:bold">
                        <span id="paymentHistoryListTotalResult">0</span> Result(s)
                    </td>
                    <td></td>
                    <td></td>
                    <td style="text-align:center;" >
                        <button class="btn" id="paymentHistoryListPreviousPage" data-loading-text="Previous">Previous</button>
                        <button id="paymentHistoryListNextPage" class="btn" data-loading-text="Next">Next</button>
                    </td>
                    <td style="text-align:right" colspan="2">
                        Page <span id="paymentHistoryListCurrentPage">1</span>
                        of <span id="paymentHistoryListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        <div class="modal fade" id="paymentHistoryDetail" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog  " style="font-size:14px">
                <div class="modal-content "  >
                    <div class="modal-body" >
                        <div class="form-group">
                            <div id="paymentHistoryDetailMessage" class="alert alert-warning" style="display:none">

                            </div>
                        </div>
                        <div class="row">
                            <div id="paymentHistoryDetailStatus" class="col-xs-12" style="text-align:center;color:red;font-weight:bold" >
                               TRANSACTION VOIDED
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>OR Number : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailOrderReceiptNumber" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Cashier : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailCashierFullName" ></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>Payer : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailPayerFullName" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Payee : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailPayeeFullName" ></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>Payment Mode : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailPaymentMode" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Remarks : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentHistoryDetailRemarks" ></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Particulars : </label>
                            </div>
                            <div class="col-xs-12 ">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Assessment Item</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentHistoryDetailAssessmentList">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="text-align:right"><label>Total:</label></td>
                                            <td id="paymentHistoryDetailAssessmentListTotalAmount" style="text-align:right" >0.00<td>
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
        <div class="prototype" style="display:none;">
            <table>
                <tr class="paymentHistoryListRow" payment_assessment_item_id="0">
                    <td class="paymentHistoryListDatetime"></td>
                    <td class="paymentHistoryListOrderReceiptNumber" >
                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                    </td>
                    <td class="paymentHistoryListPayeeFullName"></td>
                    <td class="paymentHistoryListAssessmentItem"></td>
                    <td class="paymentHistoryListAmount" style="text-align:right"></td>
                    <td class="paymentHistoryListRemarks"></td>
                </tr>
                <tr class="paymentHistoryDetailAssessmentListRow" assessment_type_id="0">
                    <td class="paymentHistoryDetailAssessmentListDescription"></td>
                    <td class="paymentHistoryDetailAssessmentListAmount" style="text-align:right"></td>
                    <td class="paymentHistoryDetailAssessmentListRemarks" style="text-align:right"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
