<?php

// 前後の空白を削除
    function trim_space($str) {
      return preg_replace('/\A[　\s]*|[　\s]*\z/u', '', $str);
    }

/**登録エラーチェック*/
    function reg_error_check($dbh){
        $err_msg = [];
        $match = "/^[a-zA-Z0-9]+$/";
        $name = trim_space($_POST['user_name']);
        
        //ユーザー名チェック
        if(count($name) !== 0){
            $count = duplication_check($dbh);
            if($count > 0){
                $err_msg[] = '既に登録されているユーザー名です。';
            }else if(mb_strlen($name) !== 0){
               if(preg_match($match,$name) === 0){
                    $err_msg[] = '名前は半角英数のみで入力して下さい。';
                }else if(mb_strlen($name) < 6 || mb_strlen($name) > 20){
                    $err_msg[] = '名前は6文字以上20文字以内にしてください。';
                } 
            }else{
                $err_msg[] = '名前を入力して下さい。';
            } 
        }
        
        //パスワードチェック
        if(mb_strlen($_POST['password']) !== 0){
            if(mb_strlen($_POST['password']) < 6 || mb_strlen($_POST['password']) > 20){
                $err_msg[] = 'パスワードは6文字以上20文字以内にしてください。';
            }
            if(preg_match($match,$_POST['password']) === 0){
                $err_msg[] = 'パスワードは半角英数のみで入力して下さい。';
            }
        }else{
            $err_msg[] = 'パスワードを入力してください。';
        }
        return $err_msg;
    }
    
/**DB名前重複チェック*/                                                       
    function duplication_check($dbh){
        $count = [];
        $name = trim_space($_POST['user_name']);
        try{
            $sql = 'SELECT COUNT(*) from ec_user where user_name = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$name, PDO::PARAM_INT);
            $stmt ->execute();
            $count = $stmt->fetchColumn();
            return $count;
        }catch(PDOException $e){
            throw $e;
        }
    }
    
/**DBユーザー登録*/
    function register_user_server($dbh){
        try{
            $now_date = date("Y/m/d H:i:s");
            $sql = 'insert into ec_user(user_name,password,create_datetime,update_datetime) values(?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $stmt->bindValue(1,$_POST['user_name'],PDO::PARAM_STR);
            $stmt->bindValue(2,$_POST['password'], PDO::PARAM_STR);
            $stmt->bindvalue(3,$now_date,          PDO::PARAM_STR);
            $stmt->bindvalue(4,$now_date,          PDO::PARAM_INT);
            $stmt ->execute();
        }catch(PDOException $e){
            throw $e;
        }
    }
?>