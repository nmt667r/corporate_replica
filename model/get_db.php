<?php
/**DBハンドルの取得*/
    function get_db_connect() {
        try {
        // データベースに接続
        $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
        throw $e;
        }
        
        return $dbh;
    }
    
/**エンティティ化*/
    function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
?>