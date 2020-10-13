<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/cart_model.php';
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
    $comp =[];
    $total =[];
    $header = get_header();
    
    //DBハンドル取得
    $dbh = get_db_connect();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //購入確定
        if($_POST['post_type'] ==='buy'){
            $cart = read_cart_db($dbh);
            if(count($cart) !== 0){
                $total = total_get_db($dbh,$cart);
                $err_msg = amount_error_check($dbh,$cart);
                if(count($err_msg) === 0){
                    buy_cart_db($dbh,$cart,$total);
                    write_history_db($dbh,$cart);
                    include_once './view/finish_view.php';
                    exit;
                }
            }
        }
        //購入確認
        if($_POST['post_type'] ==='verification'){
            $cart = read_cart_db($dbh);
            $total = total_get_db($dbh,$cart);
            include_once './view/buy_view.php';
            exit;
        }
        //カート内容更新
        if($_POST['post_type'] ==='amount_change'){
            $err_msg = change_error_check();
            if(count($err_msg) === 0){
                change_amount_server($dbh); 
            }
        }else if($_POST['post_type'] ==='delete_item'){
            delete_cart_item_db($dbh);
        }
    }

    //カートDB読み込み
    $cart = read_cart_db($dbh);
    //カート合計金額の取得
    $total = total_get_db($dbh,$cart);
    //購入処理
    

    //viewページ読み込み
    include_once './view/cart_view.php';
?>