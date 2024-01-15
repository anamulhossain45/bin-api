<?php

function noExt()
{
    $url = CURRENT_URL;
    $path = parse_url($url, PHP_URL_PATH);
    $filename = pathinfo($path, PATHINFO_BASENAME);
    if (preg_match("~\bindex\b~", $filename) || preg_match("/\./m", $filename)) {
        http_response_code(404);
        include '' . $_SERVER["DOCUMENT_ROOT"] . '/_error.php'; // provide your own HTML for the error page
        die();
    }
}
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// returns pagination links
function pagination($total, $current, $per_page)
{
    if ($total >= $per_page) {
        $totalpages = round($total / $per_page);
        //if($current>$totalpages) $current = 1;
        $totalpages2 = $total / $per_page;
        if ($totalpages2 > $totalpages) {
            $totalpages = $totalpages + 1;
        }

        //$pagesar['<i class="material-icons">skip_previous</i>'] = "1";
        if ($current > 1) {
            $cd = $current - 1;
            $pagesar['<i class="material-icons">navigate_before</i>'] = $cd;
            if ($cd == 1) {
                $st = 1;
            } else if ($cd == 2) {
                $st = $cd - 1;
            } else if ($cd >= 3) {
                $st = $cd - 2;
            }

            for ($i = $st; $i <= $cd; $i++) {
                $pagesar[$i] = $i;
            }
        }
        if ($totalpages > $current + 4) {
            $finalstop = $current + 4;
        } else {
            $finalstop = $totalpages;
        }

        for ($j = $current; $j <= $finalstop; $j++) {
            $pagesar[$j] = $j;
        }
        if ($totalpages > $current) {
            $pagesar['<i class="material-icons">navigate_next</i>'] = $current + 1;
        }

        //$pagesar['<i class="material-icons">skip_next</i>'] = $totalpages;
    } else {
        $pagesar['1'] = "1";
    }
    return $pagesar;
}

function successMessage($message, $closable = false)
{
    echo '<p id="success_msg"><div class="card-panel green lighten-4 msg">';
    if ($closable == true) {
        echo '<span onclick="this.parentElement.style.display=\'none\'">&times;</span>';
    }

    if (is_array($message)) {
        echo '<ul>';
        foreach ($message as $message) {
            if (!empty($message)) echo "<li>$message</li>";
        }
    } else {
        echo '<p><i class="material-icons">check_circle</i> ' . $message . '</p>';
    }
    echo '</div></p>';
}

function errorMessage($message, $closable = false)
{
    echo '<p id="error_msg"><div class="card-panel red lighten-4 msg">';
    if ($closable == true) {
        echo '<span onclick="this.parentElement.style.display=\'none\'">&times;</span>';
    }

    if (is_array($message)) {
        echo '<ul>';
        foreach ($message as $message) {
            if (!empty($message)) echo '<li><i class="material-icons">error</i> ' . $message . '</li>';
        }
    } else {
        echo '<p><i class="material-icons">error</i> ' . $message . '</p>';
    }
    echo '</div></p>';
}

function warningMessage($message, $closable = false)
{
    echo '<p id="warning_msg"><div class="card-panel yellow lighten-4 msg">';
    if ($closable == true) {
        echo '<span onclick="this.parentElement.style.display=\'none\'">&times;</span>';
    }

    if (is_array($message)) {
        echo '<ul>';
        foreach ($message as $message) {
            if (!empty($message)) echo "<li>$message</li>";
        }
    } else {
        echo '<p><i class="material-icons">warning</i> ' . $message . '</p>';
    }
    echo '</div></p>';
}

function infoMessage($message, $closable = false)
{
    echo '<p id="info_msg"><div class="card-panel blue lighten-4 msg">';
    if ($closable == true) {
        echo '<span onclick="this.parentElement.style.display=\'none\'">&times;</span>';
    }

    if (is_array($message)) {
        echo '<ul>';
        foreach ($message as $message) {
            if (!empty($message)) echo "<li>$message</li>";
        }
    } else {
        echo '<p><i class="material-icons">info</i> ' . $message . '</p>';
    }
    echo '</div></p>';
}

function admin()
{
    // process login
    $msg = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['username'] == ADMIN_USER && $_POST['password'] == ADMIN_PASS) {
            $_SESSION['admin'] = '1';
        } else {
            $msg = 'Username or password is wrong!';
        }
    }
    // check if logged in
    if (empty($_SESSION['admin'])) {
        // not logged in
?>
        <h4>Admin Login</h4>
        <form method="POST" action="">
            <?php if (!empty($msg)) echo '<p class="card-panel red-text red lighten-5"><i class="material-icons">error</i> ' . $msg . '</p>'; ?>
            <div class="input-field"><label for="username">Username</label><input type="text" name="username"></div>
            <div class="input-field"><label for="password">Password</label><input type="password" name="password"></div>
            <p class="center"><button type="submit" class="btn waves-effect">Login</button></p>
        </form>
<?php
        showFooter();
        exit();
    }
}
function stringToLink($string) {
 $reg_pattern = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
 return preg_replace($reg_pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $string);
}
function redirectTo($url, $type = 1, $seconds = 3)
{
    if ($type == 1) {
        header("Location: $url");
    } else {
        echo '<meta http-equiv="refresh" content="' . $seconds . '; url=' . $url . '"/>';
    }
}