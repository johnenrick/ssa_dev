<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<link rel='stylesheet' href="<?php echo load_asset("css/fullcalendar.min.css");?>"/>
<script src="<?php echo load_asset("js/moment.min.js");?>"></script>
<script src="<?php echo load_asset("js/fullcalendar.min.js");?>"></script>
<h1>Schedule Management</h1>
<!--##### Schedule Information ####--->
<div class="row addsection"  id="scheduleManagementDiv" style="display:none">
    <div class="col-md-10 col-md-offset-1" >
        <div class='alert hide' id='scheduleManagementMessage' style='text-align:center'></div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <legend>Schedule Information</legend>
    </div>
    <div class="col-md-6 col-md-offset-1">
        <form id="scheduleManagementForm" action="<?=api_url()?>c_schedule/createSchedule" method="POST">
            <div class="span4 offset1">
                <div class="form-group">
                    <label for="subject_type">Subject Type</label>
                    <div class="form-inline">
                        <select id="scheduleManagementSubjectType" class="form-control" type="text" placeholder="Type" name="subject_type">
                            <option value selected disabled style="display: none">Please choose subject type</option>
                            <option value="1">Subject</option>
                            <option value="2">Club</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="scheduleManagementYearCourse" style="display: none">
                    <label id="scheduleManagementCourseIDLabel" for="course_ID" style="padding-right: 80px;">Course & Year</label>
                    <label for="description" >Description</label>
                    <div class="form-inline">
                        <input type="hidden" name="ID" id="scheduleManagementID">
                        <span id="scheduleManagementCourseContainer">
                            <select id="scheduleManagementCourse" class="form-control" type="text" placeholder="Course" name="course_ID"></select>
                        </span>
                        <select id="scheduleManagementYearLevel" class="form-control" type="text" placeholder="Year Level" name="year_level" default_value="101">
                            <option default value="101" selected class="hide">Yr.</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select id="scheduleManagementSubject" class="form-control" type="text" placeholder="Subject" name="description"></select>
                    </div>
                </div>
                <div class="form-group span8" style="margin-left: 0px">
                    <label for="time_start">Start:</label>
                    <label for="time_end" style="padding-left: 90px;">End:</label>
                    <div class="form-inline">
                        <input id="scheduleManagementTimeStart" type="time" class="form-control" name="time_start">
                        <input id="scheduleManagementTimeEnd" type="time" class="form-control" name="time_end">
                    </div>
                    <div class="form-inline">
                        <table class="" style="width: 60%; margin-top: 4%;">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">Mon</th>
                                    <th style="width: 55px;">Tue</th>
                                    <th style="width: 55px;">Wed</th>
                                    <th style="width: 55px;">Thu</th>
                                    <th style="width: 55px;">Fri</th>
                                    <th style="width: 55px;">Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="32"></td>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="16"></td>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="8"></td>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="4"></td>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="2"></td>
                                    <td><input type="checkbox" class="scheduleManagementCheckbox" name="daysofweek[]" value="1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label for="room_ID">Room</label>
                    <div class="form-inline">
                        <span id="scheduleManagementRoomListContainer"><select id="scheduleManagementRoomList" class="form-control" type="text" placeholder="Room" name="room_ID"></select></span>
                        <button type="button" id="scheduleManagementShowScheduleButton" class="btn" data-loading-text="Retrieving Schedule">Show Room Schedule</button>
                        <input class="form-control" id="scheduleManagementSchoolYear" type="hidden" name="school_year">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="span8 offset1">
    <br>
    <button class="btn btn-warning pull-right" id="scheduleManagementCloseFormButton" type="button" style="margin-right:10px;display:none">Close</button>&nbsp;
    <button class="btn btn-success pull-right" id="scheduleManagementSubmitFormButton" type="submit" style="margin-right:10px;display:none">Submit</button>&nbsp;
    <button class="btn btn-success pull-right" id="scheduleManagementCreateFormButton" type="button" style="margin-right:10px">Create</button>

</div>
<div class="offset1 span8" id="scheduleManagementRoomSchedule">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
                <div id="room" style="width:100%"></div>
            </div>                    
        </div>

<!--###### Schedule Listing--->
<div class="row" >
    <div class="span8 offset1">
        <legend>Schedule List</legend>
    </div>
    <div class="span8 offset1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Schedule</th>
                    <th style="width: 30%">Teacher</th>
                    <th>Room</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="scheduleManagementListBody">


            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:right;">
                        <button class="btn" id="scheduleManagementListPreviousPage">Previous</button>
                        <button id="scheduleManagementListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="scheduleManagementListCurrentPage">1</span> of <span id="scheduleManagementListTotalPage">0</span>
                    </td>

                </tr>
            </tfoot>
        </table>
        <div class="prototype" style="display:none">
            <table>
                <tr class="scheduleManagementListRow" account_id="0">
                    
                    <td class="scheduleManagementListDescriprion"></td>
                    <td class="scheduleManagementListSchedule"></td>
                    <td class="">
                        <select class="form-control scheduleManagementListTeacherFilter" type="text" placeholder="Teacher" name="teacher_ID"></select>
                    </td>
                    <td class="scheduleManagementListRoom"></td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton scheduleManagementListViewButton" type="button">View</button>
                        <button class="btn btn-xs btn-danger  actionButton scheduleManagementListRemoveButton" type="button">Remove</button>
                        <span class="confirmButton" style="font-weight:bold" type="button">Delete ?</span>
                        <button class="btn btn-xs btn-info x confirmButton scheduleManagementListYesRemoveButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmButton scheduleManagementListNoRemoveButton"  type="button">No</button>
                        <span class="confirmChangeButton" style="font-weight:bold" type="button">Save ?</span>
                        <button class="btn btn-xs btn-info x confirmChangeButton scheduleManagementTeacherListYesChangeButton" type="button" >Yes</button>
                        <button class="btn btn-xs btn-danger confirmChangeButton scheduleManagementTeacherListNoRemoveButton"  type="button">No</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
