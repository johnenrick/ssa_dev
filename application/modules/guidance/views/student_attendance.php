<script src="<?php echo load_asset("js/tableAPIPagination.js");?>"></script>
<h1>Student Attendance</h1>
<div class="row">
    <div class="col-sm-12">
        <table id="studentAttendanceTable" class="table table-hover" >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot >
            </tfoot>
        </table>
    </div>
</div>
<div id="studentAttendanceModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Student Attendance</h4>
            </div>
            <div class="modal-body">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div month="6" class="monthlyAttendance panel panel-default">
                        <div href="#headingJune" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >June</a></h4>
                        </div>
                        <div id="headingJune" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="7" class="monthlyAttendance panel panel-default">
                        <div href="#headingJuly" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >July</a></h4>
                        </div>
                        <div id="headingJuly" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="8" class="monthlyAttendance panel panel-default">
                        <div href="#headingAugust" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >August</a></h4>
                        </div>
                        <div id="headingAugust" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="9" class="monthlyAttendance panel panel-default">
                        <div href="#headingSeptember" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >September</a></h4>
                        </div>
                        <div id="headingSeptember" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="10" class="monthlyAttendance panel panel-default">
                        <div href="#headingOctober" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >October</a></h4>
                        </div>
                        <div id="headingOctober" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="11" class="monthlyAttendance panel panel-default">
                        <div href="#headingNovember" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >November</a></h4>
                        </div>
                        <div id="headingNovember" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="12" class="monthlyAttendance panel panel-default">
                        <div href="#headingDecember" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >December</a></h4>
                        </div>
                        <div id="headingDecember" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="1" class="monthlyAttendance panel panel-default">
                        <div href="#headingJanuary" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >January</a></h4>
                        </div>
                        <div id="headingJanuary" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="2" class="monthlyAttendance panel panel-default">
                        <div href="#headingFebruary" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >February</a></h4>
                        </div>
                        <div id="headingFebruary" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="3" class="monthlyAttendance panel panel-default">
                        <div href="#headingMarch" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >March</a></h4>
                        </div>
                        <div id="headingMarch" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    <div month="4" class="monthlyAttendance panel panel-default">
                        <div href="#headingApril" class="panel-heading" role="tab" data-toggle="collapse" data-parent="#accordion"  aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><a role="button" >April</a></h4>
                        </div>
                        <div id="headingApril" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body"><div class="row"></div></div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="prototype" style="display:none">
    <div class="col-xs-4 dailyAttendance">
        <span><strong class="day"></strong></span>
        <span class="text-info in"></span>
        <span class="text-warning out"></span>
    </div>
    <table>
        <tr class="studentAttendanceRow">
            <td class="accountFullName"></td>
            <td class="accountGrade"></td>
            <td class="accountSection"></td>
        </tr>
    </table>
</div>