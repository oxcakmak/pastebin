<?php

include 'partial.php';

$content = 'example page';

echo $part['metaHead'].$part['metaTags'].$part['metaHeadBody'].'<div class="page">'.$part['topBar'].(isset($_SESSION['session']) ? $part['subBar'] : '').'<div class="page-wrapper">'.$content.'</div></div>'.$part['script'].$part['end'];
?>