CREATE DATABASE `tech_hours` /*!40100 DEFAULT CHARACTER SET latin1 */;

DELIMITER $$
CREATE PROCEDURE `spAdminActivateJob`(IN `sp_job_id` INT(1))
    NO SQL
UPDATE jobs SET active = 1 WHERE job_id = sp_job_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminActivateUser`(IN `sp_user_id` INT)
    NO SQL
UPDATE users SET active = 1 WHERE user_id = sp_user_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminCreateJob`(IN `sp_location` VARCHAR(16), IN `sp_job` VARCHAR(32), IN `sp_account` VARCHAR(16), IN `sp_rate` DECIMAL, IN `sp_flat` INT(1))
    NO SQL
BEGIN
  
  INSERT INTO jobs (location, job, account, rate, flat)
    VALUES (sp_location, sp_job, sp_account, sp_rate, sp_flat);

  SELECT LAST_INSERT_ID() last_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminCreateLimitedUser`(IN `sp_name` VARCHAR(64), IN `sp_email` VARCHAR(128), IN `sp_street` VARCHAR(255), IN `sp_city` VARCHAR(64), IN `sp_state` VARCHAR(2), IN `sp_zip` int(11))
INSERT INTO users (name, email, street, city, state, zip, access)
VALUES (sp_name, IF(LENGTH(sp_email) = 0, NULL, sp_email), sp_street, sp_city, sp_state, sp_zip, 3)$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminCreateUser`(IN `sp_email` VARCHAR(64), IN `sp_key` VARCHAR(64), IN `sp_access` INT(1))
BEGIN

    INSERT INTO users (`email`, `access`, `reset_key`)
    VALUES (sp_email, sp_access, sp_key);

  SELECT LAST_INSERT_ID() last_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminDeactivateJob`(IN `sp_job_id` INT(1))
    NO SQL
UPDATE jobs SET active = 0 WHERE job_id = sp_job_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminDeactivateUser`(IN `sp_user_id` INT)
    NO SQL
UPDATE users SET active = 0 WHERE user_id = sp_user_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminGetJob`(IN `sp_job_id` INT)
    NO SQL
SELECT * FROM jobs WHERE job_id = sp_job_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminGetJobs`()
    NO SQL
SELECT *
FROM jobs
ORDER BY location, job$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminGetUserByID`(IN `sp_user_id` INT)
    NO SQL
SELECT *
FROM users
WHERE user_id = sp_user_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminGetUsers`()
    NO SQL
SELECT *,
CASE access
  WHEN 0 THEN 2
  ELSE access
END access
FROM users
ORDER BY active DESC, access, username$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spAdminUpdateJob`(IN `sp_job_id` INT, IN `sp_location` VARCHAR(16), IN `sp_job` VARCHAR(32), IN `sp_account` VARCHAR(16), IN `sp_rate` DECIMAL)
    NO SQL
UPDATE jobs SET
  location = sp_location,
  job = sp_job,
  account = sp_account,
  rate = sp_rate
WHERE job_id = sp_job_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourDeleteHours`(IN `sp_hour_id` INT)
    NO SQL
BEGIN

  SELECT SUM(paid) totalPaid
  FROM hours_complete
  WHERE
    submit_date IS NULL
    AND hour_id != sp_hour_id
    AND user_id = (
          SELECT user_id
          FROM hours_complete
          WHERE hour_id = sp_hour_id
      );

  DELETE FROM hours WHERE hour_id = sp_hour_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetAllSubmittedHours`()
SELECT *
FROM hours_complete
WHERE submit_user IS NOT NULL
AND submit_date > ADDDATE(NOW(), INTERVAL -5 DAY)
ORDER BY name, date$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetAllSubmittedHoursOrderAcct`()
    NO SQL
SELECT *
FROM hours_complete
WHERE submit_user IS NOT NULL
AND submit_date > ADDDATE(NOW(), INTERVAL -5 DAY)
ORDER BY name, account, date$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetAllUnsubmittedHours`()
    NO SQL
SELECT *
FROM hours_complete
WHERE submit_user IS NULL
ORDER BY name, date$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetAllUnsubmittedHoursOrderAcct`()
    NO SQL
