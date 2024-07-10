<?php

class Permissions {
  private $filePath;
  private $permissions = [];

  public function __construct($filePath) {
      $this->filePath = $filePath;
      $this->loadPermissions();
  }

  private function loadPermissions() {
      if (!file_exists($this->filePath)) {
          throw new Exception("File not found: " . $this->filePath);
      }

      $file = fopen($this->filePath, 'r');
      while (($line = fgets($file)) !== false) {
          $line = trim($line);
          if (!empty($line)) {
              list($id, $name, $title, $description) = explode('|', $line);
              $this->permissions[$id] = [
                  'id' => $id,
                  'name' => $name,
                  'title' => $title,
                  'description' => $description
              ];
          }
      }
      fclose($file);
  }

  public function getPermissions() {
      return $this->permissions;
  }

  public function getPermissionById($id) {
      return isset($this->permissions[$id]) ? $this->permissions[$id] : null;
  }

  public function getPermissionByName($name) {
      foreach ($this->permissions as $permission) {
          if ($permission['name'] === $name) {
              return $permission;
          }
      }
      return null;
  }

  public function check($userPermissions, $permissionName):bool {
    $permissionIds = explode(',', $userPermissions);
    $permission = $this->getPermissionByName($permissionName);
    
    if ($permission === null) {
        return false;
    }
    
    return in_array($permission['id'], $permissionIds);
  }

}

?>