<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<script src="<?php echo load_asset("js/datepicker.js");?>"></script>
<script src="<?php echo load_asset("js/print.js");?>"></script>
<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Payment Report</h1>

<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Payment List</legend>
    </div>
</div>
<div class="row" >
    <div class="col-md-12">
        <div id="noAccountPayeeListTablePrint">
        <table class="table table-hover" id="noAccountPayeeListTable">
            <thead>
                <tr>
                    <th>OR #</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot >
            </tfoot>
        </table>
        </div>
        <div class="modal fade" id="noAccountPayeeAccount" selection_mode="1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="font-size:14px">
                <div class="modal-content "  >
                    <div class="modal-body" >
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" id="noAccountPayeeAccountTable">
                                    <thead>
                                        <tr>
                                            <th>ID Number</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
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
                <tr class="noAccountPayeeListRow" payement_no_account_ID="0">
                    <td class="noAccountPayeeListOrderReceiptNumber"></td>
                    <td class="noAccountPayeeListLastName"></td>
                    <td class="noAccountPayeeListFirstName"></td>
                    <td class="noAccountPayeeListMiddleName"></td>
                </tr>
                <tr class="noAccountPayeeAccountListRow" payement_no_account_ID="0">
                    <td class="noAccountPayeeAccountIdentificationNumber"></td>
                    <td class="noAccountPayeeAccountLastName"></td>
                    <td class="noAccountPayeeAccountFirstName"></td>
                    <td class="noAccountPayeeAccountMiddleName"></td>
                    <td>
                        <button class="btn btn-xs btn-success noAccountPayeeAccountListSelectButton" type="button">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Select
                        </button>
                        <div class="noAccountPayeeListConfirmSelect" style="display:none">
                            <span>Continue ? </span>
                            <button class="btn btn-xs btn-primary noAccountPayeeAccountListYesSelectButton" type="button">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Yes
                            </button>
                            <button class="btn btn-xs btn-danger noAccountPayeeAccountListNoSelectButton" type="button">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> No
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
