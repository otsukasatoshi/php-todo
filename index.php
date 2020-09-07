<?php
// 関数が定義されているファイルを読み込む
require_once('functions.php');
// トークン情報を生成する
setToken();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <!-- セッションエラーがあった場合、メッセージを表示する -->
    <?php if (!empty($_SESSION['err'])) : ?>
        <p><?= $_SESSION['err']; ?></p>
    <?php endif; ?>
    <h1>welcome hello world</h1>
    <div>
        <a href="new.php">
            <p>新規作成</p>
        </a>
    </div>
    <div>
        <table>
            <tr>
                <th>ID</th>
                <th>内容</th>
                <th>更新</th>
                <th>削除</th>
            </tr>
            <!-- getTodoList()で一覧を取得してきてループ分で要素がある分だけ一つずつ取り出す。 -->
            <?php foreach (getTodoList() as $todo) : ?>
                <tr>
                    <td><?= escape($todo['id']); ?></td> <!-- <?php echo $todo['id']; ?> の省略形 -->
                    <td><?= escape($todo['todo']); ?></td>
                    <td>
                        <!-- GETで編集対象のidをedit.phpへ送る -->
                        <a href="edit.php?id=<?= escape($todo['id']); ?>">更新</a>
                    </td>
                    <td>
                        <form action="store.php" method="post"> <!-- store.phpへ遷移する -->
                            <!-- hiddenで持っている対象のtodoのid -->
                            <input type="hidden" name="id" value="<?= escape($todo['id']); ?>">
                            <!-- hiddenでトークンを持たせる -->
                            <input type="hidden" name="token" value="<?= escape($_SESSION['token']); ?>">
                            <button type="submit">削除</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php unsetError(); ?>
</body>
</html>
