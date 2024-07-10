<?php

if(empty($_SESSION['session'])){ include('error.php'); exit; }

include 'partial.php';

$content = '<div class="page-body page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
            <h2 class="h2 text-center mb-4">'.$lang['forgot_password'].'</h2>
                <div class="mb-3">
                    <label class="form-label">'.$lang['email'].'</label>
                    <input type="email" class="form-control email" placeholder="'.$lang['email'].'" autocomplete="off">
                </div>
                <div class="form-footer"><button class="btn btn-primary w-100 btnForgot">Send Link</button></div>
            </div>
        </div>
        <div class="text-center text-muted mt-3">'.str_replace('%link%', $config['url'] . 'login', $lang['have_account_login_with_link_qm']).'</div>
    </div>
</div>';

$script = '<script>
$(".btnForgot").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Account",
    type: "POST",
    dataType: "json",
    data: {
      email: $(".email").val(),
      action: "forgot"
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