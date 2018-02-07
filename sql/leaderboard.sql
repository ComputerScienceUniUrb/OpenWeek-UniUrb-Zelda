SELECT `identities`.`id`, `identities`.`full_name`, COUNT(`reached_locations`.`location`) AS `steps`, SUM(`reached_locations`.`correct_answer`) AS `correct`, MAX(`reached_locations`.`timestamp`) AS `latest_step` FROM `identities` LEFT OUTER JOIN `reached_locations` ON `identities`.`id` = `reached_locations`.`id` WHERE `reached_locations`.`location` NOT IN ('2018-volponi-2', '2018-infoappl', '2018-tridente-2') GROUP BY `identities`.`id` ORDER BY `correct` DESC, `steps` DESC, `latest_step` ASC, `identities`.`id` ASC

SELECT `identities`.`id`, `identities`.`full_name`, COUNT(`reached_locations`.`location`) AS `steps`, SUM(`reached_locations`.`correct_answer`) AS `correct`, MAX(`reached_locations`.`timestamp`) AS `latest_step`, (SELECT COUNT(*) FROM `reached_locations` AS rinn WHERE rinn.`id` = `identities`.`id` AND `reached_locations`.`location` = '2018-la-vela') AS `completed` FROM `identities` LEFT OUTER JOIN `reached_locations` ON `identities`.`id` = `reached_locations`.`id` WHERE `reached_locations`.`location` NOT IN ('2018-volponi-2', '2018-infoappl', '2018-tridente-2') GROUP BY `identities`.`id` ORDER BY `correct` DESC, `steps` DESC, `latest_step` ASC, `identities`.`id` ASC

-- Final monster query
SELECT *, (SELECT COUNT(*) FROM `reached_locations` AS rinn WHERE rinn.`id` = `agg`.`id` AND rinn.`location` = '2018-selfie') AS `completed` FROM (SELECT `identities`.`id`, `identities`.`full_name`, COUNT(`reached_locations`.`location`) AS `steps`, SUM(`reached_locations`.`correct_answer`) AS `correct`, MAX(`reached_locations`.`timestamp`) AS `latest_step` FROM `identities` LEFT OUTER JOIN `reached_locations` ON `identities`.`id` = `reached_locations`.`id` WHERE `reached_locations`.`location` NOT IN ('2018-volponi-2', '2018-infoappl', '2018-tridente-2') GROUP BY `identities`.`id` HAVING DATE(`latest_step`) = DATE(NOW()) ORDER BY `correct` DESC, `steps` DESC, `latest_step` ASC, `identities`.`id` ASC) AS `agg` HAVING `completed` = 1