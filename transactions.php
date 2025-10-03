<?php
// transactions.php
include 'db.php';
include 'navbar.php';

$msgText = '';
$msgClass = '';

// BORROW
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'borrow') {
    $book_id = (int)$_POST['book_id'];
    $member_id = (int)$_POST['member_id'];
    $borrow_date = date('Y-m-d');

    // check availability: copies - currently borrowed
    $stmt = $conn->prepare("
      SELECT (b.quantity - IFNULL(t.count_borrowed,0)) AS available
      FROM books b
      LEFT JOIN (
        SELECT book_id, COUNT(*) AS count_borrowed FROM transactions WHERE return_date IS NULL GROUP BY book_id
      ) t ON t.book_id = b.id
      WHERE b.id = ?
    ");
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res || (int)$res['available'] <= 0) {
        $msgText = "❌ Book not available.";
        $msgClass = 'error';
    } else {
        $ins = $conn->prepare("INSERT INTO transactions (book_id, member_id, borrow_date) VALUES (?, ?, ?)");
        $ins->bind_param('iis', $book_id, $member_id, $borrow_date);
        if ($ins->execute()) {
            $ins->close();
            header("Location: transactions.php?msg=borrowed");
            exit;
        } else {
            $msgText = "Error: " . $conn->error;
            $msgClass = 'error';
            $ins->close();
        }
    }
}

// RETURN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'return') {
    $tid = (int)$_POST['transaction_id'];
    $return_date = date('Y-m-d');
    $up = $conn->prepare("UPDATE transactions SET return_date = ? WHERE id = ?");
    $up->bind_param('si', $return_date, $tid);
    if ($up->execute()) {
        $up->close();
        header("Location: transactions.php?msg=returned");
        exit;
    } else {
        $msgText = "Error: " . $conn->error;
        $msgClass = 'error';
        $up->close();
    }
}

// fetch dropdown data
$books = $conn->query("SELECT * FROM books ORDER BY title");
$members = $conn->query("SELECT * FROM members ORDER BY name");

// fetch transactions (all)
$allTx = $conn->query("
  SELECT t.id, b.title AS book, m.name AS member, t.borrow_date, t.return_date
  FROM transactions t
  JOIN books b ON t.book_id = b.id
  JOIN members m ON t.member_id = m.id
  ORDER BY t.id DESC
");

// fetch only currently borrowed for return dropdown
$borrowed = $conn->query("
  SELECT t.id, b.title AS book, m.name AS member, t.borrow_date
  FROM transactions t
  JOIN books b ON t.book_id = b.id
  JOIN members m ON t.member_id = m.id
  WHERE t.return_date IS NULL
  ORDER BY t.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Borrow / Return</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Borrow Book</h2>

  <?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'borrowed') echo "<div class='message success'>✅ Borrow recorded.</div>"; ?>
    <?php if ($_GET['msg'] === 'returned') echo "<div class='message success'>✅ Book returned.</div>"; ?>
  <?php endif; ?>
  <?php if ($msgText) echo "<div class='message {$msgClass}'>" . htmlspecialchars($msgText) . "</div>"; ?>

  <form method="post">
    <input type="hidden" name="action" value="borrow">
    <label>Book</label>
    <select name="book_id" required>
      <option value="">-- select book --</option>
      <?php while($b = $books->fetch_assoc()): ?>
        <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['title'])." (".$b['quantity'].")"; ?></option>
      <?php endwhile; ?>
    </select>

    <label>Member</label>
    <select name="member_id" required>
      <option value="">-- select member --</option>
      <?php while($m = $members->fetch_assoc()): ?>
        <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['name']); ?></option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Borrow</button>
  </form>

  <h3>Return Book</h3>
  <form method="post" style="margin-bottom:18px;">
    <input type="hidden" name="action" value="return">
    <label>Select borrowed book</label>
    <select name="transaction_id" required>
      <option value="">-- select borrowed --</option>
      <?php while($br = $borrowed->fetch_assoc()): ?>
        <option value="<?php echo $br['id']; ?>">
          <?php echo htmlspecialchars($br['book'])." — ".$br['member']." (".$br['borrow_date'].")"; ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Return</button>
  </form>

  <h3>All Transactions</h3>
  <?php if ($allTx->num_rows > 0): ?>
    <table>
      <tr><th>ID</th><th>Book</th><th>Member</th><th>Borrow Date</th><th>Return Date</th><th>Status</th></tr>
      <?php while($t = $allTx->fetch_assoc()): ?>
        <tr>
          <td><?php echo $t['id']; ?></td>
          <td><?php echo htmlspecialchars($t['book']); ?></td>
          <td><?php echo htmlspecialchars($t['member']); ?></td>
          <td><?php echo $t['borrow_date']; ?></td>
          <td><?php echo $t['return_date'] ?? '-'; ?></td>
          <td><?php echo $t['return_date'] ? 'Returned' : 'Borrowed'; ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No transactions yet.</p>
  <?php endif; ?>
</div>
</body>
</html>
