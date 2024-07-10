<?php

include 'partial.php';

$content = '<div class="container-tight py-4">
  <div class="empty">
    <div class="empty-header">'.$lang['404'].'</div>
    <p class="empty-title">'.$lang['404_title'].'</p>
    <p class="empty-subtitle text-muted">'.$lang['404_description'].'</p>
    <div class="empty-action"><a href="'.$config['url'].'" class="btn btn-primary">'.$lang['take_me_home'].'</a></div>
  </div>
</div>';

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$part['end'];
?>