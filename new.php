<?php
require_once('functions.php'); // 関数が定義されているファイルを読み込む
setToken(); // トークン情報を生成する
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規作成</title>
</head>
<body>
    <!-- セッションエラーがあった場合、メッセージを表示する -->
    <?php if (!empty($_SESSION['err'])) : ?>
        <p><?= $_SESSION['err']; ?></p>
    <?php endif; ?>
    <form action="store.php" method="post"> <!-- store.phpへPOSTする -->
        <!-- トークンをもたせる -->
        <input type="hidden" name="token" value="<?= escape($_SESSION['token']); ?>">
        <input type="text" name="todo">
        <input type="submit" value="作成">
    </form>
    <div>
        <a href="index.php">一覧へもどる</a>
    </div>
    <?php unsetError(); ?>
</body>
</html>
