/* -----------    Beginning https://github.com/dopeboy/qhojo/commit/b2c15aaec9840bb87c3d72fd412235a2a8cfdee0 ------------ */

--ALTER TABLE USER ADD PERSONAL_WEBSITE VARCHAR(160);
--ALTER TABLE USER ADD LINKEDIN_STATE VARCHAR(32);
--ALTER TABLE USER ADD LINKEDIN_PUBLIC_PROFILE_URL VARCHAR(128);

-- recreate user_vw and user_vw-extended

/* -----------    End https://github.com/dopeboy/qhojo/commit/b2c15aaec9840bb87c3d72fd412235a2a8cfdee0 ------------ */




/* -----------    Beginning https://github.com/dopeboy/qhojo/commit/xxx ------------ */

ALTER TABLE ITEM ADD PRODUCT_ID INTEGER;

-- SOCIAL STUFF
ALTER TABLE USER DROP COLUMN LINKEDIN_STATE;
ALTER TABLE USER ADD COLUMN LINKEDIN_NUM_CONNECTIONS INTEGER;
ALTER TABLE USER ADD COLUMN LINKEDIN_TOKEN_EXPIRE_DATE DATETIME;


/* -----------    End https://github.com/dopeboy/qhojo/commit/xxx
