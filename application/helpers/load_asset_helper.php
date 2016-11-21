<?php

function load_asset($path=false){
    $ci         =&  get_instance();
    $basePath   =   $ci->config->item('assetBasePath');
    if($path==false){
        return $basePath;
    }else{
        return $basePath.$path;
    }
}



