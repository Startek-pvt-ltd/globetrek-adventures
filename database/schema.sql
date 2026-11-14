CREATE DATABASE IF NOT EXISTS globetrek_adventures CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE globetrek_adventures;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS queries;
DROP TABLE IF EXISTS itinerary;
DROP TABLE IF EXISTS packages;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    role ENUM('customer','staff','admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE packages (
    package_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    destination VARCHAR(160) NOT NULL,
    activities VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    duration_days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    accommodation VARCHAR(160) NOT NULL,
    transportation VARCHAR(160) NOT NULL,
    max_persons INT NOT NULL,
    image_path VARCHAR(255) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE itinerary (
    itinerary_id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT NOT NULL,
    day_number INT NOT NULL,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (package_id) REFERENCES packages(package_id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    travel_date DATE NOT NULL,
    num_persons INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'paid',
    booking_status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(package_id) ON DELETE CASCADE
);

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(60) NOT NULL,
    transaction_id VARCHAR(120) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);

CREATE TABLE queries (
    query_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending','responded') NOT NULL DEFAULT 'pending',
    response TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

INSERT INTO users (full_name, email, password, phone, role) VALUES
('Admin User', 'admin@globetrek.com', '$2y$10$cEJmPLro5zAZWQwxTPgLHuAHTLfW4T8C7ZLyaQ1SiM6qG9iKzjSHS', '+94770000001', 'admin'),
('Staff User', 'staff1@globetrek.com', '$2y$10$KkEleTYExXZ0k2o8VOKXzOKPtPvFtaeCrzPT4KdbGMUuf1RYzabkO', '+94770000002', 'staff'),
('Customer User', 'customer@globetrek.test', '$2y$10$3N8ZCbcKidoKxuIFaoKHB.ivk6Qw9pLv8EZkJyg4aIBGnlRJgiLb.', '+94770000003', 'customer');

INSERT INTO packages (title, destination, activities, description, duration_days, price, accommodation, transportation, max_persons, image_path, status) VALUES
('Sigiriya Heritage Escape', 'Sigiriya', 'Rock fortress climb, royal gardens, village lunch, cultural triangle sightseeing', 'Climb the Lion Rock fortress, explore royal gardens, and visit nearby village trails in Sri Lanka''s Cultural Triangle.', 2, 48500.00, 'Heritage hotel in Sigiriya', 'Private AC van', 12, 'assets/images/packages/sigiriya.png', 'active'),
('Ella Tea Country Trail', 'Ella', 'Nine Arch Bridge, tea estate walk, scenic train, Little Adam''s Peak', 'Travel through tea estates, misty viewpoints, Nine Arch Bridge, Little Adam''s Peak, and relaxed hill-country cafes.', 3, 62500.00, 'Boutique hill-country guesthouse', 'Scenic train and private van', 10, 'assets/images/packages/ella.png', 'active'),
('Galle Fort Coastal Walk', 'Galle', 'Fort rampart walk, lighthouse visit, beach time, seafood dining', 'Discover Dutch Fort lanes, lighthouse views, southern beaches, seafood dining, and colonial-era coastal architecture.', 2, 54500.00, 'Fort-view boutique hotel', 'Private coastal transfer', 12, 'assets/images/packages/galle-fort.png', 'active'),
('Kandy Cultural Journey', 'Kandy', 'Temple visit, lake walk, botanical gardens, cultural dance show', 'Visit the Temple of the Sacred Tooth Relic, Kandy Lake, botanical gardens, cultural dance, and mountain viewpoints.', 2, 52000.00, 'Kandy city hotel', 'Private AC van', 14, 'assets/images/packages/kandy.png', 'active'),
('Yala Wildlife Safari', 'Yala', 'Jeep safari, wildlife photography, birdwatching, lodge stay', 'Experience guided jeep safaris, dry-zone landscapes, birdlife, elephants, and the possibility of spotting Sri Lankan leopards.', 3, 78000.00, 'Safari lodge near Yala', 'Private van and safari jeep', 8, 'assets/images/packages/yala.png', 'active'),
('Mirissa Beach & Whale Watching', 'Mirissa', 'Whale watching, beach relaxation, Coconut Tree Hill, seafood dinner', 'Relax on the south coast with beach time, coconut hill views, seafood dinners, and seasonal whale watching.', 3, 69500.00, 'Beach resort in Mirissa', 'Private coastal transfer and boat', 10, 'assets/images/packages/mirissa.png', 'active');

INSERT INTO itinerary (package_id, day_number, title, description) VALUES
(1, 1, 'Sigiriya Arrival', 'Check in near the rock fortress and visit the water gardens.'),
(1, 2, 'Lion Rock Climb', 'Early climb to Sigiriya summit followed by a village lunch.'),
(2, 1, 'Scenic Train to Ella', 'Travel through tea country and settle into Ella.'),
(2, 2, 'Nine Arch Bridge', 'Visit Nine Arch Bridge, Little Adam''s Peak, and tea viewpoints.'),
(2, 3, 'Waterfalls and Departure', 'Stop at Ravana Falls before the return transfer.'),
(3, 1, 'Galle Fort Walk', 'Explore ramparts, lighthouse views, cafes, and heritage streets.'),
(3, 2, 'Southern Coast', 'Visit nearby beaches and return along the coast.'),
(4, 1, 'Kandy Sacred City', 'Visit Temple of the Tooth and Kandy Lake.'),
(4, 2, 'Gardens and Viewpoints', 'Tour Peradeniya Botanical Gardens and mountain viewpoints.'),
(5, 1, 'Yala Arrival', 'Check into the lodge and prepare for safari.'),
(5, 2, 'Morning Safari', 'Guided jeep safari through Yala National Park.'),
(5, 3, 'Village Breakfast', 'Relaxed breakfast and return transfer.'),
(6, 1, 'Mirissa Arrival', 'Beach check-in, sunset walk, and seafood dinner.'),
(6, 2, 'Whale Watching', 'Seasonal whale watching trip and afternoon beach time.'),
(6, 3, 'Coconut Hill', 'Visit Coconut Tree Hill before departure.');
