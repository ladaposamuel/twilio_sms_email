<?php
require __DIR__ . "/vendor/autoload.php";
require('functions.php');
$dotenv = Dotenv\Dotenv::create(__DIR__);

$dotenv->load();


//echo sendEmail('Test EMail', 'Hey Sam', 'flcnxxx@gmail.com');

print_r(processAndSendEmail('TO:flcnxxx@gmail.com+SUBJ:Hello+MSG:Im sending this email using SMS'));