📚 Library Management System (PHP + MySQL)

A modern web-based Library Management System built using PHP, MySQL, HTML, CSS.
It helps librarians manage books, members, borrowing, and returning efficiently with a clean dashboard UI.

🌟 Overview

Manual records in libraries are slow, error-prone, and hard to track.
This project provides a digital system where:

✅ Books can be added, listed, borrowed, and returned.
✅ Members can be registered and tracked.
✅ A dashboard gives quick insights (books, members, borrow, return).
✅ History of borrow/return is maintained securely.

This is a mini-project to learn PHP & MySQL with CRUD operations.

🚀 Features
📖 Books

➕ Add new books with title, author, and available copies.

📋 View all books in a styled table.

❌ Delete books (only if not in borrow/return history).

👥 Members

➕ Register new members (name, email, roll number).

📋 View all members in a styled table.

❌ Delete members (only if no transaction history).

📥 Borrow

🔗 Assign a book to a member.

📉 Book stock decreases automatically.

⚠️ Prevents borrowing if no copies available.

📤 Return

✅ Return borrowed books.

📈 Book stock increases automatically.

🗓️ Records return date in transactions table.

📊 Dashboard

📚 Total books

👥 Total members

📥 Borrowed books

📤 Returned books

📑 History

📥 Borrow List → all active borrowed books.

📤 Return List → all returned books.

📂 Project Structure
library-management/
├── index.php              - Dashboard (overview & stats)
├── books.php              - Manage books (add/list/delete)
├── members.php            - Manage members (add/list/delete)
├── borrow_book.php        - Borrow a book
├── return_book.php        - Return a book
├── borrow_list.php        - List borrowed books
├── return_list.php        - List returned books
├── db.php                 - Database connection file
├── navbar.php             - Navigation bar (included in all pages)
├── lib.jpg                - Background image
├── assets/
│   └── css/
│       └── style.css      - Modern CSS styling
└── sql/
    └── library.sql        - Database schema (tables + relationships)

🛠️ Installation Guide

Download / Clone Project
Place inside htdocs (XAMPP) or www (WAMP/LAMP).

Create Database

Open phpMyAdmin.

Create database:

CREATE DATABASE library;


Import sql/library.sql.

Configure Database
Edit db.php and set credentials:

$host = "localhost";
$user = "root";
$pass = "";
$db   = "library";


Run the Project

Start Apache & MySQL.

Open browser:

http://localhost/library-management/

📌 Database Design
Tables

📖 books → (id, title, author, copies)

👥 members → (id, name, email, roll_number)

🔗 transactions → (id, book_id, member_id, borrow_date, return_date)

Relationships

transactions.book_id → references books.id

transactions.member_id → references members.id

📌 Notes

🛡️ Safe delete: Books/members cannot be deleted if they have transactions.

🔄 Use ON DELETE CASCADE in SQL if you want auto-delete of related records.

✏️ To edit book/member details, extend books.php / members.php.

🖼️ Screenshots (to add later)

📊 Dashboard

📖 Books page

👥 Members page

📥 Borrow form

📤 Return form

📑 History pages

📜 License

This project is open-source and free for educational use.
You are welcome to modify, improve, and use it 🚀