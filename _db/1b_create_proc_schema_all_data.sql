-- Adminer 4.8.1 MySQL 8.0.25 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `plavani` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `plavani`;

DELIMITER ;;

DROP PROCEDURE IF EXISTS `CntClubSeasonalStats`;;
CREATE PROCEDURE `CntClubSeasonalStats`(IN `FArg_clubID` int unsigned, IN `FArg_year` int unsigned)
SELECT id AS user_id,
       sum(CASE
               WHEN cup_id IS NULL THEN 0
               ELSE 1
           END) AS cnt
FROM
  (SELECT id,
          first_name,
          last_name,
          affiliation_club_id
   FROM sp_users
   WHERE affiliation_club_id=FArg_clubID) t3
LEFT JOIN
  (SELECT t2.cup_id,
          t2.user_id
   FROM
     (SELECT id
      FROM sp_cups
      WHERE YEAR(time_start)=FArg_year) t1
   INNER JOIN
     (SELECT DISTINCT cup_id,
                      user_id
      FROM sp_user_position_pairing) t2 ON t1.id=t2.cup_id) t4 ON t3.id=t4.user_id
GROUP BY id;;

DROP PROCEDURE IF EXISTS `CntCupsAttendOfUserGivenYear`;;
CREATE PROCEDURE `CntCupsAttendOfUserGivenYear`(IN `FArg_userID` int unsigned, IN `FArg_year` int unsigned)
SELECT COUNT(*) AS cnt
FROM
  (SELECT DISTINCT cup_id
   FROM sp_user_position_pairing
   WHERE user_id=FArg_userID) MyAttendedCups
INNER JOIN
  (SELECT id
   FROM sp_cups
   WHERE YEAR(sp_cups.time_start)=FArg_year) CupsThisYear ON MyAttendedCups.cup_id = CupsThisYear.id;;

DROP PROCEDURE IF EXISTS `CntOverallStatsOfUserGivenYear`;;
CREATE PROCEDURE `CntOverallStatsOfUserGivenYear`(IN `FArg_userID` int unsigned, IN `FArg_year` int unsigned)
SELECT Position.id AS position_id,
       CASE
         WHEN Statistic.cnt IS NULL THEN 0
         ELSE Statistic.cnt
       end         AS cnt
FROM   (SELECT id
        FROM   sp_positions
        ORDER  BY sp_positions.id ASC) Position
       LEFT JOIN (SELECT position_id,
                         Count(position_id) AS cnt
                  FROM   sp_user_position_pairing
                         INNER JOIN sp_cups
                                 ON sp_user_position_pairing.cup_id = sp_cups.id
                  WHERE  sp_user_position_pairing.user_id = farg_userid
                         AND Year(sp_cups.time_start) = farg_year
                  GROUP  BY sp_user_position_pairing.position_id
                  ORDER  BY sp_user_position_pairing.position_id DESC) Statistic
              ON Position.id = Statistic.position_id
ORDER  BY Position.id ASC;;

DROP PROCEDURE IF EXISTS `DeleteOldAvailability`;;
CREATE PROCEDURE `DeleteOldAvailability`(IN `FArg_cupID` int unsigned)
DELETE
FROM `sp_user_cup_availability`
WHERE `cup_id`=FArg_cupID;;

DROP PROCEDURE IF EXISTS `DeleteOldPairing`;;
CREATE PROCEDURE `DeleteOldPairing`(IN `FArg_cupID` int unsigned)
DELETE
FROM `sp_user_position_pairing`
WHERE `cup_id`=FArg_cupID;;

DROP PROCEDURE IF EXISTS `DeleteOldStatsPositions`;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteOldStatsPositions`()
DELETE FROM `sp_public_stats_config`;;

DROP PROCEDURE IF EXISTS `FindAllActiveUsersOrderByLastNameAsc`;;
CREATE PROCEDURE `FindAllActiveUsersOrderByLastNameAsc`()
SELECT `id`,
       `first_name`,
       `last_name`,
       `email`,
       `approved_flag`,
       `rights`,
       `referee_rank_id`,
       `affiliation_club_id`
FROM `sp_users`
WHERE `approved_flag`=1
ORDER BY `last_name` ASC;;

DROP PROCEDURE IF EXISTS `FindAllClubs`;;
CREATE PROCEDURE `FindAllClubs`()
SELECT `id`,
       `name`,
       `abbreviation`,
       `code`,
       `img`,
       `affiliation_region_id`
FROM `sp_clubs`;;

DROP PROCEDURE IF EXISTS `FindAllInactiveUsersOrderByLastNameAsc`;;
CREATE PROCEDURE `FindAllInactiveUsersOrderByLastNameAsc`()
SELECT `id`,
       `first_name`,
       `last_name`,
       `email`,
       `approved_flag`,
       `rights`,
       `referee_rank_id`,
       `affiliation_club_id`
FROM `sp_users`
WHERE `approved_flag`=0
ORDER BY `last_name` ASC;;

DROP PROCEDURE IF EXISTS `FindAllPastCupsMostRecentFirst`;;
CREATE PROCEDURE `FindAllPastCupsMostRecentFirst`()
SELECT `id`,
       `time_start`,
       `time_end`,
       `name`,
       `description`,
       `organizer_club_id`
FROM `sp_cups`
WHERE `time_end` <= NOW()
ORDER BY `time_start` DESC;;

DROP PROCEDURE IF EXISTS `FindAllPositions`;;
CREATE PROCEDURE `FindAllPositions`()
SELECT `id`,
       `name`
FROM `sp_positions`
ORDER BY `id` ASC;;

DROP PROCEDURE IF EXISTS `FindAllPostsOrderByIDDesc`;;
CREATE PROCEDURE `FindAllPostsOrderByIDDesc`()
SELECT `id`,
       `timestamp`,
       `title`,
       `content`,
       `display_flag`,
       `author_user_id`,
       `signature_flag`
FROM `sp_posts`
ORDER BY `id` DESC;;

DROP PROCEDURE IF EXISTS `FindAllRefereeRanks`;;
CREATE PROCEDURE `FindAllRefereeRanks`()
SELECT `id`,
       `name`
FROM `sp_referee_ranks`;;

DROP PROCEDURE IF EXISTS `FindAllRegions`;;
CREATE PROCEDURE `FindAllRegions`()
SELECT `id`,
       `name`,
       `abbreviation`
FROM `sp_regions`
ORDER BY `id` ASC;;

DROP PROCEDURE IF EXISTS `FindAllRegisteredTeamMembersForTheCup`;;
CREATE PROCEDURE `FindAllRegisteredTeamMembersForTheCup`(IN `FArg_cupID` int unsigned, IN `FArg_teamID` int unsigned)
SELECT `sp_user_cup_availability`.`user_id` AS id,
       `sp_users`.`first_name`,
       `sp_users`.`last_name`,
       `sp_users`.`email`,
       `sp_users`.`approved_flag`,
       `sp_users`.`rights`,
       `sp_users`.`referee_rank_id`,
       `sp_users`.`affiliation_club_id`
FROM `sp_user_cup_availability`
INNER JOIN `sp_users` ON `sp_user_cup_availability`.`user_id` = `sp_users`.`id`
WHERE `sp_user_cup_availability`.`cup_id`=FArg_cupID
  AND `sp_users`.`affiliation_club_id`=FArg_teamID;;

DROP PROCEDURE IF EXISTS `FindAllRegisteredUsersForTheCup`;;
CREATE PROCEDURE `FindAllRegisteredUsersForTheCup`(IN `FArg_cupID` int unsigned)
SELECT `sp_user_cup_availability`.`user_id` AS id,
       `sp_users`.`first_name`,
       `sp_users`.`last_name`,
       `sp_users`.`email`,
       `sp_users`.`approved_flag`,
       `sp_users`.`rights`,
       `sp_users`.`referee_rank_id`,
       `sp_users`.`affiliation_club_id`
FROM `sp_user_cup_availability`
INNER JOIN `sp_users` ON `sp_user_cup_availability`.`user_id` = `sp_users`.`id`
WHERE `sp_user_cup_availability`.`cup_id`=FArg_cupID;;

DROP PROCEDURE IF EXISTS `FindAllTeamMembers`;;
CREATE PROCEDURE `FindAllTeamMembers`(IN `FArg_teamID` int unsigned)
SELECT `id`,
       `first_name`,
       `last_name`,
       `email`,
       `approved_flag`,
       `rights`,
       `referee_rank_id`,
       `affiliation_club_id`
FROM `sp_users`
WHERE `affiliation_club_id`=FArg_teamID;;

DROP PROCEDURE IF EXISTS `FindAllUpcomingCupsEarliestFirst`;;
CREATE PROCEDURE `FindAllUpcomingCupsEarliestFirst`()
SELECT `id`,
       `time_start`,
       `time_end`,
       `name`,
       `description`,
       `organizer_club_id`
FROM `sp_cups`
WHERE `time_end` >= NOW()
ORDER BY `time_start` ASC;;

DROP PROCEDURE IF EXISTS `FindAllUsers`;;
CREATE PROCEDURE `FindAllUsers`()
SELECT `id`,
       `first_name`,
       `last_name`,
       `email`,
       `approved_flag`,
       `rights`,
       `referee_rank_id`,
       `affiliation_club_id`
FROM `sp_users`;;

DROP PROCEDURE IF EXISTS `FindLastNPosts`;;
CREATE PROCEDURE `FindLastNPosts`(IN `FArg_N` int unsigned)
SELECT `id`,
       `timestamp`,
       `title`,
       `content`,
       `display_flag`,
       `author_user_id`,
       `signature_flag`
FROM `sp_posts`
WHERE `display_flag`=1
ORDER BY `id` DESC
LIMIT FArg_N;;

DROP PROCEDURE IF EXISTS `FindLastThreePosts`;;
CREATE PROCEDURE `FindLastThreePosts`()
SELECT `id`,
       `timestamp`,
       `title`,
       `content`,
       `display_flag`,
       `author_user_id`,
       `signature_flag`
FROM `sp_posts`
WHERE `display_flag`=1
ORDER BY `id` DESC
LIMIT 3;;

DROP PROCEDURE IF EXISTS `FindPairedUsersOnCupForPosition`;;
CREATE PROCEDURE `FindPairedUsersOnCupForPosition`(IN `FArg_cupID` int unsigned, IN `FArg_posID` int unsigned)
SELECT `sp_user_position_pairing`.`user_id` AS id,
       `sp_users`.`first_name`,
       `sp_users`.`last_name`,
       `sp_users`.`email`,
       `sp_users`.`approved_flag`,
       `sp_users`.`rights`,
       `sp_users`.`referee_rank_id`,
       `sp_users`.`affiliation_club_id`
FROM `sp_user_position_pairing`
INNER JOIN `sp_users` ON `sp_user_position_pairing`.`user_id` = `sp_users`.`id`
WHERE `sp_user_position_pairing`.`cup_id`=FArg_cupID
  AND `sp_user_position_pairing`.`position_id`=FArg_posID;;

DROP PROCEDURE IF EXISTS `FindPairingsForThisCup`;;
CREATE PROCEDURE `FindPairingsForThisCup`(IN `FArg_cupID` int unsigned)
SELECT `position_id`,
       `user_id`
FROM `sp_user_position_pairing`
WHERE `cup_id`=FArg_cupID
ORDER BY `position_id`;;

DROP PROCEDURE IF EXISTS `GetClubAbbreviationByAffiliationID`;;
CREATE PROCEDURE `GetClubAbbreviationByAffiliationID`(IN `FArg_cupID` int unsigned)
SELECT `abbreviation`
FROM `sp_clubs`
WHERE `id`=FArg_cupID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetClubAffiliationToRegion`;;
CREATE PROCEDURE `GetClubAffiliationToRegion`(IN `FArg_clubID` int)
SELECT `affiliation_region_id`
FROM `sp_clubs`
WHERE `id`=FArg_clubID;;

