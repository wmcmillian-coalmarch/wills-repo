<?php

function getPhp($version = '') {
    $output = [];
    exec('which php' . $version, $output);

    return array_shift($output);
}

echo getPhp();