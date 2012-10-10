/*
	Databaze Library
*/


/* Jednorazove tabulky */

CREATE TABLE admins (
	admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	admin_login VARCHAR(50) NOT NULL,
	admin_password CHAR(41) NOT NULL,
	UNIQUE (admin_login)
);

CREATE TABLE articles (
	article_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	article_title VARCHAR(255) NOT NULL,
	article_author VARCHAR(255) DEFAULT NULL,
	article_text TEXT DEFAULT NULL,
	article_date DATETIME DEFAULT NULL,
	article_active ENUM('Y', 'N') DEFAULT 'Y',	/* Y - Yes, N - No */
	INDEX (article_title),
	INDEX (article_author)
);

CREATE TABLE news (
	new_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	new_title VARCHAR(255) NOT NULL,
	new_text TEXT DEFAULT NULL,
	new_date DATETIME NOT NULL,
	new_active ENUM('Y', 'N') DEFAULT 'Y',		/* Y - Yes, N - No */
	INDEX (new_title)
);

CREATE TABLE links (
	link_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	link_url VARCHAR(255) NOT NULL,
	link_title VARCHAR(255) DEFAULT NULL,
	link_description TEXT DEFAULT NULL,
	link_active ENUM('Y', 'N') DEFAULT 'Y',		/* Y - Yes, N - No */
	UNIQUE (link_url),
	INDEX (link_title)
);