DROP PROCEDURE IF EXISTS `GetClubByID`;;
CREATE PROCEDURE `GetClubByID`(IN `FArg_clubID` int unsigned)
SELECT `id`,
       `name`,
       `abbreviation`,
       `code`,
       `img`,
       `affiliation_region_id`
FROM `sp_clubs`
WHERE id=FArg_clubID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetClubNameByAffiliationID`;;
CREATE PROCEDURE `GetClubNameByAffiliationID`(IN `FArg_clubID` int unsigned)
SELECT `name`
FROM `sp_clubs`
WHERE id=FArg_clubID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetConfiguredStats`;;
CREATE PROCEDURE `GetConfiguredStats`()
SELECT `sp_public_stats_config`.`position_id` AS id,
       `sp_positions`.`name` AS name
FROM `sp_public_stats_config`
LEFT JOIN `sp_positions` ON `sp_public_stats_config`.`position_id`=`sp_positions`.`id`
ORDER BY `position_id` ASC;;

DROP PROCEDURE IF EXISTS `GetCupByID`;;
CREATE PROCEDURE `GetCupByID`(IN `FArg_cupID` int unsigned)
SELECT `id`,
       `time_start`,
       `time_end`,
       `name`,
       `description`,
       `organizer_club_id`
FROM `sp_cups`
WHERE id=FArg_cupID;;

DROP PROCEDURE IF EXISTS `GetEarliestCupYear`;;
CREATE PROCEDURE `GetEarliestCupYear`()
SELECT YEAR(`time_start`)
FROM `sp_cups`
ORDER BY `time_start` ASC
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetFollowingPost`;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFollowingPost`(IN `FArg_id` INT UNSIGNED)
SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag`
FROM `sp_posts`
WHERE `id`<FArg_id
AND `display_flag`=1
ORDER BY `id` DESC
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetNewCupIDByInfo`;;
CREATE PROCEDURE `GetNewCupIDByInfo`(IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_dateB` text CHARACTER SET 'utf8mb4', IN `FArg_dateE` text CHARACTER SET 'utf8mb4')
SELECT id
FROM `sp_cups`
WHERE time_start=FArg_dateB
  AND time_end=FArg_dateE
  AND name=FArg_name;;

DROP PROCEDURE IF EXISTS `GetPageByID`;;
CREATE PROCEDURE `GetPageByID`(IN `FArg_pageID` int unsigned)
SELECT `id`,
       `title`,
       `content`
FROM `sp_pages`
WHERE `id`=FArg_pageID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetPositionNameByID`;;
CREATE PROCEDURE `GetPositionNameByID`(IN `FArg_posID` int unsigned)
SELECT name
FROM `sp_positions`
WHERE `id`=FArg_posID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetPostByID`;;
CREATE PROCEDURE `GetPostByID`(IN `FArg_ID` int unsigned)
SELECT `id`,
       `timestamp`,
       `title`,
       `content`,
       `display_flag`,
       `author_user_id`,
       `signature_flag`
FROM `sp_posts`
WHERE `id` = FArg_ID;;

DROP PROCEDURE IF EXISTS `GetRefereeRank`;;
CREATE PROCEDURE `GetRefereeRank`(IN `FArg_rankID` int unsigned)
SELECT `name`
FROM `sp_referee_ranks`
WHERE `id`=FArg_rankID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetRegionByID`;;
CREATE PROCEDURE `GetRegionByID`(IN `FArg_id` int unsigned)
SELECT `id`,
       `name`,
       `abbreviation`
FROM `sp_regions`
WHERE id=FArg_id;;

DROP PROCEDURE IF EXISTS `GetRegionNameOfClub`;;
CREATE PROCEDURE `GetRegionNameOfClub`(IN `FArg_regID` int unsigned)
SELECT `name`
FROM `sp_regions`
WHERE `id`=FArg_regID;;

DROP PROCEDURE IF EXISTS `GetUserByID`;;
CREATE PROCEDURE `GetUserByID`(IN `FArg_userID` int unsigned)
SELECT `id`,
       `first_name`,
       `last_name`,
       `email`,
       `approved_flag`,
       `rights`,
       `referee_rank_id`,
       `affiliation_club_id`
FROM `sp_users`
WHERE `id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `GetUserEmailByID`;;
CREATE PROCEDURE `GetUserEmailByID`(IN `FArg_userID` int unsigned)
SELECT `email`
FROM `sp_users`
WHERE `id`=FArg_userID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `GetUserFullNameByID`;;
CREATE PROCEDURE `GetUserFullNameByID`(IN `FArg_userID` int unsigned)
SELECT `first_name`,
       `last_name`
FROM `sp_users`
WHERE `id`=FArg_userID
LIMIT 1;;

DROP PROCEDURE IF EXISTS `HashPairingForThisCup`;;
CREATE PROCEDURE `HashPairingForThisCup`(IN `FArg_cupID` int unsigned)
SELECT MD5(GROUP_CONCAT(CONCAT(`position_id`, `user_id`))) AS hash
FROM `sp_user_position_pairing`
WHERE `cup_id`=FArg_cupID
ORDER BY `position_id`;;

DROP PROCEDURE IF EXISTS `InsertNewAvailability`;;
CREATE PROCEDURE `InsertNewAvailability`(IN `FArg_cupID` int unsigned, IN `FArg_userID` int unsigned, IN `FArg_attendF` int unsigned)
INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`, `attendance_flag`)
VALUES (NULL, FArg_cupID, FArg_userID, FArg_attendF);;

DROP PROCEDURE IF EXISTS `InsertNewClub`;;
CREATE PROCEDURE `InsertNewClub`(IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_abbrev` text CHARACTER SET 'utf8mb4', IN `FArg_code` int unsigned, IN `FArg_img` text CHARACTER SET 'utf8mb4', IN `FArg_affil` int unsigned)
INSERT INTO `sp_clubs` (`id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id`)
VALUES (NULL, FArg_name, FArg_abbrev, FArg_code, FArg_img, FArg_affil);;

DROP PROCEDURE IF EXISTS `InsertNewCup`;;
CREATE PROCEDURE `InsertNewCup`(IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_dateB` text CHARACTER SET 'utf8mb4', IN `FArg_dateE` text CHARACTER SET 'utf8mb4', IN `FArg_clubID` int unsigned, IN `FArg_content` text CHARACTER SET 'utf8mb4')
INSERT INTO `sp_cups` (`name`, `time_start`, `time_end`, `organizer_club_id`, `description`)
VALUES (FArg_name, FArg_dateB, FArg_dateE, FArg_clubID, FArg_content);;

DROP PROCEDURE IF EXISTS `InsertNewPairing`;;
CREATE PROCEDURE `InsertNewPairing`(IN `FArg_cupID` int unsigned, IN `FArg_posID` int unsigned, IN `FArg_userID` int unsigned)
INSERT INTO `sp_user_position_pairing` (`id`, `cup_id`, `position_id`, `user_id`)
VALUES (NULL, FArg_cupID, FArg_posID, FArg_userID);;

DROP PROCEDURE IF EXISTS `InsertNewPost`;;
CREATE PROCEDURE `InsertNewPost`(IN `FArg_title` text CHARACTER SET 'utf8mb4', IN `FArg_content` text CHARACTER SET 'utf8mb4', IN `FArg_displ` tinyint, IN `FArg_auth` int unsigned, IN `FArg_sign` tinyint)
INSERT INTO `sp_posts` (`id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag`)
VALUES (NULL, CURRENT_TIMESTAMP, FArg_title, FArg_content, FArg_displ, FArg_auth, FArg_sign);;

