<?php
require_once('functions.php');
setToken(); // トークン情報を生成する
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>編集</title>
</head>
<body>
    <!-- セッションエラーがあった場合、メッセージを表示する -->
    <?php if (!empty($_SESSION['err'])) : ?>
        <p><?= $_SESSION['err']; ?></p>
    <?php endif; ?>
    <form action="store.php" method="post"> <!-- store.phpへ遷移する -->
        <!-- トークンをもたせる -->
        <input type="hidden" name="token" value="<?= escape($_SESSION['token']); ?>">
        <!-- hiddenでidをもたせる -->
        <input type="hidden" name="id" value="<?= escape($_GET['id']); ?>">
        <!-- 対象のidをfunctions.phpのgetSelectedTodo関数へ引数で渡す -->
        <input type="text" name="todo" value="<?= escape(getSelectedTodo($_GET['id'])); ?>">
        <input type="submit" value="更新">
    </form>
    <div>
        <a href="index.php">一覧へもどる</a>
    </div>
    <?php unsetError(); ?>
</body>
</html>
