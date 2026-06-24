<?php

if (!isset($_SESSION['user_id']))
{
    header('Location:/login');
    exit;
}

require __DIR__.'/../views/qrs.php';