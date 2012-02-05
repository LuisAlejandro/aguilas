-- --------------------------------------------------------
-- SQL Commands to set up the Aguilas DB
-- --------------------------------------------------------

-- 
-- Table structure for table `ResetPassword`
-- 

CREATE TABLE IF NOT EXISTS `ResetPassword` (
	`main_id` INT NOT NULL AUTO_INCREMENT,
	`uid` VARCHAR(255) NOT NULL,
	`mail` VARCHAR(255) NOT NULL,
	`token` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`main_id`)
)

-- 
-- Table structure for table `NewUser`
-- 

CREATE TABLE IF NOT EXISTS `NewUser` (
	`main_id` INT NOT NULL AUTO_INCREMENT,
	`uid` VARCHAR(255) NOT NULL,
	`givenName` VARCHAR(255) NOT NULL,
	`sn` VARCHAR(255) NOT NULL,
	`mail` VARCHAR(255) NOT NULL,
	`userPassword` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`token` VARCHAR(255) NOT NULL,
	PRIMARY KEY(`main_id`)
)

