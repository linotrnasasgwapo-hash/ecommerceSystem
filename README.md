# ❤️ Renalyn's Favorite Online Shop

A full-featured, modern e-commerce web application built with **PHP**, **MySQL**, and **vanilla CSS/JS**. Renalyn's Favorite Online Shop delivers a delightful shopping experience featuring Renalyn's top favorite chocolates, flowers, home-cooked Filipino meals, and beauty services.

---

## 📸 Features at a Glance

| Feature | Description |
|---|---|
| 🛍️ **Product Catalog** | Browse products by category with search, filtering, and quick-view modals |
| 🛒 **Shopping Cart** | Add/remove items, adjust quantities, AJAX-powered cart dropdown preview |
| ❤️ **Wishlist** | Save favorite products for later (logged-in users) |
| 📦 **Order Management** | Place orders with full checkout flow; track order status and history |
| 👤 **User Accounts** | Register, login, profile management with role-based access (user/admin) |
| 🔐 **Admin Dashboard** | Manage products, categories, orders, and contact messages |
| 🌙 **Dark / Light Theme** | Toggle between dark and light mode with persistent preference |
| 📱 **Mobile Responsive** | Fully responsive with hamburger menu and mobile bottom navigation bar |
| 📧 **Contact Form** | Users can send messages; admins can view and manage submissions |
| 🔍 **Product Search** | Real-time search across the product catalog |
| 📄 **Static HTML Export** | Export the site as static HTML pages for offline viewing |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 7.4+ (PDO for database) |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, Vanilla CSS, Vanilla JavaScript |
| **Typography** | [Inter](https://fonts.google.com/specimen/Inter) (Google Fonts) |
| **Icons** | [Font Awesome 6.5](https://fontawesome.com/) |
| **Alerts** | [SweetAlert2](https://sweetalert2.github.io/) |
| **Server** | XAMPP (Apache + MySQL) |

---

## 📁 Project Structure

```
e-commerceSystem/
├── admin/                      # Admin panel
│   ├── includes/               # Admin header & footer templates
│   │   ├── admin_header.php
│   │   └── admin_footer.php
│   ├── index.php               # Admin dashboard
│   ├── products.php            # Product management
│   ├── product_form.php        # Add / edit product form
│   ├── categories.php          # Category management
│   ├── orders.php              # Order management
│   ├── order_detail.php        # Single order details
│   └── contacts.php            # Contact message management
│
├── assets/
│   ├── css/
│   │   └── style.css           # Main stylesheet (~46KB)
│   ├── js/
│   │   └── main.js             # Core JavaScript
│   └── img/                    # Static images (backgrounds, team photo, etc.)
│       └── products/           # Product images
│
├── config/
│   └── database.php            # Database connection (PDO)
│
├── database/
│   └── ecommerce.sql           # Full database schema + sample data
│
├── includes/                   # Shared components & handlers
│   ├── header.php              # Global header, navbar, theme toggle
│   ├── footer.php              # Global footer
│   ├── auth.php                # Session & authentication helpers
│   ├── auth_actions.php        # Login / register / logout logic
│   ├── functions.php           # Utility functions (sanitize, redirect, etc.)
│   ├── cart_actions.php        # Cart add / update / remove handler
│   ├── ajax_cart.php           # AJAX cart data endpoint
│   ├── checkout_handler.php    # Order placement handler
│   ├── contact_handler.php     # Contact form submission handler
│   ├── profile_handler.php     # Profile update handler
│   ├── wishlist_actions.php    # Wishlist add / remove handler
│   └── quick_view.php          # Product quick-view AJAX endpoint
│
├── pages/                      # Customer-facing pages
│   ├── shop.php                # Product listing with filters & search
│   ├── product.php             # Single product detail page
│   ├── cart.php                # Shopping cart page
│   ├── checkout.php            # Checkout page
│   ├── orders.php              # Order history page
│   ├── order_details.php       # Single order detail page
│   ├── wishlist.php            # Wishlist page
│   ├── profile.php             # User profile page
│   ├── login.php               # Login page
│   ├── register.php            # Registration page
│   ├── about.php               # About us page
│   └── contact.php             # Contact page
│
├── static_export/              # Exported static HTML pages
├── export_html.php             # Static HTML export script
├── db_setup.php                # Database migration helper
├── index.php                   # Homepage (entry point)
└── README.md                   # ← You are here
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any Apache + MySQL + PHP stack)
- PHP **7.4** or higher
- MySQL **5.7+** / MariaDB **10.3+**

### Installation

1. **Clone or copy the project** into your XAMPP `htdocs` directory:
   ```bash
   # The project should be at:
   C:\xampp\htdocs\e-commerceSystem\
   ```

2. **Start XAMPP** — launch **Apache** and **MySQL** from the XAMPP Control Panel.

3. **Create the database** — open [phpMyAdmin](http://localhost/phpmyadmin) and:
   - Import the file `database/ecommerce.sql`
   - This will create the `ecommerce_db` database, all tables, and seed sample data.

4. **Configure the database connection** (optional — defaults work with standard XAMPP):
   ```
   config/database.php
   ```
   | Setting | Default |
   |---|---|
   | Host | `localhost` |
   | Database | `ecommerce_db` |
   | Username | `root` |
   | Password | *(empty)* |

5. **Open the site** in your browser:
   ```
   http://localhost/e-commerceSystem/
   ```

---

## 🔑 Default Accounts

The SQL seed data includes two pre-configured accounts:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@shop.com` | `admin123` |
| **User** | `user@shop.com` | `user123` |

> ⚠️ **Important:** Change these credentials before deploying to any public-facing environment.

---

## 🗄️ Database Schema

The system uses **7 tables** with proper foreign key relationships:

```
users ──────────┐
                ├──→ cart
                ├──→ wishlist
                ├──→ orders ──→ order_items
                │
products ───────┤
                │
categories ─────┘

contacts (standalone)
```

| Table | Purpose |
|---|---|
| `users` | User accounts with roles (`user` / `admin`) |
| `categories` | Product categories |
| `products` | Product catalog (name, price, stock, image, etc.) |
| `cart` | Shopping cart items per user |
| `wishlist` | Saved/favorited products per user |
| `orders` | Customer orders with shipping info and status |
| `order_items` | Line items for each order |
| `contacts` | Contact form submissions |

---

## 🎨 Design Highlights

- **Modern Glassmorphism** — Frosted-glass card effects and subtle backdrop blurs
- **Dark & Light Modes** — Seamless theme toggle with `localStorage` persistence
- **Smooth Animations** — Fade-up entrance animations, hover transitions, and micro-interactions
- **Curated Color Palette** — Premium dark theme with vibrant accent colors
- **Inter Font** — Clean, modern typography for excellent readability
- **SweetAlert2 Toasts** — Elegant non-blocking notifications for user actions
- **Skeleton Loaders** — Cart dropdown shows loading placeholders while fetching data

---

## 📱 Responsive Design

The application is fully responsive and optimized for all screen sizes:

- **Desktop** — Full navigation bar with search, cart dropdown on hover, user dropdown menu
- **Tablet** — Adaptive grid layouts, collapsible navigation
- **Mobile** — Hamburger menu, bottom navigation bar (Home, Shop, Cart, Profile), touch-friendly elements

---

## 🔧 Key Utility Functions

Located in `includes/functions.php`:

| Function | Description |
|---|---|
| `sanitize($data)` | HTML-encodes user input to prevent XSS |
| `formatPrice($price)` | Formats number as `$X.XX` |
| `redirect($url)` | Performs a safe HTTP redirect |
| `setFlash($type, $msg)` | Sets a session flash message |
| `getFlash()` | Retrieves and clears the flash message |
| `baseUrl($path)` | Generates absolute URL paths |
| `getCartCount($pdo, $userId)` | Returns total items in user's cart |

---

## 📄 Static Export

The system includes a static HTML export feature (`export_html.php`) that generates standalone HTML pages in the `static_export/` directory. This is useful for:

- Offline demonstrations
- Deploying a read-only version of the site
- Archiving the site's appearance

---

## 🛡️ Security Notes

- **Password Hashing** — User passwords are hashed with `bcrypt` via PHP's `password_hash()`
- **Prepared Statements** — All database queries use PDO prepared statements to prevent SQL injection
- **XSS Protection** — Output is sanitized with `htmlspecialchars()` via the `sanitize()` helper
- **Session-based Auth** — Authentication state is managed through PHP sessions
- **CSRF Note** — Consider adding CSRF token validation for production use

---

## 📋 Product Categories (Sample Data)

The seed data includes **4 categories** with **12 products**:

| Category | Products |
|---|---|
| 🔌 Electronics | Wireless Bluetooth Headphones, Smart Watch Pro, Portable Power Bank |
| 👕 Clothing | Classic Denim Jacket, Cotton Casual T-Shirt, Running Sneakers |
| 💎 Accessories | Leather Crossbody Bag, Minimalist Analog Watch, Polarized Sunglasses |
| 🏠 Home & Living | Ceramic Table Lamp, Scented Candle Set, Bamboo Kitchen Organizer |

---

## 📝 License

This project is for educational and personal use. Feel free to modify and adapt it for your needs.

---

<p align="center">
  <strong>⚡ ShopVibe</strong> — Built with ❤️ using PHP, MySQL & Vanilla CSS/JS
</p>
