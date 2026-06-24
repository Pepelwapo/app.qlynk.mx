<?php

function csrf_token()
{
    if(empty($_SESSION['_token']))
    {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_token'];
}

function csrf_validate()
{
    return isset($_POST['_token'])
        &&
        hash_equals(
            $_SESSION['_token'],
            $_POST['_token']
        );
}