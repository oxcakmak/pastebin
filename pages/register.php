<?php

include 'partial.php';

if(isset($_SESSION['session'])){ header('location: '.$config['url']); exit; }

$content = '<div class="page-body page-center">
  <div class="container container-tight py-4">
    <div class="card card-md">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Register</h2>
          <div class="mb-3">
            <label class="form-label">'.$lang['username'].'</label>
            <input type="text" class="form-control username" placeholder="'.$lang['username'].'" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">'.$lang['email'].'</label>
            <input type="email" class="form-control email" placeholder="'.$lang['email'].'" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">'.$lang['password'].'</label>
            <input type="password" class="form-control password" placeholder="'.$lang['password'].'"  autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">'.$lang['password_re'].'</label>
            <input type="password" class="form-control repassword" placeholder="'.$lang['password_re'].'"  autocomplete="off">
          </div>
          <button class="btn btn-primary w-100 registerBtn">'.$lang['register'].'</button>
      </div>
    </div>
    <div class="text-center text-muted mt-2">'.str_replace('%link%', $config['url'] . 'login', $lang['have_account_login_with_link_qm']).'</div>
  </div>
</div>';

$script = '<script>
$(".registerBtn").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Account",
    type: "POST",
    dataType: "json",
    data: {
      username: $(".username").val(),
      email: $(".email").val(),
      password: $(".password").val(),
      repassword: $(".repassword").val(),
      action: "register"      
    },
    success: function(response){
      alert(response.title, response.message, response.status);
      if(response.status==1) $("input").val("");
      response.redirect && setTimeout(() => { window.location.href = response.redirect; }, 2000);
    }
  });
});
</script>';

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$script.$part['end'];
?>