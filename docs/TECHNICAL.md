# Technical Documentation

Detailed technical reference for the SDG15 Awareness Website.

---

## 📁 Project Structure

```
sdg15-awareness-website/
│
├── 🔐 Authentication
│   ├── Homepage.php              # Landing page
│   ├── LoginPage.php             # User login
│   ├── SignUp.php                # Registration
│   ├── forgetPassword.php        # Password recovery
│   ├── Resetpassword.php         # Password reset
│   └── logout.php                # Session termination
│
├── 📝 Community Forum
│   ├── index.php                 # Forum main page
│   ├── view_post.php             # Single post view
│   ├── add_post.php              # Create post form
│   ├── edit_post.php             # Edit post form
│   ├── delete_post.php           # Delete handler
│   ├── insert_post.php           # Insert handler
│   └── update_post.php           # Update handler
│
├── 💬 Comments
│   ├── add_comment.php           # Add comment
│   └── delete_comment.php        # Delete comment
│
├── ❤️ Likes
│   ├── like.php                  # Post like toggle
│   ├── like_index.php            # AJAX like (index)
│   └── like_comment.php          # Comment like toggle
│
├── 👨‍💼 Admin
│   ├── ban.php                   # Ban/unban post
│   ├── ban_comment.php           # Ban/unban comment
│   └── banned_posts.php          # View banned
│
├── 👤 Profile
│   ├── myprofile.php             # User profile
│   ├── my_posts.php              # User's posts
│   └── liked_posts.php           # Liked posts
│
├── 📚 Content Pages
│   ├── about.php                 # About SDG 15
│   ├── initiatives.php           # Initiatives hub
│   ├── initiative1-6.php         # Individual initiatives
│   ├── resources.php             # Resources
│   ├── events.php                # Events
│   ├── getinvolved.php           # Donations/Volunteer
│   └── contactus.php             # Contact form
│
├── 🗄️ Database
│   ├── db_connect.php            # Connection & setup
│   ├── create_db.php             # DB creation
│   ├── create_table.php          # Table creation
│   ├── handle_contact.php        # Contact handler
│   └── handle_donation.php       # Donation handler
│
├── 🎨 Assets
│   ├── css/
│   │   ├── index.css             # Forum styles
│   │   ├── view_post.css         # Post view
│   │   ├── input_post.css        # Post forms
│   │   └── profile_posts.css     # Profile styles
│   ├── images/                   # Static images
│   ├── videos/                   # Background videos
│   └── uploads/                  # User uploads
│
└── 📄 Documentation
    ├── database.txt              # Schema reference
    └── db.txt                    # DB notes
```

---

## 🗄️ Database Schema

### Tables

#### `users`
| Column | Type | Description |
|--------|------|-------------|
| `id` | INT, PK, AUTO_INCREMENT | User ID |
| `username` | VARCHAR(50) | Display name |
| `email` | VARCHAR(100), UNIQUE | Login email |
| `password` | VARCHAR(255) | Bcrypt hash |
| `role` | ENUM('admin','user') | User role |

#### `posts`
| Column | Type | Description |
|--------|------|-------------|
| `post_id` | INT, PK, AUTO_INCREMENT | Post ID |
| `id` | INT, FK → users | Author |
| `title` | VARCHAR(100) | Post title |
| `content` | TEXT | Post body |
| `tags` | VARCHAR(255) | Comma-separated |
| `images` | TEXT | Comma-separated paths |
| `likes` | INT | Like count |
| `banned` | TINYINT(1) | Moderation flag |
| `created_at` | TIMESTAMP | Creation time |

#### `comments`
| Column | Type | Description |
|--------|------|-------------|
| `comment_id` | INT, PK, AUTO_INCREMENT | Comment ID |
| `post_id` | INT, FK → posts | Parent post |
| `id` | INT, FK → users | Author |
| `comment` | TEXT | Comment text |
| `comment_like` | INT | Like count |
| `banned` | TINYINT(1) | Moderation flag |
| `created_at` | TIMESTAMP | Creation time |

#### `likes`
| Column | Type | Description |
|--------|------|-------------|
| `like_id` | INT, PK, AUTO_INCREMENT | Like ID |
| `id` | INT, FK → users | User who liked |
| `post_id` | INT, FK → posts | Liked post |
| `created_at` | TIMESTAMP | When liked |

> UNIQUE constraint on (id, post_id) prevents duplicate likes

#### `CommentLikes`
| Column | Type | Description |
|--------|------|-------------|
| `Commentlikes_id` | INT, PK, AUTO_INCREMENT | Like ID |
| `id` | INT, FK → users | User who liked |
| `comment_id` | INT, FK → comments | Liked comment |
| `created_at` | TIMESTAMP | When liked |

