/**
 Beginning Zend Framework
 loudbite database creation.
 Chapter 2
 */
# Database creation
CREATE DATABASE loudbite;


# Change into the database to run commands
USE loudbite;


# accounts Table Creation
CREATE TABLE accounts (
  id int(11) AUTO_INCREMENT PRIMARY KEY, 
  username varchar(20) NOT NULL UNIQUE, 
  email varchar(200) NOT NULL UNIQUE, 
  password varchar(20) NOT NULL, 
  status varchar(10) DEFAULT 'pending', 
  email_newsletter_status varchar(3) DEFAULT 'out', 
  email_type varchar(4) DEFAULT 'text', 
  email_favorite_artists_status varchar(3) DEFAULT 'out', 
  created_date DATETIME
);


# artists Table Creation
CREATE TABLE artists (
id int(11) AUTO_INCREMENT PRIMARY KEY, 
artist_name varchar(200) NOT NULL, 
genre varchar(100) NOT NULL, 
created_date DATETIME
);


# accounts_artists Table Creation
CREATE TABLE accounts_artists (
id int(11) AUTO_INCREMENT PRIMARY KEY, 
account_id int(11) NOT NULL, 
artist_id int(11) NOT NULL, 
created_date DATETIME,
rating int(1),
is_fav int(1)
);
