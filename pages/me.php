<?php

if(empty($_SESSION['session'])){ include('error.php'); exit; }

include 'partial.php';

$header = '<link rel="stylesheet" src="'.$config['assets'].'libs/datatable/dataTables.bootstrap5.css" />';

$tabLink = "";
$tabContent = "";
$tabScript = "";

if(isset($member['permissions']) && $permissions->check($member['permissions'], "ADMIN") || $permissions->check($member['permissions'], "LIST_PASTES")){
  $tabLink .= '<a href="#" class="list-group-item list-group-item-action d-flex align-items-center active" data-tab="pastes">'.$lang['pastes'].'</a>';
  $tabContent .= '<div class="tab-pane active p-2" id="pastes">
    <h2 class="mb-2">'.$lang['pastes'].'</h2>
    <div class="row table-responsive">
      <table class="table table-responsive pastesTable" style="width:100%">
        <thead>
            <tr>
                <th>'.$lang['id'].'</th>
                <th>'.$lang['title'].'</th>
                <th>'.$lang['visibility'].'</th>
                <th>'.$lang['status'].'</th>
                <th>'.$lang['created'].': '.$lang['by'].' / '.$lang['ip_address'].' / '.$lang['date_time_with_dash'].'</th>
                <th>'.$lang['modified'].': '.$lang['by'].' / '.$lang['ip_address'].' / '.$lang['date_time_with_dash'].'</th>
                <th>'.$lang['action'].'</th>
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
  $tabScript .= '<script src="'.$config['assets'].'libs/datatable/dataTables.js"></script>
  <script src="'.$config['assets'].'libs/datatable/dataTables.bootstrap5.js"></script>
  <script>$.fn.dataTable.ext.errMode = "none";
  $(".pastesTable").dataTable({
    ajax: {
      url: "'.$config['api'].'Paste",
      type: "POST",
      data: {
        action: "list",
      },
    },
    "columns": [
      { "data": "id" },
      { "data": "title" },
      { "data": "visibility" },
      { "data": "status" },
      { "data": "created" },
      { "data": "modified" },
      { "data": "action" }
    ],
  });
  function deletePaste(id, title){
    swal.fire({
      title: "Are you sure?",
      text: "'.$lang['delete_paste_id_title_with_variable_question_mark'].'".replace("_id_", id).replace("_title_", title),
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#2DB243",
      cancelButtonColor: "#d33",
      confirmButtonText: "'.$lang['yes_delete'].'",
      cancelButtonText: "'.$lang['no_cancel'].'",
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "'.$config['api'].'Paste",
          type: "POST",
          dataType: "json",
          data: {
            id: id,
            action: "delete"      
          },
          success: function(response){
            alert(response.title, response.message, response.status);
            if(response.status==1) $(".pastesTable").DataTable().ajax.reload();
          }
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        alert("'.$lang['attention'].'", "'.$lang['paste_deletion_canceled'].'", 3);
      }
    });
  }
  </script>';
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
                      <div class="list-group list-group-transparent" id="tab-list">
                          '.$tabLink.'
                          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-tab="password">'.$lang['password'].'</a>
                          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-tab="connected-apps">Connected Apps</a>
                          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-tab="plans">Plans</a>
                          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" data-tab="billing-invoices">Billing & Invoices</a>
                      </div>
                      
                  </div>
              </div>
              <div class="col d-flex flex-column">
                  <div class="tab-content">
                      '.$tabContent.'
                      <div class="tab-pane p-2" id="password">
                        <h2 class="mb-2">'.$lang['update_password'].'</h2>
                        <div class="row g-3">
                          <div class="col-md">
                            <div class="form-label">'.$lang['password_last'].'</div>
                            <input type="password" class="form-control passwordLast" placeholder="'.$lang['password_last'].'">
                          </div>
                          <div class="col-md">
                            <div class="form-label">'.$lang['password_new'].'</div>
                            <input type="password" class="form-control passwordNew" placeholder="'.$lang['password_new'].'">
                          </div>
                          <div class="col-md">
                            <div class="form-label">'.$lang['password_re_new'].'</div>
                            <input type="password" class="form-control passwordRenew" placeholder="'.$lang['password_re_new'].'">
                          </div>
                        </div>
                        <div class="btn-list justify-content-end mt-3">
                          <a href="#" class="btn">'.$lang['cancel'].'</a>
                          <a href="#" class="btn btn-primary btnResetPassword">'.$lang['update'].'</a>
                        </div>
                      </div>
                      <div class="tab-pane" id="connected-apps">
                          <!-- Connected Apps Content -->
                      </div>
                      <div class="tab-pane" id="plans">
                          <!-- Plans Content -->
                      </div>
                      <div class="tab-pane" id="billing-invoices">
                          <!-- Billing & Invoices Content -->
                      </div>
                      <div class="tab-pane" id="feedback">
                          Give Feedback Content
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';

$script = '<script>
  /* Tabs */
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
/* Reset Password */
$(".btnResetPassword").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Account",
    type: "POST",
    dataType: "json",
    data: {
      passwordLast: $(".passwordLast").val(),
      passwordNew: $(".passwordNew").val(),
      passwordRenew: $(".passwordRenew").val(),
      action: "updatePassword"      
    },
    success: function(response){
      alert(response.title, response.message, response.status);
      if(response.status==1) $("input").val("");
      response.redirect && setTimeout(() => { window.location.href = response.redirect; }, 2000);
    }
  });
});
</script>';

echo $part['metaHead'].$part['metaTags'].$header.$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$script.$tabScript.$part['end'];
?>