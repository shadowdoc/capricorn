-- 2015-02-26-add-RVU-to-examcodedefinition.sql
-- This migration adds a RVU column to ExamCodeDefintion

use `capricorn`;

alter table ExamCodeDefinition add column `RVU` decimal(8,3) DEFAULT 0;
