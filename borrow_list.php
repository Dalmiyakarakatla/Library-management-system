<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Borrow List</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>📥 Borrow List</h2>
  <table>
    <tr><th>ID</th><th>Member</th><th>Book</th><th>Borrow Date</th></tr>
    <?php
    $res = $conn->query("SELECT t.id, m.name, b.title, t.borrow_date 
                         FROM transactions t 
                         JOIN members m ON t.member_id=m.id 
                         JOIN books b ON t.book_id=b.id 
                         WHERE t.return_date IS NULL");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['title']}</td>
          <td>{$row['borrow_date']}</td>
        </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
