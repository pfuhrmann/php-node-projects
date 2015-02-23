USE mdb_fp202;

-- Truncate database --
SET foreign_key_checks = 0;
TRUNCATE TABLE image;
TRUNCATE TABLE service;
TRUNCATE TABLE sitter;
SET foreign_key_checks = 1;

-- Sitter --
INSERT INTO sitter
  (first_name, last_name, email, phone)
VALUES
  ('Corie', 'Kasey', 'test1@gmail.com', '+4474545485'),
  ('Jayda', 'Lilian', 'test2@gmail.com', '+4474545485'),
  ('Isador', 'Munich', 'test3@gmail.com', '+4474545485'),
  ('Dominica', 'Lorin', 'test4@gmail.com', '+4474545485'),
  ('Twyla', 'Hammond', 'test5@gmail.com', '+4474545485'),
  ('Vikki', 'Bethany', 'test6@gmail.com', '+4474545485'),
  ('Lamar', 'Freeman', 'test7@gmail.com', '+4474545485'),
  ('Corinne', 'Avis', 'test8@gmail.com', '+4474545485'),
  ('Marjorie', 'Pattie', 'test9@gmail.com', '+4474545485'),
  ('Valary', 'Nathaniel', 'test10@gmail.com', '+4474545485');

-- Service --
INSERT INTO service
 (sitter_id, type, location, availability, description, charges)
