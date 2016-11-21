        </div>
        <!-- END: .site -->

        <div class="footer">
            <div class="footer-wrap limit-wrap clearfix">
                <div class="footerwidget clearfix">
                    <div id="footer-left">
                        <h3>Evolve.IT Solutions</h3>
                        <ul class="xlist icon-wrap">
                            <li class="xicon-map">6th Floor Jesa ITC Building, Mango Avenue,
                                <br>Cebu, Philippines
                            </li>
                        </ul>
                    </div>
                    <div id="footer-middle">
                        <h3>Quick Links</h3>
                        <ul class="xlist">
                            <li><a href="#">Academic</a>
                            </li>
                            <li><a href="#">Finance</a>
                            </li>
                            <li><a href="#">Administrative</a>
                            </li>
                            <li><a href="#">Assessment</a>
                            </li>
                            <li><a href="#">Parent's Portal</a>
                            </li>
                        </ul>
                    </div>
                    <div id="footer-right">
                        <h3>Find Us On</h3>
                        <div class="xlist">
                            <a href="#">
                                <img src="<?=load_asset()?>img/system_img/icon-fb.png">
                            </a>
                            <a href="#">
                                <img src="<?=load_asset()?>img/system_img/icon-tw.png">
                            </a>
                            <a href="#">
                                <img src="<?=load_asset()?>img/system_img/icon-gm.png">
                            </a>
                            <a href="#">
                                <img src="<?=load_asset()?>img/system_img/icon-in.png">
                            </a>
                            <a href="#">
                                <img src="<?=load_asset()?>img/system_img/icon-yt.png">
                            </a>
                        </div>
                    </div>
                </div>

                <div style="clearboth"></div>
                <div class="site-info">
                    <div class="pull-left">© Copyright 2014. Evolve.IT Solutions</div>
                    <div class="pull-right"><a href="#">Privacy</a> &nbsp;|&nbsp; <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->


    

    <script type="text/javascript">
        $(document).ready(function () {
            /*shrink menu-nav*/
            $(window).scroll(function () {
                if ($(window).scrollTop() > 0) {
                    $('#brand img').addClass('short');
                    $('.navbar .nav > li > a').addClass('short');
                } else {
                    $('#brand img').removeClass('short');
                    $('.navbar .nav > li > a').removeClass('short');
                }
            });

        });
    </script>


</body>

</html>