DROP PROCEDURE IF EXISTS `InsertNewRegion`;;
CREATE PROCEDURE `InsertNewRegion`(IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_abbrev` text CHARACTER SET 'utf8mb4')
INSERT INTO `sp_regions` (`id`, `name`, `abbreviation`)
VALUES (NULL, FArg_name, FArg_abbrev);;

DROP PROCEDURE IF EXISTS `InsertNewStatPosition`;;
CREATE PROCEDURE `InsertNewStatPosition`(IN `FArg_posID` int unsigned)
INSERT INTO `sp_public_stats_config` (`id`, `position_id`)
VALUES (NULL, FArg_posID);;

DROP PROCEDURE IF EXISTS `IsComingINT`;;
CREATE PROCEDURE `IsComingINT`(IN `FArg_cupID` int unsigned, IN `FArg_userID` int unsigned)
SELECT `attendance_flag`
FROM `sp_user_cup_availability`
WHERE `cup_id`=FArg_cupID
  AND `user_id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `IsEmailPresentAlready`;;
CREATE PROCEDURE `IsEmailPresentAlready`(IN `FArg_email` text CHARACTER SET 'utf8mb4')
SELECT *
FROM `sp_users`
WHERE email=FArg_email;;

DROP PROCEDURE IF EXISTS `IsUserAvailableForTheCup`;;
CREATE PROCEDURE `IsUserAvailableForTheCup`(IN `FArg_userID` int unsigned, IN `FArg_cupID` int unsigned)
SELECT *
FROM `sp_user_cup_availability`
WHERE `cup_id`=FArg_cupID
  AND `user_id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `IsUserWithIDPresentAlready`;;
CREATE PROCEDURE `IsUserWithIDPresentAlready`(IN `FArg_userID` int unsigned)
SELECT *
FROM `sp_users`
WHERE id=FArg_userID;;

DROP PROCEDURE IF EXISTS `LoginCandidateToBeAuthorized`;;
CREATE PROCEDURE `LoginCandidateToBeAuthorized`(IN `FArg_email` text CHARACTER SET 'utf8mb4')
SELECT *
FROM `sp_users`
WHERE `email`=FArg_email
LIMIT 1;;

DROP PROCEDURE IF EXISTS `RegisterUser`;;
CREATE PROCEDURE `RegisterUser`(IN `FArg_first_name` text CHARACTER SET 'utf8mb4', IN `FArg_last_name` text CHARACTER SET 'utf8mb4', IN `FArg_email` text CHARACTER SET 'utf8mb4', IN `FArg_password` text CHARACTER SET 'utf8mb4', IN `FArg_hash` text CHARACTER SET 'utf8mb4', IN `FArg_active_flag` tinyint unsigned, IN `FArg_approved_flag` tinyint unsigned, IN `FArg_rights` int unsigned, IN `FArg_ref_rank` int unsigned, IN `FArg_affil` int unsigned)
INSERT INTO `sp_users` (`id`, `first_name`, `last_name`, `email`, `password`, `hash`, `active_flag`, `approved_flag`, `rights`, `referee_rank_id`, `affiliation_club_id`)
VALUES (NULL, FArg_first_name, FArg_last_name, FArg_email, FArg_password, FArg_hash, FArg_active_flag, FArg_approved_flag, FArg_rights, FArg_ref_rank, FArg_affil);;

DROP PROCEDURE IF EXISTS `SetApprovedForUser`;;
CREATE PROCEDURE `SetApprovedForUser`(IN `FArg_userID` int unsigned)
UPDATE `sp_users`
SET `approved_flag`='1'
WHERE `id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `SetAvailabilityCanGo`;;
CREATE PROCEDURE `SetAvailabilityCanGo`(IN `FArg_cupID` int unsigned, IN `FArg_userID` int unsigned)
UPDATE `sp_user_cup_availability`
SET `attendance_flag`=1
WHERE `cup_id`=FArg_cupID
  AND `user_id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `SetAvailabilityCantGo`;;
CREATE PROCEDURE `SetAvailabilityCantGo`(IN `FArg_cupID` int unsigned, IN `FArg_userID` int unsigned)
UPDATE `sp_user_cup_availability`
SET `attendance_flag`=0
WHERE `cup_id`=FArg_cupID
  AND `user_id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `SetAvailabilityRegister`;;
CREATE PROCEDURE `SetAvailabilityRegister`(IN `FArg_cupID` tinyint unsigned, IN `FArg_userID` tinyint unsigned)
INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`, `attendance_flag`)
VALUES (NULL, FArg_cupID, FArg_userID, 1);;

DROP PROCEDURE IF EXISTS `SetLoginEmailForUser`;;
CREATE PROCEDURE `SetLoginEmailForUser`(IN `FArg_userID` int unsigned, IN `FArg_email` text CHARACTER SET 'utf8mb4')
UPDATE `sp_users`
SET `email`=FArg_email
WHERE `id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `SetPasswordForUser`;;
CREATE PROCEDURE `SetPasswordForUser`(IN `FArg_userID` int, IN `FArg_password` text CHARACTER SET 'utf8mb4')
UPDATE `sp_users`
SET `password`=FArg_password
WHERE id=FArg_userID;;

DROP PROCEDURE IF EXISTS `SetRefereeRankForUser`;;
CREATE PROCEDURE `SetRefereeRankForUser`(IN `FArg_userID` int unsigned, IN `FArg_rank` int unsigned)
UPDATE `sp_users`
SET `referee_rank_id`=FArg_rank
WHERE `id`=FArg_userID;;

DROP PROCEDURE IF EXISTS `UpdateClub`;;
CREATE PROCEDURE `UpdateClub`(IN `FArg_id` int(100) unsigned, IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_abbrev` text CHARACTER SET 'utf8mb4', IN `FArg_code` int(100) unsigned, IN `FArg_img` text CHARACTER SET 'utf8mb4', IN `FArg_affil_regID` int(100) unsigned)
UPDATE `sp_clubs`
SET `name`=FArg_name,
    `abbreviation`=FArg_abbrev,
    `code`=FArg_code,
    `img`=FArg_img,
    `affiliation_region_id`=FArg_affil_regID
WHERE `id`=FArg_id;;

DROP PROCEDURE IF EXISTS `UpdatePage`;;
CREATE PROCEDURE `UpdatePage`(IN `FArg_pageID` int unsigned, IN `FArg_title` text CHARACTER SET 'utf8mb4', IN `FArg_content` text CHARACTER SET 'utf8mb4')
UPDATE `sp_pages`
SET `title` = FArg_title,
    `content` = FArg_content
WHERE `id` = FArg_pageID;;

DROP PROCEDURE IF EXISTS `UpdatePost`;;
CREATE PROCEDURE `UpdatePost`(IN `FArg_id` int unsigned, IN `FArg_title` text CHARACTER SET 'utf8mb4', IN `FArg_content` text CHARACTER SET 'utf8mb4', IN `FArg_disp_f` tinyint, IN `FArg_sign_f` tinyint)
UPDATE `sp_posts`
SET `title` = FArg_title,
    `content` = FArg_content,
    `display_flag` = FArg_disp_f,
    `signature_flag` = FArg_sign_f
WHERE `sp_posts`.`id` = FArg_id;;

DROP PROCEDURE IF EXISTS `UpdateRegion`;;
CREATE PROCEDURE `UpdateRegion`(IN `FArg_id` int, IN `FArg_name` text CHARACTER SET 'utf8mb4', IN `FArg_abbrev` text CHARACTER SET 'utf8mb4')
UPDATE `sp_regions`
SET `name`=FArg_name,
    `abbreviation`=FArg_abbrev
WHERE `id`=FArg_id;;

DELIMITER ;

CREATE TABLE `sp_clubs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `abbreviation` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `code` int DEFAULT NULL,
  `img` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs,
  `affiliation_region_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_clubs` (`id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id`) VALUES
(0,	'Český svaz plaveckých sportů',	'CSPS',	0,	'null.jpg',	0),
(1,	'Klub plaveckých sportů Vyškov',	'KPSVy',	614,	'null.jpg',	1),
(2,	'Tělovýchovná jednota Prostějov z.s.',	'PoPro',	805,	'null.jpg',	1),
(3,	'Plavecký klub Mohelnice',	'PKMoh',	803,	'null.jpg',	1),
(4,	'Plavecký klub Zábřeh',	'PKZá',	804,	'null.jpg',	1),
(5,	'Tělovýchovná jednota Spartak Přerov',	'SpPř',	808,	'null.jpg',	1),
(6,	'Tělovýchovný jednota Šumperk z.s.',	'TJŠum',	815,	'null.jpg',	1),
(7,	'SK UP Olomouc',	'UnOl',	809,	'null.jpg',	1),
(8,	'Plavecký klub Zlín',	'PK Zlín',	0,	'null.jpg',	2),
(9,	'Zlínský plavecký klub',	'ZlPK',	0,	'null-jpg',	2),
(10,	'Plavecké sporty Kroměříž',	'PSKr',	0,	'null.jpg',	2),
(11,	'TJ Holešov',	'TJHol',	0,	'null.jpg',	2),
(12,	'Spartak Uherský Brod',	'SPUB',	0,	'null.jpg',	2),
(13,	'Slovácká Slávia Uherské Hradiště',	'SlUH',	0,	'null.jpg',	2),
(14,	'TJ Rožnov pod Radhoštěm',	'TJRo',	0,	'null.jpg',	2);

CREATE TABLE `sp_cups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_start` date NOT NULL,
  `time_end` date NOT NULL,
  `name` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `description` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `organizer_club_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owningclub` (`organizer_club_id`),
  CONSTRAINT `FK_zavody_owningclub` FOREIGN KEY (`organizer_club_id`) REFERENCES `sp_clubs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_cups` (`id`, `time_start`, `time_end`, `name`, `description`, `organizer_club_id`) VALUES
(1,	'2023-01-26',	'2023-01-28',	'Pravidelny turnaj 4',	'Suspendisse malesuada dui sit amet sapien tristique, sed tincidunt quam posuere. Mauris rhoncus placerat magna, id rhoncus libero euismod id.',	5),
(2,	'2023-02-26',	'2023-02-28',	'Vyroci zalozeni kostela 5',	'Pellentesque lacinia mollis pharetra. Praesent sit amet ligula vehicula, faucibus ante non, posuere lorem. Vestibulum vitae purus imperdiet, scelerisque arcu.',	9),
(3,	'2023-03-26',	'2023-03-28',	'Oslava zalozeni mesta 7',	'Vestibulum semper dui quis libero pellentesque cursus. Donec orci ex, vulputate eu pretium eu, elementum at lacus. Aenean auctor hendrerit.',	7),
(4,	'2023-04-26',	'2023-04-28',	'O pohar Starosty 3',	'Pellentesque lacinia mollis pharetra. Praesent sit amet ligula vehicula, faucibus ante non, posuere lorem. Vestibulum vitae purus imperdiet, scelerisque arcu.',	10),
(5,	'2023-05-26',	'2023-05-28',	'O pohar hejtmana 7',	'Duis euismod auctor ipsum. Integer sed facilisis odio, at scelerisque urna. Ut sed aliquam turpis. Pellentesque eget luctus velit, non.',	8),
(6,	'2023-06-26',	'2023-06-28',	'Mokry Eman 4',	'Curabitur lacus sapien, porttitor in dictum at, consectetur et odio. Fusce maximus, tellus vel egestas gravida, elit nisi elementum ligula.',	9),
(7,	'2023-07-26',	'2023-07-28',	'Vyrocni turnaj 5',	'Duis euismod auctor ipsum. Integer sed facilisis odio, at scelerisque urna. Ut sed aliquam turpis. Pellentesque eget luctus velit, non.',	0),
(8,	'2023-08-26',	'2023-08-28',	'K vyroci zalozeni krajske tradice 8',	'Curabitur lacus sapien, porttitor in dictum at, consectetur et odio. Fusce maximus, tellus vel egestas gravida, elit nisi elementum ligula.',	9),
(9,	'2023-09-26',	'2023-09-28',	'Ostavy zalozeni klubu 3',	'Integer varius magna nec orci efficitur vehicula. Vivamus consequat lectus sed pharetra semper. Quisque placerat rutrum blandit. Etiam et magna.',	0),
(10,	'2023-10-26',	'2023-10-28',	'Oslava zalozeni mesta 5',	'Vestibulum semper dui quis libero pellentesque cursus. Donec orci ex, vulputate eu pretium eu, elementum at lacus. Aenean auctor hendrerit.',	10),
(11,	'2023-11-26',	'2023-11-28',	'K vyroci zalozeni krajske tradice 1',	'Aliquam euismod mollis sagittis. Nulla facilisi. Morbi nec sapien arcu. Donec hendrerit velit at turpis suscipit vestibulum. Fusce non congue.',	10),
(12,	'2023-12-26',	'2023-12-28',	'Kondicni turnaj 2',	'Integer varius magna nec orci efficitur vehicula. Vivamus consequat lectus sed pharetra semper. Quisque placerat rutrum blandit. Etiam et magna.',	4);

CREATE TABLE `sp_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `content` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_pages` (`id`, `title`, `content`) VALUES
(1,	'Kontakty',	'<h2>Telefon na Luk&aacute;&scaron;e +420 724 224 292</h2>');

CREATE TABLE `sp_positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `poz` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_positions` (`id`, `name`) VALUES
(14,	'Cílový rozhodčí'),
(9,	'Časoměřič'),
(5,	'Časomíra'),
(4,	'Hlasatel'),
(18,	'Lékař'),
(10,	'Náhradní časoměřič'),
(12,	'Obrátkový rozhodčí'),
(6,	'Obsluha PC'),
(19,	'Ostatní'),
(3,	'Pomocný startér'),
(16,	'Protokol'),
(7,	'Rozhodčí plav. způsobů'),
(2,	'Startér'),
(15,	'Vedoucí protokolu'),
(13,	'Vrchní cílový rozhodčí'),
(8,	'Vrchní časoměřič'),
(11,	'Vrchní obrátkový rozhodčí'),
(1,	'Vrchní rozhodčí'),
(17,	'Výsledky');

CREATE TABLE `sp_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `title` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `content` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `display_flag` tinyint(1) NOT NULL,
  `author_user_id` int DEFAULT NULL,
  `signature_flag` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_posts` (`id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag`) VALUES
(1,	'2023-01-10 22:44:35',	'SwimmPair 1.0 live!',	'Aplikace SwimmPair v1.0 je hotova. Produkčn&iacute; verze pro testov&aacute;n&iacute; pro Luk&aacute;&scaron;e K. z TJ Prostějov bude dostupn&aacute; na&nbsp;<a href=\"http://swimmpair.cz/\">http://swimmpair.cz</a>, v&yacute;vojov&aacute; pak na&nbsp;<a href=\"http://swimmpair.stkl.cz/\">http://swimmpair.stkl.cz</a>&nbsp;k testov&aacute;n&iacute; oprav/nov&yacute;ch funkc&iacute;.<br />Z&aacute;kladn&iacute; instalace obsahuje: 2 uživatele (LK, &Scaron;K), &nbsp;2 regiony (OLK, ZLK, +ČSPS jako \"nult&yacute;\" pro nezař.), 14 klubů (ČSPS pro voln&eacute; rozhodč&iacute;) a 19 pozic na z&aacute;vody (1. Vrchn&iacute; rozhodč&iacute;, ..., 19. Ostatn&iacute;).<br />Repozit&aacute;ř s k&oacute;dem projektu ve veřejn&eacute;m repozit&aacute;ři&nbsp;<a href=\"https://github.com/KlosStepan/SwimmPair-Www\">https://github.com/KlosStepan/SwimmPair-Www</a>&nbsp;na GitHubu.',	1,	NULL,	1);

CREATE TABLE `sp_public_stats_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_public_stats_config` (`id`, `position_id`) VALUES
(1,	1),
(2,	2),
(3,	3),
(4,	4),
(5,	5),
(6,	6),
(7,	7),
(8,	8),
(9,	9),
(10,	10),
(11,	11),
(12,	12),
(13,	13),
(14,	14),
(15,	15),
(16,	16),
(17,	17),
(18,	18),
(19,	19);

CREATE TABLE `sp_referee_ranks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_referee_ranks` (`id`, `name`) VALUES
(1,	'I.'),
(2,	'II.'),
(3,	'III.'),
(4,	'FINA');

CREATE TABLE `sp_regions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `abbreviation` varchar(10) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_regions` (`id`, `name`, `abbreviation`) VALUES
(0,	'Český svaz plaveckých sportů',	'CSPS'),
(1,	'Olomoucký kraj',	'OLK'),
(2,	'Zlínský kraj',	'ZLK');

CREATE TABLE `sp_user_cup_availability` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cup_id` int NOT NULL,
  `user_id` int NOT NULL,
  `attendance_flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `zavodid` (`cup_id`),
  KEY `userid` (`user_id`),
  CONSTRAINT `FK_dostupnost_userid` FOREIGN KEY (`user_id`) REFERENCES `sp_users` (`id`),
  CONSTRAINT `FK_dostupnost_zavodid` FOREIGN KEY (`cup_id`) REFERENCES `sp_cups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`, `attendance_flag`) VALUES
(1,	1,	43,	1),
(2,	1,	96,	1),
(3,	1,	83,	1),
(4,	1,	1,	1),
(5,	1,	59,	1),
(6,	1,	94,	1),
(7,	1,	13,	1),
(8,	1,	28,	1),
(9,	1,	99,	1),
(10,	1,	95,	1),
(11,	1,	14,	1),
(12,	1,	6,	1),
(13,	1,	5,	1),
(14,	1,	58,	1),
(15,	1,	46,	1),
(16,	1,	66,	1),
(17,	1,	12,	1),
(18,	1,	56,	1),
(19,	1,	18,	1),
(20,	1,	80,	1),
(21,	2,	7,	1),
(22,	2,	96,	1),
(23,	2,	4,	1),
(24,	2,	83,	1),
(25,	2,	34,	1),
(26,	2,	36,	1),
(27,	2,	21,	1),
(28,	2,	95,	1),
(29,	2,	48,	1),
(30,	2,	6,	1),
(31,	2,	41,	1),
(32,	2,	87,	1),
(33,	2,	58,	1),
(34,	2,	46,	1),
(35,	2,	12,	1),
(36,	2,	45,	1),
(37,	2,	18,	1),
(38,	2,	80,	1),
(39,	2,	71,	1),
(40,	2,	82,	1),
(41,	3,	64,	1),
(42,	3,	93,	1),
(43,	3,	63,	1),
(44,	3,	96,	1),
(45,	3,	27,	1),
(46,	3,	4,	1),
(47,	3,	78,	1),
(48,	3,	69,	1),
(49,	3,	1,	1),
(50,	3,	68,	1),
(51,	3,	59,	1),
(52,	3,	94,	1),
(53,	3,	86,	1),
(54,	3,	54,	1),
(55,	3,	42,	1),
(56,	3,	23,	1),
(57,	3,	45,	1),
(58,	3,	53,	1),
(59,	3,	71,	1),
(60,	3,	82,	1),
(61,	4,	73,	1),
(62,	4,	37,	1),
(63,	4,	27,	1),
(64,	4,	35,	1),
(65,	4,	1,	1),
(66,	4,	74,	1),
(67,	4,	22,	1),
(68,	4,	6,	1),
(69,	4,	3,	1),
(70,	4,	76,	1),
(71,	4,	97,	1),
(72,	4,	17,	1),
(73,	4,	25,	1),
(74,	4,	11,	1),
(75,	4,	20,	1),
(76,	4,	67,	1),
(77,	4,	66,	1),
(78,	4,	57,	1),
(79,	4,	70,	1),
(80,	4,	53,	1),
(81,	5,	72,	1),
(82,	5,	73,	1),
(83,	5,	65,	1),
(84,	5,	27,	1),
(85,	5,	34,	1),
(86,	5,	1,	1),
(87,	5,	74,	1),
(88,	5,	28,	1),
(89,	5,	95,	1),
(90,	5,	10,	1),
(91,	5,	6,	1),
(92,	5,	44,	1),
(93,	5,	88,	1),
(94,	5,	5,	1),
(95,	5,	58,	1),
(96,	5,	46,	1),
(97,	5,	20,	1),
(98,	5,	77,	1),
(99,	5,	57,	1),
(100,	5,	61,	1),
(101,	6,	40,	1),
(102,	6,	27,	1),
(103,	6,	33,	1),
(104,	6,	98,	1),
(105,	6,	2,	1),
(106,	6,	84,	1),
(107,	6,	94,	1),
(108,	6,	30,	1),
(109,	6,	86,	1),
(110,	6,	92,	1),
(111,	6,	42,	1),
(112,	6,	44,	1),
(113,	6,	97,	1),
(114,	6,	11,	1),
(115,	6,	5,	1),
(116,	6,	81,	1),
(117,	6,	67,	1),
(118,	6,	18,	1),
(119,	6,	29,	1),
(120,	6,	71,	1),
(121,	7,	43,	1),
(122,	7,	24,	1),
(123,	7,	62,	1),
(124,	7,	91,	1),
(125,	7,	78,	1),
(126,	7,	31,	1),
(127,	7,	36,	1),
(128,	7,	2,	1),
(129,	7,	54,	1),
(130,	7,	41,	1),
(131,	7,	76,	1),
(132,	7,	44,	1),
(133,	7,	97,	1),
(134,	7,	17,	1),
(135,	7,	88,	1),
(136,	7,	5,	1),
(137,	7,	58,	1),
(138,	7,	81,	1),
(139,	7,	66,	1),
(140,	7,	56,	1),
(141,	8,	64,	1),
(142,	8,	38,	1),
(143,	8,	93,	1),
(144,	8,	35,	1),
(145,	8,	31,	1),
(146,	8,	33,	1),
(147,	8,	32,	1),
(148,	8,	1,	1),
(149,	8,	9,	1),
(150,	8,	74,	1),
(151,	8,	68,	1),
(152,	8,	59,	1),
(153,	8,	39,	1),
(154,	8,	3,	1),
(155,	8,	44,	1),
(156,	8,	16,	1),
(157,	8,	18,	1),
(158,	8,	90,	1),
(159,	8,	80,	1),
(160,	8,	75,	1),
(161,	9,	73,	1),
(162,	9,	65,	1),
(163,	9,	100,	1),
(164,	9,	91,	1),
(165,	9,	96,	1),
(166,	9,	31,	1),
(167,	9,	33,	1),
(168,	9,	21,	1),
(169,	9,	84,	1),
(170,	9,	94,	1),
(171,	9,	13,	1),
(172,	9,	52,	1),
(173,	9,	26,	1),
(174,	9,	17,	1),
(175,	9,	25,	1),
(176,	9,	11,	1),
(177,	9,	46,	1),
(178,	9,	23,	1),
(179,	9,	57,	1),
(180,	9,	8,	1),
(181,	10,	7,	1),
(182,	10,	40,	1),
(183,	10,	64,	1),
(184,	10,	37,	1),
(185,	10,	4,	1),
(186,	10,	69,	1),
(187,	10,	55,	1),
(188,	10,	13,	1),
(189,	10,	85,	1),
(190,	10,	99,	1),
(191,	10,	14,	1),
(192,	10,	10,	1),
(193,	10,	50,	1),
(194,	10,	42,	1),
(195,	10,	25,	1),
(196,	10,	5,	1),
(197,	10,	77,	1),
(198,	10,	79,	1),
(199,	10,	61,	1),
(200,	10,	71,	1),
(201,	11,	7,	1),
(202,	11,	73,	1),
(203,	11,	38,	1),
(204,	11,	91,	1),
(205,	11,	96,	1),
(206,	11,	27,	1),
(207,	11,	78,	1),
(208,	11,	33,	1),
(209,	11,	98,	1),
(210,	11,	94,	1),
(211,	11,	22,	1),
(212,	11,	85,	1),
(213,	11,	67,	1),
(214,	11,	18,	1),
(215,	11,	8,	1),
(216,	11,	90,	1),
(217,	11,	29,	1),
(218,	11,	71,	1),
(219,	11,	75,	1),
(220,	11,	82,	1),
(221,	12,	7,	1),
(222,	12,	93,	1),
(223,	12,	100,	1),
(224,	12,	96,	1),
(225,	12,	27,	1),
(226,	12,	35,	1),
(227,	12,	31,	1),
(228,	12,	55,	1),
(229,	12,	15,	1),
(230,	12,	33,	1),
(231,	12,	98,	1),
(232,	12,	59,	1),
(233,	12,	86,	1),
(234,	12,	44,	1),
(235,	12,	97,	1),
(236,	12,	77,	1),
(237,	12,	23,	1),
(238,	12,	57,	1),
(239,	12,	70,	1),
(240,	12,	82,	1);

CREATE TABLE `sp_user_position_pairing` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `cup_id` int NOT NULL,
  `position_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idzav` (`cup_id`),
  KEY `idpoz` (`position_id`),
  KEY `iduser` (`user_id`),
  CONSTRAINT `FK_pozicerozhodci_pozice` FOREIGN KEY (`position_id`) REFERENCES `sp_positions` (`id`),
  CONSTRAINT `FK_pozicerozhodci_user` FOREIGN KEY (`user_id`) REFERENCES `sp_users` (`id`),
  CONSTRAINT `FK_pozicerozhodci_zavod` FOREIGN KEY (`cup_id`) REFERENCES `sp_cups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_user_position_pairing` (`id`, `cup_id`, `position_id`, `user_id`) VALUES
(1,	1,	19,	43),
(2,	1,	16,	96),
(3,	1,	3,	83),
(4,	1,	16,	1),
(5,	1,	7,	59),
(6,	1,	4,	94),
(7,	1,	4,	13),
(8,	1,	9,	28),
(9,	1,	16,	99),
(10,	1,	2,	95),
(11,	1,	13,	14),
(12,	1,	17,	6),
(13,	1,	4,	5),
(14,	1,	13,	58),
(15,	1,	15,	46),
(16,	1,	10,	66),
(17,	1,	2,	12),
(18,	1,	16,	56),
(19,	1,	10,	18),
(20,	1,	18,	80),
(21,	2,	5,	7),
(22,	2,	14,	96),
(23,	2,	11,	4),
(24,	2,	12,	83),
(25,	2,	16,	34),
(26,	2,	16,	36),
(27,	2,	11,	21),
(28,	2,	19,	95),
(29,	2,	8,	48),
(30,	2,	2,	6),
(31,	2,	9,	41),
(32,	2,	17,	87),
(33,	2,	3,	58),
(34,	2,	5,	46),
(35,	2,	1,	12),
(36,	2,	6,	45),
(37,	2,	6,	18),
(38,	2,	1,	80),
(39,	2,	15,	71),
(40,	2,	17,	82),
(41,	3,	9,	64),
(42,	3,	18,	93),
(43,	3,	15,	63),
(44,	3,	10,	96),
(45,	3,	13,	27),
(46,	3,	10,	4),
(47,	3,	9,	78),
(48,	3,	14,	69),
(49,	3,	6,	1),
(50,	3,	1,	68),
(51,	3,	19,	59),
(52,	3,	10,	94),
(53,	3,	10,	86),
(54,	3,	1,	54),
(55,	3,	16,	42),
(56,	3,	12,	23),
(57,	3,	6,	45),
(58,	3,	14,	53),
(59,	3,	18,	71),
(60,	3,	15,	82),
(61,	4,	5,	73),
(62,	4,	16,	37),
(63,	4,	3,	27),
(64,	4,	3,	35),
(65,	4,	2,	1),
(66,	4,	9,	74),
(67,	4,	2,	22),
(68,	4,	9,	6),
(69,	4,	4,	3),
(70,	4,	13,	76),
(71,	4,	9,	97),
(72,	4,	16,	17),
(73,	4,	19,	25),
(74,	4,	7,	11),
(75,	4,	18,	20),
(76,	4,	17,	67),
(77,	4,	19,	66),
(78,	4,	9,	57),
(79,	4,	2,	70),
(80,	4,	6,	53),
(81,	5,	7,	72),
(82,	5,	15,	73),
(83,	5,	4,	65),
(84,	5,	5,	27),
(85,	5,	2,	34),
(86,	5,	12,	1),
(87,	5,	13,	74),
(88,	5,	13,	28),
(89,	5,	5,	95),
(90,	5,	12,	10),
(91,	5,	16,	6),
(92,	5,	16,	44),
(93,	5,	15,	88),
(94,	5,	15,	5),
(95,	5,	9,	58),
(96,	5,	2,	46),
(97,	5,	17,	20),
(98,	5,	14,	77),
(99,	5,	18,	57),
(100,	5,	18,	61),
(101,	6,	16,	40),
(102,	6,	1,	27),
(103,	6,	16,	33),
(104,	6,	7,	98),
(105,	6,	10,	2),
(106,	6,	13,	84),
(107,	6,	19,	94),
(108,	6,	13,	30),
(109,	6,	13,	86),
(110,	6,	16,	92),
(111,	6,	16,	42),
(112,	6,	5,	44),
(113,	6,	13,	97),
(114,	6,	17,	11),
(115,	6,	11,	5),
(116,	6,	9,	81),
(117,	6,	13,	67),
(118,	6,	14,	18),
(119,	6,	1,	29),
(120,	6,	7,	71),
(121,	7,	16,	43),
(122,	7,	15,	24),
(123,	7,	19,	62),
(124,	7,	10,	91),
(125,	7,	5,	78),
(126,	7,	4,	31),
(127,	7,	9,	36),
(128,	7,	11,	2),
(129,	7,	17,	54),
(130,	7,	16,	41),
(131,	7,	8,	76),
(132,	7,	7,	44),
(133,	7,	18,	97),
(134,	7,	19,	17),
(135,	7,	17,	88),
(136,	7,	17,	5),
(137,	7,	5,	58),
(138,	7,	9,	81),
(139,	7,	5,	66),
(140,	7,	13,	56),
(141,	8,	4,	64),
(142,	8,	7,	38),
(143,	8,	8,	93),
(144,	8,	18,	35),
(145,	8,	15,	31),
(146,	8,	1,	33),
(147,	8,	7,	32),
(148,	8,	16,	1),
(149,	8,	12,	9),
(150,	8,	2,	74),
(151,	8,	12,	68),
(152,	8,	8,	59),
(153,	8,	15,	39),
(154,	8,	10,	3),
(155,	8,	16,	44),
(156,	8,	8,	16),
(157,	8,	12,	18),
(158,	8,	5,	90),
(159,	8,	17,	80),
(160,	8,	9,	75),
(161,	9,	4,	73),
(162,	9,	5,	65),
(163,	9,	7,	100),
(164,	9,	13,	91),
(165,	9,	9,	96),
(166,	9,	10,	31),
(167,	9,	18,	33),
(168,	9,	1,	21),
(169,	9,	19,	84),
(170,	9,	5,	94),
(171,	9,	13,	13),
(172,	9,	4,	52),
(173,	9,	3,	26),
(174,	9,	19,	17),
(175,	9,	8,	25),
(176,	9,	11,	11),
(177,	9,	2,	46),
(178,	9,	7,	23),
(179,	9,	12,	57),
(180,	9,	10,	8),
(181,	10,	4,	7),
(182,	10,	11,	40),
(183,	10,	5,	64),
(184,	10,	7,	37),
(185,	10,	17,	4),
(186,	10,	7,	69),
(187,	10,	7,	55),
(188,	10,	6,	13),
(189,	10,	7,	85),
(190,	10,	8,	99),
(191,	10,	4,	14),
(192,	10,	18,	10),
(193,	10,	8,	50),
(194,	10,	8,	42),
(195,	10,	1,	25),
(196,	10,	5,	5),
(197,	10,	13,	77),
(198,	10,	7,	79),
(199,	10,	7,	61),
(200,	10,	6,	71),
(201,	11,	18,	7),
(202,	11,	14,	73),
(203,	11,	17,	38),
(204,	11,	14,	91),
(205,	11,	10,	96),
(206,	11,	11,	27),
(207,	11,	15,	78),
(208,	11,	5,	33),
(209,	11,	13,	98),
(210,	11,	1,	94),
(211,	11,	8,	22),
(212,	11,	13,	85),
(213,	11,	12,	67),
(214,	11,	10,	18),
(215,	11,	12,	8),
(216,	11,	13,	90),
(217,	11,	13,	29),
(218,	11,	8,	71),
(219,	11,	17,	75),
(220,	11,	9,	82),
(221,	12,	16,	7),
(222,	12,	4,	93),
(223,	12,	2,	100),
(224,	12,	8,	96),
(225,	12,	11,	27),
(226,	12,	15,	35),
(227,	12,	9,	31),
(228,	12,	7,	55),
(229,	12,	15,	15),
(230,	12,	1,	33),
(231,	12,	18,	98),
(232,	12,	4,	59),
(233,	12,	14,	86),
(234,	12,	6,	44),
(235,	12,	16,	97),
(236,	12,	10,	77),
(237,	12,	8,	23),
(238,	12,	13,	57),
(239,	12,	3,	70),
(240,	12,	9,	82);

CREATE TABLE `sp_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `last_name` varchar(50) CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `email` varchar(100) CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `password` varchar(100) CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `hash` varchar(32) CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  `active_flag` tinyint(1) NOT NULL DEFAULT '0',
  `approved_flag` tinyint(1) NOT NULL DEFAULT '0',
  `rights` tinyint(1) NOT NULL,
  `referee_rank_id` int NOT NULL,
  `affiliation_club_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `klubaffil` (`affiliation_club_id`),
  CONSTRAINT `FK_users_klub` FOREIGN KEY (`affiliation_club_id`) REFERENCES `sp_clubs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_users` (`id`, `first_name`, `last_name`, `email`, `password`, `hash`, `active_flag`, `approved_flag`, `rights`, `referee_rank_id`, `affiliation_club_id`) VALUES
(1,	'Lukáš',	'Kousal',	'mam949@seznam.cz',	'$2y$10$O21BZYcfuIiPrmbU30SCNOYRQYR2nCOvHtl.7J.jGO8vRQJ0g/o6G',	'0e01938fc48a2cfb5f2217fbfb00722d',	1,	1,	2,	2,	2),
(2,	'Štěpán',	'Klos',	'stepanklos@gmail.com',	'$2y$10$KzPEoCcbmybKJJyEjYTOruGgxdtzwi/qyh2Etk7RuaVmroE5IGa0m',	'3435c378bb76d4357324dd7e69f3cd18',	1,	1,	2,	1,	2),
(3,	'David',	'Novotny',	'davidnovotny@centrum.cz',	'$2y$10$HXnuE18sMKkrlGtTNtnZOeSTDQHWXaBMTWpcCI2cmb6Raik1xtr/y',	'7f1de29e6da19d22b51c68001e7e0e54',	1,	1,	1,	2,	9),
(4,	'Marie',	'Hajkova',	'mariehajkova@pokec.sk',	'$2y$10$eXWWj83ajhpbOJ7OBKyOyOCVEGmBXb4iXdFpHFF11ps/3bZiiMhde',	'f1b6f2857fb6d44dd73c7041e0aa0f19',	1,	1,	1,	1,	6),
(5,	'Stepan',	'Pospisil',	'stepanpospisil@gmail.com',	'$2y$10$/3Poj7KyS.QkMZEhL5l7nub5HqGLAa.D0cq6LLKjBWsWmkyhE2IqS',	'6d0f846348a856321729a2f36734d1a7',	1,	1,	0,	1,	11),
(6,	'Vojtech',	'Novotny',	'vojtechnovotny@seznam.cz',	'$2y$10$CG8P34Q6.ttsJp.3PI85C.0PxYCAcOKQP2kALhFhOxUmIV3g9G0.O',	'41f1f19176d383480afa65d325c06ed0',	1,	1,	0,	1,	14),
(7,	'Eliska',	'Benesova',	'eliskabenesova@seznam.cz',	'$2y$10$ZKDLatYEopYQR3l4yKfGDe3HjhjsygXKv1PV4NDoikXyW7l6HqAjy',	'17e62166fc8586dfa4d1bc0e1742c08b',	1,	1,	1,	4,	5),
(8,	'Daniel',	'Sedlacek',	'danielsedlacek@gmail.com',	'$2y$10$8tQ0bKdLlRJWsK.ranAHoupT1m7UNAhxSANhVfHK7GwsQW.WvAt8i',	'7ce3284b743aefde80ffd9aec500e085',	1,	1,	0,	4,	1),
(9,	'David',	'Kral',	'davidkral@centrum.cz',	'$2y$10$veAxwMQCdHgUkulp3VIg/OQvbj.bYGji5UBCEdPetCHZsv5k7x9va',	'49182f81e6a13cf5eaa496d51fea6406',	1,	1,	0,	1,	6),
(10,	'Veronika',	'Novakova',	'veronikanovakova@centrum.cz',	'$2y$10$5VSMoW6y8eydBSf8j2AEmu3Kz13.A1xb44HDyMqTDIJWW8sVYpv1.',	'46ba9f2a6976570b0353203ec4474217',	1,	1,	0,	3,	5),
(11,	'David',	'Pokorny',	'davidpokorny@gmail.com',	'$2y$10$PYYQBHM7ugqPGCj4HgRuWeJ8uzGKVO4jF0DUs478WBQlE0gQFFlr6',	'4e4b5fbbbb602b6d35bea8460aa8f8e5',	1,	1,	1,	2,	11),
(12,	'Sofie',	'Pospisilova',	'sofiepospisilova@pokec.sk',	'$2y$10$lc8tmqjwYC2mTkbSwlI8deE1iRFMfPSVRW9j8QO5Dalr0rQ9GuGdO',	'd840cc5d906c3e9c84374c8919d2074e',	1,	1,	1,	2,	13),
(13,	'Ondrej',	'Marek',	'ondrejmarek@hotmail.com',	'$2y$10$VLoF09Tl.QVGQGoPPLyAW.Fs5KrrFw4ajVItkqQrIW5xiFkPMxZLO',	'0b8aff0438617c055eb55f0ba5d226fa',	1,	1,	1,	4,	1),
(14,	'Simon',	'Nemec',	'simonnemec@seznam.cz',	'$2y$10$riNei5cgjG0YYkWh/KVYXusjepwqqB6t/D0WVqsum9qWxzDdwg2wm',	'6f4922f45568161a8cdf4ad2299f6d23',	1,	1,	0,	2,	10),
(15,	'Tereza',	'Horakova',	'terezahorakova@pokec.sk',	'$2y$10$AkKuqGErXgkeeX.WKykdhuesQFDzv5CBCMp3AsI3Ojf21V1ba4Cum',	'28dd2c7955ce926456240b2ff0100bde',	1,	1,	1,	4,	2),
(16,	'Marek',	'Prochazka',	'marekprochazka@pokec.sk',	'$2y$10$gL4SQspb3jPHPC8CwfZYcOVAb125nSEDfhCgeVXSJqJSPgJzH.tPe',	'8dd48d6a2e2cad213179a3992c0be53c',	1,	1,	0,	3,	2),
(17,	'Karolina',	'Pokorna',	'karolinapokorna@centrum.cz',	'$2y$10$5O.HhfY.V3cv90mqN6wdNuu6iTSR2IgbJc0YTIs8MKsPa9kl5cU7.',	'3cec07e9ba5f5bb252d13f5f431e4bbb',	1,	1,	1,	1,	9),
(18,	'Julie',	'Ruzickova',	'julieruzickova@centrum.cz',	'$2y$10$l4W4tDIyoNVD7TTGHm4tcOEf.qZX6pZ3nwzP2U/Xpv2DyOx4J8x2W',	'faa9afea49ef2ff029a833cccc778fd0',	1,	1,	0,	1,	4),
(19,	'Katerina',	'Kralova',	'katerinakralova@hotmail.com',	'$2y$10$l5ZXgbrB3.tAccdYvDWaieidziVLMIGZ9WVADSJm17Relv9.rUhxu',	'2f2b265625d76a6704b08093c652fd79',	1,	1,	0,	4,	11),
(20,	'Julie',	'Pospisilova',	'juliepospisilova@email.cz',	'$2y$10$rNAqqCWftSt4ug2Bc5Y/7.PgQs72VAzdkt5hiNvfgnHRR18FZ.tg.',	'd709f38ef758b5066ef31b18039b8ce5',	1,	1,	0,	2,	13),
(21,	'Ema',	'Kralova',	'emakralova@seznam.cz',	'$2y$10$EQjm0HTbQZAdkUasz4isMOizXgguY8/VDmbBwnINVFG.nY.30OQAy',	'b5dc4e5d9b495d0196f61d45b26ef33e',	1,	1,	0,	1,	1),
(22,	'Kristyna',	'Nemcova',	'kristynanemcova@seznam.cz',	'$2y$10$3grjHPOAplrbmcwbS7L19uOjZgBD9inYxPA3M4F3t2YnuLyfxwqNS',	'6f4922f45568161a8cdf4ad2299f6d23',	1,	1,	1,	3,	14),
(23,	'Jiri',	'Prochazka',	'jiriprochazka@gmail.com',	'$2y$10$9pNx.FXq7HU0VuyfvR96vumeWb/KWDTEcg9nsaEsZ74zri8ODDN.6',	'846c260d715e5b854ffad5f70a516c88',	1,	1,	0,	2,	3),
(24,	'Karolina',	'Dvorakova',	'karolinadvorakova@gmail.com',	'$2y$10$E3mT7iv8MavguxeNmfpR2ezQGplyWGspxBANSKagzIi1Xu7jWDyYC',	'705f2172834666788607efbfca35afb3',	1,	1,	1,	1,	11),
(25,	'Simon',	'Pokorny',	'simonpokorny@centrum.cz',	'$2y$10$ZGf9ok/VOw6bNN7uO07C5O.eY06kY4PMhdd68iht/KOYtKYQ.TQx6',	'5fd0b37cd7dbbb00f97ba6ce92bf5add',	1,	1,	0,	4,	7),
(26,	'Tomas',	'Novak',	'tomasnovak@email.cz',	'$2y$10$o.BInEkFUXUvnMcThOcIIumyO0HAmrYMh95Dod.KbwqkNwUXFS28m',	'a760880003e7ddedfef56acb3b09697f',	1,	1,	0,	3,	2),
(27,	'Marie',	'Fialova',	'mariefialova@hotmail.com',	'$2y$10$saANfrf8jncOIdNuBLODTuZx3iVPVpIx4IHsgBsRrVJ7Yx7YXS.p2',	'69cb3ea317a32c4e6143e665fdb20b14',	1,	1,	1,	3,	5),
(28,	'Viktorie',	'Nemcova',	'viktorienemcova@centrum.cz',	'$2y$10$OVE0ifKbUxKpb.pM/aDeVeqLYaaZhdL4OYbl/nDutM11IKz6f8ywC',	'3ad7c2ebb96fcba7cda0cf54a2e802f5',	1,	1,	1,	2,	6),
(29,	'Vojtech',	'Svoboda',	'vojtechsvoboda@hotmail.com',	'$2y$10$RvSh2WHBv8FxYhdVmRej/e9Qr0n8sqhcFSaHCkCA8LT.Wmm9NXrTG',	'577ef1154f3240ad5b9b413aa7346a1e',	1,	1,	0,	3,	4),
(30,	'Daniel',	'Marek',	'danielmarek@centrum.cz',	'$2y$10$I7d/D09WmRM0bg7tmKe.XOAJEK8DXKOnIChREGPcr5cvm9IjYjyz6',	'5a4b25aaed25c2ee1b74de72dc03c14e',	1,	1,	1,	4,	8),
(31,	'Filip',	'Horak',	'filiphorak@pokec.sk',	'$2y$10$a8n4sSDYKB63vi0f7bQ6LeS.5qxFAxoSQKpfkr4Jiolo9xj2hqWOa',	'7f6ffaa6bb0b408017b62254211691b5',	1,	1,	0,	1,	1),
(32,	'Julie',	'Jelinkova',	'juliejelinkova@hotmail.com',	'$2y$10$v/v9voLIovrcdkZNMaw02uIiEGg3lZhh1V8A/QFgZ407nm7c5lDcC',	'd64a340bcb633f536d56e51874281454',	1,	1,	0,	4,	0),
(33,	'Lukas',	'Jelinek',	'lukasjelinek@pokec.sk',	'$2y$10$8tCL2gxXG4mdqW73r2TGtefzVo2tnbGCQ/EfL2nfB5glF71d5lTwy',	'63923f49e5241343aa7acb6a06a751e7',	1,	1,	1,	3,	3),
(34,	'Jan',	'Jelinek',	'janjelinek@pokec.sk',	'$2y$10$cc2ePTQegV2Rt0LuIZsC/.z3qU.ZxTZbqf5IvxBvsQdCMnCm2w1xW',	'0d3180d672e08b4c5312dcdafdf6ef36',	1,	1,	1,	3,	1),
(35,	'Ondrej',	'Horak',	'ondrejhorak@hotmail.com',	'$2y$10$LaSZ6Uy.X4Se2.RAkB6rq.Sar7Yh6zognuNfvOnw9DEKJ19ibXhdy',	'e2ef524fbf3d9fe611d5a8e90fefdc9c',	1,	1,	0,	4,	14),
(36,	'Dominik',	'Jelinek',	'dominikjelinek@seznam.cz',	'$2y$10$YkMF0lK.xKrYRqBCzIrscOqjlBYMDJaPFzEj8o/LJNVlfooY9VdoG',	'54229abfcfa5649e7003b83dd4755294',	1,	1,	0,	3,	4),
(37,	'Emma',	'Fialova',	'emmafialova@hotmail.com',	'$2y$10$yinUMRgintsr9lav/np24u24QyFcoZF3ROVLle6oEbDax0IS/VpCG',	'74db120f0a8e5646ef5a30154e9f6deb',	1,	1,	1,	4,	1),
(38,	'Jiri',	'Dvorak',	'jiridvorak@hotmail.com',	'$2y$10$qhhX2bUlHRXU4x1OsfNKq.MyffbGdp.X8RfBZb1uFoS/xSk8ChQ7S',	'68ce199ec2c5517597ce0a4d89620f55',	1,	1,	0,	4,	7),
(39,	'Vojtech',	'Nemec',	'vojtechnemec@seznam.cz',	'$2y$10$ElVwUAdLHCYu28RIgK6tU.q56mXXQhFgfp09YMnBjwAzJzuybmz1C',	'99c5e07b4d5de9d18c350cdf64c5aa3d',	1,	1,	0,	3,	9),
(40,	'Julie',	'Cerna',	'juliecerna@email.cz',	'$2y$10$hVL1rLJIOe/oLtPasCrhk.dQXufP9adzd.vPDhIioCBSzJZpwqqmC',	'812b4ba287f5ee0bc9d43bbf5bbe87fb',	1,	1,	0,	3,	6),
(41,	'Karolina',	'Pokorna',	'karolinapokorna@pokec.sk',	'$2y$10$YxyPKPh3hs8e41f.omW65.dda30P/u.bg3WsdHkquLHnt4zmq3BIK',	'e70611883d2760c8bbafb4acb29e3446',	1,	1,	1,	2,	7),
(42,	'Adela',	'Pokorna',	'adelapokorna@pokec.sk',	'$2y$10$GSwp1Z/.CRV3j9QieCy7r.ABjChQlZzEYzzPM1PDVv/fFqmt9zsRC',	'b53b3a3d6ab90ce0268229151c9bde11',	1,	1,	0,	3,	4),
(43,	'Tereza',	'Cerna',	'terezacerna@centrum.cz',	'$2y$10$7p80IHmcSeHFdBLcL6lHRuT7U2JkXqjCULl0.3mPtJM25GUb2D2nS',	'82489c9737cc245530c7a6ebef3753ec',	1,	1,	0,	2,	0),
(44,	'Anna',	'Pokorna',	'annapokorna@seznam.cz',	'$2y$10$WzX0VowbCXsKpLcrEMfiC.pY5NcFCT6yMpJHe19Zzo1e3irpFdil.',	'97e8527feaf77a97fc38f34216141515',	1,	1,	0,	2,	2),
(45,	'Filip',	'Ruzicka',	'filipruzicka@seznam.cz',	'$2y$10$Y4PVRv43jPJMwZbhj79xlecatrxf4k8L9ym4tzawg0bPDEh6fmHiu',	'8613985ec49eb8f757ae6439e879bb2a',	1,	1,	0,	4,	2),
(46,	'Barbora',	'Pospisilova',	'barborapospisilova@hotmail.com',	'$2y$10$LnfXH8SzXnXNtP0.v79Rbe2gsCWhMysBxIKCPmCtgFfUMIFI6qO3u',	'1be3bc32e6564055d5ca3e5a354acbef',	1,	1,	0,	2,	5),
(47,	'Ema',	'Pokorna',	'emapokorna@gmail.com',	'$2y$10$KttDxksh1GP5V1pnlxH9AOWp89UYOQPN3sXgPgsSRe8voBmknFyB.',	'642e92efb79421734881b53e1e1b18b6',	1,	1,	0,	2,	3),
(48,	'Marie',	'Novakova',	'marienovakova@pokec.sk',	'$2y$10$6Mk4Jd1WO/OJI8f5A.i52./xiM7.7OiR.OZXpQ9G0pttvpRT8RYFy',	'0efe32849d230d7f53049ddc4a4b0c60',	1,	1,	1,	4,	10),
(49,	'Simon',	'Vesely',	'simonvesely@centrum.cz',	'$2y$10$n9nh6FyVuL4xS2Jkj.22KOLKzKZF/ZWFjjqP0bjqWH4jQSAM5DT16',	'274ad4786c3abca69fa097b85867d9a4',	1,	1,	0,	3,	14),
(50,	'Veronika',	'Novotna',	'veronikanovotna@hotmail.com',	'$2y$10$v8PLtaFv4MQlfzhvgECAoOrCa/m8LUVKVBLWTxB5QpiOJVKzW/8Ve',	'0e01938fc48a2cfb5f2217fbfb00722d',	1,	1,	1,	1,	11),
(51,	'Tereza',	'Fialova',	'terezafialova@seznam.cz',	'$2y$10$QkuIvIjyDhF7Ra0UyefY7upTGLOW8hS16jyb5HoBtYe/o9yGb8IlS',	'aa169b49b583a2b5af89203c2b78c67c',	1,	1,	0,	4,	14),
(52,	'Lukas',	'Marek',	'lukasmarek@hotmail.com',	'$2y$10$C/D4s55KmHs4eYhQZolsCeQASlUaN40.WgPQ/vlQCmhHraMi6.0Sq',	'fa14d4fe2f19414de3ebd9f63d5c0169',	1,	1,	0,	1,	10),
(53,	'Natalie',	'Sedlackova',	'nataliesedlackova@gmail.com',	'$2y$10$tL1LyNlzWyLLRdvFq88TJuNeJM9w0va2egKmi1Jd3KJoGUA.T7V5a',	'58e4d44e550d0f7ee0a23d6b02d9b0db',	1,	1,	0,	4,	10),
(54,	'Lukas',	'Novotny',	'lukasnovotny@centrum.cz',	'$2y$10$T5HX0REZgtzphDn9uMzrluu7ZApUfKefNH6sDiJPCU.T9j3/y1XpO',	'43feaeeecd7b2fe2ae2e26d917b6477d',	1,	1,	0,	4,	6),
(55,	'Karolina',	'Horakova',	'karolinahorakova@hotmail.com',	'$2y$10$.8a3q.gtdq3CoyzwRwS.WuaQCpON4XeQ9yUze5acWcv12sIDCz/dO',	'812b4ba287f5ee0bc9d43bbf5bbe87fb',	1,	1,	1,	1,	1),
(56,	'Matyas',	'Prochazka',	'matyasprochazka@pokec.sk',	'$2y$10$veK4qyvd0yakkMmunMOIr.0AHGi08R2B.dgv4XfMXad34dQkIrGnC',	'b337e84de8752b27eda3a12363109e80',	1,	1,	1,	3,	5),
(57,	'Eliska',	'Prochazkova',	'eliskaprochazkova@pokec.sk',	'$2y$10$UMJ1CuGYCk1a1sAyJ8LDGOTBfLD5bYklQ.8DMgf5b6sRn3EAa15Cu',	'357a6fdf7642bf815a88822c447d9dc4',	1,	1,	0,	2,	5),
(58,	'Antonin',	'Pospisil',	'antoninpospisil@hotmail.com',	'$2y$10$XYfMGmY6MAD729pfzNGI0.GsrE6EfyICkviqu1mgW4.jalHWL3U7S',	'3ef815416f775098fe977004015c6193',	1,	1,	1,	3,	11),
(59,	'Lucie',	'Kucerova',	'luciekucerova@hotmail.com',	'$2y$10$C7Qhiw7oi8mrhBkuUNMk2OuuriAPTyjm27R97AMPZDDQlOrf23zFm',	'912d2b1c7b2826caf99687388d2e8f7c',	1,	1,	0,	3,	12),
(60,	'Klara',	'Pokorna',	'klarapokorna@email.cz',	'$2y$10$4bRoLRlDoycAcK.8ojetleYjrWrb3ViDiRoEFsEYExJ3LogK07lLG',	'e96ed478dab8595a7dbda4cbcbee168f',	1,	1,	0,	1,	12),
(61,	'Tomas',	'Ruzicka',	'tomasruzicka@gmail.com',	'$2y$10$K/.jBjhw2xlaRxpnHEb/XOTpkowDG8lJrMYS1lDGSVvt2wflDXFgK',	'3cef96dcc9b8035d23f69e30bb19218a',	1,	1,	1,	3,	7),
(62,	'Anna',	'Dvorakova',	'annadvorakova@email.cz',	'$2y$10$aPqygrymN9DboSMlQV85f.j1OJt3/Qe8YXdrx79Kz4TnSRBXHSf6y',	'6c524f9d5d7027454a783c841250ba71',	1,	1,	1,	4,	7),
(63,	'Julie',	'Fialova',	'juliefialova@centrum.cz',	'$2y$10$e1wkzgiFxpjW8b03VS6jSOgeeLjvjZs567hPIhmcuGw4f.GMWI45.',	'c042f4db68f23406c6cecf84a7ebb0fe',	1,	1,	0,	3,	6),
(64,	'Daniel',	'Cerny',	'danielcerny@centrum.cz',	'$2y$10$EJo8VFNnX0hnN84VR0H0NOiFQtTq.t0Y8WMesn7g0ksO.x5R3mt7y',	'55b37c5c270e5d84c793e486d798c01d',	1,	1,	1,	3,	10),
(65,	'Petr',	'Cerny',	'petrcerny@centrum.cz',	'$2y$10$ys0lukNqv8sY7CDLNiFBPezuv3vZzK/SBuYN6502GnnvidMVSZKBa',	'05f971b5ec196b8c65b75d2ef8267331',	1,	1,	0,	3,	9),
(66,	'Viktorie',	'Pospisilova',	'viktoriepospisilova@hotmail.com',	'$2y$10$GaofCx.7qbWBMVsjcH.fY.uWzn.x2cA4yoEIw8Al3xPtZ3Il3DnyS',	'1700002963a49da13542e0726b7bb758',	1,	1,	1,	1,	10),
(67,	'Emma',	'Pospisilova',	'emmapospisilova@hotmail.com',	'$2y$10$FgljqFLuE6IxvsbCrcxQBepRd/nQRBcq6vXbny404lravqciN12Te',	'c74d97b01eae257e44aa9d5bade97baf',	1,	1,	1,	3,	0),
(68,	'Matej',	'Kucera',	'matejkucera@email.cz',	'$2y$10$ne6GBSeeU2ljiS0uYphpveV/khGzHQBNc56QxUwzMOXZYVdrTQsvW',	'aab3238922bcc25a6f606eb525ffdc56',	1,	1,	1,	4,	0),
(69,	'Jan',	'Horak',	'janhorak@gmail.com',	'$2y$10$IUN.7mx0W1IG7Q0hdRUjsOV.z3MA9QXXJby.NnydQs/g/MD9TqlwC',	'92cc227532d17e56e07902b254dfad10',	1,	1,	0,	2,	6),
(70,	'Tomas',	'Sedlacek',	'tomassedlacek@gmail.com',	'$2y$10$dDoSgZItk4pGaV71oix1X.wzeigoDTq0Wig4nZdCrIT8A3CFQapXq',	'6f2268bd1d3d3ebaabb04d6b5d099425',	1,	1,	0,	4,	3),
(71,	'Veronika',	'Svobodova',	'veronikasvobodova@gmail.com',	'$2y$10$iluT85wd8lnW.YApl/4GKuYwMICvaniB7l0Y2ZRo8NbY2lQZ73Bt2',	'53c3bce66e43be4f209556518c2fcb54',	1,	1,	1,	2,	5),
(72,	'Karolina',	'Benesova',	'karolinabenesova@email.cz',	'$2y$10$jDxPfChnbuizt44mxoXhmurObDVgbp4CSio6LhcquKJNhVBBkr/xG',	'8cb22bdd0b7ba1ab13d742e22eed8da2',	1,	1,	1,	3,	5),
(73,	'Laura',	'Cerna',	'lauracerna@pokec.sk',	'$2y$10$HB9lhIQCykCts4H/UHumcuE9J08eILW8sx7HdQFEhZawEGOwSmKre',	'85d8ce590ad8981ca2c8286f79f59954',	1,	1,	0,	3,	12),
(74,	'Barbora',	'Kralova',	'barborakralova@hotmail.com',	'$2y$10$LilXNyWjJ23gnAtR1PjrhuQWxfhHfUD7Ke6WjHYWmvnE1bXSbd7ti',	'6a10bbd480e4c5573d8f3af73ae0454b',	1,	1,	0,	4,	10),
(75,	'Sofie',	'Svobodova',	'sofiesvobodova@pokec.sk',	'$2y$10$arjoS1zXhBNLZlYLTGq2ZeuYjnrDdhs2VPKa1wqJejjeStnP0MMBG',	'b2eeb7362ef83deff5c7813a67e14f0a',	1,	1,	1,	2,	0),
(76,	'Lucie',	'Pokorna',	'luciepokorna@pokec.sk',	'$2y$10$3/UQSAxYo77SrN.HWyzY9e44cFjhmU9VcngposCUrA75Wc/3iNouG',	'fe7ee8fc1959cc7214fa21c4840dff0a',	1,	1,	1,	2,	6),
(77,	'Martin',	'Prochazka',	'martinprochazka@seznam.cz',	'$2y$10$eS6UwiQPUtDxTXVr3egpWOIgWzVqOBmzm7rZjDkoi2e7ind8nwaQu',	'6f4922f45568161a8cdf4ad2299f6d23',	1,	1,	1,	1,	4),
(78,	'Ema',	'Hajkova',	'emahajkova@email.cz',	'$2y$10$ezTa.bJhpv6Md5bc/yiACeBJ8aMDRN2Mi4gppOSbUAvcnghNk/MaO',	'1d7f7abc18fcb43975065399b0d1e48e',	1,	1,	1,	1,	2),
(79,	'Antonin',	'Ruzicka',	'antoninruzicka@pokec.sk',	'$2y$10$AvGLllJqyaeSlFGf99KpReiJiFuE7U41IB9ST9tv2Hk6CwkUEULUO',	'e44fea3bec53bcea3b7513ccef5857ac',	1,	1,	0,	1,	13),
(80,	'Kristyna',	'Sedlackova',	'kristynasedlackova@pokec.sk',	'$2y$10$SP90iDf6VZW7xETi0HTIfOiifEwYoKcpyD6elmbWXUu.xo/e5TAT.',	'addfa9b7e234254d26e9c7f2af1005cb',	1,	1,	1,	4,	5),
(81,	'Adam',	'Pospisil',	'adampospisil@pokec.sk',	'$2y$10$LtDUDSRuNvYlNNljghwta.OIArorcleKXf.ahPbQi6F/rKBbv2JWe',	'b2f627fff19fda463cb386442eac2b3d',	1,	1,	1,	4,	10),
(82,	'Vojtech',	'Vesely',	'vojtechvesely@seznam.cz',	'$2y$10$yD1moevvgVp49WOjxDy4iuluHlCmXTpPKoFiuTmzwp2OECu.7mp2G',	'98dce83da57b0395e163467c9dae521b',	1,	1,	0,	1,	9),
(83,	'Martin',	'Horak',	'martinhorak@pokec.sk',	'$2y$10$lI57nWvIdNNPGgBXeI7M2exyemOprzn.Gf1GeogHofAKvaz6MYeTa',	'4311359ed4969e8401880e3c1836fbe1',	1,	1,	0,	1,	10),
(84,	'Klara',	'Kucerova',	'klarakucerova@pokec.sk',	'$2y$10$G4g15hz/3.9mPvIA/CMBZu0YBf280aATCUhuJmS.9JHcxJc6Y85SK',	'16c222aa19898e5058938167c8ab6c57',	1,	1,	1,	2,	11),
(85,	'Marie',	'Nemcova',	'marienemcova@pokec.sk',	'$2y$10$DjpYwCBJYYbplsSZaihBXOlQ8bS3thcf9zNFmUDyccnQZxA7n95qi',	'51d92be1c60d1db1d2e5e7a07da55b26',	1,	1,	0,	4,	4),
(86,	'Matej',	'Nemec',	'matejnemec@seznam.cz',	'$2y$10$7J7at15DFkrbv6v8km0DjeVk8kpdsOQ2moT2XyRKe1vVfR0cBiCna',	'210f760a89db30aa72ca258a3483cc7f',	1,	1,	0,	2,	2),
(87,	'Stepan',	'Pospisil',	'stepanpospisil@pokec.sk',	'$2y$10$jBUye33wpD2Xkd0P0b3gAu.aqM3w/DmB0R3NkaJeKRPBZO99Q0hJq',	'63538fe6ef330c13a05a3ed7e599d5f7',	1,	1,	0,	2,	9),
(88,	'Matej',	'Pospisil',	'matejpospisil@centrum.cz',	'$2y$10$nFbb0jhHtAH3xnSArn4w8O/G7BNQst8cPZIsLUoDUOncdw/mJBHum',	'4daa3db355ef2b0e64b472968cb70f0d',	1,	1,	1,	1,	7),
(89,	'Anna',	'Fialova',	'annafialova@email.cz',	'$2y$10$.AwEP5HlaFq3StMnP23fe.1/5I5j2olP8CKSeh6zkkPZpoBeUjaw.',	'66f041e16a60928b05a7e228a89c3799',	1,	1,	1,	3,	6),
(90,	'Stepan',	'Sedlacek',	'stepansedlacek@hotmail.com',	'$2y$10$sdAUU/r0kGpJMe1WI99t/evXUyKiQmcu0pmcXBCELS6BZm9urpqe6',	'37693cfc748049e45d87b8c7d8b9aacd',	1,	1,	0,	1,	10),
(91,	'Dominik',	'Fiala',	'dominikfiala@gmail.com',	'$2y$10$3zL6h0B66RebQNUOJfoNzuJvKUS4whKOgOy2jwpTY5o4ZYcxTeYba',	'1905aedab9bf2477edc068a355bba31a',	1,	1,	0,	1,	14),
(92,	'Petr',	'Novotny',	'petrnovotny@hotmail.com',	'$2y$10$sx6tQIkGpZoF7N5fShOabuUjoeGAZ3oxfMQLirQ/Zon20irQzuqMC',	'13f320e7b5ead1024ac95c3b208610db',	1,	1,	1,	4,	7),
(93,	'Sofie',	'Dvorakova',	'sofiedvorakova@gmail.com',	'$2y$10$YIzLqqik/qwABiAcXtXNRurhPSTuEB5VeNg0An35.2JrYBqFJpLfK',	'cc1aa436277138f61cda703991069eaf',	1,	1,	1,	1,	1),
(94,	'Adam',	'Marek',	'adammarek@pokec.sk',	'$2y$10$qfIPIFpASSP9xTeHeCOPsODQn0SCtfqalREEdACJk4MbwJ5fDSLSS',	'a49e9411d64ff53eccfdd09ad10a15b3',	1,	1,	1,	2,	3),
(95,	'Jakub',	'Nemec',	'jakubnemec@gmail.com',	'$2y$10$QQT/9rQpDbqZ7VcAzO7tXuTxmFWJ6u08018d0pzaqMb8LMvq6On0G',	'e744f91c29ec99f0e662c9177946c627',	1,	1,	1,	4,	1),
(96,	'Julie',	'Fialova',	'juliefialova@pokec.sk',	'$2y$10$KM1aBAxWkgMg8Ewwq4OfLudGQl8A0N1UxwxDVGeXwu/7rUOOeBBwK',	'432aca3a1e345e339f35a30c8f65edce',	1,	1,	0,	2,	8),
(97,	'Barbora',	'Pokorna',	'barborapokorna@email.cz',	'$2y$10$8b5d62IJpT5EIPCgUTPzfumzPE9HnrHAFekTihE44faNRH5vJkTHu',	'6aab1270668d8cac7cef2566a1c5f569',	1,	1,	1,	3,	4),
(98,	'Ema',	'Jelinkova',	'emajelinkova@pokec.sk',	'$2y$10$V5ZwH2TBoBu6zpC6kVTCxezxMi1v64KqtLAC7l1zFxA8FjAkbHzAy',	'f7664060cc52bc6f3d620bcedc94a4b6',	1,	1,	1,	2,	14),
(99,	'Tomas',	'Nemec',	'tomasnemec@hotmail.com',	'$2y$10$1JIANgkdNsGLkg3BBIlBPeqjhic3WPqToXaUQHcwnB/CbKgVAha/y',	'd240e3d38a8882ecad8633c8f9c78c9b',	1,	1,	0,	1,	5),
(100,	'Sofie',	'Dvorakova',	'sofiedvorakova@gmail.com',	'$2y$10$iNWkPMapWagS8fYm.iKn4uOqaDFKckTl4MTmAEJgnv44veXNbqY7i',	'555d6702c950ecb729a966504af0a635',	1,	1,	1,	4,	0);

-- 2023-01-13 23:10:01
