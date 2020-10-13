<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
        <link rel="stylesheet" href="html5reset-1.6.1.css">
        <link rel="stylesheet" href="./css/template.css">
        <style>.margin{margin:20px auto 0px auto;text-align:center;}.big{font-size:30px;}</style>
    </head>
    <body>
        <?php //if(isset($logout_msg) === TRUE){
            //echo $logout_msg;
        //}?>
        <header>
            <div class="center_area">
                <div class="flex">
                    <div class="logo"><img src="./css/logo.png"></div>
                    <div class="right_box">
                        <div class="flex nav">
                            <p>
                                <form action="./login.php">
                                    <input type="submit" value="ログイン">
                                </form>
                            </p>
                        </div>
                    </div>    
                </div>
            </div>
        </header>
        
        <main>
            <div class="center_area">
                <div class="margin big">ようこそ</div>
                <?php foreach ($err_msg as $read) { ?>
                    <p><?php print $read; ?></p>
                <?php } ?>
                <form method="POST">
                    <div class="margin"><input type="text" name="user_name">：ユーザー名</div>
                    <div class="margin"><input type="password" name="password">：パスワード</div>
                    <div class="margin"><input type="submit" value="ログイン"></div>
                    <input type="hidden" name="post_type" value="login">
                </form>
                <div class="margin"><a href="./register.php">新規登録はこちら</a></div>
            </div>
        </main>

    </body>
</html>

