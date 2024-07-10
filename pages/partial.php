<?php

$part = array();

$part['metaHead'] = '<!doctype html><html><head>';
$part['metaTags'] = '<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link href="'.$config['assets'].'css/tabler.min.css" rel="stylesheet" />
<link href="'.$config['assets'].'css/tabler-vendors.min.css" rel="stylesheet" />
<link href="'.$config['assets'].'css/demo.min.css" rel="stylesheet" />
<link href="'.$config['assets'].'libs/sweetalert2-11.11.1/sweetalert2.min.css" rel="stylesheet" />
<link href="'.$config['assets'].'libs/mdi-7.4.47/css/materialdesignicons.min.css" rel="stylesheet" />';

$part['metaHeadBody'] = '</head><body>';

$part['topBar'] = '<header class="navbar navbar-expand-md d-print-none" >
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
        <a href="'.$config['url'].'"><img src="'.$config['assets'].'static/logo.svg" width="110" height="32" alt="Tabler" class="navbar-brand-image"></a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex" id="toggle-theme-btn">
                <a href="#" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                </a>
                <a href="#" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                </a>
            </div>
            
            '.(isset($_SESSION['session']) ? '<div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url(./assets/static/avatars/000m.jpg)"></span>
                <div class="d-none d-xl-block ps-2">
                    <div>'.$member['username'].'</div>
                    <div class="mt-1 small text-muted">'.$member['id'].'</div>
                </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="./profile.html" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="'.$config['url'].'me" class="dropdown-item">'.$lang['me'].'</a>
                    <a href="'.$config['url'].'logout" class="dropdown-item">'.$lang['logout'].'</a>
                </div>
            </div>': '<div class="nav-item d-none d-md-flex me-3">
                <div class="btn-list">
                    <a href="'.$config['url'].'login" class="btn">'.$lang['login'].'</a>
                    <a href="'.$config['url'].'register" class="btn">'.$lang['register'].'</a>
                </div>
            </div>').'
        </div>
    </div>
</header>';

$part['subBar'] = '<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="'.$config['url'].'" ><span class="nav-link-title">'.$lang['home'].'</span></a></li>
                    <!--
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" ><span class="nav-link-title">Interface</span></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="./accordion.html">Accordion</a>
                                    <a class="dropdown-item" href="./blank.html">Blank page</a>
                                    <a class="dropdown-item" href="./badges.html">Badges<span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span></a>
                                    <a class="dropdown-item" href="./buttons.html">Buttons</a>
                                    <div class="dropend">
                                        <a class="dropdown-item dropdown-toggle" href="#sidebar-cards" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >Cards<span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span></a>
                                        <div class="dropdown-menu">
                                            <a href="./cards.html" class="dropdown-item">Sample cards</a>
                                            <a href="./card-actions.html" class="dropdown-item">Card actions<span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span></a>
                                            <a href="./cards-masonry.html" class="dropdown-item">Cards Masonry</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false"><span class="nav-link-title">Help</span></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="https://tabler.io/docs" target="_blank" rel="noopener">Documentation</a>
                            <a class="dropdown-item" href="./changelog.html">Changelog</a>
                            <a class="dropdown-item" href="https://github.com/tabler/tabler" target="_blank" rel="noopener">Source code</a>
                            <a class="dropdown-item text-pink" href="https://github.com/sponsors/codecalm" target="_blank" rel="noopener">Sponsor project!</a>
                        </div>
                    </li>
                    -->
                    '.((isset($member['id']) && isset($member['permissions'])) && $permissions->check($member['permissions'], "ADMIN") ? '<li class="nav-item"><a class="nav-link" href="'.$config['url'].'admin" ><span class="nav-link-title">'.$lang['admin'].'</span></a></li>' : '').'
                    <li class="nav-item"><a class="nav-link" href="'.$config['url'].'me" ><span class="nav-link-title">'.$lang['me'].'</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="'.$config['url'].'logout" ><span class="nav-link-title">'.$lang['logout'].'</span></a></li>
                </ul>
                <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                    <form action="./" method="get" autocomplete="off" novalidate>
                        <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </span>
                        <input type="text" value="" class="form-control" placeholder="Search…" aria-label="Search in website">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>';

$part['script'] = '<script src="'.$config['assets'].'js/jquery-2.2.4.min.js"></script>
<script src="'.$config['assets'].'js/tabler.min.js"></script>
<script src="'.$config['assets'].'js/demo.min.js"></script>
<script src="'.$config['assets'].'js/demo-theme.js"></script>
<script src="'.$config['assets'].'libs/sweetalert2-11.11.1/sweetalert2.min.js"></script>
<script>
function alert(title, text, type){ 
	swal.fire({
		title: title,
		text: text,
		icon: ((type==0)?"error":((type==1)?"success":((type==2)?"warning":((type==3)?"info":"info")))),
		confirmButtonText: "'.$lang['ok'].'"
	});
}
</script>';

$part['end'] = '</body></html>';

?>