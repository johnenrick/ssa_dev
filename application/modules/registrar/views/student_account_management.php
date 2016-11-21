<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Student Management</h1>
<div id="studentInformationDiv" class="panel panel-default" style="display:none">
    <div class="panel-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary" >
                <div class="panel-heading" data-toggle="collapse"  href="#studentAccountManagementPeronalInformationTab">
                    <h3 class="panel-title">
                        Personal Information
                    </h3>
                </div>
                <div id="studentAccountManagementPeronalInformationTab" class="panel-collapse collapse in" >
                    <div class="panel-body">
                        <form action="<?php echo api_url().'c_account/createAccount'; ?>" id="studentInfo">
                            <input type="hidden" name="account_ID">
                            <input type="hidden" name="account_type_ID" value="4">
                            <div class="row">
                                <div id="studentAccountManagementPeronalInformationMessage" class="col-md-12">
                                
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Family Name</label>
                                        <input type="text" class="form-control" id="" name="last_name" placeholder="Family Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">Given Name</label>
                                        <input type="text" class="form-control" id="" name="first_name" placeholder="Given Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="" name="middle_name" placeholder="Middle Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" name="gender" placeholder="Gender" required>
                                            <option value="" disabled selected>Select your option</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_date">Birth Date</label>
                                        <input type="date" class="form-control" id="bdatetime" name="birth_date" placeholder="Birth Date" required>
                                        <input class="form-control" type="hidden" name="birth_datetime" value="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="birth_place">Birth Place</label>
                                        <input type="text" class="form-control" id="" name="birth_place" placeholder="Birth Place" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="religion_maintainable_ID">Religion</label>
                                        <select class="form-control" name="religion_maintainable_ID" placeholder="Religion" required><option>None</option></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nationality_maintainable_ID">Nationality</label>
                                        <select class="form-control" name="nationality_maintainable_ID" placeholder="Nationality" required><option>None</option></select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!--##### Previous School Information--->
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse"  href="#studentAccountManagementPreviousSchoolInformationTab">
                    <h3 class="panel-title">Previous School Information</h3>
                </div>
                <div id="studentAccountManagementPreviousSchoolInformationTab" class="panel-collapse collapse out" >
                    <div class="panel-body">
                        <form id="previousSchoolHistoryForm" action="<?=api_url()?>c_account_school_history/createAccountSchoolHistory" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_ID">Course & Year</label>
                                        <input id="previousSchoolHistoryID" type="hidden" name="ID" value="1">
                                        <input class="previousSchoolHistoryAccountID" id="previousSchoolHistoryAccountIDID" type="hidden" name="account_ID" value="0">
                                        <div class="form-inline">
                                            <select id="previousSchoolHistoryCourse" class="form-control" type="text" placeholder="Previous Course" name="course_ID"></select>
                                            <input id="previousSchoolHistoryYearLevel" class="form-control" type="text" placeholder="Year Level" name="year_level">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_campus_maintainable_ID">School</label>
                                        <select class="form-control" id="previousSchoolCampusHistory" type="text" placeholder="Previous School" name="school_campus_maintainable_ID"></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Year</label>
                                        <input type="text" class="form-control" id="previousSchoolHistorySchoolYear" name="" placeholder="School Year">
                                        <input id="previousSchoolHistoryDatetime" type="hidden" placeholder="School Year" name="datetime">
                                    </div>
                                    <div class="form-group">
                                        <label for="section">Section</label>
                                        <input type="text" class="form-control" id="previousSchoolHistorySection" name="section" placeholder="Previous Section">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                    <div class="panel-heading" data-toggle="collapse"  href="#studentAccountManagementStudentGuardianDetailTab">
                        <h3 class="panel-title">Student Guardian Detail</h3>
                    </div>
                    <div id="studentAccountManagementStudentGuardianDetailTab" class="panel-collapse collapse out" >
                        <div class="panel-body">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Student Guardian Detail 1</h3>
                                </div>
                                <div class="panel-body">
                                    <form id="studentGuardian1Form" action="<?=api_url()?>c_account/createAccountGuardian" method="POST">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">Family Name</label>
                                                <input id="studentGuardianID1" type="hidden" name="ID" value="1">
                                                <input class="previousSchoolHistoryAccountID" id="studentGuardianAccountID1" type="hidden" name="account_ID" value="1">
                                                <input id="studentGuardianFamilyName1" name="last_name" class="form-control" type="text" placeholder="Family Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="first_name">Given Name</label>
                                                <input id="studentGuardianGivenName1" name="first_name" class="form-control" type="text" placeholder="Given Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                    <input id="studentGuardianMiddleName1" name="middle_name" class="form-control" type="text" placeholder="Middle Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="relationship">Relationship</label>
                                                <select class="form-control relationshipMaintainable" id="studentGuardianRelationship1" type="text" placeholder="Relationship" name="relationship"><option>None</option></select>
                                            </div>
											<div class="form-group">
                                                <label for="address">Address</label>
                                                <input class="form-control" id="studentGuardianAddress1" type="text" placeholder="Address" name="address">
                                            </div>
											<div class="form-group">
                                                <label for="contactNumber">Contact Number</label>
                                                <input class="form-control" id="studentGuardianContactNumber1" type="text" placeholder="Contact Number" name="contact_number">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Student Guardian Detail 2</h3>
                                </div>
                                <div class="panel-body">
                                    <form id="studentGuardian2Form" action="<?=api_url()?>c_account/createAccountGuardian" method="POST">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">Family Name</label>
                                                <input id="studentGuardianID2" type="hidden" name="ID" value="1">
                                                <input class="previousSchoolHistoryAccountID" id="studentGuardianAccountID2" type="hidden" name="account_ID" value="1">
                                                <input id="studentGuardianFamilyName2" name="last_name" class="form-control" type="text" placeholder="Family Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="first_name">Given Name</label>
                                                <input id="studentGuardianGivenName2" name="first_name"  class="form-control" type="text" placeholder="Given Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input id="studentGuardianMiddleName2" name="middle_name" class="form-control" type="text" placeholder="Middle Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="relationship">Relationship</label>
                                                <select class="form-control relationshipMaintainable" id="studentGuardianRelationship2" type="text" placeholder="Relationship" name="relationship"><option>None</option></select>
                                            </div>
											<div class="form-group">
                                                <label for="address">Address</label>
                                                <input class="form-control" id="studentGuardianAddress2" type="text" placeholder="Address" name="address">
                                            </div>
											<div class="form-group">
                                                <label for="contactNumber">Contact Number</label>
                                                <input class="form-control" id="studentGuardianContactNumber2" type="text" placeholder="Contact Number" name="contact_number">
                                            </div>
                                        </div>
                                    </form>
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
    <button class="btn btn-warning pull-right" id="closeInfo" type="button" style="display:none;margin-right:10px">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="edit" type="button" style="display:none;margin-right:10px">Edit</button>&nbsp;
    <button class="btn btn-warning pull-right" id="closeInfo" type="button" style="display:none;margin-right:10px">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="submitInfo" type="submit" style="display:none;margin-right:10px">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="createStudent" type="button" style="margin-right:10px">Create New</button>
    <span class="help-inline pull-right" style="display:none;"></span>
