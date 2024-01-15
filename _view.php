<?php
require_once("includes/init.php");
$tag = $_GET['tag'];
$result = $db->select_row('contents', 'tag', $tag);
$btn = '';
if ($result['editable'] == '1'||isset($_SESSION['admin'])) $btn = '<p class="center"><a class="btn waves-effect" href="' . $tag . '/edit" rel="nofollow"><i class="material-icons">edit</i> Edit</a></p>';
if ($result) {
    $title = '' . $result['tag'] . ' - '.SITE_NAME;
    showHeader($title, '<span class="right padding_fix"><a class="btn waves-effect" href="' . $tag . '/raw" target="_blank">Raw</a></span>');
    // found
    $content = htmlspecialchars_decode($result['content']);
    $code = $result['content'];
} else {
    showHeader('Not Found - '.SITE_NAME, '');
    // not found
    $code = '<span class="grey-text center">Not found!</span>';
}
    $paragraphs = preg_split('/\n+/', $code);
?>
<p>
    <?php
    foreach ($paragraphs as $p) {
        //$p = nl2br($p);
        $p = stringToLink($p);
        echo "<code>$p</code><br/>";
    }
    ?>
</p>
</div>
<?php
showFooter('<script src="/js/view.js"></script>', $btn);
