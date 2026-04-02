-- Remove Envato / purchase-code JavaScript blob from the admin footer source.
-- Run once on the VPS MySQL database used by the admin panel.
-- Table/column names match the stock GoEvent schema (adjust if yours differ).

UPDATE `tbl_validate`
SET `data` = ''
WHERE `id` = 1;

-- Verify (optional): first row should show tiny or empty data length
-- SELECT id, CHAR_LENGTH(`data`) AS data_len FROM `tbl_validate` LIMIT 3;