SELECT *
FROM hours_complete
WHERE submit_user IS NULL
ORDER BY name, account, date$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetJobs`()
    NO SQL
SELECT *
FROM jobs
WHERE active = 1
ORDER BY location, job$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetSubmittedHours`(IN `sp_user_id` INT)
    NO SQL
SELECT *
FROM hours_complete
WHERE user_id = sp_user_id
AND submit_date IS NOT NULL
ORDER BY date DESC$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourGetUnsubmittedHours`(IN `sp_user_id` INT)
    NO SQL
SELECT *
FROM hours_complete
WHERE
user_id = sp_user_id
AND submit_user IS NULL
ORDER BY date$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHourModifyHours`(IN `sp_hour_id` INT, IN `sp_in1` TIME, IN `sp_out1` TIME, IN `sp_in2` TIME, IN `sp_out2` TIME, IN `sp_date` DATE, IN `sp_job_id` INT, IN `sp_comment` TEXT, IN `sp_user_id` INT)
BEGIN

  UPDATE hours SET
    in1 = sp_in1,
    out1 = sp_out1,
    in2 = sp_in2,
    out2 = sp_out2,
    date = sp_date,
    job_id = sp_job_id,
    comment = sp_comment,
    modify_user = sp_user_id,
    modify_date = Now()
  WHERE hour_id = sp_hour_id;

  SELECT
    in1,
    out1,
    in2,
    out2,
    date,
    job_id,
    job,
    location,
    comment,
    paid
  FROM hours_complete
  WHERE hour_id = sp_hour_id;

  SELECT SUM(paid) totalPaid
  FROM hours_complete
  WHERE
  submit_date IS NULL
  AND user_id = (
        SELECT user_id
        FROM hours_complete
        WHERE hour_id = sp_hour_id
    );

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spHoursGetNoHoursUsers`()
SELECT 
  U.user_id,
  U.`name`
FROM users U
LEFT OUTER JOIN hours H ON H.user_id = U.user_id AND H.submit_date IS NULL
WHERE H.hour_id IS NULL
AND U.access != 1
AND U.active = 1
ORDER BY U.`name` ASC$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserCreateAdminAccount`(IN `sp_user_id` INT, IN `sp_username` VARCHAR(64), IN `sp_name` VARCHAR(64), IN `sp_email` VARCHAR(64), IN `sp_password` VARCHAR(64))
    NO SQL
BEGIN

    UPDATE users SET
      username = sp_username,
      name = sp_name,
      email = sp_email,
      password = PASSWORD(sp_password),
      reset_key = NULL
    WHERE user_id = sp_user_id;

  SELECT * FROM users WHERE user_id = sp_user_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserCreateUserAccount`(IN `sp_user_id` INT, IN `sp_username` VARCHAR(64), IN `sp_name` VARCHAR(64), IN `sp_email` VARCHAR(64), IN `sp_street` VARCHAR(64), IN `sp_city` VARCHAR(64), IN `sp_state` VARCHAR(2), IN `sp_zip` INT, IN `sp_password` VARCHAR(64))
    NO SQL
BEGIN

    UPDATE users SET
      username = sp_username,
      name = sp_name,
      email = sp_email,
      street = sp_street,
      city = sp_city,
      state = sp_state,
      zip = sp_zip,
      password = PASSWORD(sp_password),
      reset_key = NULL
    WHERE user_id = sp_user_id;

  SELECT * FROM users WHERE user_id = sp_user_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserGetNewAccount`(IN `sp_key` VARCHAR(16))
    NO SQL
SELECT *
FROM users
WHERE reset_key = sp_key$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserGetUserByPassword`(IN `sp_username` VARCHAR(255), IN `sp_password` VARCHAR(255))
    NO SQL
BEGIN
  
  IF EXISTS (
        SELECT 1 FROM users WHERE (
          username = sp_username
            OR email = sp_username
        )
        AND password = PASSWORD(sp_password)
        AND active = 1
    ) THEN
               
      UPDATE users SET reset_key = NULL
        WHERE (
          username = sp_username
            OR email = sp_username
        )
        AND password = PASSWORD(sp_password)
        AND active = 1;
  END IF;

    SELECT
      user_id,
      username,
      name,
      email,
      access,
      street,
      city,
      state,
      zip,
      email_hours_submitted
    FROM users
    WHERE (
        username = sp_username
        OR email = sp_username
    )
    AND password = PASSWORD(sp_password)
    AND active = 1;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserPasswordReset`(IN `sp_username` VARCHAR(64), IN `sp_key` VARCHAR(16))
    NO SQL
