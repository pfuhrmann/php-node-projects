'use strict';

var mongoose = require('mongoose'),
    Schema = mongoose.Schema,
    ObjectId = Schema.ObjectId;

var sitterSchema = new Schema({
    id: ObjectId,
    first_name: String,
    last_name: String,
    email: String,
    phone: String
});

module.exports = mongoose.model('sitterSchema', sitterSchema);
