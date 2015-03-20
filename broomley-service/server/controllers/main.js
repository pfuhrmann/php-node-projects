'use strict';

/*
* Index (main) page
*
* HTTP GET /
*/
exports.index = function(req, res) {
    // Setup headers
    res.header('Cache-Control', 'public, max-age=86400');
    res.header('X-UA-Compatible', 'IE=edge,chrome=1');

    // Render view
    res.render('index');
};

/*
* Email me
*
* HTTP GET /email
*/
exports.email = function(req, res) {
    var api_key = 'key-ccc0002ef3abc6002e63491cb24fa432';
    var domain = 'patrikfuhrmann.com';
    var mailgun = require('mailgun-js')({apiKey: api_key, domain: domain});

    var data = {
      from: req.body.name+' <'+req.body.email+'>',
      to: 'Patrik Fuhrmann <contact@patrikfuhrmann.com>',
      subject: req.body.subject,
      text: 'New massage sent from patrikfuhrmann.com: "'+req.body.message+'"'
    };

    var message = '';
    mailgun.messages().send(data, function (error, body) {
        console.log(body);
        if (!error) {
            message = 'OK';
        } else {
            message = 'Ups, there was an error sending your message.';
        }

        res.send(message);
    });
};