BEGIN

  UPDATE users SET
    reset_key = sp_key
    WHERE username = sp_username;
    
    SELECT email, name FROM users WHERE username = sp_username;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserUpdatePassword`(IN `sp_user_id` INT, IN `sp_password` VARCHAR(32))
    NO SQL
UPDATE users SET
password = PASSWORD(sp_password)
WHERE user_id = sp_user_id$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `spUserUpdateUserInfo`(IN `sp_user_id` INT, IN `sp_username` VARCHAR(64), IN `sp_name` VARCHAR(64), IN `sp_email` VARCHAR(128), IN `sp_hours_submitted` INT(1), IN `sp_street` VARCHAR(128), IN `sp_city` VARCHAR(32), IN `sp_state` VARCHAR(2), IN `sp_zip` INT(5))
    NO SQL
UPDATE users SET
  username = sp_username,
  name = sp_name,
  email = sp_email,
  street = sp_street,
  city = sp_city,
  state = sp_state,
  zip = sp_zip
WHERE user_id = sp_user_id$$
DELIMITER ;

CREATE TABLE `archive` (
  `user` varchar(16) NOT NULL,
  `name` varchar(45) NOT NULL,
  `week` int(2) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL AUTO_INCREMENT,
  `in1` time NOT NULL DEFAULT '00:00:01',
  `out1` time NOT NULL DEFAULT '00:00:01',
  `in2` time NOT NULL DEFAULT '00:00:01',
  `out2` time NOT NULL DEFAULT '00:00:01',
  `total` decimal(11,2) NOT NULL DEFAULT '0.00',
  `rate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `flat` decimal(11,2) NOT NULL DEFAULT '0.00',
  `earned` decimal(11,2) NOT NULL DEFAULT '0.00',
  `day` varchar(16) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `job` varchar(45) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `account` varchar(16) NOT NULL,
  `submitdate` date NOT NULL DEFAULT '0000-00-00',
  `submitby` varchar(16) NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=1832 DEFAULT CHARSET=latin1;

CREATE TABLE `hours` (
  `hour_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `in1` time DEFAULT NULL,
  `out1` time DEFAULT NULL,
  `in2` time DEFAULT NULL,
  `out2` time DEFAULT NULL,
  `date` date NOT NULL,
  `comment` text,
  `job_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_date` datetime DEFAULT NULL,
  `modify_user` varchar(32) DEFAULT NULL,
  `submit_user` int(11) DEFAULT NULL,
  `submit_date` date DEFAULT NULL,
  PRIMARY KEY (`hour_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3794 DEFAULT CHARSET=latin1;

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job` varchar(45) NOT NULL,
  `account` varchar(16) NOT NULL,
  `rate` decimal(11,2) NOT NULL DEFAULT '0.00',
  `flat` int(1) NOT NULL DEFAULT '0',
  `location` varchar(64) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`job_id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `access` int(11) DEFAULT NULL,
  `password` varchar(41) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `reset_key` varchar(16) DEFAULT NULL,
  `email_hours_submitted` int(1) NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;

CREATE OR REPLACE VIEW hours_complete
AS
SELECT
  U.user_id,
  U.username,
  U.name,
  U.email,
  SU.user_id submit_user,
  SU.username submit_username,
  MU.user_id modify_user,
  MU.username modify_username,
  H.hour_id,
  H.in1,
  H.out1,
  H.in2,
  H.out2,
  H.date,
  H.comment,
  H.create_date,
  H.modify_date,
  H.submit_date,
  J.job_id,
  J.job,
  J.location,
  J.account,
  J.flat,
  J.rate,
  CASE
    WHEN J.rate IS NOT NULL THEN 
      ROUND(((TIME_TO_SEC(TIMEDIFF(H.out1, H.in1))/60) + (TIME_TO_SEC(TIMEDIFF(H.out2, H.in2))/60)) * J.rate/60, 2)
    ELSE J.flat
  END paid
FROM hours H
  INNER JOIN users U ON U.user_id = H.user_id
  LEFT JOIN users SU ON SU.user_id = H.submit_user
  LEFT JOIN users MU ON MU.user_id = H.modify_user
  INNER JOIN jobs J ON J.job_id = H.job_id;