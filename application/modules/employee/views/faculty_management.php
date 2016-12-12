<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Faculty/Staff Management</h1>

<div id="facultyManagementDiv" class="row" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary" >
            <div class="panel-heading" >
                <h3 class="panel-title">
                    Faculty/Staff Management
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12"> 
                        <form id="facultyManagementForm" method="POST">
                            <input id="facultyManagementAccountID" type="hidden" name="account_ID" value="6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <div class='alert alert-warning' id='facultyManagementMessage' style='text-align:center;display:none'></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group ">
                                                <label for="school_year">ID Number</label>
                                                <div class="">
                                                    <input class="form-control"  id="facultyManagementIdentificationNumber" type="text" placeholder="Identification Number" name="identification_number">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Position</label>
                                                <div class="">
                                                    <select class="form-control"  id="facultyManagementAccountType" type="text" placeholder="Identification Number" name="account_type_ID">
                                                        <option value="3">Registrar</option>
                                                        <option value="5">Teller</option>
                                                        <option value="6">Faculty</option>
                                                        <option value="7">Librarian</option>
                                                        <option value="8">Non Teaching Staff</option>
                                                        <option value="9">Guidance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Last Name</label>
                                                <div class="">
                                                    <input class="form-control" id="facultyManagementLastName" type="text" placeholder="Last Name" name="last_name">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">First Name</label>
                                                <div class="">
                                                    <input class="form-control" id="facultyManagementFirstName" type="text" placeholder="First Name" name="first_name">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Middle Name</label>
                                                <div class="">
                                                    <input class="form-control" id="facultyManagementMiddleName" type="text" placeholder="Middle Name" name="middle_name">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group ">
                                                <label for="school_year">Gender</label>
                                                <div class="">
                                                    <select class="form-control" id="facultyManagementGender" type="text" placeholder="Gemder" name="gender">
                                                        <option value="1">MALE</option>
                                                        <option value="2">FEMALE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Birth Date</label>
                                                <div class="">
                                                    <input class="form-control"  id="facultyManagementBirthDate" type="date" placeholder="Birth Date">
                                                    <input id="facultyManagementBirthDatetime" type="hidden" name="birth_datetime">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Birth Place</label>
                                                <div class="">
                                                    <input class="form-control" id="facultyManagementBirthPlace" type="text" placeholder="Birth Place" name="birth_place">
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Religion</label>
                                                <div class="">
                                                    <select class="form-control" id="facultyManagementReligion" type="text" placeholder="Religion" name="religion_maintainable_ID">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="school_year">Nationality</label>
                                                <div class="">
                                                    <select class="form-control" id="facultyManagementNationality" type="text" placeholder="Middle Name" name="nationality_maintainable_ID">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary" >
                            <div class="panel-heading" data-toggle="collapse"  href="#facultyManagementAccessControlListTab">
                                <h3 class="panel-title">
                                    Access Control List
                                </h3>
                            </div>
                            <div id="facultyManagementAccessControlListTab" class="panel-body panel-collapse collapse out" >
                            
                                    
               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <br>
    <button class="btn btn-warning pull-right" id="facultyManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="facultyManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="facultyManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <legend>Faculty/Staff List</legend>
    </div>
    <!--##### New Student ####--->

    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="facultyManagementListSorter" sorter_name="identification_number" sort="0" >ID Number
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="facultyManagementListSorter" sorter_name="full_name" sort="0" >Name
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="facultyManagementListSorter" sorter_name="description" sort="0" >Position
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:20%">Action</th>
                </tr>
                <tr>
                    <th>
                        <input id="facultyManagementListFilterIdentificationNumber" class="form-control" type="text" placeholder="ID Number">
                    </th>
                    <th>
                        <input id="facultyManagementListFilterLastName" class="form-control inline" type="text" placeholder="Last Name" style="width:30%"> ,
                        <input id="facultyManagementListFilterFirstName" class="form-control inline" type="text" placeholder="First Name" style="width:30%">
                        <input id="facultyManagementListFilterMiddleName" class="form-control inline" type="text" placeholder="Middle Name" style="width:30%">
                    </th>
                    <th>
                        <select id="facultyManagementListFilterAccountType" class="form-control" type="text" placeholder="ID Number">
                            <option value="0">All</option>
                            <option value="3">Registrar</option>
                            <option value="5">Teller</option>
                            <option value="6">Faculty</option>
                            <option value="7">Librarian</option>
                            <option value="8">IT</option>
                            <option value="9">Guidance</option>
                        </select>
                    </th>
                    <th>
                        <button id="facultyManagementListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody id="facultyManagementListBody">
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-size:13px;font-weight:bold">
                        <span id="facultyManagementListTotalResult">0</span> Result(s)
                    </td>

                    <td style="text-align:center;" >
                        <button class="btn" id="facultyManagementListPreviousPage">Previous</button>
                        <button id="facultyManagementListNextPage" class="btn">Next</button>
                    </td>
                    <td></td>
                    <td style="text-align:right">
                        Page <span id="facultyManagementListCurrentPage">1</span>
                        of <span id="facultyManagementListTotalPage">1</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none;">
            <table>
                <tr class="facultyManagementListRow" assessment_type_id="0">
                    <td class="facultyManagementListIdentificationNumber"></td>
                    <td class="facultyManagementListFullName"></td>
                    <td class="facultyManagementListPosition"></td>
                    <td>
                        <!--<button class="btn-primary  actionButton facultyManagementListViewButton" type="button">View</button>-->
                        <button class="btn btn-xs btn-info  actionButton facultyManagementListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton facultyManagementListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton facultyManagementListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton facultyManagementListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
            <div class="panel panel-default accessControlListGroup" >
                <div class="panel-heading" data-toggle="collapse">
                    <h3 class="panel-title">
                        Access Control List
                    </h3>
                </div>
                <div  class="panel-body panel-collapse collapse  accessControlListGroupContainer" >
                    <div class="row">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 accessControlListUnit">
                <input class="accessControlListUnitInput" type="checkbox"> <span class="accessControlListUnitLabel"></span>
            </div>
        </div>
    </div>
</div>
