<?php
require_once('../includes/init.php');
showHeader('Manage MS Bin | Admin CP - '.SITE_NAME);
admin();
// pagination
$p_n = $_GET['p'];
if(empty($p_n)) $p_n=1;
if(!is_numeric($p_n)||strlen($p_n)>4) {
    redirectTo('/');
    exit();
}
$start = ($p_n-1)*12;
$list = $db->select_rows('contents', array((int) $start, 12));
$total = $db->count_rows('contents');
?>
<ul class="collection with-header">
    <li class="collection-header"><h4>Pastes</h4></li>
    <li class="collection-item"><div><a href="/" class="btn waves-effect"><i class="material-icons">add</i> Add new</a> <a href="search.php" class="btn waves-effect"><i class="material-icons">search</i> Search</a></div></li>
    <?php
    if(empty($list)) {
      echo '</ul>';
      infoMessage('No urls found!');
    } else {
    foreach($list as $paste) echo '<li class="collection-item"><div><a href="/'.$paste['tag'].'" class=""><i class="material-icons">description</i> '.$paste['tag'].'</a> ('.date('d-m-Y g:i a', $paste['time']).')<span class="right"><a href="/'.$paste['tag'].'/edit" class=""><i class="material-icons">edit</i></a> <a href="javascript:void(0)" onclick="del_confirm('.$paste['id'].')"><i class="material-icons red-text">delete_forever</i></a></span></div></li>';
    }
    ?>
</ul>
<div id="delete" class="modal">
    <div class="modal-content">
      <h14>Delete Paste</h41>
      <p><i class="material-icons red-text">warning</i> Are you sure to delete this paste?</p>
    </div>
    <div class="modal-footer">
      <a href="javascript:void(0)" class="modal-close waves-effect btn-flat">No</a> <a href="javascript:void(0)" class="modal-close waves-effect btn" id="conf_btn">Yes</a>
    </div>
  </div>
<?php
if($total){
  echo '<ul class="pagination center">';
  $pages=pagination($total,$p_n,12);
  if(is_array($pages))
  {
  foreach($pages as $key => $val)
  {
  if($val == $p_n)
  {echo ' <li class="active"><a href="javascript:void(0)"> '.$key.' </a></li> ';}
  else{echo ' <li class="waves-effect"><a href="index.php?p='.$val.'"> '.$key.' </a></li> ';}
  }
  echo '</ul>';
  }
}
?>
<?php
$scr = '<script>
function del_confirm(id) {
    document.getElementById("conf_btn").setAttribute("href", "delete.php?id=" + id);
    var elems = document.getElementById("delete");
    var modal = M.Modal.init(elems);
    modal.open();
}
</script>';
showFooter($scr);
?>