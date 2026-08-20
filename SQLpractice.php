<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タイトル</title>
    <link rel="stylesheet" href="style.css">
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

    $check_id = $_POST['check_id'] ?? null ;

    if(isset($_POST['check_id'])) {
    $stmt = $pdo -> prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt -> execute([':id'  => $check_id]);
    $target = $stmt -> fetch();
    //var_dump($target);
    $is_done = $target["is_done"];
    //echo $is_done ;
        if($is_done == 0) {
            $stmt = $pdo -> prepare("UPDATE tasks SET is_done = 1 WHERE id = :id") ;
            $stmt -> execute([':id' => $check_id]) ;
            //return;
        } else {
            $stmt = $pdo -> prepare("UPDATE tasks SET is_done = 0 WHERE id = :id") ;
            $stmt -> execute([':id' => $check_id]) ;
            //return;
        }
        
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }


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
                <form class="form checkbox" action="" method="post">
                    <input type="hidden" name="check_id" value="<?php echo $task['id']; ?>">
                    <input type="checkbox" name="done" onchange="this.form.submit()" <?php echo $task['is_done']==1 ? 'checked' : '' ?>>
                </form>
                <form class="form title <?php echo $task['is_done']==1 ? 'completed' : 'orange' ?>" action="" method="post">
                    <?php echo htmlspecialchars($task['title'],ENT_QUOTES,'UTF-8'); ?>
                </form>
                <form class="form edit" action="" method="post">
                    <input type="hidden" name="edit_id" value="<?php echo $task['id']; ?>">
                    <input type="text" name="edit">
                    <input type="submit" value="編集">
                </form>
                <form class="form delete" action="" method="post">
                    <input type="hidden" name="delete_id" value="<?php echo $task['id']; ?>">
                    <input type="submit" value="削除">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>