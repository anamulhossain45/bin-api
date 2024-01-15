<?php
require_once('includes/init.php');
noExt();
showHeader('Help - '.SITE_NAME.'');
?>
<h4>Basic</h4>
<p><i class="material-icons">info</i> This is a free paste bin service. Which means you can save text contents here and share with anyone easily! Just type or paste anything and then save!</p>
<h4>Keyboard Shortcuts</h4>
<p><i class="material-icons">keyboard</i> <strong>Alt</strong> + <strong>n</strong> to create a new paste in a seperate window</p>
<p><i class="material-icons">keyboard</i> <strong>Alt</strong> + <strong>u</strong> to save the current paste if it has any text written</p>
<p><i class="material-icons">keyboard</i> <strong>Alt</strong> + <strong>r</strong> to open the currently viewing paste as raw in a seperate window</p>
<p><i class="material-icons">keyboard</i> <strong>Alt</strong> + <strong>w</strong> to edit the currently viewing paste as raw in a seperate window</p>
<?php
showFooter();
?>