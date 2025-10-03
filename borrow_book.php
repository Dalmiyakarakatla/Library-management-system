<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Borrow Book</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>📥 Borrow Book</h2>

  <form method="POST">
    <label>Member</label>
    <select name="member_id" required>
      <option value="">Select Member</option>
      <?php
      $res = $conn->query("SELECT * FROM members");
      while ($m = $res->fetch_assoc()) {
          echo "<option value='{$m['id']}'>{$m['name']} ({$m['email']})</option>";
      }
      ?>
    </select>

    <label>Book</label>
    <select name="book_id" required>
      <option value="">Select Book</option>
      <?php
      $res = $conn->query("SELECT * FROM books WHERE copies>0");
      while ($b = $res->fetch_assoc()) {
          echo "<option value='{$b['id']}'>{$b['title']} by {$b['author']} ({$b['copies']} copies)</option>";
      }
      ?>
    </select>

    <button type="submit" name="borrow">Borrow</button>
  </form>

  <?php
  if (isset($_POST['borrow'])) {
      $stmt = $conn->prepare("INSERT INTO transactions(member_id, book_id, borrow_date) VALUES (?,?,NOW())");
      $stmt->bind_param("ii", $_POST['member_id'], $_POST['book_id']);
      $stmt->execute();

      $conn->query("UPDATE books SET copies=copies-1 WHERE id=" . intval($_POST['book_id']));
      echo "<script>alert('✅ Book borrowed');window.location='borrow_list.php';</script>";
  }
  ?>
</div>
</body>
</html>
