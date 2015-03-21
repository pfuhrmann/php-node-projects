'use strict';

var mongoose = require('mongoose'),
    Sitter = mongoose.model('sitterSchema'),
    ImageM = mongoose.model('ImageM'),
    Service = mongoose.model('Service');

/*
 * Populate DB
 *
 * HTTP GET /populate
 */
exports.populate = function() {
    mongoose.connection.db.dropDatabase();

    var sitter1 = new Sitter({
        first_name: 'Fredie',
        last_name: 'Oswin',
        email: 'test1@gmail.com',
        phone: '+4474545485'
    });
    var sitter2 = new Sitter({
        first_name: 'Renee',
        last_name: 'Meredith',
        email: 'test2@gmail.com',
        phone: '+4474545485'
    });
    var sitter3 = new Sitter({
        first_name: 'Gaila',
        last_name: 'Asher',
        email: 'test3@gmail.com',
        phone: '+4474545485'
    });
    var sitter4 = new Sitter({
        first_name: 'Griselda',
        last_name: 'Waldo',
        email: 'test4@gmail.com',
        phone: '+4474545485'
    });
    var sitter5 = new Sitter({
        first_name: 'Libby',
        last_name: 'Haze',
        email: 'test5@gmail.com',
        phone: '+4474545485'
    });

    var image1 = new ImageM({
        code: 'http://media4.popsugar-assets.com/files/2013/06/19/700/n/1922664/772994b82620c65e_Babysitter.xxxlarge/i/How-Hire-Babysitter-Vacation.jpg'
    });

    var image2 = new ImageM({
        code: 'http://static.guim.co.uk/sys-images/Society/Comment/Columnist/2012/3/2/1330713290501/Ivy-Gunn-with-sitter-Step-007.jpg'
    });

    var image3 = new ImageM({
        code: 'http://www.homeinstead.co.uk/edinburgh/1896.do/uploads/_NEWS/5138dce027fba3.29928645.jpg'
    });

    var image4 = new ImageM({
        code: 'http://2.bp.blogspot.com/_Sog5JsjlJ6s/TP0uEissQXI/AAAAAAAAAAc/8XnETZo0hVg/S1600-R/pet-sitter.jpg'
    });

    var image5 = new ImageM({
        code: 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQM4e_pmLeqcs63cxUYDDi2VDoDG9_5nou2TFYq1yVBDaBHVylR'
    });

    var service1 = new Service({
            type: 'petsitter',
            location: 'Broomley A',
            availability: 'Mondays, Fridays',
            description: 'Description test',
            charges: 8.50
        }
    );
    service1.sitter.push(sitter1);
    service1.images.push(image1);
    service1.images.push(image2);
    service1.save(function(err, doc) {
        if (err) console.log(err);
        console.log(doc);
    });

    var service2 = new Service({
            type: 'babysitter',
            location: 'Broomley B',
            availability: 'Tuesdays, Fridays',
            description: 'Description test',
            charges: 12.50
        }
    );
    service2.sitter.push(sitter2);
    service2.images.push(image2);
    service2.images.push(image3);
    service2.save(function(err, doc) {
        if (err) console.log(err);
        console.log(doc);
    });

    var service3 = new Service({
            type: 'grannysitter',
            location: 'Broomley Z',
            availability: 'Fridays',
            description: 'Description test',
            charges: 10.00
        }
    );
    service3.sitter.push(sitter3);
    service3.images.push(image4);
    service3.images.push(image5);
    service3.save(function(err, doc) {
        if (err) console.log(err);
        console.log(doc);
    });

    var service4 = new Service({
            type: 'petsitter',
            location: 'Broomley Y',
            availability: 'Weekends, Wednesdays',
            description: 'Description test',
            charges: 15.00
        }
    );
    service4.sitter.push(sitter4);
    service4.images.push(image1);
    service4.images.push(image4);
    service4.save(function(err, doc) {
        if (err) console.log(err);
        console.log(doc);
    });

    var service5 = new Service({
            type: 'housesitter',
            location: 'Broomley D',
            availability: 'Weekends',
            description: 'Description test',
            charges: 8.6
        }
    );
    service5.sitter.push(sitter5);
    service5.images.push(image2);
    service5.images.push(image5);
    service5.save(function(err, doc) {
        if (err) console.log(err);
        console.log(doc);
    });
};
