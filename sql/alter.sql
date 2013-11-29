ALTER TABLE USER ADD PERSONAL_WEBSITE VARCHAR(160);
ALTER TABLE USER ADD LINKEDIN_STATE VARCHAR(32);
ALTER TABLE USER ADD LINKEDIN_PUBLIC_PROFILE_URL VARCHAR(128);

-- recreate user_vw and user_vw-extended

/* -----------    Above was for https://github.com/dopeboy/qhojo/commit/b2c15aaec9840bb87c3d72fd412235a2a8cfdee0 ------------ */

ALTER TABLE ITEM ADD PRODUCT_ID INTEGER;

-- create CATEGORY, BRAND, PRODUCT, PRODUCT_IMAGE
-- create indices
-- insert base_data for above tables
-- copy product images into /uploads/product

-- recreate all views
-- backfill existing items with product_ids