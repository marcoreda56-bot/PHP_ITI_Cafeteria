-- seeds.sql: sample data for the Cafeteria_app
-- Adjust the database name if yours differs
USE Cafeteria_app;

-- Rooms
INSERT INTO
    rooms (room_number, ext)
VALUES
    ('101', '1001'),
    ('102', '1002'),
    ('201', '2001');

-- Categories
INSERT INTO
    category (cat_name)
VALUES
    ('Beverages'),
    ('Snacks');

-- Products (cat_id matches inserted categories above)
INSERT INTO
    products (product_name, price, product_img, status, cat_id)
VALUES
    (
        'Coffee',
        2.50,
        'assets/products/coffee.jpg',
        'available',
        1
    ),
    (
        'Tea',
        2.00,
        'assets/products/tea.jpg',
        'available',
        1
    ),
    (
        'Sandwich',
        5.00,
        'assets/products/sandwich.jpg',
        'available',
        2
    );

-- Users
-- NOTE: Replace the password hashes with real ones produced by your application if needed.
INSERT INTO
    users (
        name,
        email,
        password,
        role,
        room_id,
        profile_path
    )
VALUES
    (
        'Admin User',
        'admin@example.com',
        '$2y$10$abcdefghijklmnopqrstuvabcdefghijklmnopqrstuvabcdefghijkl',
        'admin',
        1,
        'assets/profiles/admin.jpg'
    ),
    (
        'Jane Doe',
        'jane@example.com',
        '$2y$10$abcdefghijklmnopqrstuvabcdefghijklmnopqrstuvabcdefghijkl',
        'user',
        2,
        'assets/profiles/jane.jpg'
    );

-- Orders and order_items (sample purchase: Jane bought 1 Sandwich)
INSERT INTO
    orders (user_id, room_id, status, total_price, notes)
VALUES
    (2, 2, 'completed', 5.00, 'Sample order for Jane');

INSERT INTO
    order_items (order_id, product_id, quantity, price)
VALUES
    (1, 3, 1, 5.00);

-- A simple check record for the order
INSERT INTO
    checks (order_id, payment_method)
VALUES
    (1, 'cash');

-- Done
SELECT
    'Seed data inserted.' AS result;