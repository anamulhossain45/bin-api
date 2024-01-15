<?php
require_once('../includes/init.php');
$q = $_REQUEST['q'];
// pagination
$p_n = $_GET['p'];
if(empty($p_n)) $p_n=1;
if(!is_numeric($p_n)||strlen($p_n)>4) {
    redirectTo('/');
    exit();
}
$start = ($p_n-1)*12;
$scr = '';
showHeader('Search Paste | Admin CP - '.SITE_NAME);
admin();
if (!empty($q)) {
    $pastes = $db->search_rows('contents', 'tag', $q, array((int) $start, 12));
    $total = $db->count_search_rows('contents','tag',$q);
?>
            <h4>Search Results</h4>
        <?php
        if (empty($pastes)) {
            infoMessage('Nothing found for <b>' . $q . '</b>!');
        } else {
            infoMessage(''.$total.' paste found for <b>' . $q . '</b>!');
            echo '<ul class="collection with-header">';
            foreach ($pastes as $paste) echo '<li class="collection-item"><div><a href="/'.$paste['tag'].'" class=""><i class="material-icons">description</i> '.$paste['tag'].'</a><span class="right">a href="/'.$paste['tag'].'/edit" class=""><i class="material-icons">edit</i></a> <a href="javascript:void(0)" onclick="del_confirm('.$paste['id'].')"><i class="material-icons red-text">delete_forever</i></a></span></div></li>';
            echo '</ul>';
        }
        ?>
        <div id="delete" class="modal">
            <div class="modal-content">
                <h4>Delete Paste</h4>
                <p><i class="material-icons red-text">warning</i> Are you sure to delete this paste?</p>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="modal-close waves-effect btn-flat">No</a> <a href="javascript:void(0)" class="modal-close waves-effect btn" id="conf_btn">Yes</a>
            </div>
        </div>
    <?php
if($total>12){
    echo '<ul class="pagination center">';
    $pages=pagination($total,$p_n,12);
    if(is_array($pages))
    {
    foreach($pages as $key => $val)
    {
    if($val == $p_n)
    {echo ' <li class="active"><a href="javascript:void(0)"> '.$key.' </a></li> ';}
    else{echo ' <li class="waves-effect"><a href="search.php?q='.$q.'&p='.$val.'"> '.$key.' </a></li> ';}
    }
    echo '</ul>';
    }
}
echo '<p class="center"><a href="search.php" class="btn waves-effect"><i class="material-icons">search</i> Search Again</a> <a href="javascript:void(0)" onclick="history.back()" class="btn waves-effect"><i class="material-icons">arrow_back</i> Go Back</a></p>';
    $scr = '<script>
function del_confirm(id) {
    document.getElementById("conf_btn").setAttribute("href", "delete.php?id=" + id);
    var elems = document.getElementById("delete");
    var modal = M.Modal.init(elems);
    modal.open();
}
</script>';
} else {
    ?>
        <h4>Search</h4>
        <form method="get" action="search.php">
            <div class="input-field"><input type="text" name="q" id="q" required><label for="q">Enter any keyword</label></div>
            <div class="center"><button class="btn waves-effect" type="submit"><i class="material-icons">search</i> Search</button> <a href="javascript:void(0)" onclick="history.back()" class="btn waves-effect"><i class="material-icons">arrow_back</i> Back</a></div>
        </form>
    <?php
}
showFooter($scr);
 ?>