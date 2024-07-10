<?php

include 'partial.php';

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
              <input type="text" class="form-control title" id="title" autocomplete="off">
              <label for="title">'.$lang['title'].'</label>
            </div>
            <div class="form-floating mb-1">
              <textarea class="form-control content" id="content" style="height: 400px;"></textarea>
              <label for="content">'.$lang['code'].'</label>
            </div>
            '.(isset($member['id']) ? '
            <fieldset class="form-fieldset">
              <label class="form-label">'.$lang['who_can_view'].'</label>
              <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                  <input type="radio" name="visibility" value="private" class="form-selectgroup-input">
                  <span class="form-selectgroup-label">'.$lang['only_me'].' ('.$lang['private'].')</span>
                </label>
                <label class="form-selectgroup-item">
                  <input type="radio" name="visibility" value="public" class="form-selectgroup-input">
                  <span class="form-selectgroup-label">'.$lang['everyone'].' ('.$lang['public'].')</span>
                </label>
              </div>
            </fieldset>' : '').'
          </div>
          <div class="card-footer py-2">
            <div class="btn-list justify-content-end">
              <button class="btn btn-lg btn-red btnClear">'.$lang['clear'].'</button>
              <button class="btn btn-lg btn-yellow btnCopy">'.$lang['copy'].'</button>
              <button class="btn btn-lg btn-green btnPaste">'.$lang['paste'].'</button>
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
$(".btnPaste").click(function(e){
  e.preventDefault();
  $.ajax({
    url: "'.$config['api'].'Paste",
    type: "POST",
    dataType: "json",
    data: {
      title: $(".title").val(),
      content: $(".content").val(),
      '.(isset($member['id']) ? 'visibility: $("input[name=\'visibility\']:checked").val(),':'').'
      action: "create"
    },
    success: function(response){
      alert(response.title, response.message, response.status);
      if(response.status==1) $("input").val("");
      response.redirect && setTimeout(() => { window.location.href = response.redirect; }, 2000);
    }
  });
});
</script>';

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$script.$part['end'];
?>