
DROP DATABASE IF EXISTS wolvineers;
CREATE DATABASE IF NOT EXISTS wolvineers;
USE wolvineers;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    APIKEY VARCHAR(40) NOT NULL UNIQUE,
    user_name VARCHAR(30) NOT NULL,
    user_email VARCHAR(80) NOT NULL UNIQUE,
    user_role VARCHAR(50) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(80) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    visibility ENUM('public', 'private') NOT NULL,
    article_title VARCHAR(255) NOT NULL,
    descripcio TEXT,
    article_status ENUM('draft', 'published', 'archived') NOT NULL,
    user_id INT,
    category_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

CREATE TABLE properties (
    property_id INT AUTO_INCREMENT PRIMARY KEY,
    property_name VARCHAR(100) NOT NULL,
    format VARCHAR(50) NOT NULL
);

CREATE TABLE CategoriesXProperties (
    PKCXP INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    property_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE
);

CREATE TABLE PropertiesXArticles (
    PKPXA INT AUTO_INCREMENT PRIMARY KEY,
    article_value VARCHAR(5000) NOT NULL,
    position INT NOT NULL,
    property_id INT,
    article_id INT,
    FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE
);

CREATE TABLE comentaris (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    coment_text TEXT NOT NULL,
    article_id INT,
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE
);