<?php
// Check MySQLi extension
if (function_exists('mysqli_connect')) {
    echo "MySQLi extension is enabled.<br>";
} else {
    echo "MySQLi extension is NOT enabled.<br>";
}

// Check PDO MySQL extension
if (class_exists('PDO')) {
    echo "PDO MySQL extension is enabled.<br>";
} else {
    echo "PDO MySQL extension is NOT enabled.<br>";
}
?>

<?php
echo 'Current PHP Version: ' . phpversion();
?>
