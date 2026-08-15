<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タイトル</title>
    <link rel="stylesheet" href="cssを指定">
</head>

<body>

    <?php

    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=todo','root','taichi.0315');

    $title = $_POST['add'] ?? null ;

    if (isset($_POST['add'])) {
        if($title !== '') {
            $stmt = $pdo -> prepare("INSERT INTO tasks (title) VALUES (:title)") ;
            $stmt -> execute([':title' => $title]);
        }
        header('Location: ' .$_SERVER['PHP_SELF']);
            exit;
    }

    //$delete = $_POST['delete_id'] ?? null ;

    if (isset($_POST['delete_id'])) {
        $stmt = $pdo -> prepare("DELETE from tasks WHERE id = :id") ;
        $stmt -> execute([':id' => $_POST['delete_id']]) ;

        header('Location: ' .$_SERVER['PHP_SELF']);
        exit;
    }

    $new_title = $_POST['edit'] ?? null ;
    $edit_id = $_POST['edit_id'] ?? null ;

    if(isset($new_title)) {
        if($new_title !== '' && $edit_id !== '') {
            $stmt = $pdo -> prepare("UPDATE tasks SET title = :title WHERE id = :id") ;
            $stmt -> execute([':title' => $new_title , ':id' => $edit_id]);
        }
        header('Location: ' .$_SERVER['PHP_SELF']);
        exit;
    }

    $stmt = $pdo -> query("select * from tasks") ;
    $tasks = $stmt->fetchAll();

    ?>

    <form action="" method="post">
        <input type="text" name="add">
        <input type="submit" value="追加">
    </form>

    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <form action="" method="post">
                    <?php echo htmlspecialchars($task['title'],ENT_QUOTES,'UTF-8'); ?>
                    <input type="hidden" name="delete_id" value="<?php echo $task['id']; ?>">
                    <input type="submit" value="削除">
                </form>
                <form action="" method="post">
                    <input type="hidden" name="edit_id" value="<?php echo $task['id']; ?>">
                    <input type="text" name="edit">
                    <input type="submit" value="編集">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    
</body>