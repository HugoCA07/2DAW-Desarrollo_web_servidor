<?php

function dump(mixed $var): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}