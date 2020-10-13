<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>新規登録</title>
        <link rel="stylesheet" href="html5reset-1.6.1.css">
        <link rel="stylesheet" href="./css/template.css">
        <style>.margin{margin:20px auto 0px auto;text-align:center;}.big{font-size:30px;}</style>
    </head>
    <body>
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
        <div class="center_area">
            <div class="margin big">ようこそ　会員登録をしましょう。</div>
            <?php foreach ($err_msg as $read) { ?>
                <div class="margin"><?php print $read; ?></div>
            <?php } ?>
            <form method="POST">
                <div class="margin"><input type="text" name="user_name">：ユーザー名(6文字以上20文字以内にしてください。)</div>
                <div class="margin"><input type="password" name="password">：パスワード(6文字以上20文字以内にしてください。)</div>
                <div class="margin"><input type="submit" value="登録"></div>
            </form>
        </div>
    </body>
</html>