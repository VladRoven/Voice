<?php
# UVM
# Component
# Tools

function endsWith($str, $needle)
{
    $length = strlen($needle);
    if ($length == 0)
        return true;
    return (substr($str, -$length) === $needle);
}

# UVM
?>