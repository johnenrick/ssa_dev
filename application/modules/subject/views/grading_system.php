<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<style>
</style>

<h1>Grading System</h1>

<div class="row addsection">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Schedule</th>
                    <th>Room</th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="gclassListBody">


            </tbody>
        </table>
        <div class="prototype-gschedule" style="display:none">
            <table>
                <tr class="gclassListRow" schedule_id="0" section_id="0">
                    <td class="gclassListGrade"></td>
                    <td class="gclassListSection"></td>
                    <td class="gclassListDescriprion"></td>
                    <td class="gclassListSchedule"></td>
                    <td class="gclassListRoom"></td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton gclassListViewButton" type="button">Grade Students</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="col-md-10 col-md-offset-1">

</div>
<!--###### Student Listing-->
<div class="row" id="studentListGradingSystem" style="margin-top: 4%;display:none">
    <div class="row">
        <div class="col-sm-12" style="text-align:center">
            <select id="gradingSystemGrading" class="input-lg ">
                <option value="1">1st Grading</option>
                <option value="2">2nd Grading</option>
                <option value="3">3rd Grading</option>
                <option value="4">4th Grading</option>
            </select>
        </div>
    </div>
    <br>
    <table class="table-bordered" style="margin: 0 auto;">
        <thead id="gradingSystemHeader">
            <tr id="gradingSystemHeader1"  style="text-align: center; font-weight: bold">
                <td id="gradingSystemSubjectDescription" colspan="2" style="text-align: center">MAPEH</td>
                <td>Initial</td>
                <td>Transmuted</td>
            </tr>
            <tr id ="gradingSystemHeader2">
                <td id="gradingSystemClassDescription" colspan="2" style="text-align: center; font-weight: bold">7 - ST ANDREW</td>
                <td colspan="2"></td>
            </tr>
            <tr id ="gradingSystemHeader3" style="text-align: center;">
                <td colspan="2">Students</td>
            </tr>
        </thead>
        <tbody id="gradingSystemStudentList" class="gradingSystemStudentList-Boys-Girls"></tbody>
    </table>
    
</div>
<div class="prototype-studentlist" style="display:none;">
    <table>
        <tr class="gradingSystemStudentListRow" account_id="0">
            <td class="gradingSystemStudentListName"></td>
            <td class="gradingSystemStudentList1"></td>
            <td class="gradingSystemStudentList2"></td>
            <td class="gradingSystemStudentList3"></td>
            <td class="gradingSystemStudentList4"></td>
            <td class="gradingSystemStudentList5"></td>
            <td class="gradingSystemStudentList6"></td>
            <td class="gradingSystemStudentList7"></td>
            <td class="gradingSystemStudentList8"></td>
            <td class="gradingSystemStudentList9"></td>
            <td class="gradingSystemStudentList10"></td>
            <td class="gradingSystemStudentList11"></td>
        </tr>
        <tr class="columnP">
            <td class="subjectComponentDescription" colspan="3" style="text-align: center; font-weight: bold">
                <input class="form-control">
            </td>
            
            <td class="subjectComponentHPS" colspan="2">
                <input class="form-control" placeholder="HPS"  style="text-align: right">
            </td>
            <td class="subjectComponentWeight"  style="text-align: center;">
            </td>
            
            
            
            
            
            <td class="totalScore" subject_schedule_component_hps="0">
                <input class="form-control" style="text-align: right">
            </td>
            <td class="partialScore" style="text-align: right">
            </td>
            <td class="weightedScore" style="text-align: right">
            </td>
            <td class="initialGrade" style="text-align: right">
            </td>
            <td class="transmutedGrade" style="text-align: right">
            </td>
        </tr>
        <tr class="scheduleStudent">
            <td class="scheduleStudentLastName"></td>
            <td class="scheduleStudentFirstName"></td>
            
        </tr>
    </table>
</div>
