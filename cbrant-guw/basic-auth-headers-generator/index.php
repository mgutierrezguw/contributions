<?php

    // instantiate variables
    $base64 = "";

    // encode string if we have it
    if (array_key_exists('user', $_POST) && array_key_exists('pass', $_POST)) {
        $base64 = base64_encode($_POST['user'] . ':' . $_POST['pass']);
    }

?>

<h1>Basic Auth Header Generator</h1>

<?php echo ($base64 == "" ? "" : "<strong>Authorization: Basic " . $base64 . "</strong><br /><br />"); ?>

<form action="index.php" autocomplete="false" method="POST">
    <input name="user" type="text" placeholder="Username" value="" />
    <input name="pass" type="password" placeholder="Password" value="" />
    <input type="submit" value="Create Header" />
</form>