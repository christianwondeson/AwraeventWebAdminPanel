-- Optional one-time update: set site timezone to Addis Ababa and currency to ETB.
-- Run on your MySQL database if existing rows still show another timezone/currency.

UPDATE `tbl_setting`
SET
  `timezone` = 'Africa/Addis_Ababa',
  `currency` = 'ETB'
WHERE `id` = 1;
