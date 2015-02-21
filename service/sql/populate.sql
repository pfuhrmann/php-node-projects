USE mdb_fp202;

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
	('FirstName2' ,'LastName2', 'test2@gmail.com', '+44745454'),
	('FirstName3' ,'LastName3', 'test3@gmail.com', '+44745454'),
	('FirstName4' ,'LastName4', 'test4@gmail.com', '+44745454'),
	('FirstName5' ,'LastName5', 'test5@gmail.com', '+44745454');
 
INSERT INTO service
 (sitter_id, type, location, availability, description, charges)
VALUES
	(1 ,'babysitter', 'GreenwichA', 'mondays', 'Test desc', '12'),
	(1 ,'petsitter', 'GreenwichZ', 'tuesdays', 'Test desc 2', '8.3'),
    (2 ,'grannysitter', 'GreenwichC', 'tuesdays', 'Test desc 3', '14.8');
    
INSERT INTO image
 (service_id, code)
VALUES
	(1 ,'http://test.te'),
	(1 ,'http://test.te2');