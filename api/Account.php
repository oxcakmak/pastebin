<?php

header('Content-Type: application/json; charset=utf-8');

if(!$_SERVER['REQUEST_METHOD'] || $_SERVER['REQUEST_METHOD']!=="POST"){ echo json_encode(array('title' => $lang['warning'], 'message' => 'No post request found!', 'status' => '0')); exit; }

if(isset($_POST['action'])){
    if(isset($_SESSION['session'])){
        /* Reset */
        if($_POST['action'] == "updatePassword"){
            
            $passwordLast = $helper->Strings->sanitizeOutput($_POST['passwordLast']);
            $passwordNew = $helper->Strings->sanitizeOutput($_POST['passwordNew']);
            $passwordRenew = $helper->Strings->sanitizeOutput($_POST['passwordRenew']);
            $hashedPassword = $helper->Hashings->hashPasswordSecure($passwordNew, 1);
            
            if(!$passwordLast || !$passwordNew || !$passwordRenew){  echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }
            
            if($passwordNew !== $passwordRenew){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['passwords_entered_are_not_same'], 'status' => '0')); exit; }

            $data = array(
                'password' => $hashedPassword
            );

            $db->where("id", $member['id']);
            $reset = $db->update("users", $data);

            $db->disconnect();

            if($reset){
                echo json_encode(array('title' => $lang['success'], 'message' => $lang['password_updated'], 'status' => '1')); exit;
            }else{
                echo json_encode(array('title' => $lang['danger'], 'message' => $lang['password_not_updated'], 'status' => '0')); exit;
            }
           
        }
    }else{
        /* Login */
        if($_POST['action'] == "login"){
            
            $user = $helper->Strings->sanitizeOutput($_POST['user']);
            $password = $helper->Strings->sanitizeOutput($_POST['password']);

            $domains = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com'];
            
            if(!$user || !$password){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }

            if($helper->Checks->checkEmailDomain($user, $domains)){ 
                $db->where("email", $user);
            }else{
                $db->where("username", $user);
            }
            
            $user = $db->getOne("users");

            if(!$user){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['user_not_found'], 'status' => '0')); exit; }

            if(!$helper->Hashings->verifyPasswordSecure($password, $user['password'])){ 
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['password_is_incorrect'], 'status' => '0')); exit;
            }

            $_SESSION['session'] = true;
            $_SESSION['user'] = $user['id'];

            $data = array(
                'loginAddress' => $helper->Gets->getCurrentIpAddress(),
                'loginDateTime' => $helper->Times->getCurrentDateTime("m-d-Y H:i")
            );

            $db->where("id", $user['id']);
            $login = $db->update("users", $data);

            $db->disconnect();

            echo json_encode(array('title' => $lang['success'], 'message' => $lang['login_successful_redirecting'], 'status' => '1', 'redirect' => $config['url']));
            exit;

        }
        
        /* Register */
        if($_POST['action'] == "register"){
            
            $username = $helper->Strings->sanitizeOutput($_POST['username']);
            $email = $helper->Strings->sanitizeOutput($_POST['email']);
            $password = $helper->Strings->sanitizeOutput($_POST['password']);
            $repassword = $helper->Strings->sanitizeOutput($_POST['repassword']);
            $hashedPassword = $helper->Hashings->hashPasswordSecure($password, 1);

            $domains = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com'];
            
            if(!$username || !$email || !$password || !$repassword){  echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }

            if(!$helper->Checks->checkEmailDomain($email, $domains)){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['unsupported_email_extension'], 'status' => '0')); exit; }

            if($password !== $repassword){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['passwords_entered_are_not_same'], 'status' => '0')); exit; }

            $db->where("username", $username)
            ->orWhere("email", $email);
            $user = $db->getOne("users");

            if($user){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['user_registered'], 'status' => '0')); exit; }

            $data = array(
                'username' => $username,
                'email' => $email,
                'emailKey' => $helper->Generators->generateStrongPassword(29, 30, true, true, true, false),
                'password' => $hashedPassword,
                'permissions' => '7,8,9,11',
                'registeredAddress' => $helper->Gets->getCurrentIpAddress(),
                'registeredDateTime' => $helper->Times->getCurrentDateTime("m-d-Y H:i")
            );

            $register = $db->insert("users", $data);

            $db->disconnect();

            if($register){
                echo json_encode(array('title' => $lang['success'], 'message' => $lang['registration_completed'], 'status' => '1', 'redirect' => $config['url'].'login')); exit;
            }else{
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['registration_uncompleted'], 'status' => '0')); exit;
            }
        }

        /* Forgot */
        if($_POST['action'] == "forgot"){

            $email = $helper->Strings->sanitizeOutput($_POST['email']);

            $domains = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com'];
            
            if(!$email){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }

            if(!$helper->Checks->checkEmailDomain($email, $domains)){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['unsupported_email_extension'], 'status' => '0')); exit; }

            $db->where("email", $email);
            $user = $db->getOne("users");

            $db->disconnect();

            if(!$user){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['user_not_found'], 'status' => '0')); exit; }

            // send email
            
            echo json_encode(array('title' => $lang['success'], 'message' => $lang['password_reset_link_sent'], 'status' => '1'));
            exit;

        }

        /* Reset */
        if($_POST['action'] == "reset"){
            
            $key = $helper->Strings->sanitizeOutput($_POST['key']);
            $password = $helper->Strings->sanitizeOutput($_POST['password']);
            $repassword = $helper->Strings->sanitizeOutput($_POST['repassword']);
            $hashedPassword = $helper->Hashings->hashPasswordSecure($password, 1);
            
            if(!$key || !$password || !$repassword){  echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }
            
            if($password !== $repassword){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['passwords_entered_are_not_same'], 'status' => '0')); exit; }

            $db->where("emailKey", $key);
            $user = $db->getOne("users");

            if(!$user){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['verification_key_not_found'], 'status' => '0')); exit; }

            if($key !== $user['emailKey']){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['verification_key_invalid'], 'status' => '0')); exit; }

            $data = array(
                'emailKey' => $helper->Generators->generateStrongPassword(29, 30, true, true, true, false),
                'password' => $hashedPassword
            );

            $db->where("id", $user['id']);
            $reset = $db->update("users", $data);

            $db->disconnect();

            if($reset){
                echo json_encode(array('title' => $lang['success'], 'message' => $lang['password_updated'], 'status' => '1')); exit;
            }else{
                echo json_encode(array('title' => $lang['danger'], 'message' => $lang['password_not_updated'], 'status' => '0')); exit;
            }
           
        }

    }
}

?>