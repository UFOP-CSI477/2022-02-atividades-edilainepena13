const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();

app.use(bodyParser.json());
app.use(cors({ origin: true }));

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '123456',
  database: 'crud'
});

connection.connect((err) => {
  if (err) throw err;
  console.log('Connected to database');

  // Create stock table if it doesn't exist
  connection.query(`
    CREATE TABLE IF NOT EXISTS stock (
      id INT NOT NULL AUTO_INCREMENT,
      name VARCHAR(255) NOT NULL,
      price DECIMAL(10,2),
      quantity INT,
      PRIMARY KEY (id)
    )
  `, (err, result) => {
    if (err) throw err;
    console.log('Stock table created or already exists');
  });

  connection.query(`
    CREATE TABLE IF NOT EXISTS sells (
      id INT NOT NULL AUTO_INCREMENT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      paymentType VARCHAR(32),
      products JSON,
      PRIMARY KEY (id)
    )
  `, (err, result) => {
    if (err) throw err;
    console.log('Sells table created or already exists');
  });
});

// Create a new item in stock
app.post('/stock', (req, res) => {
  const { name, price, quantity } = req.body;
  const query = `INSERT INTO stock (name, price, quantity) VALUES ('${name}', ${price}, ${quantity})`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send('Item added to stock');
  });
});

// Get all items in stock
app.get('/stock', (req, res) => {
  const query = 'SELECT * FROM stock';

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send(result);
  });
});

// Get a specific item in stock by ID
app.get('/stock/:id', (req, res) => {
  const { id } = req.params;
  const query = `SELECT * FROM stock WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send(result);
  });
});

// Update an item in stock by ID
app.put('/stock/:id', (req, res) => {
  const { id } = req.params;
  const { name, price, quantity } = req.body;
  const query = `UPDATE stock SET name = '${name}', price = ${price}, quantity = ${quantity} WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send('Item updated in stock');
  });
});

// Delete an item in stock by ID
app.delete('/stock/:id', (req, res) => {
  const { id } = req.params;
  const query = `DELETE FROM stock WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send('Item deleted from stock');
  });
});

// Create a new sell
app.post('/sells', (req, res) => {
  const { products, paymentType, newQuantity } = req.body;
  const query = `INSERT INTO sells (products, paymentType) VALUES ('${JSON.stringify(products)}', '${paymentType}')`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    newQuantity.map(q => {
      const newQuery = `UPDATE stock SET quantity = ${q.newQuantity} WHERE id = ${q.id}`;
      connection.query(newQuery);
    });
    res.send('Sell added to database');
  });
});

// Get all sells
app.get('/sells', (req, res) => {
  const query = 'SELECT * FROM sells';

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send(result);
  });
});

// Get a specific sell by ID
app.get('/sells/:id', (req, res) => {
  const { id } = req.params;
  const query = `SELECT * FROM sells WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send(result);
  });
});

// Update a sell by ID
app.put('/sells/:id', (req, res) => {
  const { id } = req.params;
  const { products } = req.body;
  const query = `UPDATE sells SET products = '${JSON.stringify(products)}' WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send('Sell updated in database');
  });
});

// Delete a sell by ID
app.delete('/sells/:id', (req, res) => {
  const { id } = req.params;
  const query = `DELETE FROM sells WHERE id = ${id}`;

  connection.query(query, (err, result) => {
    if (err) throw err;
    console.log(result);
    res.send('Sell deleted from database');
  });
});

app.listen(3001, () => {
  console.log('Server running on port 3001');
});
