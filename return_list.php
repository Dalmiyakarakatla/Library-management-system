<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Return List</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>📤 Return List</h2>
  <table>
    <tr><th>ID</th><th>Member</th><th>Book</th><th>Borrow Date</th><th>Return Date</th></tr>
    <?php
    $res = $conn->query("SELECT t.id, m.name, b.title, t.borrow_date, t.return_date 
                         FROM transactions t 
                         JOIN members m ON t.member_id=m.id 
                         JOIN books b ON t.book_id=b.id 
                         WHERE t.return_date IS NOT NULL");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['title']}</td>
          <td>{$row['borrow_date']}</td>
          <td>{$row['return_date']}</td>
        </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
