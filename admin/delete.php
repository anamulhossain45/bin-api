<?php
require_once('../includes/init.php');
showHeader('Delete Paste | Admin CP - '.SITE_NAME);
admin();
$id = trim($_REQUEST['id']);
echo '<h4>Delete Paste</h4>';
if(empty($id)) {
    errorMessage('ID Not found!');
}  else {
    $check = $db->select_row('contents','id',$id);
    if(!$check) {
        errorMessage('Paste Not found!');
    } else {
        if($db->delete_row('contents','id',$id)) {
            successMessage('Paste deleted!');
        } else {
            errorMessage('Error deleting the Paste!');
        }
    }
}
?>
<p class="center"><a href="index.php" class="btn waves-effect"><i class="material-icons">arrow_back</i> Back</a></p>
<?php
showFooter();
?>