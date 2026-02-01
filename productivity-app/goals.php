<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Goals</title>
<link rel="stylesheet" href="css/global.css">

<style>
/* --- LAYOUT --- */
.goals-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

/* --- CARDS --- */
.quote-card {
    grid-column: span 2;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.quote-text {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-family: serif; /* Elegant touch */
    font-style: italic;
}

.quote-author {
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* --- GOALS LIST --- */
.goals-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.input-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    gap: 0.5rem;
}

.goal-list-container {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.goal-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s;
}

.goal-card:hover {
    transform: translateX(4px);
    border-color: var(--primary-color);
}

.goal-left { display: flex; align-items: center; gap: 1rem; flex: 1; }

.goal-check {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.2s;
}

.goal-check.checked {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.goal-text { font-size: 1.1rem; transition: all 0.2s; }
.goal-card.completed .goal-text {
    text-decoration: line-through;
    color: var(--text-secondary);
}

/* --- PROGRESS RING --- */
.progress-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.ring-container {
    position: relative;
    width: 160px;
    height: 160px;
    margin-bottom: 1rem;
}

.progress-ring-circle {
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    transition: stroke-dashoffset 0.5s ease-in-out;
}

.ring-text {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.ring-percent { font-size: 2rem; font-weight: 700; color: var(--text-primary); }
.ring-label { font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; }

/* Responsive */
@media (max-width: 900px) {
    .goals-dashboard { grid-template-columns: 1fr; }
    .quote-card { grid-column: span 1; }
}
</style>
</head>

<body>
<div class="container">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">⚡</div>
        <h1>ProductivityHub</h1>
    </div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item">📊 Dashboard</a>
        <a href="pomodoro.php" class="nav-item">☕ Pomodoro</a>
        <a href="habits.php" class="nav-item">✨ Habits</a>
        <a href="tasks.php" class="nav-item">✓ Tasks</a>
        <a href="goals.php" class="nav-item active">🎯 Goals</a>
        <a href="focus.php" class="nav-item">🧘 Focus</a>
    </nav>
    <div class="sidebar-footer">
        🔥 Day Streak: <span id="currentStreak">0</span>
    </div>
</aside>

<!-- MAIN -->
<main class="main-content">
    <header class="page-header">
        <h2>Daily Goals</h2>
    </header>

    <div class="goals-dashboard">
        
        <!-- QUOTE -->
        <div class="quote-card">
            <div class="quote-text" id="dailyQuote">"The secret of getting ahead is getting started."</div>
            <div class="quote-author" id="quoteAuthor">- Mark Twain</div>
        </div>

        <!-- LEFT: GOALS LIST -->
        <div class="goals-section">
            <div class="input-card">
                <input type="text" id="goalInput" class="form-input" placeholder="What is your main focus for today?">
                <button class="btn-primary" onclick="addGoal()">Add</button>
            </div>

            <div id="goalsList" class="goal-list-container">
                <!-- Goals injected here -->
            </div>
        </div>

        <!-- RIGHT: PROGRESS -->
        <div class="progress-card">
            <h3>Daily Progress</h3>
            <div class="ring-container">
                <svg width="160" height="160">
                    <circle cx="80" cy="80" r="70" stroke="rgba(255,255,255,0.1)" stroke-width="12" fill="none"></circle>
                    <circle id="ringCircle" class="progress-ring-circle" cx="80" cy="80" r="70" 
                            stroke="#10b981" stroke-width="12" fill="none" 
                            stroke-dasharray="440" stroke-dashoffset="440" stroke-linecap="round"></circle>
                </svg>
                <div class="ring-text">
                    <div class="ring-percent" id="ringPercent">0%</div>
                    <div class="ring-label">Done</div>
                </div>
            </div>
            <div style="color:var(--text-secondary); font-size:0.9rem">
                <span id="completedCount">0</span> of <span id="totalCount">0</span> goals completed
            </div>
        </div>

    </div>
</main>
</div>

<script>
// --- STATE ---
let goals = [];
const api = 'backend/api.php';
// Simple Quote Rotator
const quotes = [
    {text: "The secret of getting ahead is getting started.", author: "Mark Twain"},
    {text: "It always seems impossible until it's done.", author: "Nelson Mandela"},
    {text: "Don't watch the clock; do what it does. Keep going.", author: "Sam Levenson"},
    {text: "Quality is not an act, it is a habit.", author: "Aristotle"},
    {text: "Believe you can and you're halfway there.", author: "Theodore Roosevelt"}
];

window.onload = () => {
    // Random Quote
    const q = quotes[Math.floor(Math.random() * quotes.length)];
    document.getElementById('dailyQuote').textContent = `"${q.text}"`;
    document.getElementById('quoteAuthor').textContent = `- ${q.author}`;
    
    fetchGoals();
};

async function fetchGoals() {
    try {
        const res = await fetch(`${api}?action=get_data`);
        const data = await res.json();
        goals = data.goals || [];
        renderGoals();
    } catch (e) {
        console.error("Error loading goals:", e);
    }
}

function renderGoals() {
    const list = document.getElementById('goalsList');
    list.innerHTML = '';
    
    if (goals.length === 0) {
        list.innerHTML = `<div style="text-align:center; padding: 2rem; color: var(--text-secondary)">No goals set for today.</div>`;
    }

    goals.forEach(g => {
        const card = document.createElement('div');
        card.className = `goal-card ${g.completed ? 'completed' : ''}`;
        card.innerHTML = `
            <div class="goal-left">
                <button class="goal-check ${g.completed ? 'checked' : ''}" onclick="toggleGoal('${g.id}')">
                    ${g.completed ? '✓' : ''}
                </button>
                <span class="goal-text">${g.text}</span>
            </div>
            <button class="delete-btn" onclick="deleteGoal('${g.id}')">✕</button>
        `;
        list.appendChild(card);
    });
    
    updateProgress();
}

async function addGoal() {
    const input = document.getElementById('goalInput');
    const text = input.value.trim();
    if (!text) return;
    
    // Add locally
    const newGoal = { id: Date.now().toString(), text, completed: false };
    goals.unshift(newGoal);
    
    input.value = '';
    renderGoals();
    
    // API
    await fetch(`${api}?action=add_goal`, {
        method: 'POST',
        body: JSON.stringify({ id: newGoal.id, text })
    });
}

async function toggleGoal(id) {
    const g = goals.find(x => x.id == id);
    if(g) {
        g.completed = !g.completed;
        renderGoals();
        
        await fetch(`${api}?action=toggle_goal`, {
            method: 'POST',
            body: JSON.stringify({ id })
        });
    }
}

async function deleteGoal(id) {
    if(!confirm("Remove this goal?")) return;
    goals = goals.filter(g => g.id != id);
    renderGoals();
    
    await fetch(`${api}?action=delete_goal`, {
        method: 'POST',
        body: JSON.stringify({ id })
    });
}

function updateProgress() {
    const total = goals.length;
    const done = goals.filter(g => g.completed).length;
    const percent = total === 0 ? 0 : Math.round((done / total) * 100);
    
    // Update Text
    document.getElementById('ringPercent').textContent = `${percent}%`;
    document.getElementById('completedCount').textContent = done;
    document.getElementById('totalCount').textContent = total;
    
    // Update Ring
    const circle = document.getElementById('ringCircle');
    const radius = 70;
    const circumference = 2 * Math.PI * radius; // ~440
    const offset = circumference - (percent / 100) * circumference;
    
    circle.style.strokeDashoffset = offset;
    
    // Change color on 100%
    if (percent === 100 && total > 0) {
        circle.style.stroke = '#10b981'; // Green
    } else {
        circle.style.stroke = '#8b5cf6'; // Purple default
    }
}
</script>

</body>
</html>