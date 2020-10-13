<?php

/**更新完了メッセ*/
    function comp_mes($err_msg){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(count($err_msg) === 0){    
                if($_POST['post_type'] === 'add_cart') {
                    $comp = '商品をカートに追加しました。';    
                }
                return $comp;
            }
            
        }
    }
    
/**ユーザーIDの取得*/
    function get_user_id($dbh){
        try{
                  $sql = 'SELECT * from ec_user where user_name = ?';
                  $stmt = $dbh -> prepare($sql);
                  $stmt ->bindValue(1,$_SESSION['user_name'], PDO::PARAM_INT);
                  $stmt ->execute();
                  $user_date = $stmt->fetch();
        }catch(PDOException $e){
            throw $e;
        }
        return $user_date;
    }

/**カートDB読み込み*/
function read_cart_db($dbh){
        try {
            $sql = 'SELECT ec_item_master.price,
                           ec_item_master.img,
                           ec_item_master.item_name,
                           ec_item_master.status,
                           ec_cart.item_id,
                           ec_cart.amount,
                           ec_cart.user_id,
                           ec_item_stock.stock
                    FROM   ec_item_master 
                           INNER JOIN ec_cart
                           ON ec_item_master.item_id = ec_cart.item_id
                           INNER JOIN ec_item_stock
                           ON ec_cart.item_id = ec_item_stock.item_id
                    WHERE  ec_item_master.status = 1 AND user_id = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt->bindValue(1,$_SESSION['user_id'],PDO::PARAM_STR);
            $stmt ->execute();
            $cart = $stmt->fetchAll();
            return $cart;
        } catch(PDOException $e){
            throw $e;
        }
    } 
    
/**カートの合計金額を取得*/
    function total_get_db($dbh,$cart){
        $subtotal = '';
        $total = '';
        foreach ($cart as $read) {
            $subtotal = $read['price'] * $read['amount'];
            $total = $total + $subtotal;
        }
        return $total;    
    }
    
/**アイテム個数エラーチェック*/
    function change_error_check(){
        try{
            $err_msg = [];
            $num_check = '/^[0-9]+$/';
            if(mb_strlen($_POST['amount']) === 0){
                $err_msg[] = '個数を入力してください。';
            }else if(preg_match($num_check, $_POST['amount']) === 0){
                $err_msg[] = '個数は正しい数値で入力してください。';
            }
            return $err_msg;
        }catch (PDOException $e){
            throw $e;   
        }
    }
    
/**カート内アイテム個数変更*/
    function change_amount_server($dbh){
        try{
            $now_date = date("Y/m/d H:i:s");
            $dbh->beginTransaction();
            $sql = 'UPDATE ec_cart SET amount=?, update_datetime=? where user_id = ? AND item_id =? ';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['amount'],      PDO::PARAM_INT);
            $stmt ->bindValue(2,$now_date,             PDO::PARAM_STR);
            $stmt ->bindValue(3,$_SESSION['user_id'],  PDO::PARAM_INT);
            $stmt ->bindValue(4,$_POST['item_id'],     PDO::PARAM_INT);
            $stmt ->execute();
            $dbh->commit();
        }catch (PDOException $e){
            throw $e;   
        }
    }
    
/**カート内アイテム削除*/
    function delete_cart_item_db($dbh){
        $sql = 'DELETE FROM ec_cart WHERE user_id = ? AND item_id =? ';
        $stmt = $dbh -> prepare($sql);
        $stmt ->bindValue(1,$_SESSION['user_id'],  PDO::PARAM_INT);
        $stmt ->bindValue(2,$_POST['item_id'],     PDO::PARAM_INT);
        $stmt ->execute();
    }
    
/**カート内購入確定処理*/
    function buy_cart_db($dbh,$cart,$total){
        $now_date = date("Y/m/d H:i:s");
        try{
            $dbh->beginTransaction();
            foreach ($cart as $read){
                $sql = 'SELECT * FROM ec_item_stock where item_id =? ';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$read['item_id'],      PDO::PARAM_INT);
                $stmt ->execute();
                $item = $stmt->fetch();
                
                $item_stock = $item['stock'] - $read['amount'];
                
                $sql = 'UPDATE ec_item_stock SET stock=?, update_datetime=? where item_id =? ';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$item_stock,      PDO::PARAM_INT);
                $stmt ->bindValue(2,$now_date,             PDO::PARAM_STR);
                $stmt ->bindValue(3,$read['item_id'],     PDO::PARAM_INT);
                $stmt ->execute();
                
                $sql = 'DELETE FROM ec_cart WHERE user_id = ? AND item_id =? ';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$_SESSION['user_id'],  PDO::PARAM_INT);
                $stmt ->bindValue(2,$read['item_id'],     PDO::PARAM_INT);
                $stmt ->execute();
                
                $sql = 'insert ec_history(user_id,item_id,amount,create_datetime) values(?,?,?,?)';
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,$_SESSION['user_id'],PDO::PARAM_INT);
                        $stmt ->bindValue(2,$read['item_id'],    PDO::PARAM_INT);
                        $stmt ->bindValue(3,$read['amount'],     PDO::PARAM_INT);
                        $stmt ->bindValue(4,$now_date,           PDO::PARAM_STR);
                        $stmt ->execute();
            }
            $dbh->commit();
        }catch (PDOException $e){
            throw $e;   
        }
        $result = array($total,$cart);
        return $result;
    }
 
/**在庫数チェック*/   
    function amount_error_check($dbh,$cart){
        $num_check = '/^[0-9]+$/';
        $err_msg = [];
        $deta = [];
        foreach ($cart as $read){
            try{
                $sql = 'SELECT * from ec_item_stock where item_id = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$read['item_id'], PDO::PARAM_INT);
                $stmt ->execute();
                $deta = $stmt->fetch();
            }catch(PDOException $e){
                throw $e;
            }
        
            if($deta['stock'] < $read['amount']){
                $err_msg[] = $deta['stock'];
                /**$sql = 'DELETE FROM ec_cart WHERE user_id = ? AND item_id =? ';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$_SESSION['user_id'],  PDO::PARAM_INT);
                $stmt ->bindValue(2,$read['item_id'],     PDO::PARAM_INT);
                $stmt ->execute();
                */
            }
        }
        return $err_msg; 
    }
    
    
/**購入履歴をDBに書込*/
    function write_history_db($dbh,$cart){
        
    }
?>