VALUES
  (1 ,'babysitter', 'Greenwich A', 'monday', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris eu nunc luctus, consequat nulla interdum, venenatis mi. Curabitur justo urna, pellentesque sit amet lacus vitae, vulputate consectetur urna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi sem sapien, elementum vitae felis sed, sodales bibendum mauris. Fusce pharetra feugiat pellentesque. Nulla venenatis, magna in venenatis pulvinar, tortor ipsum mattis ante, in mattis felis nulla id odio. Nam convallis eget enim eget scelerisque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ornare tincidunt urna nec dapibus. Sed tempus lacus nec dignissim efficitur. In molestie vitae velit tempor consectetur.', '12'),
  (1 ,'petsitter', 'Greenwich Z', 'tuesday, friday', 'Suspendisse ac accumsan eros. Sed aliquam metus odio, nec dictum purus molestie non. Etiam vitae arcu augue. Duis venenatis sodales felis quis rhoncus. In mollis dolor id neque congue vehicula. Donec et quam quis lacus facilisis consequat. Aliquam erat volutpat. Morbi hendrerit vitae leo eu tincidunt. Nullam tempus sodales libero, nec gravida ex maximus eu.', '8.3'),
  (2 ,'grannysitter', 'Greenwich C', 'tuesday, wedsnesday', 'Integer odio magna, maximus in odio eget, aliquet iaculis velit. Suspendisse at mauris id eros feugiat porttitor ac vel felis. Aliquam eget lacinia ex. Quisque in diam vel mauris tristique consectetur eget at felis. Vivamus vel finibus lectus. Donec euismod, tellus id ultricies placerat, ipsum nisi condimentum mauris, vestibulum mattis mi urna non velit. Donec ornare varius lacinia. Maecenas et lacinia est.', '14.8'),
  (3 ,'housesitter', 'Greenwich B', 'monday', 'Morbi ipsum enim, mattis eget diam sit amet, accumsan egestas augue. Curabitur posuere mi ut tempus tempus. Nulla vitae turpis egestas, accumsan enim vitae, rutrum orci. Maecenas ac mauris sed libero egestas porta sit amet et libero. Mauris ullamcorper pulvinar nunc non vestibulum. Praesent laoreet aliquet tortor. Curabitur varius vehicula vulputate. Mauris lacus nisi, eleifend sit amet nibh ut, convallis semper justo. Nunc vulputate leo nisi, ac feugiat diam suscipit venenatis. Morbi fringilla blandit metus, in scelerisque tortor aliquam vitae. In luctus auctor elit, vel sodales purus bibendum vel. Vestibulum et lorem hendrerit, volutpat metus at, volutpat tellus. Integer euismod odio in enim luctus convallis. Nulla consequat varius massa, tincidunt aliquam dui venenatis ut. Morbi ullamcorper porta lorem eu varius.', '8.8'),
  (4 ,'petsitter', 'Greenwich F', 'friday', 'Morbi porta dapibus faucibus. Duis sed turpis nulla. Sed non turpis ac ex posuere gravida. Morbi faucibus accumsan justo vitae malesuada. Sed non odio ex. Proin in blandit elit. Donec sollicitudin nec libero quis ullamcorper. Ut vel ipsum sit amet dui vehicula venenatis et id massa. Cras imperdiet euismod leo, non maximus ipsum fringilla vel. Nunc ac turpis sollicitudin, gravida sapien non, pulvinar nisi. Quisque feugiat mauris augue, vitae ultrices eros rhoncus eu. Nunc gravida dui nec nulla accumsan, ac auctor risus hendrerit. Aliquam semper dictum metus eget tristique. Ut elit odio, sollicitudin rutrum odio vitae, semper sagittis elit.', '7.3'),
  (3 ,'grannysitter', 'Greenwich Y', 'tuesdas', 'Quisque purus lectus, faucibus in lacus ut, faucibus ultricies felis. Sed id ante sit amet odio finibus aliquam. Mauris non justo magna. Donec accumsan nulla viverra, placerat mi sit amet, vestibulum felis. Quisque sodales ligula magna, eu tempor arcu rhoncus sed. Proin vitae lacus ipsum. Proin ut euismod lorem. Proin vitae mollis ligula, vel dignissim velit. Ut molestie neque et eros cursus, non pharetra risus posuere. Suspendisse vel nibh viverra mauris efficitur aliquet.', '12'),
  (5 ,'plantsitter', 'Greenwich D', 'wedsnesday', 'Aliquam eu massa a libero volutpat congue. Integer sit amet ante mattis, rhoncus magna eget, convallis libero. Nulla facilisi. Nulla molestie vel nisl vitae commodo. Etiam augue augue, ultricies id efficitur eu, facilisis non purus. Sed vel sem id ipsum pellentesque luctus vel sed erat. Aenean consectetur quam orci, eu tincidunt nulla eleifend eget. Praesent odio felis, pharetra vel aliquet a, tempor sed ipsum. Integer sollicitudin augue ut lobortis tristique. Proin nec placerat mi, eu vestibulum nibh. Nulla non neque malesuada, sollicitudin urna sit amet, gravida massa.', '13'),
  (5 ,'housesitter', 'Greenwich B', 'thursday, friday', 'Mauris nisl risus, rutrum eget fermentum eget, auctor in quam. Phasellus ultrices ante metus, quis fermentum nibh varius ut. Praesent convallis lacinia finibus. Phasellus vestibulum efficitur ligula vitae sagittis. Nulla interdum augue eu ornare feugiat. Sed sed enim lacus. Praesent tincidunt sagittis nunc, quis vulputate massa euismod eu. Donec sagittis, orci in gravida tempus, turpis nulla varius metus, non dictum justo ligula vel turpis. Fusce tempus pellentesque augue, faucibus fringilla nisi placerat vel. In hac habitasse platea dictumst. Proin vulputate tincidunt orci id eleifend. Donec id sollicitudin nulla. Curabitur congue lacus in lectus blandit, vitae venenatis metus venenatis. Sed vulputate tellus et tortor viverra, et elementum enim semper.', '12.3'),
  (8 ,'babysitter', 'Greenwich Z', 'weekends', 'Duis sodales dui ac mauris tincidunt, ac luctus quam blandit. In hac habitasse platea dictumst. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam fermentum, ex vitae dictum consequat, urna lorem rutrum urna, non ornare risus erat non nisl. Quisque lobortis tellus turpis, ac egestas nunc efficitur in. Pellentesque vehicula imperdiet erat, ac dictum neque. Mauris fringilla commodo ligula, non tempus enim condimentum eu. Praesent rutrum est sem, at tristique sem accumsan nec. Pellentesque sit amet bibendum mi. Pellentesque vulputate sed velit ac dignissim. Duis maximus ultricies tortor, id accumsan nisi semper sit amet. Nulla vel suscipit neque.', '15.3');

-- Image --
INSERT INTO image
  (service_id, code)
VALUES
  (1 ,'http://media4.popsugar-assets.com/files/2013/06/19/700/n/1922664/772994b82620c65e_Babysitter.xxxlarge/i/How-Hire-Babysitter-Vacation.jpg'),
  (1 ,'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTPjKI5Ww087Yt0HM16cnajPeMBnmBmjOc1N23r4HObc91WQWOO'),
  (6 ,'http://static.guim.co.uk/sys-images/Society/Comment/Columnist/2012/3/2/1330713290501/Ivy-Gunn-with-sitter-Step-007.jpg'),
  (6 ,'http://www.homeinstead.co.uk/edinburgh/1896.do/uploads/_NEWS/5138dce027fba3.29928645.jpg'),
  (2 ,'http://2.bp.blogspot.com/_Sog5JsjlJ6s/TP0uEissQXI/AAAAAAAAAAc/8XnETZo0hVg/S1600-R/pet-sitter.jpg'),
  (2 ,'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQM4e_pmLeqcs63cxUYDDi2VDoDG9_5nou2TFYq1yVBDaBHVylR'),
  (2 ,'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQSBfafDrifKCxDGZXq3Vrp5KJ2KWY6ilhFEBbUXy21ADYvivM9'),
  (9 ,'http://media4.popsugar-assets.com/files/2013/06/19/700/n/1922664/772994b82620c65e_Babysitter.xxxlarge/i/How-Hire-Babysitter-Vacation.jpg'),
  (9 ,'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTPjKI5Ww087Yt0HM16cnajPeMBnmBmjOc1N23r4HObc91WQWOO'),
  (3 ,'http://static.guim.co.uk/sys-images/Society/Comment/Columnist/2012/3/2/1330713290501/Ivy-Gunn-with-sitter-Step-007.jpg'),
  (3 ,'http://www.homeinstead.co.uk/edinburgh/1896.do/uploads/_NEWS/5138dce027fba3.29928645.jpg')
  ;
  