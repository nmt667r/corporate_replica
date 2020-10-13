<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/register_model.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    
    session_start();
    
    already_login_check();
    
    //関数の初期化
    $err_msg = [];
    $dbh = [];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    //ユーザー登録処理
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $err_msg = reg_error_check($dbh);
        if(count($err_msg) === 0){
            register_user_server($dbh);
            get_session($dbh);
            require_once './model/header.php';
            $header = get_header();
            include_once './view/register_finish.php';
            exit;
        }
    }

    //viewページ読み込み
    include_once './view/register_view.php';
?>