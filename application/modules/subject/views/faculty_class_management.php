<script src="<?php echo load_asset("js/jquery.form.min.js");?>"></script>
<h1>Schedules</h1>

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
            <tbody id="classListBody">


            </tbody>
            <!--<tfoot>
                <tr>
                    <td>
                    </td>

                    <td style="text-align:right;">
                        <button class="btn" id="classListListPreviousPage">Previous</button>
                        <button id="classListListNextPage" class="btn">Next</button>
                    </td>
                    <td style="text-align:right">
                        Page <span id="classListListCurrentPage">1</span> of <span id="classListListTotalPage">0</span>
                    </td>
                </tr>
            </tfoot>-->
        </table>
        <div class="prototype-schedule" style="display:none">
            <table>
                <tr class="classListRow">
                    <td class="classListGrade"></td>
                    <td class="classListSection"></td>
                    <td class="classListDescription"></td>
                    <td class="classListSchedule"></td>
                    <td class="classListRoom"></td>
                    <td class="">
                        <button class="btn btn-xs btn-info  actionButton classListViewButton" type="button">View Class List</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="col-md-12">

</div>
<!--###### Student Listing-->
<div class="row" id="studentListClassList" style="display: none; margin-top: 4%">
    <div class="col-md-12" style="padding: 0">
        <legend>Student List</legend>
    </div>
    <!--##### New Student ####-->

    <div class="col-md-12" style="padding: 0">
        <div class="col-md-12" style="text-align: center; font-size:20px">
            <span id="classListSubjectName"></span>&nbsp;
            <span id="classListGradeSectionName"></span>
        </div>
        <ul class="col-md-6 classListStudentListBody-Boys-Girls" id="classListStudentListBody-Boys">
        </ul>
        <ul class="col-md-6 classListStudentListBody-Boys-Girls" id="classListStudentListBody-Girls">
        </ul>
    </div>
</div>
<div class="prototype" style="display:none">
    <li class="studentListRow" style="font-size: 16px;" account_id="">
        <span class="studentLastName col-md-5">ABAY</span>
        <span class="studentFirstName col-md-7">, ALTHEA MAE</span>
    </li>
</div>
