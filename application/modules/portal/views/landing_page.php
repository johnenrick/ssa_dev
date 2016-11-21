<!--Includes-->



<div class="row">
    <div class="col-md-3">
        <p>
            Please enter your user name and password.
        </p>

        <form id="logInAccountForm" action="<?=base_url()?>portal/c_portal/logInAccount" method="POST">
            <div>
                <legend>Account&nbsp;Information</legend>
                <div class="form-group">
                    <label for="UserName">User name</label>
                    <input type="text" class="form-control" name="username" id="UserName" placeholder="">
                </div>
                <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" class="form-control" name="password" id="Password" placeholder="">
                </div>
                <button type="submit" id="logInButton" class="btn btn-primary">Log on</button>
            </div>
        </form>
    </div>
    <div class="col-md-9">
        <div class="jumbotron">
            <h1 style="font-size:50px">Welcome to </h1>
            <h1 style="font-size:50px">St. Scholastica's Academy</h1>
            <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
        </div>
    </div>
</div>
