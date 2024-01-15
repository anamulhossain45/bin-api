<?php
require_once('includes/init.php');
noExt();
showHeader(SITE_NAME,'<span class="right padding_fix" id="save_btn"><button class="btn waves-effect" id="save" disabled>Save</button></span>');
?>
<div class="row"><div class="col s12">
<textarea name="content" id="content" class="materialize-textarea content" spellcheck="false" placeholder="Start typing here..."></textarea>
</div></div>
<input type="hidden" id="url">
<?php
showFooter('<script src="js/main.js"></script>','<div class="container"><p id="result"></p></div>
<div id="option"><div class="row container options valign-wrapper">
 <div class="col s8 input-field"><input type="text" id="tag"><label for="tag">Custom Tag (Optional)</label></div>
<div class="col s4"><p><label><input type="checkbox" id="editable" name="editable"/><span>Editable</span></label></p></div></div>
 </div>');
?>