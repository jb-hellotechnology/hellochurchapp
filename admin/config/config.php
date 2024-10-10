<?php
    switch($_SERVER['SERVER_NAME']) {

        case 'hcapp':
            include(__DIR__.'/config.hcapp.php');
            break;

        default:
            include('config.production.php');
            break;
    }

    define('PERCH_LICENSE_KEY', 'R3-LOCAL-LDT241-LAF010-BVR026');
    define('PERCH_EMAIL_FROM', 'jack@hellotechnology.co.uk');
    define('PERCH_EMAIL_FROM_NAME', 'Jack Barber');

    define('PERCH_LOGINPATH', '/admin');
    define('PERCH_PATH', str_replace(DIRECTORY_SEPARATOR.'config', '', __DIR__));
    define('PERCH_CORE', PERCH_PATH.DIRECTORY_SEPARATOR.'core');

    define('PERCH_RESFILEPATH', PERCH_PATH . DIRECTORY_SEPARATOR . 'resources');
    define('PERCH_RESPATH', PERCH_LOGINPATH . '/resources');
    
    define('PERCH_HTML5', true);
    define('PERCH_TZ', 'UTC');