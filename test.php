<?php
echo "PHP is working!<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Files in root:<br>";
foreach (scandir(__DIR__) as $file) {
    echo $file . "<br>";
}