#### `donations`
| Column | Type | Description |
|--------|------|-------------|
| `id` | INT, PK, AUTO_INCREMENT | Donation ID |
| `name` | VARCHAR(100) | Donor name |
| `email` | VARCHAR(100) | Donor email |
| `amount` | DECIMAL(10,2) | Amount |
| `donated_at` | TIMESTAMP | When donated |

#### `contact_messages`
| Column | Type | Description |
|--------|------|-------------|
| `id` | INT, PK, AUTO_INCREMENT | Message ID |
| `username` | VARCHAR(100) | Sender name |
| `email` | VARCHAR(100) | Sender email |
| `message` | TEXT | Message content |
| `sent_at` | TIMESTAMP | When sent |

---

## 📊 Entity Relationship Diagram

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │◄────────────────────────────────┐
│ username        │                                  │
│ email           │                                  │
│ password        │                                  │
│ role            │                                  │
└────────┬────────┘                                  │
         │                                           │
         │ 1:N                                       │
         ▼                                           │
┌─────────────────┐       ┌─────────────────┐       │
│     posts       │       │    comments     │       │
├─────────────────┤       ├─────────────────┤       │
│ post_id (PK)    │◄──────│ post_id (FK)    │       │
│ id (FK)─────────┼───────│ id (FK)─────────┼───────┤
│ title           │  1:N  │ comment_id (PK) │       │
│ content         │       │ comment         │       │
│ tags            │       │ banned          │       │
│ images          │       └────────┬────────┘       │
│ banned          │                │                │
└────────┬────────┘                │ 1:N            │
         │                         ▼                │
         │ 1:N            ┌─────────────────┐       │
         ▼                │  CommentLikes   │       │
┌─────────────────┐       ├─────────────────┤       │
│     likes       │       │ Commentlikes_id │       │
├─────────────────┤       │ id (FK)─────────┼───────┤
│ like_id (PK)    │       │ comment_id (FK) │       │
│ id (FK)─────────┼───────┘                         │
│ post_id (FK)    │                                 │
└─────────────────┘                                 │
                                                    │
┌─────────────────┐       ┌─────────────────┐       │
│   donations     │       │contact_messages │       │
├─────────────────┤       ├─────────────────┤       │
│ id (PK)         │       │ id (PK)         │       │
│ name            │       │ username        │       │
│ email           │       │ email           │       │
│ amount          │       │ message         │       │
│ donated_at      │       │ sent_at         │       │
└─────────────────┘       └─────────────────┘       
```

---

## 🔌 API Endpoints

### AJAX Endpoints

| Endpoint | Method | Purpose | Parameters | Response |
|----------|--------|---------|------------|----------|
| `like_index.php` | POST | Toggle post like | `post_id` | `{success, like_count}` |
| `like_comment.php` | POST | Toggle comment like | `comment_id` | `{success, like_count}` |
| `add_comment.php` | POST | Add comment | `post_id`, `comment` | Redirect |
| `ban.php` | GET | Ban/unban post | `post_id`, `action` | Redirect |
| `ban_comment.php` | GET | Ban/unban comment | `comment_id`, `action` | Redirect |

### Response Example
```json
{
    "success": true,
    "like_count": 15
}
```

---

## 📄 Page Routes

| Page | URL | Auth |
|------|-----|------|
| Homepage | `/Homepage.php` | No |
| Login | `/LoginPage.php` | No |
| Sign Up | `/SignUp.php` | No |
| Community | `/index.php` | Yes |
| View Post | `/view_post.php?post_id=X` | Yes |
| Add Post | `/add_post.php` | Yes |
| Profile | `/myprofile.php` | Yes |
| About | `/about.php` | Yes |
| Initiatives | `/initiatives.php` | Yes |
| Resources | `/resources.php` | Yes |
| Events | `/events.php` | Yes |
| Get Involved | `/getinvolved.php` | Yes |
| Contact | `/contactus.php` | Yes |

---

## 🏷️ Available Tags

| Category | Tags |
|----------|------|
| Environment & Conservation | Reforestation, Biodiversity, Forest Conservation, Wildlife Protection |
| Community & Participation | NGO Initiatives, Volunteering, Community Stories |
| Education & Insights | Education, Global Reports, Case Studies, Events |
| Policy & Sustainability | Sustainable Practices, Policy & Law |

---

## 🔒 Security Implementation

| Feature | Implementation |
|---------|----------------|
| SQL Injection | Prepared statements with `bind_param()` |
| Password Storage | `password_hash()` with bcrypt |
| XSS Prevention | `htmlspecialchars()` on output |
| Authentication | PHP sessions |
| Authorization | Role checks (`$_SESSION['role']`) |
