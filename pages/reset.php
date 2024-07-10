<?php

include 'partial.php';

if(isset($_SESSION['session'])){ header('location: '.$config['url']); exit; }

$content = '<div class="page-body page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Reset Password</h2>
                <div class="mb-2">
                    <label class="form-label">'.$lang['verification_key'].'</label>
                    <input type="text" class="form-control key" placeholder="'.$lang['verification_key'].'" autocomplete="off" readonly disabled'.(isset($address[1]) && $address[1] ? ' value="'.$helper->Strings->sanitizeOutput($address[1]).'"' : '').'>
                </div>
                <div class="mb-2">
                    <label class="form-label">'.$lang['password_new'].'</label>
                    <input type="password" class="form-control password"  placeholder="'.$lang['password_new'].'" autocomplete="off">
                </div>
                <div class="mb-2">
                    <label class="form-label">'.$lang['password_re_again'].'</label>
                    <input type="password" class="form-control repassword" placeholder="'.$lang['password_re_again'].'" autocomplete="off">
                </div>
                <div class="form-footer"><button class="btn btn-primary w-100 btnResetPassword">'.$lang['reset_password'].'</button></div>
            </div>
        </div>
        <div class="text-center text-muted mt-2">'.str_replace('%link%', $config['url'] . 'login', $lang['have_account_login_with_link_qm']).'</div>
    </div>
</div>';

$script = '<script>
$(".btnResetPassword").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Account",
    type: "POST",
    dataType: "json",
    data: {
      key: $(".key").val() || "'.(isset($address[1]) && $address[1] ? $helper->Strings->sanitizeOutput($address[1]) : '').'",
      password: $(".password").val(),
      repassword: $(".repassword").val(),
      action: "reset"      
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