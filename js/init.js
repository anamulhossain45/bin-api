
window.addEventListener('load', function () {
    M.AutoInit();
});
function copyText(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    copyText.setSelectionRange(0, 0);
    M.toast({ html: '<span><i class="material-icons green-text">check_circle</i> URL Copied!</span>' });
}
document.addEventListener('keydown', function(event) {
    if (event.altKey && event.key === 'n') {
        window.open('/');
    }
});