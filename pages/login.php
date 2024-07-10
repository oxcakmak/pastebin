<?php

include 'partial.php';

if(isset($_SESSION['session'])){ header('location: '.$config['url']); exit; }

$content = '<div class="page-body page-center">
  <div class="container container-tight py-2">
    <div class="card card-md">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">'.$lang['register'].'</h2>
          <div class="mb-3">
            <label class="form-label">'.$lang['username_or_email'].'</label>
            <input type="text" class="form-control user" placeholder="'.$lang['username_or_email'].'" autocomplete="off">
          </div>
          <div class="mb-0">
            <label class="form-label">
              '.$lang['password'].'
              <span class="form-label-description"><a href="'.$config['url'].'forgot">'.$lang['i_forgot_my_password'].'</a></span>
            </label>
            <input type="password" class="form-control password"  placeholder="'.$lang['password'].'" autocomplete="off">
          </div>
          <div class="form-footer"><button class="btn btn-primary w-100 btnLogin">'.$lang['login'].'</button></div>
      </div>
      <!--
      <div class="hr-text">or</div>
      <div class="card-body">
        <div class="row">
          <div class="col"><a href="#" class="btn w-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-github" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" /></svg>
              Login with Github
            </a></div>
          <div class="col"><a href="#" class="btn w-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-twitter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" /></svg>
              Login with Twitter
            </a></div>
        </div>
      </div>
      -->
    </div>
    <div class="text-center text-muted mt-3">'.str_replace('%link%', $config['url'] . 'register', $lang['dont_have_account_login_with_link_qm']).'</div>
  </div>
</div>';

$script = '<script>
$(".btnLogin").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Account",
    type: "POST",
    dataType: "json",
    data: {
      user: $(".user").val(),
      password: $(".password").val(),
      action: "login"      
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