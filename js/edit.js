function ajaxRequest() {
    var hr = new XMLHttpRequest();
    var url = "/ajax";
    var vars = "type=update&content=" + content.value + "&tag=" + tag.value;
    hr.open("POST", url, true);
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function () {
        if (hr.readyState == 4 && hr.status == 200) {
            var return_data = hr.responseText;
            showResullt(return_data);
        }
    }
    hr.send(vars);
    document.getElementById('result').innerHTML = '<i class="material-icons spin">sync</i> Updating...';
}
var button = document.getElementById("save");
var content = document.getElementById("content");
var tag = document.getElementById("tag");
var result = document.getElementById("result");
var original = document.getElementById("original");
button.addEventListener("click", function () {
    if (content.value.length > 0) {
        if (original.value != content.value) {
            ajaxRequest();
            button.setAttribute("disabled", "disabled");
        }
    }
});
content.addEventListener("keyup", function () {
    if (content.value.length > 0) {
        if (original.value != content.value) {
            button.removeAttribute("disabled");
        } else {
            button.setAttribute("disabled", "disabled");
        }
    }
});
content.addEventListener("input", function () {
    if (content.value.length > 0) {
        if (original.value != content.value) {
            button.removeAttribute("disabled");
        } else {
            button.setAttribute("disabled", "disabled");
        }
    }
});
function showResullt(result) {
    console.log(result);
    if (result == 'tag_unavailable') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> The tag is not available! Please type other tag or add something to your tag at the and or beginning.</p>';
        if (content.value.length > 0) {
            if (original.value != content.value) {
                button.removeAttribute("disabled");
            }
        }
    } else if (result == 'update_fail') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Failed to save! Server error. Please try again later.</p>';
        if (content.value.length > 0) {
            if (original.value != content.value) {
                button.removeAttribute("disabled");
            }
        }
    } else if (result == 'same') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> New content is same as old!</p>';
    } else if (result == 'not_found') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Content was not found!</p>';
    } else if (result == 'not_editable') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> This content is not editable!</p>';
    } else if (result == 'updated') {
        document.getElementById('result').innerHTML = '<p class="card-panel green-text green lighten-4"><i class="material-icons">error</i> Saved successfully!</p><p class="center"><button class="btn waves-effect" type="button" onclick="copyUrl()"><i class="material-icons">content_copy</i> Copy URL</button></p>';
    } else {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Server error! Please try again later.</p>';
        if (content.value.length > 0) {
            if (original.value != content.value) {
                button.removeAttribute("disabled");
            }
        }
    }
}
function copyUrl() {
    navigator.clipboard.writeText(window.location.href.replace("/edit", ""));
    M.toast({ html: '<span><i class="material-icons green-text">check_circle</i> URL Copied!</span>' });
}
window.addEventListener('load', function () {
    document.getElementById('logo').setAttribute("src", "/images/ms_full.png");
});