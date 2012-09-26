<?php
header("Content-type: text/plain");

for ($i = 0; $i<10; $i++) {
    $hash = base64_encode(hash('sha256',mt_rand(),true));
    echo $hash . " " . strlen($hash) . "\n";
}
?>