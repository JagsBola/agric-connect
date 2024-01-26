<?php 

if (isset($flashSession['errors'])) {
    foreach ($flashSession['errors'] as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}

if(isset($flashSession['success'])) {
    echo "<div class='alert alert-success'>{$flashSession['success']}</div>";
}

?>