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
(0,    'Český svaz plaveckých sportů',    'CSPS',    0,    'null.jpg',    0),
(1,    'Klub plaveckých sportů Vyškov',    'KPSVy',    614,    'null.jpg',    1),
(2,    'Tělovýchovná jednota Prostějov z.s.',    'PoPro',    805,    'null.jpg',    1),
(3,    'Plavecký klub Mohelnice',    'PKMoh',    803,    'null.jpg',    1),
(4,    'Plavecký klub Zábřeh',    'PKZá',    804,    'null.jpg',    1),
(5,    'Tělovýchovná jednota Spartak Přerov',    'SpPř',    808,    'null.jpg',    1),
(6,    'Tělovýchovný jednota Šumperk z.s.',    'TJŠum',    815,    'null.jpg',    1),
(7,    'SK UP Olomouc',    'UnOl',    809,    'null.jpg',    1),
(8,    'Plavecký klub Zlín',    'PK Zlín',    0,    'null.jpg',    2),
(9,    'Zlínský plavecký klub',    'ZlPK',    0,    'null-jpg',    2),
(10,    'Plavecké sporty Kroměříž',    'PSKr',    0,    'null.jpg',    2),
(11,    'TJ Holešov',    'TJHol',    0,    'null.jpg',    2),
(12,    'Spartak Uherský Brod',    'SPUB',    0,    'null.jpg',    2),
(13,    'Slovácká Slávia Uherské Hradiště',    'SlUH',    0,    'null.jpg',    2),
(14,    'TJ Rožnov pod Radhoštěm',    'TJRo',    0,    'null.jpg',    2);

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
) ENGINE=InnoDB DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;


CREATE TABLE `sp_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `content` text CHARACTER SET cp1250 COLLATE cp1250_czech_cs NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_pages` (`id`, `title`, `content`) VALUES
(1,    'Kontakty',    '<h2>Telefon na Luk&aacute;&scaron;e +420 724 224 292</h2>');

CREATE TABLE `sp_positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `poz` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_positions` (`id`, `name`) VALUES
(14,    'Cílový rozhodčí'),
(9,    'Časoměřič'),
(5,    'Časomíra'),
(4,    'Hlasatel'),
(18,    'Lékař'),
(10,    'Náhradní časoměřič'),
(12,    'Obrátkový rozhodčí'),
(6,    'Obsluha PC'),
(19,    'Ostatní'),
(3,    'Pomocný startér'),
(16,    'Protokol'),
(7,    'Rozhodčí plav. způsobů'),
(2,    'Startér'),
(15,    'Vedoucí protokolu'),
(13,    'Vrchní cílový rozhodčí'),
(8,    'Vrchní časoměřič'),
(11,    'Vrchní obrátkový rozhodčí'),
(1,    'Vrchní rozhodčí'),
(17,    'Výsledky');

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
(1,    '2023-01-10 22:44:35',    'SwimmPair 1.0 live!',    'Aplikace SwimmPair v1.0 je hotova. Produkčn&iacute; verze pro testov&aacute;n&iacute; pro Luk&aacute;&scaron;e K. z TJ Prostějov bude dostupn&aacute; na&nbsp;<a href=\"http://swimmpair.cz/\">http://swimmpair.cz</a>, v&yacute;vojov&aacute; pak na&nbsp;<a href=\"http://swimmpair.stkl.cz/\">http://swimmpair.stkl.cz</a>&nbsp;k testov&aacute;n&iacute; oprav/nov&yacute;ch funkc&iacute;.<br />Z&aacute;kladn&iacute; instalace obsahuje: 2 uživatele (LK, &Scaron;K), &nbsp;2 regiony (OLK, ZLK, +ČSPS jako \"nult&yacute;\" pro nezař.), 14 klubů (ČSPS pro voln&eacute; rozhodč&iacute;) a 19 pozic na z&aacute;vody (1. Vrchn&iacute; rozhodč&iacute;, ..., 19. Ostatn&iacute;).<br />Repozit&aacute;ř s k&oacute;dem projektu ve veřejn&eacute;m repozit&aacute;ři&nbsp;<a href=\"https://github.com/KlosStepan/SwimmPair-Www\">https://github.com/KlosStepan/SwimmPair-Www</a>&nbsp;na GitHubu.',    1,    NULL,    1);

CREATE TABLE `sp_public_stats_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `position_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_public_stats_config` (`id`, `position_id`) VALUES
(1,    1),
(2,    2),
(3,    3),
(4,    4),
(5,    5),
(6,    6),
(7,    7),
(8,    8),
(9,    9),
(10,    10),
(11,    11),
(12,    12),
(13,    13),
(14,    14),
(15,    15),
(16,    16),
(17,    17),
(18,    18),
(19,    19);

CREATE TABLE `sp_referee_ranks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_referee_ranks` (`id`, `name`) VALUES
(1,    'I.'),
(2,    'II.'),
(3,    'III.'),
(4,    'FINA');

CREATE TABLE `sp_regions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `abbreviation` varchar(10) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_regions` (`id`, `name`, `abbreviation`) VALUES
(0,    'Český svaz plaveckých sportů',    'CSPS'),
(1,    'Olomoucký kraj',    'OLK'),
(2,    'Zlínský kraj',    'ZLK');

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
) ENGINE=InnoDB DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;


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
) ENGINE=InnoDB DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs;

INSERT INTO `sp_users` (`id`, `first_name`, `last_name`, `email`, `password`, `hash`, `active_flag`, `approved_flag`, `rights`, `referee_rank_id`, `affiliation_club_id`) VALUES
(1,    'Lukáš',    'Kousal',    'mam949@seznam.cz',    '$2y$10$O21BZYcfuIiPrmbU30SCNOYRQYR2nCOvHtl.7J.jGO8vRQJ0g/o6G',    '0e01938fc48a2cfb5f2217fbfb00722d',    1,    1,    2,    2,    2),
(2,    'Štěpán',    'Klos',    'stepanklos@gmail.com',    '$2y$10$KzPEoCcbmybKJJyEjYTOruGgxdtzwi/qyh2Etk7RuaVmroE5IGa0m',    '3435c378bb76d4357324dd7e69f3cd18',    1,    1,    2,    1,    2);

-- 2023-01-12 01:32:29
