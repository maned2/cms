<?php
/**
 * Created by
 * User: Маром
 * Date: 10.01.15
 * Time: 11:34
 */

defined('_YRNEXEC') or die;

//функция проверки е-маил
function checkEmail($str)
{
    return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}

//функция отправки е-маил
function send_mail($from,$to,$subject,$body, $ishtml=false)
{
    global $config;
    // Для отправки HTML-письма должен быть установлен заголовок Content-type
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    if($ishtml) $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= "From: $from\n";
    $headers .= "Reply-to: $from\n";
    $headers .= "Return-Path: $from\n";
    $headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
    $headers .= "Date: " . date('r', time()) . "\n";

    if ($ishtml) {
        $body = '
        <html>
            <head>
                <title>'.$subject.'</title>
                <!-- Bootstrap -->
                <link href="'.YRNHTTP_HOSTFULL.'templates/css/bootstrap.min.css" rel="stylesheet">
                <link href="'.YRNHTTP_HOSTFULL.'templates/css/bootstrap-theme.min.css" rel="stylesheet">
                <link href="'.YRNHTTP_HOSTFULL.'templates/css/theme.css" rel="stylesheet">
            </head>
            <body><div class="container">
            <h1>'.$config->sitename.'</h1>
            '.$body;
        $body .= '
            </div></body>
        </html>
        ';
    }

    mail($to,$subject,$body,$headers);
    //TODO: реализовать через phpmailer
}// end send_mail

