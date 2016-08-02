CREATE TABLE auth_login_attempts
(
    auth_login_attempt_id INT(100) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    auth_login_attempt_ip VARCHAR(500) NOT NULL,
    auth_login_attempt_blocked TINYINT(1) DEFAULT '0',
    auth_login_attempt_successful TINYINT(1) DEFAULT '1',
    auth_login_attempt_os VARCHAR(500),
    auth_login_attempt_useragent VARCHAR(5000),
    auth_login_attempt_browser VARCHAR(500),
    auth_login_attempt_time TIMESTAMP DEFAULT 'CURRENT_TIMESTAMP' NOT NULL,
    auth_login_attempt_userid INT(100) NOT NULL
);
CREATE INDEX auth_login_attempts___user ON auth_login_attempts (auth_login_attempt_userid);
CREATE TABLE auth_tokens
(
    auth_tokenid INT(100) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    auth_token_string VARCHAR(300) NOT NULL,
    auth_token_created VARCHAR(100) NOT NULL,
    auth_token_userid INT(100) NOT NULL,
    auth_token_ip VARCHAR(500) NOT NULL,
    auth_token_valid TINYINT(1) DEFAULT '1' NOT NULL,
    auth_token_useragent VARCHAR(5000),
    auth_token_os VARCHAR(500),
    auth_token_browser VARCHAR(500),
    auth_token_apptoken TINYINT(1) DEFAULT '0',
    auth_token_app_install VARCHAR(600)
);
CREATE INDEX auth_tokens___app_install ON auth_tokens (auth_token_app_install);
CREATE INDEX auth_tokens___users ON auth_tokens (auth_token_userid);
CREATE TABLE auth_users
(
    auth_userid INT(100) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    auth_username VARCHAR(255) NOT NULL,
    auth_email VARCHAR(1024) NOT NULL,
    auth_email_verified TINYINT(1) DEFAULT '0',
    auth_password_hash VARCHAR(300) NOT NULL,
    auth_password_salt_1 VARCHAR(100) NOT NULL,
    auth_password_salt_2 VARCHAR(100) NOT NULL,
    auth_created TIMESTAMP DEFAULT 'CURRENT_TIMESTAMP' NOT NULL,
    auth_signup_token VARCHAR(255),
    auth_suspended TINYINT(1) DEFAULT '0' NOT NULL,
    auth_notes VARCHAR(10000) DEFAULT 'Standard User',
    auth_forename VARCHAR(100) NOT NULL,
    auth_surname VARCHAR(100) NOT NULL
);
CREATE UNIQUE INDEX auth_users_auth_username_uindex ON auth_users (auth_username);