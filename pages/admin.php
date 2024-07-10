<?php

if(empty($_SESSION['session'])){ include('error.php'); exit; }

if(isset($member['permissions']) &&  !$permissions->check($member['permissions'], "ADMIN")){ include "error.php"; exit; }

include 'partial.php';

$header = '<link rel="stylesheet" src="'.$config['assets'].'libs/datatable/dataTables.bootstrap5.css" />';

$tabLink = "";
$tabContent = "";
$tabScript = "";

if(isset($member['permissions']) && $permissions->check($member['permissions'], "LIST_USERS")){

  $tabLink .= '<a class="list-group-item list-group-item-action active" data-tab="users">'.$lang['users'].'</a>';
  $tabContent .= '<div class="tab-pane active" id="users">
    <h2 class="mb-2">'.$lang['users'].'</h2>
    <div class="row table-responsive">
      <table class="table usersTable" style="width:100%">
        <thead>
            <tr>
                <th>'.$lang['id'].'</th>
                <th>'.$lang['username'].'</th>
                <th>'.$lang['email'].'</th>
                <th>'.$lang['email_verified'].'</th>
                <th>'.$lang['permissions'].'</th>
                <th>'.$lang['login_and_register_with_symbol'].'</th>
            </tr>
        </thead>
      </table>
    </div>
  </div>
  <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">n/a</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">n/a</div>
      </div>
    </div>
  </div>';
  $tabScript .= '
  <script src="'.$config['assets'].'libs/datatable/dataTables.js"></script>
  <script src="'.$config['assets'].'libs/datatable/dataTables.bootstrap5.js"></script>
  <script>$.fn.dataTable.ext.errMode = "none";
  $(".usersTable").dataTable({
    ajax: {
      url: "'.$config['api'].'Admin",
      type: "POST",
      data: {
        action: "list_users",
      }
    },
    "columns": [
      { "data": "id" },
      { "data": "username" },
      { "data": "email" },
      { "data": "emailVerified" },
      { "data": "permissions" },
      { "data": "loginAndRegister" }
    ],
  });
  function viewLoginAndRegister(data){
    const info = data.split("|");
    $(".modal-title").text("'.$lang['user_login_and_register_information'].'");
    $(".modal-body").html(\'<dl class="row"><dt class="col-5">'.$lang['login_ip_address'].'</dt><dd class="col-7">\'+info[0]+\'</dd><dt class="col-5">'.$lang['login_date_time'].'</dt><dd class="col-7">\'+info[1]+\'</dd><dt class="col-5">'.$lang['register_ip_address'].'</dt><dd class="col-7">\'+info[2]+\'</dd><dt class="col-5">'.$lang['register_date_time'].'</dt><dd class="col-7">\'+info[3]+\'</dd></dl>\');
    $("#modal-simple").modal("toggle");
  }
  function viewPermissions(data){
    let permissionHtml = "";
    $(".modal-title").text("'.$lang['user_permissions'].'");

    permissionHtml += \'<ul class="list-unstyled">\';

    const permissionsArray = data.split(", ");

    const formattedItems = permissionsArray.map(permission => {
        // Replace underscores with <span> elements
        return permission.replace(
          /_(.+?)_|(?!<[^<]*>)([^< ]+)(?![^<]*>)/g,
          match => match.startsWith("_") ?
            `<span class="text-green fw-bolder">${match.slice(1, -1)}</span>` :
            `<span class="text-red">${match}</span>`
        );
    });

    formattedItems.forEach(item => {
        permissionHtml += `<li>${item}</li>`;
    });

    permissionHtml += \'</ul>\';

    $(".modal-body").html(permissionHtml);

    $("#modal-simple").modal("toggle");
  }

  
  </script>';
  if(isset($member['permissions']) && $permissions->check($member['permissions'], "UPDATE_USER_PERMISSIONS")){

    $jsonPermissions = [];
  
    foreach ($permissions->getPermissions() as $permissionLine) {
      $permissionId = $permissionLine['id'];
      $permissionTitle = $permissionLine['title'];
      
      // Add permission data to the array
      $jsonPermissions[] = [
        'id' => $permissionId,
        'title' => $permissionTitle
      ];
    }
    
    // (Optional) Encode the array to JSON for safer JavaScript usage
    $permissionsJson = json_encode($jsonPermissions);

    $tabScript .= '
    <div class="modal modal-blur fade userPermissionsModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">'.$lang['user_permissions'].'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"><div class="divide-y userPermissions">n/a</div></div>
          <div class="modal-footer"><button class="btn btn-md btn-green btnUpdatePermissions">'.$lang['update'].'</button></div>
        </div>
      </div>
    </div>
    <script>
    let latestUser = "";
    function editPermissions(user, permissions){
      let permissionsHtml = "";

      latestUser = user;

      const memberPermissions = permissions.join(",");
      const serverPermissions = '.$permissionsJson.';
      serverPermissions.forEach(permissionLine => {

        const permissionId = permissionLine.id;
        const permissionTitle = permissionLine.title;
        const isChecked = memberPermissions.includes(permissionId);
        
        const labelClass = isChecked ? "text-green" : "text-red";
        const checkboxClass = isChecked ? "checked" : "";

        permissionsHtml += `
          <div>
            <label class="row">
              <span class="col ${labelClass}">${permissionTitle}</span>
              <span class="col-auto"><label class="form-check form-check-single form-switch"><input class="form-check-input" type="checkbox" permission="${permissionId}" ${checkboxClass}></label></span>
            </label>
          </div>
        `;
      });

      $(".userPermissions").html(permissionsHtml);
      $(".userPermissionsModal").modal("toggle");
    }

    $(".btnUpdatePermissions").click((e) => {
      e.preventDefault();

      let tempPermissions = [];

      $( ".userPermissions div label span label input" ).each(function( index ) {
        tempPermissions.push($(this).attr("permission"));
      });

      const memberPermissions = tempPermissions.join(",");

      $.ajax({
        url: "'.$config['api'].'Paste",
        type: "POST",
        dataType: "json",
        data: {
          user: latestUser,
          permissions: memberPermissions,
          action: "update_permissions"
        },
        success: function(response){
          alert(response.title, response.message, response.status);
          response.redirect && setTimeout(() => { window.location.href = response.redirect; }, 2000);
        }
      });
    });
    </script>';
  }
}else{
  $tabContent .= $lang['you_have_no_permission'];
}

$content = '<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="row g-0">
        <div class="container px-2">
          <div class="row">
            <div class="col-3 d-none d-md-block border-end px-0">
              <div class="card-body p-4">
                <!-- <h4 class="subheader">Business settings</h4> -->
                <div class="list-group list-group-transparent" id="tab-list">'.$tabLink.'</div>
              </div>
            </div>
            <div class="col d-flex flex-column"><div class="tab-content">'.$tabContent.'</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';

$script = '<script>
  document.addEventListener("DOMContentLoaded", function () {
  const tabList = document.getElementById("tab-list");
  const tabs = tabList.querySelectorAll(".list-group-item");
  const tabPanes = document.querySelectorAll(".tab-pane");
  tabs.forEach(tab => {
    tab.addEventListener("click", function (e) {
        e.preventDefault();

        // Remove active class from all tabs
        tabs.forEach(tab => tab.classList.remove("active"));

        // Add active class to the clicked tab
        tab.classList.add("active");

        // Get the target tab pane id
        const targetTab = tab.getAttribute("data-tab");

        // Hide all tab panes
        tabPanes.forEach(pane => pane.classList.remove("active"));

        // Show the target tab pane
        document.getElementById(targetTab).classList.add("active");
    });
  });
});
</script>';

echo $part['metaHead'].$part['metaTags'].$header.$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$script.$tabScript.$part['end'];
?>