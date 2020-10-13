<?php

/**ユーザーリスト取得*/
    function userlist_read($dbh){
        $deta = [];
        try{
            $sql = 'SELECT * from ec_user';
            $stmt = $dbh -> prepare($sql);
            $stmt ->execute();
            $date = $stmt->fetchAll();
            return $date;
        }catch(PDOException $e){
            throw $e;
        }
    }

?>