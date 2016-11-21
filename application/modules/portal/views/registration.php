<object id="webcard" type="application/x-webcard" width="0" height="0"><param name="onload" value="pluginLoaded" /></object>


<h1>RFID Registration</h1>
<legend></legend>

<style>
    [contenteditable=true]:empty:before{
      content: attr(placeholder);
      display: block;
      color: #999;
    }
    table {
        counter-reset: rowNumber;
    }

    table tbody tr {
        counter-increment: rowNumber;
    }

    table tbody tr th:first-child::before {
        content: counter(rowNumber);
        min-width: 1em;
        margin-right: 0.5em;
    }
    table tbody:empty + tfoot {
        display: table-footer-group;
    }

    table tbody + tfoot {
        display: none;
        color: #aaa;
    }
</style>
<div id="materialDiv" class="row">
    <div class="col-md-12">
        <form>
            <div class="alert alert-success" style="display:none" role="alert"><strong>Yehey!</strong> Successfully registered.</div>
            <div class="alert alert-danger" style="display:none" role="alert"><strong>Booo!</strong> Something's wrong.</div>

            <table id="listBody" class="table table-hover">
                <thead>
                    <tr>
                        <th style="width:10%">#</th>
                        <th style="width:15%">ID No.</th>
                        <th>Name</th>
                        <th style="width:18%">UID</th>
                        <th style="width:15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align:center">Waiting for RFID to be scan...</td>
                    </tr>
                </tfoot>
            </table>
            <button type="button" id="register" class="btn btn-success">Register</button>
        </form>
    </div>
</div>

<table class="hide">
    <tr class="sample hide">
        <th scope="row"></th>
        <td class="listID" style="vertical-align:center;" ><input type="text" name="listID" class="form-control input-sm" placeholder="type here..."></td>
        <td class="listName"></td>
        <td class="listUID"><input type="hidden" name="listUID" class="form-control"></td>
        <td>
            <button class="btn btn-xs btn-danger actionButton listRemoveButton" type="button">Remove</button>
            <span class="confirmButton" style="font-weight:bold;display:none" type="button">Delete ?</span>
            <br/>
            <button class="btn btn-xs btn-info x confirmButton listYesRemoveButton" style="display:none" type="button" >Yes</button>
            <button class="btn btn-xs btn-danger confirmButton listNoRemoveButton" style="display:none" type="button">No</button>
        </td>
    </tr>
</table>
