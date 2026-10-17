<?php

function dump(mixed $var){
    echo "<pre>" . $var . "</pre>";
}

dump(print_r($_GET, true));