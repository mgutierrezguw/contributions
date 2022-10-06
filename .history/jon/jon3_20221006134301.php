<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Examples</title>
</head>
<body>

<?php

$sql = 'SELECT * FROM `everflow_subscriptions` WHERE `timestamp_sent_to_everflow` IS NULL ORDER BY `Id` DESC';
$result = conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        if($debug){
            echo'<pre>';
            var_dump($row);
            echo'</pre>';
        }

    }
}
else {
    echo '0 results';
}

$sql = 'UPDATE everflow_subscriptions SET timestamp_sent_to_everflow = ? WHERE everflow_subscriptions.Id = ?';

if (false === ($stmt = $conn->prepare($query))) {
    echo 'error preparing statement: ' . $conn->error;
} elseif (!$stmt->bind_param('si', $var1_string, $var2_int)) {
    echo 'error binding params: ' . $stmt->error;
} elseif (!$stmt->execute()) {
    echo 'error executing statement: ' . $stmt->error;
}

$query = 'INSERT INTO `Contacts` (`email`) VALUES (?)';

if (false === ($stmt = $conn->prepare($query))) {
    echo 'error preparing statement: ' . $conn->error;
} elseif (!$stmt->bind_param('si', $var1_string, $var2_int)) {
    echo 'error binding params: ' . $stmt->error;
} elseif (!$stmt->execute()) {
    echo 'error executing statement: ' . $stmt->error;
}

?>
    
</body>
</html>