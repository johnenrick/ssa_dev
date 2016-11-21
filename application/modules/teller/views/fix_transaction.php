<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Fix Transaction</h1>

<!---->

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Transaction List</legend>
    </div>
</div>
<div class="row" >
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                Test
            </div>
        </div>
        <div id="paymentListListTablePrint">
            <table class="table table-hover" id="paymentListListTable">
                <thead>
                    <tr>
                        <th>OR #</th>
                        <th>Account</th>
                        <th>Total</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot >
                </tfoot>
            </table>
        </div>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="paymentListListRow" >
                    <td class="paymentListOrderReceiptNumber"></td>
                    <td class="">
                        <input class="paymentListFullName form-control">
                        <div class="paymentListPayeeSuggestion">
                        </div>
                    </td>
                    <td class="paymentListTotalAmount"></td>
                    <td class="paymentListRemarks"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
