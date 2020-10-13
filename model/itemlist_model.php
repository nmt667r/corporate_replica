<?php
    
/**絞り込みDB読み込み*/
function read_item_db($dbh){
        try {
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                if($_POST['post_type'] === 'list_change'){
                    //カテゴリ絞り込みのみ
                    if(isset($_POST['category']) === TRUE && isset($_POST['search']) !== TRUE){
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
                                       ON ec_item_master.item_id = ec_item_details.item_id
                                WHERE  ec_item_master.status = 1 AND ec_item_details.category = ?';
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,$_POST['category'], PDO::PARAM_INT);
                    //商品名検索のみ
                    }else if(isset($_POST['category']) !== TRUE && isset($_POST['search']) === TRUE){
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
                                       ON ec_item_master.item_id = ec_item_details.item_id
                                WHERE  ec_item_master.status = 1 AND  ec_item_master.item_name LIKE ? ';
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,'%'.$_POST['search'].'%', PDO::PARAM_INT);
                    //カテゴリ絞り込み＆商品名検索
                    }else if(isset($_POST['category']) === TRUE && isset($_POST['search']) === TRUE){
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
                                       ON ec_item_master.item_id = ec_item_details.item_id
                                WHERE  ec_item_master.status = 1 AND ec_item_details.category = ? AND  ec_item_master.item_name LIKE ?' ;
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,$_POST['category'], PDO::PARAM_STR);
                        $stmt ->bindValue(2,'%'.$_POST['search'].'%', PDO::PARAM_INT);
                    }
                }
            }else{
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
                                       ON ec_item_master.item_id = ec_item_details.item_id
                                WHERE  ec_item_master.status = 1';
                $stmt = $dbh -> prepare($sql);
            }
            $stmt ->execute();
            $deta = $stmt->fetchAll();
            return $deta;
        } catch(PDOException $e){
            throw $e;
        }
    }


/**個数入力値チェック*/
    function amount_error_check($dbh){
        $num_check = '/^[0-9]+$/';
        $err_msg = [];
        $deta = [];
        if(mb_strlen($_POST['amount']) !== 0){
            if($_POST['amount'] === 0 || preg_match($num_check,$_POST['amount']) === 0){
                $err_msg[] = '個数は正しい値を入力して下さい。';
            }
        }else{
            $err_msg[] = '個数を入力してくだい。';
        }
        try{
            $sql = 'SELECT * from ec_item_stock where item_id = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt ->bindValue(1,$_POST['item_id'], PDO::PARAM_INT);
            $stmt ->execute();
            $deta = $stmt->fetch();
        }catch(PDOException $e){
            throw $e;
        }
        if($deta['stock'] < $_POST['amount']){
            $err_msg[] = '申し訳有りませんが在庫が不足しています。';
        }
        return $err_msg; 
    }
    
/**カート追加(トランザクション)*/ 
    function add_cart_server($dbh){
        $now_date = date("Y/m/d H:i:s");
        $err_msg = [];
        $deta = [];
        $stock = [];
        $items = [];
            try{
                $sql = 'SELECT * from ec_cart where user_id = ? AND item_id =?';
                $stmt = $dbh -> prepare($sql);
                $stmt ->bindValue(1,$_SESSION['user_id'], PDO::PARAM_INT);
                $stmt ->bindValue(2,$_POST['item_id'], PDO::PARAM_INT);
                $stmt ->execute();
                $items = $stmt->fetchAll();
                if(count($items) === 0){
                    try{
                        //カートに新規追加
                        $dbh->beginTransaction();
                        $sql = 'insert ec_cart(user_id,item_id,amount,create_datetime,update_datetime) values(?,?,?,?,?)';
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,$_SESSION['user_id'],PDO::PARAM_INT);
                        $stmt ->bindValue(2,$_POST['item_id'],     PDO::PARAM_INT);
                        $stmt ->bindValue(3,$_POST['amount'],      PDO::PARAM_INT);
                        $stmt ->bindValue(4,$now_date,             PDO::PARAM_STR);
                        $stmt ->bindValue(5,$now_date,             PDO::PARAM_STR);
                        $stmt ->execute();
                        $dbh->commit();
                    }catch(PDOException $e){
                        throw $e;
                    }
                }else{
                    $item = $items[0];
                    $new_amount = $item['amount'] + $_POST['amount'];
                    try{
                        //重複物があった場合の個数更新(同一商品の注文統合化)
                        $dbh->beginTransaction();
                        $sql = 'UPDATE ec_cart SET amount=?, update_datetime=? where user_id = ? AND item_id =? ';
                        $stmt = $dbh -> prepare($sql);
                        $stmt ->bindValue(1,$new_amount,      PDO::PARAM_INT);
                        $stmt ->bindValue(2,$now_date,             PDO::PARAM_STR);
                        $stmt ->bindValue(3,$_SESSION['user_id'],PDO::PARAM_INT);
                        $stmt ->bindValue(4,$item['item_id'],     PDO::PARAM_INT);
                        $stmt ->execute();
                        $dbh->commit();
                    }catch(PDOException $e){
                        throw $e;
                    }
                }
            }catch(PDOException $e){
                throw $e;
            }
        }    
        
/**更新完了メッセ
    function comp_mes($err_msg){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(count($err_msg) === 0){    
                if($_POST['post_type'] === ''){
                    
                }
                return $comp;
            }
            
        }
        
DB読み込み
function read_public_db($dbh){
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
                           ON ec_item_master.item_id = ec_item_details.item_id
                    WHERE  ec_item_master.status = 1 ';

            $stmt = $dbh -> prepare($sql);
            $stmt ->execute();
            $deta = $stmt->fetchAll();
            return $deta;
        } catch(PDOException $e){
            throw $e;
        }
    }
    

    }*/
?>