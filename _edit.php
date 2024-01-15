<?php
require_once("includes/init.php");
$tag = $_GET['tag'];
$btn = '';
$title = 'Not Found - '.SITE_NAME;
$result = $db->select_row('contents', 'tag', $tag);
if ($result) {
    $btn = '<p class="center"><a href="/'.$tag.'" class="btn waves-effect"><i class="material-icons">description</i> View</a> <a href="/'.$tag.'/raw" class="btn waves-effect"><i class="material-icons">insert_drive_file</i> View Raw</a></p>';
    if ($result['editable'] == 1||isset($_SESSION['admin'])) {
        $title = 'Edit ' . $result['tag'] . ' - '.SITE_NAME;
        showHeader($title, '<span class="right padding_fix"><button class="btn waves-effect waves-light" name="save" id="save" disabled="disabled">Save</button></span>');
        // found
        $content = htmlspecialchars_decode($result['content']);
        $code = $result['content'];
?>
        <textarea name="content" id="content" class="materialize-textarea content" spellcheck="false" placeholder="Start typing here..."><?= $content; ?></textarea>
        <input type="hidden" id="tag" value="<?= $tag; ?>">
        <input type="hidden" id="original" value="<?= $content; ?>">
        <p id="result"></p>
<?php
    } else {
        $btn = '<p class="center"><a href="/'.$tag.'" class="btn waves-effect"><i class="material-icons">description</i> View</a> <a href="/'.$tag.'/raw" class="btn waves-effect"><i class="material-icons">insert_drive_file</i> View Raw</a></p>';
        showHeader($title);
        echo '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> This content is not editable.</p>';
    }
} else {
    showHeader($title);
    echo '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> This content is not available.</p>';
}
showFooter('<script src="/js/edit.js"></script>', $btn);
?>