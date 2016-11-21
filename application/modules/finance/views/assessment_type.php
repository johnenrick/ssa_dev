<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Assessment Type</h1>

<div id="assessmentTypeDiv" class="row" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <h3 class="panel-title">
                    Assessment Type
                </h3>
            </div>
            <div class="panel-body">
                <form id="assessmentTypeForm" method="POST">
                    <input type="hidden" name="type" value="1">
                    <input id="assessmentTypeID" type="hidden" name="ID" value="0">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class='alert alert-warning' id='assessmentTypeMessage' style='text-align:center;display:none'></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group ">
                                <label for="school_year">Description</label>
                                <div class="">
                                    <input class="form-control"  id="assessmentTypeDescription" type="text" placeholder="Description" name="description">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="school_year">Code</label>
                                <div class="">
                                    <input class="form-control" id="assessmentTypeCode" type="text" placeholder="Code" name="code">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">
    <br>
    <button class="btn btn-warning pull-right" id="assessmentTypeCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="assessmentTypeSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="assessmentTypeCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Assessment Type List</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="assessmentTypeListSorter" sorter_name="description" sort="0" >Description
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="assessmentTypeListSorter" sorter_name="code" sort="0" style="width:30%">Code
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
                <tr>
                    <th>
                        <input id="assessmentTypeListFilterDescription" class="form-control" type="text" placeholder="Description">
                    </th>
                    <th>
                        <input id="assessmentTypeListFilterCode" class="form-control inline" type="text" placeholder="Code" >
                    </th>
                    <th>
                        <button id="assessmentTypeListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody id="assessmentTypeListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="assessmentTypeListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" >
                        <button class="btn" id="assessmentTypeListPreviousPage">Previous</button>
                        <button id="assessmentTypeListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="assessmentTypeListCurrentPage">1</span>
                        of <span id="assessmentTypeListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="assessmentTypeListRow" assessment_type_id="0">
                    <td class="assessmentTypeListDescription"></td>
                    <td class="assessmentTypeListCode"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton assessmentTypeListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton assessmentTypeListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton assessmentTypeListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton assessmentTypeListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton assessmentTypeListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
