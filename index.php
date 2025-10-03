<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Library Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h1>📚 Library Management Dashboard</h1>
  <div class="dashboard-cards">
    <?php
    $books    = $conn->query("SELECT COUNT(*) AS c FROM books")->fetch_assoc()['c'];
    $members  = $conn->query("SELECT COUNT(*) AS c FROM members")->fetch_assoc()['c'];
    $borrows  = $conn->query("SELECT COUNT(*) AS c FROM transactions WHERE return_date IS NULL")->fetch_assoc()['c'];
    $returns  = $conn->query("SELECT COUNT(*) AS c FROM transactions WHERE return_date IS NOT NULL")->fetch_assoc()['c'];
    ?>
    <div class="card">Books <small><?= $books ?></small></div>
    <div class="card">Members <small><?= $members ?></small></div>
    <div class="card">Borrowed <small><?= $borrows ?></small></div>
    <div class="card">Returned <small><?= $returns ?></small></div>
  </div>
</div>
</body>
</html>
