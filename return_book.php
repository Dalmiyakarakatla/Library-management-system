<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Return Book</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>📤 Return Book</h2>

  <form method="POST">
    <label>Borrowed Book</label>
    <select name="tid" required>
      <option value="">Select Borrowed</option>
      <?php
      $res = $conn->query("SELECT t.id, m.name, b.title 
                           FROM transactions t 
                           JOIN members m ON t.member_id=m.id 
                           JOIN books b ON t.book_id=b.id 
                           WHERE t.return_date IS NULL");
      while ($r = $res->fetch_assoc()) {
          echo "<option value='{$r['id']}'>#{$r['id']} - {$r['name']} borrowed {$r['title']}</option>";
      }
      ?>
    </select>
    <button type="submit" name="return">Return</button>
  </form>

  <?php
  if (isset($_POST['return'])) {
      $tid = intval($_POST['tid']);
      $stmt = $conn->prepare("UPDATE transactions SET return_date=NOW() WHERE id=?");
      $stmt->bind_param("i", $tid);
      $stmt->execute();

      // increase book copies
      $res = $conn->query("SELECT book_id FROM transactions WHERE id=$tid");
      $book_id = $res->fetch_assoc()['book_id'];
      $conn->query("UPDATE books SET copies=copies+1 WHERE id=$book_id");

      echo "<script>alert('✅ Book returned');window.location='return_list.php';</script>";
  }
  ?>
</div>
</body>
</html>
