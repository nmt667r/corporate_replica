<?php
/**ログイン処理*/
  function login_check($dbh){
    $err_msg = [];
    try{
                  $sql = 'SELECT * from ec_user where user_name = ?';
                  $stmt = $dbh -> prepare($sql);
                  $stmt ->bindValue(1,$_POST['user_name'], PDO::PARAM_INT);
                  $stmt ->execute();
                  $deta = $stmt->fetch();
                  if($deta !== FALSE ){
                    if($deta['password'] !== $_POST['password']){
                      $err_msg[] ='名前またはパスワードが間違っています';
                    }
                  }else{
                    $err_msg[] ='名前またはパスワードが間違っています'; 
                  }
        }catch(PDOException $e){
            throw $e;
        }
        return $err_msg;
  }



















/**クッキーチェック*/
    function cookie_check(){
        if ( !isset($_COOKIE['visit_count']) ) {
          setcookie('visit_count', 1, time() + 43200);
        }
        else {
          $count = $_COOKIE['visit_count'] + 1;
          setcookie('visit_count', $count, time() + 43200);
        }
    }