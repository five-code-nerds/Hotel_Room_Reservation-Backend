CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verification_code VARCHAR(10),
    code_expires DATETIME, 
    is_verified INT DEFAULT 0,
    role ENUM('user', 'admin')  NOT NULL DEFAULT 'user'
);

CREATE TABLE room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_catagory ENUM('normal', 'vip') DEFAULT 'normal' NOT NULL,
    beds INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type_id INT NOT NULL,
    status ENUM('available','unavailable') NOT NULL DEFAULT 'available',
    image_url VARCHAR(255),
    FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE RESTRICT
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    number_of_guests INT NOT NULL,
    guest_name VARCHAR(100) NULL,
    guest_email VARCHAR(150) NULL,
    guest_phone VARCHAR(20) NULL,
    status ENUM('pending_payment', 'confirmed','cancelled', 'completed', 'expired') DEFAULT 'pending_payment',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    transaction_ref VARCHAR(100) NOT NULL UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    provider VARCHAR(50) DEFAULT 'chapa',
    transaction_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

CREATE INDEX users_email_index 
ON users(email);
CREATE INDEX payments_tx_ref_index 
ON payments(transaction_ref);
CREATE INDEX reservations_user_index 
ON reservations(user_id);
CREATE INDEX reservations_email_index 
ON reservations(guest_email);