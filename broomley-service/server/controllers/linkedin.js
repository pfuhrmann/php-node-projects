'use strict';

var request = require('request');

var clientID = '77od0mpvyjn0fz';
var clientSecret = 'L8uHyHdlE8PIzJPr';
var redirectURI = 'http://localhost/callback';
/*
* Authorize app
*
* HTTP GET /auth
*/
exports.auth = function(req, res) {
    var authorization_uri = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id='+clientID+'&state=DCEEFWF45453sdffef424&redirect_uri='+redirectURI;

    res.redirect(authorization_uri);
};

/*
* Authorization callback
*
* HTTP GET /callback
*/
exports.callback = function(req, res) {
    var code = req.query.code;
    console.log(code);
    var token_uri = 'https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&code='+code+'&redirect_uri='+redirectURI+'&client_id='+clientID+'&client_secret='+clientSecret;

    request(token_uri, function (error, response, body) {
      res.send(body);
    });
};

/*
* Get updates
*
* HTTP GET /updates
*/
exports.updates = function(req, res) {
    var token = 'AQXYuou0neRh-PwotwxzOthjcpZyK5MPhg1zzv4WB54vtcA_kH2d-GBDUOYXdBR_IOhqHHHVeQ0JfVWqYlq5JrHmtdNJt1GQxI-hg66bVFu89BwI8DEfPv4l2wTcvKNYyN1oJdozY7sDJ_1dqAx67aGHMvYW4vCELaGv2980TGalKweeznE';
    var networkUpdateURI = 'https://api.linkedin.com/v1/people/~/network/updates?count=250&before=1415701339000';

    var options = {
        url: networkUpdateURI,
        headers: {
            'Authorization': 'Bearer '+token
        }
    };
    
    request(options, function (error, response, body) {
        res.type('text/html');
        res.send(body);
    });
};