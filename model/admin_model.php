<?php

/**DB読み込み*/
function read_db($dbh){
        try {
            $sql = 'SELECT ec_item_master.price,
                           ec_item_master.img,
                           ec_item_master.item_name,
                           ec_item_master.status,
                           ec_item_master.item_id,
                           ec_item_stock.stock,
                           ec_item_details.category,
                           ec_item_details.comment1,
                           ec_item_details.comment2
                    FROM   ec_item_master 
                           INNER JOIN ec_item_stock
                           ON ec_item_master.item_id = ec_item_stock.item_id
                           INNER JOIN ec_item_details
                           ON ec_item_master.item_id = ec_item_details.item_id';
            $stmt = $dbh -> prepare($sql);
            $stmt ->execute();
            $deta = $stmt->fetchAll();
            return $deta;
        } catch(PDOException $e){
            throw $e;
        }
    } 
    

 
/**更新完了メッセ*/
    function comp_mes($err_msg){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(count($err_msg) === 0){    
                if($_POST['post_type'] === 'stock_change' || $_POST['post_type'] === 'status_change' ||  $_POST['post_type'] === 'category_change' ||  $_POST['post_type'] === 'comment_fix1' ||  $_POST['post_type'] === 'comment_fix2'){
                    $comp = '更新が正常に行われました。';
                }else if($_POST['post_type'] === 'add') {
                    $comp = '商品の追加が完了しました。';    
                }else if($_POST['post_type'] === 'delete_item'){
                    $comp = '商品を削除しました。';
                }
                return $comp;
            }
            
        }
    } 
    
/**-----------------------------------------------------------------------書込*/

/**商品追加エラーチェック*/
     function add_error_check() {
        try {
            $err_msg = [];
            $num_check = '/^[0-9]+$/';
            $half_space = '/^[ ]+$/';
            $all_space  = '/^[　]+$/';
            if(isset($_POST['status']) === TRUE){
                if($_POST['status'] !== '1' && $_POST['status'] !== '0'){
                    $err_msg[] = 'ステータスの値が異常です。';
                }
            } 
            if(mb_strlen($_POST['item_name']) === 0){
                $err_msg[] = '名前を入力してください。';
            }
            if(preg_match($half_space, $_POST['item_name']) === 1 || preg_match($all_space, $_POST['item_name']) === 1){
                $err_msg[] = 'スペースのみの名前は使用出来ません。';
            }
            if(mb_strlen($_POST['price']) === 0){
                $err_msg[] = '値段を入力してください。';
            }else if(preg_match($num_check, $_POST['price']) === 0){
                $err_msg[] = '値段は正しい数値で入力してください。';
            }
            if(mb_strlen($_POST['stock']) === 0){
                $err_msg[] = '個数を入力してください。';
            }else if(preg_match($num_check, $_POST['stock']) === 0){
                $err_msg[] = '個数は正しい数値で入力してください。';
            }
            if(mb_strlen($_POST['comment1']) > 20 || mb_strlen($_POST['comment2']) > 20){
                    $err_msg[] = 'コメントは20文字以下にしてください。';
            }
            //投稿画像チェック
            if (is_uploaded_file($_FILES['img']['tmp_name']) === TRUE) {
                $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                if ($extension !== 'png' && $extension !== 'jpeg' && $extension !== 'PNG' && $extension !== 'JPEG' && $extension !== 'JPG' && $extension !== 'jpg') {
                  $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGもしくはPNGのみ利用可能です。';
                }
            } else {
                $err_msg[] = 'ファイルを選択してください。';
            }
            return $err_msg;   
        }catch (PDOException $e) {
            throw $e;    
        }
     }
    
/**DB商品追加書込*/
    function add_item_server($dbh){
         try{
            $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $img = sha1(uniqid(mt_rand(), true)). '.' . $extension;
            move_uploaded_file($_FILES['img']['tmp_name'], IMG_DIR . $img);
            
            $now_date = date("Y/m/d H:i:s");
            $dbh->beginTransaction(); 
            $sql = 'insert into ec_item_master(item_name,price,img,status,create_datetime,update_datetime) values(?,?,?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $stmt->bindValue(1,$_POST['item_name'],PDO::PARAM_STR);
            $stmt->bindValue(2,$_POST['price'],     PDO::PARAM_INT);
            $stmt->bindvalue(3,$img,      PDO::PARAM_STR);
            $stmt->bindvalue(4,$_POST['status'],    PDO::PARAM_INT);
            $stmt->bindvalue(5,$now_date,           PDO::PARAM_STR);
            $stmt->bindvalue(6,$now_date,           PDO::PARAM_STR);
            $stmt ->execute();
            
            $item_id = $dbh->lastInsertId('item_id');
            $sql = 'insert into ec_item_stock(item_id,stock,create_datetime,update_datetime) values(?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$item_id,      PDO::PARAM_INT);
            $stmt ->bindValue(2,$_POST['stock'],PDO::PARAM_INT);
            $stmt ->bindValue(3,$now_date,      PDO::PARAM_STR);
            $stmt ->bindValue(4,$now_date,      PDO::PARAM_STR);
            $stmt ->execute();
            
            $sql = 'insert into ec_item_details(item_id,category,comment1,comment2,update_datetime) values(?,?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$item_id,      PDO::PARAM_INT);
            $stmt ->bindValue(2,$_POST['category'],PDO::PARAM_STR);
            $stmt ->bindValue(3,$_POST['comment1'],PDO::PARAM_STR);
            $stmt ->bindValue(4,$_POST['comment2'],PDO::PARAM_STR);
            $stmt ->bindValue(5,$now_date,      PDO::PARAM_STR);
            $stmt ->execute();
            
            
            $dbh->commit();
        }catch(PDOException $e) {
            $dbh->rollBack();                                             
            throw $e;
        }
    }
    
