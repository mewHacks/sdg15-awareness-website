# 🌍 SDG15 Awareness Website

A PHP-based web application promoting awareness about **UN Sustainable Development Goal 15: Life on Land**.

![SDG 15](https://cdn-images-1.medium.com/max/1600/1*7MDLuoSaJjS-q5tZ_vJbVA.png)

---

## ✨ Features

### 🔐 Authentication
- User registration & secure login (bcrypt)
- Password reset functionality
- Role-based access (Admin/User)

### 📝 Community Forum
- Create posts with images and tags
- Like & comment system
- Search, filter, and sort posts
- Admin moderation (ban/unban)

### 📚 Educational Content
- About SDG 15 - Mission, causes, impacts
- 6 detailed initiative pages
- Resources & case studies
- Events calendar
- Donation & volunteering

---

## 🛠 Tech Stack

| Backend | Frontend | Database |
|---------|----------|----------|
| PHP 8.x | HTML5/CSS3 | MySQL 5.7+ |
| Apache | jQuery, Select2 | MariaDB |

---

## 🚀 Quick Start

### Prerequisites
- XAMPP/MAMP/LAMP stack
- PHP 8.0+ with mysqli
- MySQL 5.7+

### Installation

1. **Clone to web directory**
   ```bash
   git clone https://github.com/yourusername/sdg15-awareness-website.git
   # XAMPP: C:/xampp/htdocs/
   # MAMP: /Applications/MAMP/htdocs/
   ```

2. **Start Apache & MySQL**

3. **Access the site**
   ```
   http://localhost/sdg15-awareness-website/Homepage.php
   ```

> 💡 Database and tables are **auto-created** on first visit!

### Default Admin
```
Email: admin@admin.com
Password: admin123
```

---

## 📖 Documentation

For detailed technical documentation, see:
- [Technical Documentation](TECHNICAL.md) - Project structure, database schema, ERD, API endpoints

---

## 👥 User Roles

| Role | Capabilities |
|------|-------------|
| **User** | View content, create/edit own posts, like, comment |
| **Admin** | All user permissions + ban/unban content, view moderated posts |

---

## 🔒 Security

- ✅ SQL Injection prevention (prepared statements)
- ✅ Password hashing (bcrypt)
- ✅ XSS prevention (htmlspecialchars)
- ✅ Session-based authentication
- ✅ Role-based access control

---

## 📞 Support

Use the **Contact Us** page or open a GitHub issue.

---

<p align="center">
  <b>🌲 Protecting Life on Land for a Sustainable Future 🌲</b>
</p>
