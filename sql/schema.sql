-- Create the database
CREATE DATABASE IF NOT EXISTS mysite_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mysite_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (full_name, email, password) VALUES
('Sarah Wilson', 'Sarah.Wilson@astera.cg', '$2b$12$wBzzQyLEnPL8z/tWjgR6jer59R49P8umxhZePsxjlI1Zxr11aOmoq'),
('William Walker', 'William.Walker@astera.cg', '$2b$12$pZfg8r6.ZZSZDpzbrcKP3up45CqsNszZbN9xyTZIsAo7SgxlnfpM2'),
('Kevin Harris', 'Kevin.Harris@astera.cg', '$2b$12$bIJyt97i1xv0lEDD5xGXW.nWEkFMbiuWFl09UQ9xoQMTlpqp5oZ6m'),
('Emily Davis', 'Emily.Davis@astera.cg', '$2b$12$bhFiq7spGCD9nTND3vBnm./ifK9.05qKC7bWIPYMdgMf95WLgGiNW'),
('Sophia King', 'Sophia.King@astera.cg', '$2b$12$3pGrKMh14ZjnJ3zQArgJh.3.Q0qt2Zct2q0bVmrHYZOHYKzz.xn0G');