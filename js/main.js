var button = document.getElementById("save");
var content = document.getElementById("content");
var tag = document.getElementById("tag");
var result = document.getElementById("result");
var editable = document.getElementById('editable');
content.addEventListener("keyup", function () {
    if (content.value.length > 0) {
        button.removeAttribute("disabled");
    } else {
        button.setAttribute("disabled", "disabled");
    }
});
content.addEventListener("input", function () {
    if (content.value.length > 0) {
        button.removeAttribute("disabled");
    }
});
tag.addEventListener("keyup", function () {
    if (tag.value.length > 0) {
        ajaxRequest('tag');
    }
});
tag.addEventListener("blur", function () {
    ajaxRequest('tag');
});
button.addEventListener("click", function () {
    if (content.value.length > 0) {
        ajaxRequest('content');
    }
});
function showResullt(result) {
    if (result == 'tag_length') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Invalid tag length! Tag must be between 4 and 10 character.</p>';
        if (content.value.length > 0) {
            button.removeAttribute("disabled");
        }
    } else if (result == 'tag_invalid') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Invalid tag! Tag can only contain alphabets and numbers.</p>';
        if (content.value.length > 0) {
            button.removeAttribute("disabled");
        }
    } else if (result == 'tag_unavailable') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> The tag is not available! Please type other tag or add something to your tag at the and or beginning.</p>';
        if (content.value.length > 0) {
            button.removeAttribute("disabled");
        }
    } else if (result == 'save_fail') {
        document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Failed to save! Server error. Please try again later.</p>';
        if (content.value.length > 0) {
            button.removeAttribute("disabled");
        }
    } else if (result) {
        const regex = /(^[0-9a-z]{4,10})$/i;
        if (regex.exec(result)) {
            document.getElementById("save_btn").innerHTML = "";
            document.getElementById("option").innerHTML = "";
            document.getElementById('result').innerHTML = '<p class="card-panel green-text green lighten-4"><i class="material-icons">error</i> Saved successfully!</p><p class="center"><button class="btn waves-effect" type="button" onclick="copyUrl()"><i class="material-icons">content_copy</i> Copy URL</button> <button class="btn waves-effect" type="button" onclick="goTo()"><i class="material-icons">description</i> View Content</button></p>';
            document.getElementById('logo').setAttribute("src", "/images/ms_full.png");
            document.getElementById('url').value = result;
        } else {
            document.getElementById('result').innerHTML = '<p class="card-panel red-text red lighten-4"><i class="material-icons">error</i> Server error! Please try again later.</p>';
            if (content.value.length > 0) {
                button.removeAttribute("disabled");
            }
        }
    } else {
        document.getElementById('result').innerHTML = '';
    }
}
window.addEventListener('load', function () {
    if (content.value.length > 0) {
        button.removeAttribute("disabled");
    }
});
function ajaxRequest(type) {
    var hr = new XMLHttpRequest();
    var url = "ajax";
    if (type == 'tag') {
        var vars = "type=tag&tag=" + tag.value;
    } else if (type == 'content') {
        var vars = "type=content&content=" + content.value + "&tag=" + tag.value + "&editable=" + editable.checked;
    }
    hr.open("POST", url, true);
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function () {
        if (hr.readyState == 4 && hr.status == 200) {
            var return_data = hr.responseText;
            showResullt(return_data);
        }
    }
    hr.send(vars);
    if (type == 'content') {
        document.getElementById('result').innerHTML = '<i class="material-icons spin">sync</i> Processing...';
    }
}
function copyUrl() {
    navigator.clipboard.writeText(window.location.href + document.getElementById("url").value);
    M.toast({ html: '<span><i class="material-icons green-text">check_circle</i> URL Copied!</span>' });
}
function goTo() {
    window.location.href = window.location.href + document.getElementById("url").value;
}
document.addEventListener('keydown', function(event) {
    if (event.altKey && event.key === 'u') {
        if (content.value.length > 0) {
            ajaxRequest('content');
        }
    }
});