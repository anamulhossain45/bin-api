window.addEventListener('load', function () {
    document.getElementById('logo').setAttribute("src", "/images/ms_full.png");
});
document.addEventListener('keydown', function(event) {
    if (event.altKey && event.key === 'r') {
        var tag = window.location.pathname.split('/')[1];
        window.open('/' + tag + '/raw');
    }
});
document.addEventListener('keydown', function(event) {
    if (event.altKey && event.key === 'w') {
        var tag = window.location.pathname.split('/')[1];
        window.open('/' + tag + '/edit');
    }
});