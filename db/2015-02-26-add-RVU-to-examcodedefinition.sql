-- 2015-02-26-add-RVU-to-examcodedefinition.sql
-- modified 2015-03-2015 --This migration adds RVU columns to ExamCodeDefintion. These changes allow recording up to 3 CPT codes within each examcode. Queries will pull the TOTAL_RVU field which is designed to be the sum of the three CPT RVU values or the product of the RVU value adjusted by a modifier value. The default -1 value of Total_RVU is used as a flag for ExamCodes without a set RVU value.

use `capricorn`;

alter table ExamCodeDefinition add  (`CPT_Code_1` varchar(10), `CPT_Code_2` varchar(10), `CPT_Code_3` varchar(10), `RVU_Modifier` varchar(5), `CPT_Desc_1` varchar(35), `CPT_Desc_2` varchar(35), `CPT_Desc_3` varchar(35), `RVU_CPT_1` decimal(8,3) NOT NULL DEFAULT 0, `RVU_CPT_2` decimal(8,3) NOT NULL DEFAULT 0, `RVU_CPT_3` decimal(8,3) NOT NULL DEFAULT 0,`TOTAL_RVU` decimal(8,3) NOT NULL DEFAULT -1);

