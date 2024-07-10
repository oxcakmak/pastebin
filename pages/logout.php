<?php

if(isset($_SESSION['session'])){ 
    session_destroy();
}

header("location:" . $config['url']);
?>