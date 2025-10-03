<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Books</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>📖 Books</h2>

  <form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>
    <label>Author</label>
    <input type="text" name="author" required>
    <label>Copies</label>
    <input type="number" name="copies" min="1" required>
    <button type="submit" name="add">Add Book</button>
  </form>

  <?php
  if (isset($_POST['add'])) {
      $stmt = $conn->prepare("INSERT INTO books(title,author,copies) VALUES (?,?,?)");
      $stmt->bind_param("ssi", $_POST['title'], $_POST['author'], $_POST['copies']);
      $stmt->execute();
      echo "<script>alert('✅ Book added');window.location='books.php';</script>";
  }

  if (isset($_GET['delete'])) {
      $id = intval($_GET['delete']);
      // Safe delete: prevent if transactions exist
      $check = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE book_id=?");
      $check->bind_param("i", $id);
      $check->execute();
      $check->bind_result($count);
      $check->fetch();
      $check->close();
      if ($count > 0) {
          echo "<script>alert('❌ Cannot delete this book. It has borrow history.');</script>";
      } else {
          $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
          $stmt->bind_param("i", $id);
          $stmt->execute();
          echo "<script>alert('✅ Book deleted');window.location='books.php';</script>";
      }
  }
  ?>

  <table>
    <tr><th>ID</th><th>Title</th><th>Author</th><th>Copies</th><th>Action</th></tr>
    <?php
    $res = $conn->query("SELECT * FROM books");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['title']}</td>
          <td>{$row['author']}</td>
          <td>{$row['copies']}</td>
          <td><a href='books.php?delete={$row['id']}' onclick='return confirm(\"Delete book?\")'>🗑 Delete</a></td>
        </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
