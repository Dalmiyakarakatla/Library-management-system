<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Members</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <h2>👥 Members</h2>

  <form method="POST">
    <label>Name</label>
    <input type="text" name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <button type="submit" name="add">Add Member</button>
  </form>

  <?php
  if (isset($_POST['add'])) {
      $stmt = $conn->prepare("INSERT INTO members(name,email) VALUES (?,?)");
      $stmt->bind_param("ss", $_POST['name'], $_POST['email']);
      $stmt->execute();
      echo "<script>alert('✅ Member added');window.location='members.php';</script>";
  }

  if (isset($_GET['delete'])) {
      $id = intval($_GET['delete']);
      $check = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE member_id=?");
      $check->bind_param("i", $id);
      $check->execute();
      $check->bind_result($count);
      $check->fetch();
      $check->close();
      if ($count > 0) {
          echo "<script>alert('❌ Cannot delete this member. They have borrow history.');</script>";
      } else {
          $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
          $stmt->bind_param("i", $id);
          $stmt->execute();
          echo "<script>alert('✅ Member deleted');window.location='members.php';</script>";
      }
  }
  ?>

  <table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php
    $res = $conn->query("SELECT * FROM members");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['email']}</td>
          <td><a href='members.php?delete={$row['id']}' onclick='return confirm(\"Delete member?\")'>🗑 Delete</a></td>
        </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
