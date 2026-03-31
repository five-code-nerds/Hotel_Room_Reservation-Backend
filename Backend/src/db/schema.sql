CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE guests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,     
    beds INT NOT NULL,             
    price DECIMAL(10,2) NOT NULL,
    description TEXT
);


CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type_id INT NOT NULL,

    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
        ON DELETE RESTRICT
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    guest_id INT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    number_of_guests INT NOT NULL,
    special_requests TEXT,
    status ENUM('booked', 'cancelled', 'completed') DEFAULT 'booked',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL,
    FOREIGN KEY (guest_id) REFERENCES guests(id)
        ON DELETE SET NULL,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
        ON DELETE RESTRICT
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE CASCADE
);

DELIMITER //

CREATE TRIGGER trg_user_or_guest_insert
BEFORE INSERT ON reservations
FOR EACH ROW
BEGIN
  IF (NEW.user_id IS NULL AND NEW.guest_id IS NULL) OR
     (NEW.user_id IS NOT NULL AND NEW.guest_id IS NOT NULL) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Either user_id OR guest_id must be set, not both';
  END IF;
END//

CREATE TRIGGER trg_user_or_guest_update
BEFORE UPDATE ON reservations
FOR EACH ROW
BEGIN
  IF (NEW.user_id IS NULL AND NEW.guest_id IS NULL) OR
     (NEW.user_id IS NOT NULL AND NEW.guest_id IS NOT NULL) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Either user_id OR guest_id must be set, not both';
  END IF;
END//