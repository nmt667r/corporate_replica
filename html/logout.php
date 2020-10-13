<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    //require_once './model/
    
    //関数の初期化
    $err_msg = [];
    $dbh = [];
    
    //ログアウト処理
        session_start();
        $session_name = session_name();
        $_SESSION = array();
        if (isset($_COOKIE[$session_name])) {
          $params = session_get_cookie_params();
          setcookie($session_name, '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
          );
        }
        session_destroy();
    //viewページ読み込み
    header('Location: login.php');
    exit;
?>