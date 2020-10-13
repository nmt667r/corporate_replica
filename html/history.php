<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/history_model.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    require_once './model/header.php';
    
    session_start();
    
    //ログインチェック
    not_login_check();
    
    //関数の初期化
    $err_msg = [];
    $dbh = [];
    $user_date = [];
    $cart = [];
    $total =[];
    $header = get_header();
    $history =[];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    
    //カートDB読み込み
    $history = get_history_db($dbh);

    //viewページ読み込み
    include_once './view/history_view.php';
?>