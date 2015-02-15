USE comp1688;

-- Truncate database
SET foreign_key_checks = 0;
TRUNCATE TABLE image;
TRUNCATE TABLE service;
TRUNCATE TABLE sitter;
SET foreign_key_checks = 1;

INSERT INTO sitter
 (first_name, last_name, email, phone)
VALUES
	('FirstName1' ,'LastName1', 'test1@gmail.com', '+44745454'),
	('FirstName2' ,'LastName2', 'test1@gmail.com', '+44745454'),
	('FirstName3' ,'LastName3', 'test1@gmail.com', '+44745454'),
	('FirstName4' ,'LastName4', 'test1@gmail.com', '+44745454'),
	('FirstName5' ,'LastName5', 'test1@gmail.com', '+44745454');

INSERT INTO service
 (sitter_id, type, location, availability, description, charges)
VALUES
	(1 ,'Babysitter', 'SE109ED', 'mondays', 'Test desc', '12£ p/h'),
	(1 ,'Petsitter', 'SE109ED', 'tuesdays', 'Test desc 2', '12£ p/h');
    
INSERT INTO image
 (service_id, code)
VALUES
	(1 ,'http://test.te'),
	(1 ,'http://test.te2');