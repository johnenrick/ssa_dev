<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Material Access Control</h1>

<div id="materialAccessControlDiv" class="row" style="display:none">
    <div class="col-md-12">
        <form id="materialAccessControlForm" method="POST">
            <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#materialMaterialTab">
                    <h3 class="panel-title">
                        Control Information
                    </h3>
                </div>
                <div id="materialMaterialTab" class="panel-collapse collapse in" >
                    <div class="panel-body">
                        <input type="hidden" name="type" value="1">
                        <input id="materialAccessControlID" type="hidden" name="ID" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" >
                                        <div class='alert alert-warning' id='materialAccessControlMessage' style='text-align:center;display:none'></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group ">
                                            <label for="school_year">Description</label>
                                            <div class="">
                                                <input class="form-control"  id="materialAccessControlDescription" type="text" placeholder="Description" name="description">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row users-container">
                                    <div class="users">
                                        <div class="col-md-12">
                                            <br>
                                            <legend style="font-size:16px">User</legend>
                                            <input class="libraryuserID" type="hidden" name="libraryuserID[]" value="0">
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group ">
                                                <label for="school_year">Period</label>
                                                <div class="form-inline">
                                                    <input class="form-control materialAccessControlPeriodNumber" type="number" placeholder="Period" name="periodNumber[]" >
                                                    <select class="form-control materialAccessControlPeriodUnit" name="periodUnit[]" >
                                                        <option value="0">Hour/s</option>
                                                        <option value="1">Day/s</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="school_year">Fine Rate</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">Php.</div>
                                                    <input class="form-control materialAccessControlFineRate" type="number" step="0.01" placeholder="Fine Rate" name="fineRate[]" required>
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
        </form>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">
    <br>
    <button class="btn btn-warning pull-right" id="materialAccessControlCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="materialAccessControlSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="materialAccessControlCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <!--##### New Student ####--->

    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width:15%"s>#</th>
                    <th style="width:65%">Description</th>
    <!--                <th style="width:15%">Period
                    </th>
    -->
                    <th style="width:20%">Action</th>
                </tr>
<!--
                <tr>
                    <th>
                        <input id="materialAccessControlListFilterDescription" class="form-control" type="text" placeholder="Description">
                    </th>
                    <th>
                    </th>
                    <th>
                        <button id="materialAccessControlListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
-->
            </thead>
            <tbody id="materialAccessControlListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="materialAccessControlListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" >
                    </td>
                    <td style="text-align:right">
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="materialAccessControlListRow" assessment_type_id="0">
                    <td class="materialAccessControlListNumber"></td>
                    <td class="materialAccessControlListDescription"></td>
       <!--             <td class="materialAccessControlListPeriod"></td>  -->
                    <td>
                        <!--<button class="btn-primary  actionButton materialAccessControlListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton materialAccessControlListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton materialAccessControlListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton materialAccessControlListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton materialAccessControlListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
