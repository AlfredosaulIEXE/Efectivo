ALTER TABLE `tbl_lead` ADD `lead_insurance_id` INT NULL DEFAULT NULL AFTER `lead_source_description`;
ALTER TABLE `tbl_lead` ADD `lead_master_status_id` INT NULL DEFAULT NULL AFTER `c_seller`;
UPDATE `tbl_lead` SET `lead_master_status_id`= 1 WHERE 1;
UPDATE `tbl_lead` SET `lead_master_status_id`= 3 WHERE valid_admin = 1 AND valid_manager = 1 AND valid_sales = 1;