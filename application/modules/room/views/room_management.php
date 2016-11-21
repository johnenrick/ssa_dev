<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Room Management</h1>
<!--##### Room Information ####--->
<div class="row"  id="roomManagementDiv" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <h3 class="panel-title">
                    Room Information
                </h3>
            </div>
            <div class="panel-body">
                <form id="roomManagementForm" action="<?=api_url()?>c_room/createRoom" method="POST">
                    <div class="row">
                        <div class="col-md-12" >
                            <div class='alert hide' id='roomManagementMessage' style='text-align:center'></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Building_ID">Building No.</label>
                                <div class="form-inline">
                                    <select id="roomManagementBuilding" class="form-control" type="text" placeholder="Building" name="building_ID">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input id="roomManagementID" type="hidden" name="ID" >
                                <input class="form-control" id="roomManagementDescription" type="text" placeholder="Description" name="description">
                            </div>
                            <div class="form-group">
                                <label for="capacity">Capacity</label>
                                <div class="form-inline">
                                    <input id="roomManagementCapacity" class="form-control" type="text" placeholder="Capacity" name="capacity">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="span8 offset1">
    <br>
    <button class="btn btn-warning pull-right" id="roomManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="roomManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="roomManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>

</div>

<!--###### Room Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Room List</legend>
    </div>
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Description</th>
                    
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="roomManagementListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>

                    <td style="text-align:right;">
                        <button class="btn" id="roomManagementListPreviousPage">Previous</button>
                        <button id="roomManagementListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="roomManagementListCurrentPage">1</span> of <span id="roomManagementListTotalPage">0</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none">
            <table>
                <tr class="roomManagementListRow" account_id="0">

                    <td class="roomManagementListDescriprion"></td>
                    <td class="roomManagementListCapacity"></td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton roomManagementListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton roomManagementListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton roomManagementListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton roomManagementListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
