<?php
 //設定読込
    require_once './conf/const.php';
    //関数読込
    //require_once './model/user_list.php';
    require_once './model/get_db.php';
    require_once './model/session.php';
    require_once './model/header.php';
    
    session_start();
    
    //ログインチェック
    not_login_check();
    
    //関数の初期化
    $dbh = [];
    $date = [];
    
    //DBハンドル取得
    $dbh = get_db_connect();
    
    //DB読み込み
        try{
            $sql = 'SELECT * from ec_user';
            $stmt = $dbh -> prepare($sql);
            $stmt ->execute();
            $date = $stmt->fetchAll();
        }catch(PDOException $e){
            throw $e;
        }
        
    $header = get_header();
    //管理者チェック？

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ユーザーリスト</title>
        <link rel="stylesheet" href="./css/template.css">
        <link rel="stylesheet" href="./css/user_list.css">
    </head>
    <body>
        <header>
            <div class="center_area">
                <div class="flex">
                    <div class="logo"><img src="./css/logo0.png"></div>
                    <div class="right_box">
                        <div class="flex nav">
                            <p>
                                <form action="./logout.php">
                                    <input type="submit" value="ログアウト">
                                </form>
                            </p>
                            <?php foreach ($header as $read) { ?>
                                <p class="nav_jr"><a href="<?php print $read['link']?>" class="deco_delete"><?php print $read['name']?></a></p>
                            <?php } ?>
                        </div>
                        <div class="user_name"><p class="nav_jr"><?php echo $_SESSION['user_name']?>さん</p></div>
                    </div>    
                </div>
            </div>
        </header>
        <div class="center_area">
            <h1>登録ユーザーリスト</h1>
            <table>
                <tr>
                    <th class="user_about">ユーザー名</th>
                    <th class="reg_about">登録日時</th>
                </tr>
                <?php foreach ($date as $read){?>
                <tr>
                    <td><?php print h($read['user_name']); ?></td>
                    <td><?php print h($read['create_datetime']); ?></td>
                </tr>
            <?php }?>
            </table>
            <div class="admin"><a href="./admin.php">商品管理ページ</a></div>
        </div>
    </body>
</html>