<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/login_model.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    require_once './model/header.php';
    
    session_start();
    
    //ログインチェック
     already_login_check();
     
    //関数の初期化
    $err_msg = [];
    $dbh = [];
    $comp = [];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    //ログイン処理
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if($_POST['post_type'] ==='login'){
            if($_POST['user_name'] === 'admin' && $_POST['password'] === 'admin'){
                get_session($dbh);
                header('Location: admin.php');
                exit;    
            }else{
                $err_msg = login_check($dbh);
                if(count($err_msg) === 0){
                    //セッション関数を取得
                    get_session($dbh);
                    header('Location: itemlist.php');
                    exit;
                }
            }
        }
    }
    
    //viewページ読み込み
    include_once './view/login_view.php';
?>