</div>
<!--###### Student Listing--->
<div class="row" >
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="studentListSorter" sorter_name="identification_number" sort="0" sorted="1" style="width:15%" id="studentListSortIDNumber" >ID Number 
                        <span class="glyphicon glyphicon-triangle-bottom sortDown" aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th class="studentListSorter" sorter_name="name" sort="0" sorted="0" id="studentListSortName" >Name
                        <span class="glyphicon glyphicon-triangle-bottom sortDown " aria-hidden="true" style="display:none"></span>
                        <span class="glyphicon glyphicon-triangle-top sortUp" aria-hidden="true" style="display:none"></span>
                    </th>
                    <th style="width:21%">Action</th>
                </tr>
                <tr>
                    <td>
                        <input id="studentListFilterIdentificationNumber" class="form-control" type="text" placeholder="ID Number">
                    </td>
                    <td>
                        <input id="studentListFilterLastName" class="form-control inline" type="text" placeholder="Last Name" style="width:25%;">
                        <input id="studentListFilterFirstName" class="form-control inline" type="text" placeholder="First Name" style="width:25%;">
                        <input id="studentListFilterMiddleName" class="form-control inline" type="text" placeholder="Middle Name" style="width:25%;">
                    </td>
                    <td>
                        <button id="studentListFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody id="studentListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn" id="previousStudentListPage">Previous</button> <button id="nextStudentListPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="currentStudenListPage">1</span> of <span id="totalStudentListPage">0</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none">
            <table>
                <tr class="studentListRow" account_id="0">
                    <td class="identification_number">10104459</td>
                    <td class="account_full_name">Plenos, John Enrick</td>
                    <td class="">
                        <button class="btn btn-xs btn-info viewStudentListEntry actionButton" type="button">
                            <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> View
                        </button>
                        <button class="btn btn-xs btn-danger removeStudentListEntry actionButton" type="button">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove
                        </button>
                        <span class="confirmButton" type="button">Delete? </span>
                        <button class="btn btn-xs btn-info yesRemoveStudentListEntry confirmButton" type="button">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Yes
                        </button>
                        <button class="btn btn-xs btn-danger noRemoveStudentListEntry confirmButton" type="button">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>No
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
