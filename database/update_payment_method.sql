USE taphoa_online;
ALTER TABLE orders ADD COLUMN payment_method ENUM('cod','banking') NOT NULL DEFAULT 'cod' AFTER note;
