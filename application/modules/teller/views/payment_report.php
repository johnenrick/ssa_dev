<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<h1>Payment Report</h1>

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Payment List 
            <button id="paymentReportListPrintReport" class="btn btn-primary pull-right" >
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
            </button>
        </legend>
    </div>
    <div id="paymentReportListMessage" class="col-md-11 col-md-offset-1">
        
    </div>
</div>
<div class="row" >
    <div class="col-md-2 col-xs-4 ">
        <label>Start Date</label>
        <input id="paymentReportListFilterStartDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
    </div>
    <div class="col-md-2 col-xs-4">
        <label>End Date</label>
        <input id="paymentReportListFilterEndDatetime" class="form-control" type="date" placeholder="mm/dd/yyyy">
    </div>
	<div class="col-md-2 col-xs-4">
        <label>Assessment Item</label><br>
        <select id="paymentReportListFilterAssessmentItem" class="form-control" ></select>
        
    </div>
	<div class="col-md-2 col-xs-4 ">
        <label>Cashier</label>
        <select id="paymentReportListFilterCashier" class="form-control" ></select>
    </div>
    <div class="col-md-2 col-xs-4 ">
        <label>Section</label>
        <select id="paymentReportListFilterSection" class="form-control" ></select>
    </div>
    <div class="col-md-2 col-xs-4" style="text-align:right">
        <label>&nbsp;</label><br>
        <button id="paymentReportListFilterSearch" class="btn btn-primary" >
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
        </button>
    </div>
</div>
<div class="row" >
    <div class="col-md-12">
        <div id="paymentReportListTablePrint">
		<style>
			/*table,
			table tr td {
				page-break-inside: avoid;
			}
			@page {
			  margin: 30;
			}*/
                        #paymentReportListTable{
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
        <table class="table table-hover" id="paymentReportListTable">
            <thead>
                <tr>
                    <th class="paymentReportListSorter" sorter_name="payment__datetime" sort="0" sorted="1" style="width:10%">Date
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentReportListSorter" sorter_name="payment__order_receipt_number" sort="0" style="width:10%">OR
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentReportListSorter" sorter_name="payee_full_name" sort="0" style="width:30%">Payee
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentReportListSorter" sorter_name="assessment_item__description" sort="0" style="width:20%">Assessment Item
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentReportListSorter" sorter_name="payment_assessment_item__amount" sort="0" style="width:10%">Amount
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="paymentReportListSorter" sorter_name="payment__remarks" sort="0" style="width:30%">Details
                    </th>
                </tr>
            </thead>
            <tbody id="paymentReportListBody">
            </tbody>
            <tfoot >
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;font-weight:bold">Total : </td>
                    <td id="paymentReportListTotalAmount"  style="text-align:right">0.00</td>
                    <td></td>
                </tr>
                <tr class="tablePagination">
                    <td style="font-size:13px;font-weight:bold">
                        <span id="paymentReportListTotalResult">0</span> Result(s)
                    </td>
                    <td></td>
                    <td></td>
                    <td style="text-align:center;" >
                        <button class="btn" id="paymentReportListPreviousPage" data-loading-text="Previous">Previous</button>
                        <button id="paymentReportListNextPage" class="btn" data-loading-text="Next">Next</button>
                    </td>
                    <td style="text-align:right" colspan="2">
                        Page <span id="paymentReportListCurrentPage">1</span>
                        of <span id="paymentReportListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        <div class="modal fade" id="paymentReportDetail" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog  " style="font-size:14px">
                <div class="modal-content "  >
                    <div class="modal-body" >
                        <div class="form-group">
                            <div id="paymentReportDetailMessage" class="alert alert-warning" style="display:none">

                            </div>
                        </div>
                        <div class="row">
                            <div id="paymentReportDetailStatus" class="col-xs-12" style="text-align:center;color:red;font-weight:bold" >
                               TRANSACTION VOIDED
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>OR Number : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailOrderReceiptNumber" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Cashier : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailCashierFullName" ></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>Payer : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailPayerFullName" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Payee : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailPayeeFullName" ></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label>Payment Mode : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailPaymentMode" ></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label>Remarks : </label>
                                <br>
                                <div class="col-xs-offset-1">
                                    <span class="" id="paymentReportDetailRemarks" ></span>
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
                                    <tbody id="paymentReportDetailAssessmentList">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="text-align:right"><label>Total:</label></td>
                                            <td id="paymentReportDetailAssessmentListTotalAmount" style="text-align:right" >0.00<td>
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
                <tr class="paymentReportListRow" payment_assessment_item_id="0">
                    <td class="paymentReportListDatetime"></td>
                    <td class="paymentReportListOrderReceiptNumber" >
                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                    </td>
                    <td class="paymentReportListPayeeFullName"></td>
                    <td class="paymentReportListAssessmentItem"></td>
                    <td class="paymentReportListAmount" style="text-align:right"></td>
                    <td class="paymentReportListRemarks"></td>
                </tr>
                <tr class="paymentReportDetailAssessmentListRow" assessment_type_id="0">
                    <td class="paymentReportDetailAssessmentListDescription"></td>
                    <td class="paymentReportDetailAssessmentListAmount" style="text-align:right"></td>
                    <td class="paymentReportDetailAssessmentListRemarks" style="text-align:right"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
