# Major refactors in DB/Model Controllers
## Managers OUT
### UsersManager.php
- public function FindAllNametagsForTheCup($cupId) **(+DB PROC)**
- public function FindPairedPositionIDUserIDForCup($cupId) **(+DB PROC)**
### CupsManager.php
- public function GetCupNameByID($cupID) **(+DB PROC)**
## DB OUT
- FindAllNametagsForTheCup (FArg_cupID)
```
SELECT DISTINCT `user_id` AS id,
                `sp_users`.`first_name`,
                `sp_users`.`last_name`,
                `sp_users`.`email`,
                `sp_users`.`approved_flag`,
                `sp_users`.`rights`,
                `sp_users`.`referee_rank_id`,
                `sp_users`.`affiliation_club_id`
FROM `sp_user_position_pairing`
INNER JOIN `sp_users` ON `sp_users`.`id` = `sp_user_position_pairing`.`user_id`
WHERE `cup_id`=FArg_cupID
```
- FindPairedPositionIDUserIDForCup (FArg_cupID)
```
SELECT `position_id`,
       `user_id`
FROM `sp_user_position_pairing`
WHERE `cup_id`=FArg_cupID
```
- GetCupNameByID (FArg_cupID)
```
SELECT `name`
FROM `sp_cups`
WHERE id=FArg_cupID
LIMIT 1
```