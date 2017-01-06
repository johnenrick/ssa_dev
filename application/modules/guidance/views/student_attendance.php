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
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne"data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title">
                                <a role="button" >
                                    Collapsible Group Item #1
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <span><strong>01</strong></span>
                                        <span class="text-info">01:10 am</span> / <span class="text-warning">04:10 pm</span>
                                    </div>
                                </div>
                            </div>
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
    <table>
        <tr class="studentAttendanceRow">
            <td class="accountFullName"></td>
            <td class="accountGrade"></td>
            <td class="accountSection"></td>
        </tr>
    </table>
</div>