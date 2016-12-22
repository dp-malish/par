<?php
class Mail{
    public static function confirmMail($mail,$id,$user_name,$pass,$key){//php5.kiev.ua/manual/ru/function.mail.html
        $site=$_SERVER['SERVER_NAME'];
        $subject='Подтверждение регистрации';
        $message='<html><head><title>Подтверждение регистрации</title></head><body><h3>Подтверждение регистрации!</h3>
        <p>Мы рады приветствовать Вас '.$user_name.' на нашем сайте <a href="'.Opt::PROTOCOL.$site.'/">'.$site.'</a></p>
        <p>Ваши данные для авторизации на сайте:</p>
        <table><tr><td>Логин</td><th>'.$mail.'</th></tr><tr><td>Пароль</td><th>'.$pass.'</th></tr></table>
        <p>Для подтверждения регистрации <a href="'.Opt::PROTOCOL.$site.'/?u='.$id.'&key='.$key.'&mailconfirm">перейдите по ссылке</a></p></body></html>';
        $headers='MIME-Version: 1.0'."\r\n";
        $headers.='Content-type: text/html; charset=utf-8'."\r\n";
        $headers.='From: '.$site.' <noreply@'.$site.'>'."\r\n";
        return mail($mail,$subject,$message,$headers);
    }
    public static function rememberPass($mail,$pass){
        $site=$_SERVER['SERVER_NAME'];
        $subject='Восстановление пароля';
        $message='<html><head><title>Восстановление пароля</title></head><body><h3>Восстановление пароля!</h3>
        <p>Для авторизации на сайте <a href="'.Opt::PROTOCOL.$site.'/">'.$site.'</a> необходимо ввести регистрационные данные.</p>
        <p>Ваши данные для авторизации на сайте:</p>
        <table><tr><td>Логин</td><th>'.$mail.'</th></tr><tr><td>Пароль</td><th>'.$pass.'</th></tr></table>        </body></html>';
        $headers='MIME-Version: 1.0'."\r\n";
        $headers.='Content-type: text/html; charset=utf-8'."\r\n";
        $headers.='From: '.$site.' <noreply@'.$site.'>'."\r\n";
        return mail($mail,$subject,$message,$headers);
    }
}