<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Subject Management</h1>
<!--##### Subject Information ####--->
<div class="row addsection"  id="subjectManagementDiv" style="display:none">
    <div class="col-md-10 col-md-offset-1" >
        <div class='alert hide' id='subjectManagementMessage' style='text-align:center'></div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <legend>Subject Information</legend>
    </div>
    <div class="col-md-5 col-md-offset-1">
        <form id="subjectManagementForm" action="<?=api_url()?>c_subject/createsubject" method="POST">
            <input id="subjectManagementAcademicYear" type="hidden" name="academic_year" >
            <div class="span4 offset1">
                <div class="form-group">
                    <label for="subject_type">Subject Type</label>
                    <div class="form-inline">
                        <select id="subjectManagementSubjectType" class="form-control" type="text" placeholder="Type" name="subject_type">
                            <option value selected disabled style="display: none">Please choose subject type</option>
                            <option value="1">Subject</option>
                            <option value="2">Club</option>
                        </select>
                    </div>
                </div>
                <div id="subjectManagementYearCourse" style="display:none" class="form-group">
                    <label for="course_ID">Course & Year</label>
                    <div class="form-inline">
                        <select id="subjectManagementCourse" class="form-control" type="text" placeholder="Course" name="course_ID"></select>
                        <select id="subjectManagementYearLevel" class="form-control" type="text" placeholder="Year Level" name="year_level">
                            <option default value="101" selected class="hide">Yr.</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="subjectManagementID" type="hidden" name="ID" >
                    <input class="form-control" id="subjectManagementDescription" type="text" placeholder="Description" name="description" style="text-transform: uppercase;">
                    <span><input type="checkbox" id="subjectManagementElective" class="" name="elective" value="1" /> <span>ELECTIVE</span></span>
                </div>
                <div class="form-group">
                    <label for="subject_component">Subject Component</label>
                    <div class="form-inline">
                        <table class="table">
                            <thead>
                                <th> Description </th>
                                <th> % </th>

                                <th> Action </th>
                            </thead>
                            <tbody id="subjectComponentManagementListBody">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td id="subjectComponentTotalPercentage"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="span8 offset1">
    <br>
    <button class="btn btn-warning pull-right" id="subjectManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="subjectManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="subjectManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>

</div>

<!--###### Subject Listing--->
<div class="row" >
    <div class="span8 offset1">
        <legend>Subject List</legend>
    </div>
    <div class="span8 offset1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 30%">Year Level</th>
                    <th>Description</th>

                    <th>Action</th>
                </tr>
                <tr>
                    <td>
                        <select id="subjectManagementFilterYearLevel"  class="form-control" type="text" placeholder="Year Level" name="year_level">
                            <option default value selected class="hide">Select Grade Level</option>
                            <option value="">ALL</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                            <option value="4">Grade 4</option>
                            <option value="5">Grade 5</option>
                            <option value="6">Grade 6</option>
                            <option value="7">Grade 7</option>
                            <option value="8">Grade 8</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                            <option value="101">CLUB</option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Description" class="form-control" id="subjectManagementFilterDescription"></td>
                    <td>
                        <button id="subjectManagementFilterSearch" data-loading-text="Searching..." class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody id="subjectManagementListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>

                    <td style="text-align:right;">
                        <button class="btn" id="subjectManagementListPreviousPage">Previous</button>
                        <button id="subjectManagementListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="subjectManagementListCurrentPage">1</span> of <span id="subjectManagementListTotalPage">0</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none">
            <table>
                <tr class="subjectManagementListRow" account_id="0">

                    <td class="subjectManagementListYearLevel"></td>
                    <td class="subjectManagementListDescriprion"></td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton subjectManagementListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton subjectManagementListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton subjectManagementListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton subjectManagementListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="prototype-subjectcomponent" style="display:none">
            <table>
                <tr class="subjectComponentListRow" component_id="0">
                    <td class="subjectComponentListDescriprion"></td>
                    <td class="subjectComponentListPercentage"></td>
                    <td class="subjectComponentListDelete"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
