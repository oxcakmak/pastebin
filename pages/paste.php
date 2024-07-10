<?php

if(($link['visibility'] === 'private') && isset($member['id']) !== $link['createdBy'] && !$permissions->check($member['permissions'], "ADMIN")){ include 'error.php'; exit; }

include 'partial.php';


if(isset($_GET['edit'])){

  $lastPastes = "";

  $db->where("visibility", "public")
  ->orderBy("id", "DESC ");
  $pastes = $db->get("pastes", 10);

  $db->disconnect();

  if($pastes){
    foreach( $pastes as $paste )
      $lastPastes .= '<div class="list-group-item"><div class="row"><div class="col text-truncate"><a href="'.$config['url'].$paste['id'].'" class="text-reset d-block">'.$paste['title'].'</a><div class="d-block text-secondary text-truncate mt-n1">By '.$paste['createdBy'].' (#'.$paste['id'].')</div></div></div></div>';
  }else{
    $lastPastes = '<div class="text-secondary p-2">'.$lang['no_paste_found'].'</div>';
  }

  $content = '<div class="page-body">
    <div class="container-xl">
      <div class="row row-cards">
        <div class="col-lg-9">
          <div class="card card-lg">
            <div class="card-body p-2">
              <div class="form-floating mb-3 mt-2">
                <input type="text" class="form-control title" id="title" autocomplete="off" value="'.$link['title'].'">
                <label for="title">'.$lang['title'].'</label>
              </div>
              <div class="form-floating mb-1">
                <textarea class="form-control content" id="content" style="height: 400px;">'.$link['content'].'</textarea>
                <label for="content">'.$lang['code'].'</label>
              </div>
              '.(isset($member['id']) ? '
              <fieldset class="form-fieldset">
                <label class="form-label">'.$lang['who_can_view'].'</label>
                <div class="form-selectgroup">
                  <label class="form-selectgroup-item">
                    <input type="radio" name="visibility" value="private" class="form-selectgroup-input"'.($link['visibility'] === 'private' ? ' checked':'').'>
                    <span class="form-selectgroup-label">'.$lang['only_me'].' ('.$lang['private'].')</span>
                  </label>
                  <label class="form-selectgroup-item">
                    <input type="radio" name="visibility" value="public" class="form-selectgroup-input"'.($link['visibility'] === 'public' ? ' checked':'').'>
                    <span class="form-selectgroup-label">'.$lang['everyone'].' ('.$lang['public'].')</span>
                  </label>
                </div>
              </fieldset>' : '').'
            </div>
            <div class="card-footer py-2">
              <div class="btn-list justify-content-end">
                <button class="btn btn-lg btn-red btnClear">'.$lang['clear'].'</button>
                <button class="btn btn-lg btn-yellow btnCopy">'.$lang['copy'].'</button>
                <button class="btn btn-lg btn-green btnUpdate">'.$lang['update'].'</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card">
            <div class="card-header"><h3 class="card-title">'.$lang['last_pastes'].'</h3></div>
            <div class="card-body p-0"><div class="list-group list-group-flush list-group-hoverable">'.$lastPastes.'</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>';

  $script = '<script>
  $(".btnUpdate").click(function(e){
    e.preventDefault();
    $.ajax({
      url: "'.$config['api'].'Paste",
      type: "POST",
      dataType: "json",
      data: {
        title: $(".title").val(),
        content: $(".content").val(),
        visibility: $("input[name=\'visibility\']:checked").val(),
        id: "'.$link['id'].'",
        action: "update"
      },
      success: function(response){
        alert(response.title, response.message, response.status);
        response.redirect && setTimeout(() => { window.location.href = response.redirect; }, 2000);
      }
    });
  });
  </script>';

}else{

$content = '<div class="page-body">
  <div class="container-xl">
    <div class="row row-cards">
      <div class="col-lg-8">
        <div class="card card-lg">
          <div class="card-body p-2">
            <div class="btn-list justify-content-end mb-2"><button class="btn btn-primary btnCopy" data-clipboard-target=".paste">'.$lang['copy'].'</button></div>
            <pre><code class="paste">'.htmlentities($link['content']).'</code></pre>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <dl class="row">
              <dt class="col-5">'.$lang['title'].'</dt>
              <dd class="col-7">'.$link['title'].'</dd>
              <dt class="col-5">'.$lang['created_by'].'</dt>
              <dd class="col-7">'.$link['createdBy'].'</dd>
              <dt class="col-5">'.$lang['created_at'].':</dt>
              <dd class="col-7">'.$link['createdDateTime'].'</dd>
              '.( (isset($member['id']) && isset($member['permissions'])) && $permissions->check($member['permissions'], "ADMIN") ? '<dt class="col-5">'.$lang['created_ip_address'].'</dt><dd class="col-7">'.$link['createdAddress'].'</dd><dt class="col-5">'.$lang['modified_by'].'</dt><dd class="col-7">'.$link['modifiedBy'].'</dd><dt class="col-5">'.$lang['modified_ip_address'].'</dt><dd class="col-7">'.$link['modifiedAddress'].'</dd><dt class="col-5">'.$lang['modified_at'].'</dt><dd class="col-7">'.$link['modifiedDateTime'].'</dd>' : '' ).'
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';

$script = '<script src="'.$config['assets'].'libs/clipboard-2.0.11/clipboard.min.js"></script>
<script>
const clipboard = new ClipboardJS(".btnCopy");

clipboard.on("success", function(e) {
    alert("'.$lang['success'].'", "'.$lang['copied'].'", "1");
    e.clearSelection();
});

clipboard.on("error", function(e) {
    console.error("Action:", e.action);
    console.error("Trigger:", e.trigger);
    alert("'.$lang['warning'].'", "'.$lang['copy_failed_to_clipboard'].'", "0");
});
</script>';

}

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$script.$part['end'];
?>