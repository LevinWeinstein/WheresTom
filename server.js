console.log('this is a node test');

const express = require('express');
const bodyParser = require('body-parser')
const app = express();
const MongoClient = require('mongodb').MongoClient;

var db;

MongoClient.connect('mongodb://kashgoudarzi:Kha.goud1@ds135680.mlab.com:35680/wherestom', function (err, database) {
	if (err) {
		return console.log(err);
	}
	db = database;
	app.listen(3000, function () {
		console.log('listening on 3000 boyyy');
	});
});
app.use(express.static(__dirname + "/public"));

app.get('/', function(req, res){
	fs.readFile(__dirname + '/public/index.html', 'utf8', function(err, text){
		res.send(text);
	});
});
app.post('/quotes', function (req, res) {
	console.log(req.body);

	db.collection('quotes').save(req.body, function (err, result) {
		if (err) return console.log(err);
		console.log('saved to database');
		res.redirect('/');
	});
});