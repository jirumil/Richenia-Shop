-- =========================================================
-- Richenia — Database Migration & Seed Data
-- Run this once in phpMyAdmin (Import tab) or via:
--   mysql -u root -p < schema.sql
--
-- After importing this file, also run database/seed.php once
-- in your browser (http://localhost/richenia/database/seed.php)
-- to create the default admin account and demo coupons. That
-- step needs to happen in PHP (not SQL) because the admin
-- password must be hashed with PHP's password_hash().
-- =========================================================

CREATE DATABASE IF NOT EXISTS richenia_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE richenia_db;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------
-- Table: users
-- -------------------------------------------------
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username        VARCHAR(50)     NOT NULL,
  email           VARCHAR(150)    NOT NULL,
  password_hash   VARCHAR(255)    NOT NULL,
  role            ENUM('client','admin') NOT NULL DEFAULT 'client',
  created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

-- -------------------------------------------------
-- Table: products
-- -------------------------------------------------
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS products;

CREATE TABLE products (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(150)    NOT NULL,
  slug          VARCHAR(170)    NOT NULL,
  price         DECIMAL(10,2)   NOT NULL,
  stock         INT UNSIGNED    NOT NULL DEFAULT 0,
  category      VARCHAR(80)     NOT NULL DEFAULT 'Menswear',
  image_url     VARCHAR(255)    NOT NULL,
  description   TEXT            NULL,
  is_featured   TINYINT(1)      NOT NULL DEFAULT 0,
  created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_products_slug (slug),
  KEY idx_products_category (category)
) ENGINE=InnoDB;

-- -------------------------------------------------
-- Table: orders
-- -------------------------------------------------
DROP TABLE IF EXISTS orders;

CREATE TABLE orders (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id           INT UNSIGNED    NOT NULL,
  subtotal          DECIMAL(10,2)   NOT NULL DEFAULT 0,
  discount_applied  DECIMAL(10,2)   NOT NULL DEFAULT 0,
  coupon_code       VARCHAR(50)     NULL,
  total_price       DECIMAL(10,2)   NOT NULL DEFAULT 0,
  status            VARCHAR(20)     NOT NULL DEFAULT 'completed',
  created_at        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_orders_user (user_id),
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------
-- Table: order_items
-- -------------------------------------------------
CREATE TABLE order_items (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id      INT UNSIGNED    NOT NULL,
  product_id    INT UNSIGNED    NULL,
  product_name  VARCHAR(150)    NOT NULL,
  quantity      INT UNSIGNED    NOT NULL DEFAULT 1,
  price         DECIMAL(10,2)   NOT NULL,
  KEY idx_order_items_order (order_id),
  KEY idx_order_items_product (product_id),
  CONSTRAINT fk_order_items_order   FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------
-- Table: coupons
-- -------------------------------------------------
DROP TABLE IF EXISTS coupons;

CREATE TABLE coupons (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code          VARCHAR(50)     NOT NULL,
  type          ENUM('percentage','fixed') NOT NULL DEFAULT 'percentage',
  value         DECIMAL(10,2)   NOT NULL,
  active        TINYINT(1)      NOT NULL DEFAULT 1,
  max_uses      INT UNSIGNED    NULL,
  times_used    INT UNSIGNED    NOT NULL DEFAULT 0,
  expires_at    DATETIME        NULL,
  created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_coupons_code (code)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------
-- Seed data — products
-- Image URLs are branded placeholders (placehold.co) so the
-- storefront renders correctly out of the box. Swap image_url
-- for real product photography (e.g. assets/images/products/..)
-- whenever you have it — no other code changes are required.
-- -------------------------------------------------
INSERT INTO products (name, slug, price, stock, category, image_url, description, is_featured) VALUES
('The Ink Overcoat',        'the-ink-overcoat',        420.00, 12, 'Menswear',   'https://placehold.co/700x900/161512/F2EEE6?text=The+Ink+Overcoat',       'Double-faced wool overcoat in near-black ink. Cut for a clean, structured drape.', 1),
('Heritage Trench',         'heritage-trench',         510.00,  8, 'Menswear',   'https://placehold.co/700x900/3C3A33/F2EEE6?text=Heritage+Trench',        'Water-resistant cotton twill trench with horn buttons and a storm flap.', 1),
('Sable Knit Crewneck',     'sable-knit-crewneck',     180.00, 25, 'Menswear',   'https://placehold.co/700x900/4A4640/F2EEE6?text=Sable+Knit+Crewneck',    'Heavyweight merino crewneck, garment-dyed for depth of colour.', 0),
('Cloud Cashmere Sweater',  'cloud-cashmere-sweater',  260.00, 15, 'Menswear',   'https://placehold.co/700x900/EAE4D8/161512?text=Cloud+Cashmere+Sweater', 'Pure cashmere knit with a relaxed, considered fit.', 1),
('Tailored Wool Trouser',   'tailored-wool-trouser',   220.00, 18, 'Menswear',   'https://placehold.co/700x900/2E2C27/F2EEE6?text=Tailored+Wool+Trouser',  'Mid-rise wool trouser with a tapered leg and clean front.', 0),
('Brushed Cotton Shirt',    'brushed-cotton-shirt',    140.00, 30, 'Menswear',   'https://placehold.co/700x900/D8D0C0/161512?text=Brushed+Cotton+Shirt',  'Soft-brushed cotton shirt, cut for layering under knitwear.', 0),
('Stone Linen Blazer',      'stone-linen-blazer',      340.00,  6, 'Menswear',   'https://placehold.co/700x900/C9C2B4/161512?text=Stone+Linen+Blazer',    'Unstructured linen blazer in a quiet stone tone.', 1),
('Minimalist Bomber',       'minimalist-bomber',       295.00,  0, 'Menswear',   'https://placehold.co/700x900/1F1E1B/F2EEE6?text=Minimalist+Bomber',     'Technical bomber with a matte finish and concealed hardware.', 0),
('Full-Grain Leather Belt', 'full-grain-leather-belt',  95.00, 40, 'Accessories','https://placehold.co/700x900/5B6655/F2EEE6?text=Leather+Belt',          'Vegetable-tanned full-grain leather belt with a brushed buckle.', 0),
('Merino Travel Scarf',     'merino-travel-scarf',      85.00, 22, 'Accessories','https://placehold.co/700x900/8A8472/161512?text=Travel+Scarf',          'Featherweight merino scarf, woven for year-round travel.', 0);
