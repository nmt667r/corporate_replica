<?php

/**ログイン済みの場合商品一覧に飛ばす(管理者は商品管理ページ)*/
    function already_login_check(){
        if(isset($_SESSION['user_name']) === TRUE){
        header('Location: itemlist.php');
        exit;
        }
    }

/**未ログインの場合にログイン画面に飛ばす*/
    function not_login_check(){
        if(isset($_SESSION['user_name']) !== TRUE){
        header('Location: login.php');
        exit;
        }
    }

/**ユーザー情報をセッション変数に格納*/
    function get_session($dbh){
        $_SESSION['user_name'] = $_POST['user_name'];
         try{
            $sql = 'SELECT * from ec_user where user_name = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['user_name'], PDO::PARAM_STR);
            $stmt ->execute();
            $user_date = $stmt->fetch();
        }catch(PDOException $e){
            throw $e;
        }
        $_SESSION['user_id']   = $user_date['user_id'];
    }
    
    
?>