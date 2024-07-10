<?php

header('Content-Type: application/json; charset=utf-8');

if(!$_SERVER['REQUEST_METHOD'] || $_SERVER['REQUEST_METHOD']!=="POST"){ echo json_encode(array('title' => $lang['warning'], 'message' => 'No post request found!', 'status' => '0')); exit; }

if(isset($_POST['action'])){
    if(isset($_SESSION['session'])){

        /* List */
        if($_POST['action'] == "list"){
            $data = array();
            $i = -1;
            $permissionTitles = array();
            if(!$permissions->check($member['permissions'], "ADMIN")){
                $db->where("createdBy", $member['id']);
            }
            $db->orderBy("id", "DESC");
            foreach($db->get("pastes", "", ['id', 'title', 'visibility', 'status', 'createdBy', 'createdAddress', 'createdDateTime', 'modifiedBy', 'modifiedAddress', 'modifiedDateTime']) as $row){
                $i++;
                $data['data'][$i]["id"] = $row["id"];
                $data['data'][$i]["title"] = $row["title"];
                $data['data'][$i]["visibility"] = $row["visibility"]=="public" ? '<span class="badge bg-green text-green-fg">' . $lang['public'] . '</span>' : '<span class="badge bg-red text-red-fg">' . $lang['private'] . '</span>';
                $data['data'][$i]["status"] = $row["status"]=="published" ? '<span class="badge bg-green text-green-fg">' . $lang['published'] . '</span>' : '<span class="badge bg-red text-red-fg">' . $lang['unpublished'] . '</span>';
                $data['data'][$i]["created"] = $row["createdBy"] . " / ". $row["createdAddress"] . " / ". $row["createdDateTime"];
                $data['data'][$i]["modified"] = $row["modifiedBy"] . " / ". $row["modifiedAddress"] . " / ". $row["modifiedDateTime"];
                $data['data'][$i]["action"] = '<div class="btn-group"><a href="'.$config['url'].$row["id"].'" target="_blank" class="btn btn-primary btn-sm" title="'.$lang['view'].'">'.$lang['view'].'</a><a href="'.$config['url'].$row["id"].'?edit" target="_blank" class="btn btn-yellow btn-sm" title="'.$lang['edit'].'">'.$lang['edit'].'</a><button class="btn btn-red btn-sm" onclick="deletePaste(\''.$row['id'].'\', \''.$row['title'].'\')">'.$lang['delete'].'</button></div>';
            }
            echo json_encode($data);
        }

        /* Update */
        if($_POST['action'] == "update"){
            $id = $helper->Strings->sanitizeOutput($_POST['id']);
            $title = isset($_POST['title']) && isset($member['id']) ? $helper->Strings->sanitizeOutput($_POST['title']) : "Untitled";
            $content = $_POST['content'];

            $visibility = isset($_POST['visibility']) ? $helper->Strings->sanitizeOutput($_POST['visibility']) : "public";

            if(!intval($id)){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['id_value_is_not_numeric'], 'status' => '0')); exit; }
        
            if(!$title || !$content){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }

            $db->where("id", $id);
            $paste = $db->getOne("pastes");

            if(!$paste){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['paste_not_found'], 'status' => '0')); exit; }

            if(!$permissions->check($member['permissions'], "ADMIN") && (!$permissions->check($member['permissions'], "UPDATE_PASTE_OTHER") || $paste['createdBy'] != $member['id'])){ 
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['you_have_no_permission'], 'status' => '0')); exit;
            }
            
            $data = array(
                'title' => $title,
                'content' => $content,
                'visibility' => $visibility,
                'modifiedBy' => $member['id'],
                'modifiedAddress' => $helper->Gets->getCurrentIpAddress(),
                'modifiedDateTime' => $helper->Times->getCurrentDateTime("m-d-Y H:i")
            );

            $db->where("id", $id);
            $update = $db->update("pastes", $data);

            $db->disconnect();

            if($update){
                echo json_encode(array('title' => $lang['success'], 'message' => $lang['paste_updated_redirecting'], 'status' => '1', 'redirect' => $config['url'].$id."?edit")); exit;
            }else{
                echo json_encode(array('title' => $lang['danger'], 'message' => $lang['paste_not_updated'], 'status' => '0')); exit;
            }
        }

        /* Delete */
        if($_POST['action'] == "delete"){
            $id = $helper->Strings->sanitizeOutput($_POST['id']);

            if(!$id){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '0')); exit; }

            if(!intval($id)){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['id_value_is_not_numeric'], 'status' => '0')); exit; }

            $db->where("id", $id);
            $paste = $db->getOne("pastes");

            if(!$paste){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['paste_not_found'], 'status' => '0')); exit; }

            if($paste['createdBy'] != $member['id'] || !$permissions->check($member['permissions'], "ADMIN")){ 
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['you_have_no_permission'], 'status' => '0')); exit;
            }

            $db->where("id", $id);
            $delete = $db->delete("pastes");

            $db->disconnect();

            if($delete){
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['paste_deleted'], 'status' => '1')); exit;
            }else{
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['paste_not_deleted'], 'status' => '0')); exit;
            }
        }


    }

    /* Create */
    if($_POST['action'] == "create"){
        $title = isset($member['id']) ? $helper->Strings->sanitizeOutput($_POST['title']) : isset($_POST['title']) ? $helper->Strings->sanitizeOutput($_POST['title']) : "Untitled";
        $content = $_POST['content'];
        $visibility = isset($member['id']) && isset($_POST['visibility']) ? $helper->Strings->sanitizeOutput($_POST['visibility']) : "public";
    
        if(!$content){ echo json_encode(array('title' => $lang['warning'], 'message' => $lang['dont_leave_empty_space'], 'status' => '3')); exit; }
        
        $data = array(
            'title' => $title,
            'content' => $content,
            'visibility' => $visibility,
            'createdBy' => isset($member['id']) ? $member['id'] : 'anonymous',
            'createdAddress' => $helper->Gets->getCurrentIpAddress(),
            'createdDateTime' => $helper->Times->getCurrentDateTime("m-d-Y H:i"),
            'modifiedBy' => isset($member['id']) ? $member['id'] : 'anonymous',
            'modifiedAddress' => $helper->Gets->getCurrentIpAddress(),
            'modifiedDateTime' => $helper->Times->getCurrentDateTime("m-d-Y H:i")
        );

        $insert = $db->insert("pastes", $data);

        $db->disconnect();

        if($insert){
            echo json_encode(array('title' => $lang['success'], 'message' => $lang['paste_created_redirecting'], 'status' => '1', 'redirect' => $config['url'].$insert)); exit;
        }else{
            echo json_encode(array('title' => $lang['danger'], 'message' => $lang['paste_not_created'], 'status' => '0')); exit;
        }
    }
}

?>