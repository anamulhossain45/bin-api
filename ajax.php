<?php
require_once("includes/init.php");
noExt();
$content = trim(htmlspecialchars($_POST['content']));
$tag = trim(strtolower($_POST['tag']));
$editable = $_POST['editable'];
if (empty($editable)) $editable = '0';
if ($editable == 'true') {
    $editable = '1';
} else {
    $editable = '0';
}
$time = date('U');
$action = $_POST['type'];
if ($action == 'tag') {
    if (!empty($tag)) {
        if (strlen($tag) > 10 || strlen($tag) < 4) {
            echo 'tag_length';
        } elseif (!preg_match('/(^[0-9a-z]+)$/i', $tag)) {
            echo 'tag_invalid';
        }
    }
} elseif ($action == 'content') {
    if (empty($tag)) {
        $tag = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 1, 10);
    }
    if (strlen($tag) > 10 || strlen($tag) < 0) {
        echo 'tag_length';
    } elseif (!preg_match('/(^[0-9a-z]+)$/i', $tag)) {
        echo 'tag_invalid';
    } else {
        if ($db->select_row('contents', 'tag', $tag)) {
            echo 'tag_unavailable';
        } else {
            $column = array('tag', 'content', 'editable', 'time');
            $data = array($tag, $content, $editable, $time);
            if ($db->insert_row('contents', $column, $data)) {
                echo $tag;
            } else {
                echo 'save_fail';
            }
        }
    }
}
if ($action == 'update') {
    if (!empty($content)) {
        $check = $db->select_row('contents', 'tag', $tag);
        if ($check) {
            if ($check['editable'] == 1||isset($_SESSION['admin'])) {
                if ($content != $check['content']) {
                    $columns = array('content');
                    $data = array($content);
                    if ($db->update_row('contents', 'tag', $tag, $columns, $data)) {
                        // suceessfull
                        echo 'updated';
                    } else {
                        echo 'update_fail';
                    }
                } else {
                    echo 'same';
                }
            } else {
                echo 'not_editable';
            }
        } else {
            echo 'not_found';
        }
    }
}
