# SDG15 Awareness Website - Life on Land

A comprehensive PHP-based web application promoting awareness about **UN Sustainable Development Goal 15: Life on Land**. This platform educates users about protecting terrestrial ecosystems, sustainably managing forests, combating desertification, halting biodiversity loss, and reversing land degradation.

![SDG 15 Logo](https://cdn-images-1.medium.com/max/1600/1*7MDLuoSaJjS-q5tZ_vJbVA.png)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Structure](#-project-structure)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Database Schema](#-database-schema)
- [User Roles](#-user-roles)
- [Pages Overview](#-pages-overview)
- [API Endpoints](#-api-endpoints)
- [Security Features](#-security-features)
- [Contributing](#-contributing)

---

## ✨ Features

### 🔐 Authentication System
- **User Registration** with email validation
- **Secure Login** with password hashing (bcrypt)
- **Remember Me** functionality with cookies
- **Password Reset** via email verification
- **Role-based Access Control** (Admin/User)

### 📝 Community Forum
- **Create Posts** with rich content and image uploads
- **Tag System** for categorizing posts (Environment, Conservation, Policy, etc.)
- **Like System** for posts and comments
- **Comment System** with nested discussions
- **Search & Filter** posts by title, content, or tags
- **Sort Options** (Newest, Oldest, A-Z, Z-A)

### 👨‍💼 Admin Dashboard
- **Content Moderation** - Ban/Unban posts and comments
- **View Banned Content** - Review moderated content
- **User Management** - Admin role identification

### 📚 Educational Content
- **About Page** - SDG 15 mission, causes, statistics, and impacts
- **Initiatives** - 6 detailed initiative pages covering:
  - Environmental Education
  - Reforestation Programs
  - Wildlife Protection Laws
  - NGO Partnerships
  - FSC Certification
  - Public Awareness Campaigns
- **Resources** - Reports, case studies, and analysis
- **Events** - Upcoming environmental events and campaigns

### 💚 Get Involved
- **Donation System** - Support conservation efforts
- **Volunteer Opportunities** - Tree planting, awareness campaigns
- **Contact Form** - Direct communication channel

---

## 🛠 Tech Stack

| Category | Technology |
|----------|------------|
| **Backend** | PHP 8.x |
| **Database** | MySQL 5.7+ / MariaDB |
| **Frontend** | HTML5, CSS3, JavaScript |
| **CSS Framework** | Custom CSS with modern styling |
| **JavaScript Libraries** | jQuery 3.6, Select2 |
| **Icons** | Font Awesome 6.5 |
| **Server** | Apache (XAMPP)|

---

## 📁 Project Structure

```
sdg15-awareness-website/
├── Homepage.php              # Landing page with SDG 15 overview
├── LoginPage.php             # User authentication
├── SignUp.php                # New user registration
├── forgetPassword.php        # Password recovery
├── Resetpassword.php         # Password reset handler
├── logout.php                # Session termination
│
├── index.php                 # Community forum main page
├── view_post.php             # Individual post view with comments
├── add_post.php              # Create new post form
├── edit_post.php             # Edit existing post
├── delete_post.php           # Delete post handler
├── insert_post.php           # Post insertion handler
├── update_post.php           # Post update handler
│
├── add_comment.php           # Add comment handler
├── delete_comment.php        # Delete comment handler
├── like.php                  # Post like toggle
├── like_index.php            # AJAX like handler for index
├── like_comment.php          # Comment like toggle
│
├── ban.php                   # Ban/unban post (admin)
├── ban_comment.php           # Ban/unban comment (admin)
├── banned_posts.php          # View banned posts (admin)
│
├── myprofile.php             # User profile page
├── my_posts.php              # User's own posts
├── liked_posts.php           # User's liked posts
│
├── about.php                 # About SDG 15
├── initiatives.php           # Initiatives overview
├── initiative1.php - 6.php   # Individual initiative pages
├── resources.php             # Educational resources
├── events.php                # Environmental events
├── getinvolved.php           # Donation & volunteering
├── contactus.php             # Contact form
│
├── db_connect.php            # Database connection & setup
├── create_db.php             # Database creation script
├── create_table.php          # Table creation script
├── handle_contact.php        # Contact form handler
├── handle_donation.php       # Donation form handler
├── message.php               # Flash message display
│
├── article_search.js         # Search functionality
│
├── css/
│   ├── index.css                # Community page styles
│   ├── view_post.css            # Post view styles
│   ├── input_post.css           # Post form styles
│   └── profile_posts.css        # Profile page styles
│
├── images/                   # Static images
├── videos/                   # Background videos
├── uploads/                  # User-uploaded images
│
├── database.txt              # SQL schema reference
└── db.txt                    # Database documentation
```

---

## 📋 Prerequisites

Before installation, ensure you have:

- **PHP 8.0+** with mysqli extension
- **MySQL 5.7+** or MariaDB 10.x
- **Apache** web server with mod_rewrite
- **XAMPP** stack (recommended for local development)

---

## 🚀 Installation

### Step 1: Clone or Download
```bash
# Clone the repository
git clone https://github.com/mewHacks/sdg15-awareness-website.git

# Or download and extract to your web server directory
# XAMPP: C:/xampp/htdocs/sdg15-awareness-website
```

### Step 2: Start Your Server
```bash
# Start Apache and MySQL via XAMPP control panel
# Or via command line:
sudo service apache2 start
sudo service mysql start
```

### Step 3: Configure Database
The database and tables are **automatically created** when you first visit the site!

The `db_connect.php` file handles:
- Creating the `user_system` database
- Creating all required tables
- Inserting a default admin user

**Default Admin Credentials:**
```
Email: admin@admin.com
Password: admin123
```

### Step 4: Access the Website
```
http://localhost/sdg15-awareness-website/Homepage.php
```

---

## 🗄 Database Schema

### Tables Overview

```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,        -- bcrypt hashed
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Posts table
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,                        -- FK to users.id
    title VARCHAR(100) NOT NULL,
    content TEXT,
    tags VARCHAR(255),                      -- comma-separated
    images TEXT,                            -- comma-separated paths
    likes INT DEFAULT 0,
    banned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,                   -- FK to posts.post_id
    id INT NOT NULL,                        -- FK to users.id
    comment TEXT NOT NULL,
    comment_like INT DEFAULT 0,
    banned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Likes table (post likes tracking)
CREATE TABLE likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,                        -- FK to users.id
    post_id INT NOT NULL,                   -- FK to posts.post_id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (id, post_id)
);

-- CommentLikes table
CREATE TABLE CommentLikes (
    Commentlikes_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,                        -- FK to users.id
    comment_id INT NOT NULL,                -- FK to comments.comment_id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_comment_like (id, comment_id)
);

-- Donations table
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Entity Relationship Diagram

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   users     │       │    posts    │       │  comments   │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │◄──────│ id (FK)     │   ┌──►│ comment_id  │
│ username    │       │ post_id (PK)│◄──┤   │ post_id(FK) │
│ email       │       │ title       │   │   │ id (FK)     │
│ password    │       │ content     │   │   │ comment     │
│ role        │       │ tags        │   │   │ banned      │
└─────────────┘       │ banned      │   │   └─────────────┘
      │               └─────────────┘   │
      │                     │           │
      │               ┌─────┴─────┐     │
      │               ▼           ▼     │
      │         ┌─────────┐  ┌──────────────┐
      └────────►│  likes  │  │ CommentLikes │
                └─────────┘  └──────────────┘
```

---

## 👥 User Roles

### 👤 Regular User
- View all public content
- Create, edit, delete own posts
- Like posts and comments
- Add comments to any post
- Access profile and settings

### 👨‍💼 Administrator
- All regular user permissions
- View banned content
- Ban/Unban posts and comments
- Posts highlighted with special styling
- Access to moderation tools

---

## 📄 Pages Overview

| Page | URL | Description | Auth Required |
|------|-----|-------------|---------------|
| Homepage | `/Homepage.php` | Landing page with SDG 15 intro | No |
| Login | `/LoginPage.php` | User authentication | No |
| Sign Up | `/SignUp.php` | New user registration | No |
| Community | `/index.php` | Forum with all posts | Yes |
| View Post | `/view_post.php?post_id=X` | Single post with comments | Yes |
| Add Post | `/add_post.php` | Create new post | Yes |
| Profile | `/myprofile.php` | User profile & settings | Yes |
| About | `/about.php` | SDG 15 information | Yes |
| Initiatives | `/initiatives.php` | Conservation initiatives | Yes |
| Resources | `/resources.php` | Educational materials | Yes |
| Events | `/events.php` | Environmental events | Yes |
| Get Involved | `/getinvolved.php` | Donation & volunteering | Yes |
| Contact | `/contactus.php` | Contact form | Yes |

---

## 🔌 API Endpoints

### AJAX Endpoints

| Endpoint | Method | Purpose | Parameters |
|----------|--------|---------|------------|
| `like_index.php` | POST | Toggle post like | `post_id` |
| `like_comment.php` | POST | Toggle comment like | `comment_id` |
| `add_comment.php` | POST | Add new comment | `post_id`, `comment` |
| `ban.php` | GET | Ban/Unban post | `post_id`, `action` |
| `ban_comment.php` | GET | Ban/Unban comment | `comment_id`, `action` |

### Response Format
```json
{
    "success": true,
    "like_count": 15,
    "message": "Post liked successfully"
}
```

---

## 🔒 Security Features

### ✅ Implemented Security Measures

1. **SQL Injection Prevention**
   - Prepared statements with parameter binding
   - No direct variable interpolation in queries

2. **Password Security**
   - Bcrypt hashing via `password_hash()`
   - Secure verification via `password_verify()`

3. **XSS Prevention**
   - `htmlspecialchars()` on all user output
   - Input sanitization

4. **Session Security**
   - Server-side session management
   - Session-based authentication

5. **Access Control**
   - Role-based permissions
   - Authentication checks on protected pages

---

## 🏷 Available Post Tags

| Category | Tags |
|----------|------|
| Environment & Conservation | Reforestation, Biodiversity, Forest Conservation, Wildlife Protection |
| Community & Participation | NGO Initiatives, Volunteering, Community Stories |
| Education & Insights | Education, Global Reports, Case Studies, Events |
| Policy & Sustainability | Sustainable Practices, Policy & Law |

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📞 Support

For questions or issues, use the **Contact Us** page or open a GitHub issue.

---

## 📜 License

This project is created for educational purposes to promote awareness of UN SDG 15: Life on Land.

---

<p align="center">
  <b>🌲 Protecting Life on Land for a Sustainable Future 🌲</b>
</p>