/**-------------------------------------------------------------------個数変更*/
    
/**在庫数変更エラーチェック*/    
    function change_error_check(){
        try{
            $err_msg = [];
            $num_check = '/^[0-9]+$/';
            if(mb_strlen($_POST['stock']) === 0){
                $err_msg[] = '個数を入力してください。';
            }else if(preg_match($num_check, $_POST['stock']) === 0){
                $err_msg[] = '個数は正しい数値で入力してください。';
            }
            return $err_msg;
        }catch (PDOException $e){
            throw $e;   
        }
    }
    
/**DB在庫数変更書込*/    
    function change_stock_server($dbh){
        try{
            $now_date = date("Y/m/d H:i:s");
            $dbh->beginTransaction();
            $sql = 'UPDATE ec_item_stock SET stock=?, update_datetime=? WHERE item_id=? ';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['stock'],    PDO::PARAM_INT);
            $stmt ->bindValue(2,$now_date,          PDO::PARAM_STR);
            $stmt ->bindValue(3,$_POST['item_id'], PDO::PARAM_INT);
            $stmt ->execute();
            $dbh->commit();
        }catch (PDOException $e){
            throw $e;   
        }
    }
/**------------------------------ステータス変更、削除、コメント更新、カテゴリ変更    
/**DBステータス変更*/
    function change_status_server($dbh){
        $now_date = date("Y/m/d H:i:s");
        if($_POST['status'] === "1"){
            $after_status = "0";
        }else{
            $after_status = "1";
        }
        $sql = 'UPDATE ec_item_master SET status=? ,update_datetime=? WHERE item_id=?';
        $stmt = $dbh -> prepare($sql);
        $stmt ->bindValue(1,$after_status, PDO::PARAM_INT);
        $stmt ->bindValue(2,$now_date,          PDO::PARAM_STR);
        $stmt ->bindValue(3,$_POST['item_id'], PDO::PARAM_INT);
        $stmt ->execute();
    }
    
/**DB削除*/
    function delete_item_server($dbh){
        $sql = 'DELETE FROM ec_item_master WHERE item_id = ? ';
        $stmt = $dbh -> prepare($sql);
        $stmt ->bindValue(1,$_POST['item_id'], PDO::PARAM_INT);
        $stmt ->execute();
        
        $sql = 'DELETE FROM ec_item_stock WHERE item_id = ? ';
        $stmt = $dbh -> prepare($sql);
        $stmt ->bindValue(1,$_POST['item_id'], PDO::PARAM_INT);
        $stmt ->execute();
    }
    
/**カテゴリ変更*/
    function change_category_server($dbh){
        $now_date = date("Y/m/d H:i:s");
        $sql = 'UPDATE ec_item_details SET category =? ,update_datetime=? WHERE item_id=?';
        $stmt = $dbh -> prepare($sql);
        $stmt ->bindValue(1,$_POST['category'], PDO::PARAM_INT);
        $stmt ->bindValue(2,$now_date,          PDO::PARAM_STR);
        $stmt ->bindValue(3,$_POST['item_id'], PDO::PARAM_INT);
        $stmt ->execute();
    }
    
/**コメント文字数チェック*/
    function error_comment_check(){
        $err_msg = [];
        if(isset($_POST['comment1']) === TRUE){
            if(!mb_strlen($_POST['comment1']) > 20){
                $err_msg[] = 'コメント数は20文字以内でお願いします。';
            }
        }else{
            if(!mb_strlen($_POST['comment2']) > 20){
                $err_msg[] = 'コメント数は20文字以内でお願いします。';
            }
        }
        return $err_msg;
    }

         
    
/**コメント更新*/
    function fix_comment_server($dbh){
        $now_date = date("Y/m/d H:i:s");
        if(isset($_POST['comment1']) === TRUE){
            $sql = 'UPDATE ec_item_details SET comment1 =? ,update_datetime=? WHERE item_id=?';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['comment1'], PDO::PARAM_INT);
            $stmt ->bindValue(2,$now_date,          PDO::PARAM_STR);
            $stmt ->bindValue(3,$_POST['item_id'], PDO::PARAM_INT);
            $stmt ->execute();
        }else{
            $sql = 'UPDATE ec_item_details SET comment2 =? ,update_datetime=? WHERE item_id=?';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['comment2'], PDO::PARAM_INT);
            $stmt ->bindValue(2,$now_date,          PDO::PARAM_STR);
            $stmt ->bindValue(3,$_POST['item_id'], PDO::PARAM_INT);
            $stmt ->execute();
        }
    }

?>