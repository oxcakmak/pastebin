<?php

header('Content-Type: application/json; charset=utf-8');

if(!$_SERVER['REQUEST_METHOD'] || $_SERVER['REQUEST_METHOD']!=="POST"){ echo json_encode(array('title' => $lang['warning'], 'message' => 'No post request found!', 'status' => '0')); exit; }

if(isset($_POST['action'])){
    if(isset($_SESSION['session'])){

        /* List Users */
        if($_POST['action']=="list_users"){
            $data = array();
            $i = -1;
            $permissionTitles = array();
            foreach($db->get("users", "", ['id', 'username', 'email', 'emailVerified', 'permissions', 'loginAddress', 'loginDateTime', 'registeredAddress', 'registeredDateTime', 'registeredDateTime']) as $row){
                $i++;
                $data['data'][$i]["id"] = $row["id"];
                $data['data'][$i]["username"] = $row["username"];
                $data['data'][$i]["email"] = $row["email"];
                $data['data'][$i]["emailVerified"] = $row["emailVerified"]=="yes" ? '<span class="badge bg-green text-green-fg">' . $lang['yes'] . '</span>' : '<span class="badge bg-red text-red-fg">' . $lang['no'] . '</span>';
                $permissionsData = $permissions->getPermissions();
                $processedPermissions = [];
                $preprocessedPermissions = [];
                foreach ($permissionsData as $permissionLine) {
                    $permissionId = $permissionLine['id'];
                    $permissionTitle = $permissionLine['title'];
                
                    if (in_array($permissionId, explode(",", $row['permissions']))) {
                        $processedPermissions[] = '_' . $permissionTitle . '_';
                    } else {
                        $processedPermissions[] = $permissionTitle;
                    }

                    if (in_array($permissionId, explode(",", $row['permissions']))) {
                        $preprocessedPermissions[] = '_' . $permissionId . '_';
                    } else {
                        $preprocessedPermissions[] = $permissionId;
                    }
                }

                $data['data'][$i]["permissions"] = '<div class="btn-group"><button class="btn btn-cyan btn-sm w-50" onclick="viewPermissions(\''.implode(", ", $processedPermissions).'\')">'.$lang['view'].'</button>'.($permissions->check($member['permissions'], "UPDATE_USER_PERMISSIONS") ? '<button class="btn btn-yellow btn-sm w-50" onclick="editPermissions(\''.$row['id'].'\', ['.implode(", ", str_replace("_", "", $preprocessedPermissions)).'])">'.$lang['edit'].'</button>':'').'</div>';
                $data['data'][$i]["loginAndRegister"] = '<button class="btn btn-primary btn-sm w-50" onclick="viewLoginAndRegister(\''.$row["loginAddress"]."|".$row["loginDateTime"]."|".$row["registeredAddress"]."|".$row["registeredDateTime"].'\')">'.$lang['view'].'</button>';
            }
            echo json_encode($data);
        }

        /* Update Permissions */
        if($_POST['action']=="update_permissions"){
            $user = $helper->Strings->sanitizeOutput($_POST['user']);
            $permissions = $helper->Strings->sanitizeOutput($_POST['permissions']);

            $db->where("id", $user);
            $users = $db->getOne("users");

            if(!$user || $users){ echo json_encode(array('title' => $lang['error'], 'message' => $lang['user_not_found'], 'status' => '0')); exit; }

            if(!$permissions->check($member['permissions'], "UPDATE_USER_PERMISSIONS")){
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['you_have_no_permission'], 'status' => '0')); exit;
            }

            $data = [
                "permissions" => $permissions
            ];

            $db->where("id", $user);
            $update = $db->update("users", $data);
            if($update){
                echo json_encode(array('title' => $lang['success'], 'message' => $lang['permissions_updated'], 'status' => '1'));
            }else{
                echo json_encode(array('title' => $lang['warning'], 'message' => $lang['permissions_not_updated'], 'status' => '0'));
            }

        }
    }
}

?>