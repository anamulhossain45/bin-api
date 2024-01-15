<?php
function showHeader($title, $btn='', $des = '', $kw = '')
{
    header("Link: </css/materialize.css; rel=preload; as=style");
    header("Link: </css/fix.css; rel=preload; as=style");
    header("Link: </css/material-icons.css; rel=preload; as=style");
    header("Link: </js/init.js; rel=preload; as=script");
    header("Link: </js/materialize.min.js; rel=preload; as=script");
    header("Link: </images/logo.svg; rel=preload; as=image");
    header("Link: </images/preloader.svg; rel=preload; as=image");
    echo '<!DOCTYPE html>
    <html lang="en-US">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="/css/materialize.css" />
    <link rel="stylesheet" href="/css/fix.css" />
    <link rel="stylesheet" href="/css/material-icons.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>' . $title . '</title>
    <meta name="robots" content="index, follow">
    <meta name="title" content="">
    <meta name="descrption" content="">
    <meta name="keywords" content="">
    ';
    if (!empty($extra)) echo $extra;
    echo '<link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
    <link rel="manifest" href="/images/icons/site.webmanifest">
    <link rel="mask-icon" href="/images/icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <body>
        <header>
        <div class="navbar-fixed">
            <nav>
                <div class="nav-wrapper">
                  <a href="/" class="brand-logo"><img src="/images/ms_empty.png" class="logo" id="logo"/></a>
                  '.$btn.'
                  
                </div>
            </nav>
        </div>';
    echo '</header>
      <main class="container">';
}
function showFooter($js = '',$btn='')
{
    echo '</main>
'.$btn.'
  <footer class="page-footer no-padding">
  <p class="center"><i class="material-icons">info</i> Total '.$GLOBALS['db']->count_rows('contents').'+ Contents Saved!</p>
  <p class="center">
  <a href="/api" class="btn waves-effect"><i class="material-icons">code</i> API</a> <a href="/help" class="btn waves-effect"><i class="material-icons">help</i> HELP</a>
  </p>
         <div class="footer-copyright center">
            <p class="container">
                <strong>© ' . date('Y') . ' '.SITE_NAME.'</strong>. All Rights Are Reserved.
            </p>
        </div>
    </footer>
    <script type="text/javascript" src="/js/materialize.min.js"></script>
    <script type="text/javascript" src="/js/init.js"></script>
    ' . $js . '
</body>
</html>';
}
