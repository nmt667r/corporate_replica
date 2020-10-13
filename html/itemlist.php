<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/itemlist_model.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    require_once './model/header.php';
    
    session_start();
    
    //ログインチェック
    not_login_check();
    
    //関数の初期化
    $err_msg = [];
    $dbh = [];
    $deta = [];
    $comp = [];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    
    //カートに追加
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if($_POST['post_type'] === 'add_cart'){
            //個数入力値チェック
            $err_msg = amount_error_check($dbh);
            if(count($err_msg) === 0){
            //在庫数チェック・カート追加(トランザクション)
            add_cart_server($dbh);
            header('Location: cart.php');
            exit;
            }
        }
    }
    
    //DB読み込み
    $deta = read_item_db($dbh);
    $category_all = get_all_category();
    $header = get_header();
    //viewページ読み込み
    include_once './view/itemlist_view.php';
?>