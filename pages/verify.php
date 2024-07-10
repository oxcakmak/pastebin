<?php

include 'partial.php';

$content = "";

$key = isset($address[1]) ? $address[1] : (isset($_GET['key']) ? $_GET['key'] : null);

$db->where('emailKey', $key);
$user = $db->getOne('users', ['id', 'emailKey', 'emailVerified']);



if(!$key || !$user){
    $content = '<div class="container-tight py-4">
    <div class="empty">
        <div class="empty-header">'.$lang['warning'].'</div>
        <p class="empty-title">'.$lang['verification_key_not_found'].'</p>
    </div>
    </div>';
}else if($user && $key !== $user['emailKey']){
        $content = '<div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header">'.$lang['warning'].'</div>
            <p class="empty-title">'.$lang['verification_key_invalid'].'</p>
        </div>
        </div>';
}else if($user && $user['emailVerified'] == "yes"){
    $content = '<div class="container-tight py-4">
    <div class="empty">
        <div class="empty-header">'.$lang['warning'].'</div>
        <p class="empty-title">'.$lang['email_already_verified'].'</p>
    </div>
    </div>';
}else if($user){

    $data = array(
        'emailKey' => $helper->Generators->generateStrongPassword(29, 30, true, true, true, false),
        'emailVerified' => "yes"
    );

    $db->where("id", $user['id']);
    $reset = $db->update("users", $data);

    if(!$reset){
        $content = '<div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header">'.$lang['warning'].'</div>
            <p class="empty-title">'.$lang['email_unverified'].'</p>
        </div>
        </div>';
    }else{
        $content = '<div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header">'.$lang['success'].'</div>
            <p class="empty-title">'.$lang['email_verified'].'</p>
        </div>
        </div>';
    }
}

$db->disconnect();

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$part['end'];
?>