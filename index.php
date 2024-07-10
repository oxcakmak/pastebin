<?php

require_once __DIR__ . '/core/autoload.php';

if($address){
    
    if($address && $address[0] === "api"){
        include __DIR__ . '/api/'.$address[1].'.php';
    }else{
        $db->where("id", $address[0]);
        $link = $db->getOne("pastes");
        if($link){
            include __DIR__ . '/pages/paste.php';
        }else{
            $page = __DIR__ . '/pages/'.$address[0].'.php';
            if(file_exists($page)){
                include $page;
            }else{
                include __DIR__ . '/pages/error.php';
            }
        }
    }
    
}else{ include __DIR__ . '/pages/index.php'; }

?>