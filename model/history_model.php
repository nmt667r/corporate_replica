<?php

/**購入履歴を取得*/
    function get_history_db($dbh){
         try{
                $sql = 'SELECT ec_item_master.price,
                               ec_item_master.img,
                               ec_item_master.item_name,
                               ec_item_master.status,
                               ec_item_master.item_id,
                               ec_history.amount,
                               ec_history.user_id,
                               ec_history.create_datetime,
                               ec_item_stock.stock
                        FROM   ec_item_master 
                               INNER JOIN ec_history
                               ON ec_item_master.item_id = ec_history.item_id
                               INNER JOIN ec_item_stock
                               ON ec_history.item_id = ec_item_stock.item_id
                        WHERE  user_id = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$_SESSION['user_id'], PDO::PARAM_INT);
                $stmt ->execute();
                $history = $stmt->fetchAll();
                return $history;
            }catch(PDOException $e){
                throw $e;
            }
    }


/**全購入履歴を取得*/
    function get_history_all($dbh){
         try{
                $sql = 'SELECT ec_item_master.price,
                               ec_item_master.img,
                               ec_item_master.item_name,
                               ec_item_master.status,
                               ec_item_master.item_id,
                               ec_history.amount,
                               ec_history.user_id,
                               ec_history.create_datetime,
                               ec_item_stock.stock,
                               ec_user.user_name
                        FROM   ec_item_master 
                               INNER JOIN ec_history
                               ON ec_item_master.item_id = ec_history.item_id
                               INNER JOIN ec_item_stock
                               ON ec_history.item_id = ec_item_stock.item_id
                               INNER JOIN ec_user
                               ON ec_history.user_id = ec_user.user_id';
                               
                               
                               
                               
                $stmt = $dbh -> prepare($sql);
                $stmt ->execute();
                $history = $stmt->fetchAll();
                return $history;
            }catch(PDOException $e){
                throw $e;
            }
    }

?>