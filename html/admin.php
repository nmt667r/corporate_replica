<?php
     //設定読込
    require_once './conf/const.php';
    //関数読込
    require_once './model/admin_model.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    require_once './model/header.php';
    
    session_start();
    
    //ログインチェック
    not_login_check();
    
    //関数の初期化
    $err_msg = [];
    $comp = [];
    $dbh = [];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    
    try{    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //商品追加
            if($_POST['post_type'] ==='add'){
                    $err_msg = add_error_check();
                    if(count($err_msg) === 0){
                        add_item_server($dbh);    
                    }
            //在庫数変更    
            }else if($_POST['post_type'] ==='stock_change'){
                $err_msg = change_error_check();
                if(count($err_msg) === 0){
                    change_stock_server($dbh);  
                }
            //公開ステータス変更        
            }else if($_POST['post_type'] ==='status_change'){
                change_status_server($dbh);
            //商品削除    
            }else if($_POST['post_type'] ==='delete_item'){
                delete_item_server($dbh);
            //カテゴリ変更
            }else if($_POST['post_type'] ==='category_change'){
                change_category_server($dbh);
            //コメント変更
            }else if($_POST['post_type'] ==='comment_fix1' || $_POST['post_type'] === 'comment_fix2'){
                $err_msg = error_comment_check();
                if(count($err_msg) === 0){
                   fix_comment_server($dbh);  
                }
            }
        }
    }catch (Exception $e) {
        $err_msg[] = $e->getMessage();
    }
    
    //DB読み込み
    $deta = read_db($dbh);
    
    //更新完了メッセージ取得
    $comp = comp_mes($err_msg);
    
    $header = get_header();
    $category_all = get_all_category();
    //viewページ読み込み
    include_once './view/admin_view.php';
?>