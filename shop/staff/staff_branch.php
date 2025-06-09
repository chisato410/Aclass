<?php
session_start();
session_regenerate_id(true);

if (empty($_SESSION['login'])) {
  echo 'ログインされていません。<br>';
  echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}

$staff_code = $_POST['staffcode'] ?? null;

// 表示ボタンが押されたとき
if (isset($_POST['disp'])) {
  if ($staff_code === null) {
    header('Location: staff_ng.php');
    exit();
  }
  header('Location: staff_disp.php?staffcode=' . rawurlencode($staff_code));
  exit();
}

// 追加ボタンが押されたとき
if (isset($_POST['add'])) {
  header('Location: staff_add.php');
  exit();
}

// 修正ボタンが押されたとき
if (isset($_POST['edit'])) {
  if ($staff_code === null) {
    header('Location: staff_ng.php');
    exit();
  }
  header('Location: staff_edit.php?staffcode=' . rawurlencode($staff_code));
  exit();
}

// 削除ボタンが押されたとき
if (isset($_POST['delete'])) {
  if ($staff_code === null) {
    header('Location: staff_ng.php');
    exit();
  }
  header('Location: staff_delete.php?staffcode=' . rawurlencode($staff_code));
  exit();
}
