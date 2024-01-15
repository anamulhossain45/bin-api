<?php
require_once("includes/init.php");
$tag = $_GET['tag'];
$result = $db->select_row('contents','tag',$tag);
if($result) {
    echo '<pre>'.htmlspecialchars(html_entity_decode($result['content'])).'</pre>';
} else {
echo 'The document is not found!';
} ?>