const express = require('express');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const app = express();
const PORT = process.env.PORT || 3000;
const DATA_FILE = __dirname + '/data.json';

const ADMIN_LOGIN = process.env.ADMIN_LOGIN || 'sar3th';
const ADMIN_PASSWORD_HASH = process.env.ADMIN_PASSWORD_HASH ||
  crypto.createHash('sha256').update('Gatewayn95!').digest('hex');

let adminSessions = new Set();

// Load or initialize data
let data = { participants: [], rewards: [], tasks: [] };
try {
  if (fs.existsSync(DATA_FILE)) {
    const raw = fs.readFileSync(DATA_FILE, 'utf8');
    data = JSON.parse(raw);
  }
} catch (err) {
  console.error('Failed to load data file:', err);
}

function saveData() {
  try {
    fs.writeFileSync(DATA_FILE, JSON.stringify(data, null, 2));
  } catch (err) {
    console.error('Failed to save data file:', err);
  }
}

app.use(express.json());
// serve static files from ../public
app.use(express.static(path.join(__dirname, '..', 'public')));

function authenticate(req, res, next) {
  const token = req.headers['authorization'];
  if (token && adminSessions.has(token)) {
    return next();
  }
  res.status(401).json({ error: 'Unauthorized' });
}

app.post('/login', (req, res) => {
  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ error: 'Missing credentials' });
  }
  const hash = crypto.createHash('sha256').update(password).digest('hex');
  if (username === ADMIN_LOGIN && hash === ADMIN_PASSWORD_HASH) {
    const token = crypto.randomBytes(16).toString('hex');
    adminSessions.add(token);
    return res.json({ token });
  }
  res.status(401).json({ error: 'Invalid credentials' });
});

app.post('/logout', authenticate, (req, res) => {
  const token = req.headers['authorization'];
  adminSessions.delete(token);
  res.json({ success: true });
});

app.get('/participants', (req, res) => {
  res.json(data.participants);
});

app.post('/participants', authenticate, (req, res) => {
  const { name } = req.body;
  if (!name) return res.status(400).json({ error: 'Name required' });
  if (data.participants.find(p => p.name === name)) {
    return res.status(409).json({ error: 'Participant exists' });
  }
  const participant = { name, points: 0, password: null, redeemedRewards: [] };
  data.participants.push(participant);
  saveData();
  res.status(201).json(participant);
});

// fallback to index.html for other GET requests (SPA support)
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, '..', 'public', 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
});
