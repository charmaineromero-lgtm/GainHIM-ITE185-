# Gain Him --- A Cozy Christian Journaling Web App

*A gentle space for MSU-IIT students and believers to enjoy God's Word
every day.*\
Inspired by **Jeremiah 15:16** --- *"Thy words were found, and I did eat
them..."*

Gain Him is a clean, quiet, and minimal web app for journaling, tagging
entries, and keeping a spiritual diary. Built with **PHP + MySQL**, it's
lightweight enough to run on any local server like XAMPP.



## Features

### Journaling

-   Create, edit, view, and delete journals
-   Cozy UI with soft colors
-   Timestamped entries

### Tag System

-   Can add tag per journal
-   Prevents duplicate journal-tag pairs
-   Preloaded tags: `Faith`, `Joy`, `Peace`, `Hope`

### Search and Filtering

-   Search by title/content
-   Filter journals by tag
-   Pagination-ready

### User Accounts

-   Register, login, logout
-   Secure password hashing

### Dashboard

-   Total number of journals
-   Tag count
-   Most-used tags

### UI Experience

-   Blurred background
-   Minimalist design
-   Mobile-friendly layout



## Tech Stack

**Frontend:** HTML, CSS\
**Backend:** PHP\
**Database:** MySQL\
**Authentication:** Sessions + Password Hashing\
**Security:** Prepared statements, token-based password reset



## Folder Structure

    /
    │── aboutus.php
    │── add_journal.php
    │── dashboard.php
    │── delete.php
    │── edit.php
    │── login.php
    │── logout.php
    │── register.php
    │── forgot_password.php
    │── reset_password.php
    │── config.php
    │── style.css
    │── logo-v2.png
    │── bg_casestudy.jpg
    │── README.md



# Database Schema (Complete + Clean)

``` sql
CREATE TABLE `user_tbl` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `journal` (
  `journal_id` INT(11) NOT NULL AUTO_INCREMENT,
  `id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT DEFAULT NULL,
  `is_community` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`journal_id`),
  FOREIGN KEY (`id`) REFERENCES user_tbl(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_is_community ON journal(is_community);

CREATE TABLE `tags` (
  `tag_id` INT(11) NOT NULL AUTO_INCREMENT,
  `tag_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tags (tag_name) VALUES ('Faith'), ('Joy'), ('Peace'), ('Hope');

CREATE TABLE `journal_tags` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `journal_id` INT NOT NULL,
    `tag_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_journal_tag` (`journal_id`, `tag_id`),
    CONSTRAINT `fk_journal` FOREIGN KEY (`journal_id`) REFERENCES `journal`(`journal_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags`(`tag_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `password_resets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```



## Installation

### 1️⃣ Clone the repository

``` bash
git clone https://github.com/yourusername/gainhim.git
```

### 2️⃣ Import the SQL

-   Open phpMyAdmin\
-   Create database: **gainhim**\
-   Import SQL above

### 3️⃣ Configure your database

Edit `config.php`:

``` php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gainhim";

$conn = mysqli_connect($host, $user, $pass, $dbname);
```

### 4️⃣ Run the app

    http://localhost/gainhim/login.php

<img width="1919" height="905" alt="image" src="https://github.com/user-attachments/assets/e6d11df2-9bf2-475d-8757-d5b8b3e2d82a" />
<img width="1919" height="913" alt="image" src="https://github.com/user-attachments/assets/438bbe7c-2177-4670-a675-6370098d2ec9" />
<img width="1919" height="906" alt="image" src="https://github.com/user-attachments/assets/7f64c303-9d67-405c-9df7-b703f31ef3a5" />
<img width="1919" height="908" alt="image" src="https://github.com/user-attachments/assets/84e2c89c-425b-4d17-a6ed-751db45a122d" />
<img width="395" height="216" alt="image" src="https://github.com/user-attachments/assets/1269dc8b-06ef-47d1-81b4-9476a8086a99" />


## Future Enhancements

-   ✨ Dark Mode\
-   ✨ Rich Text Editor\
-   ✨ Tag creation for users\
-   ✨ Export journals to PDF\
-   ✨ Community feed using is_community=1



## License

Free to use for ministry, academic, and personal purposes.
