<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Focus</title>
<link rel="stylesheet" href="css/global.css">

<style>
/* --- LAYOUT --- */
.focus-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    height: calc(100vh - 140px); /* Fill remaining space */
}

/* --- MAIN FOCUS AREA --- */
.focus-main {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

/* Ambient Pulse Background */
.focus-pulse {
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    animation: pulse 4s infinite ease-in-out;
    pointer-events: none;
}
@keyframes pulse { 0%{transform:scale(0.8);opacity:0.5} 50%{transform:scale(1.2);opacity:0.8} 100%{transform:scale(0.8);opacity:0.5} }

.timer-display {
    font-size: 6rem;
    font-weight: 200;
    font-variant-numeric: tabular-nums;
    color: var(--text-primary);
    z-index: 1;
    margin-bottom: 1rem;
    font-family: inherit; /* Use global font */
}

.focus-controls {
    display: flex;
    gap: 1rem;
    z-index: 1;
    margin-bottom: 3rem;
}

.btn-large {
    padding: 1rem 3rem;
    font-size: 1.2rem;
    border-radius: 50px; /* Pill shape */
}

.task-input-container {
    width: 100%;
    max-width: 500px;
    z-index: 1;
}
.task-input-large {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 2px solid var(--border-color);
    font-size: 1.5rem;
    text-align: center;
    color: var(--text-primary);
    padding: 0.5rem;
    transition: all 0.3s;
}
.task-input-large:focus {
    outline: none;
    border-color: var(--primary-color);
}

/* --- SIDEBAR HISTORY --- */
.history-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    overflow: hidden;
}

.history-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.history-header {
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
    font-weight: 600;
}

.session-list {
    overflow-y: auto;
    flex: 1;
    padding-right: 0.5rem;
}

.session-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem;
    background: rgba(255,255,255,0.03);
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.session-info { display: flex; flex-direction: column; gap: 2px; }
.session-task { font-weight: 500; font-size: 0.95rem; }
.session-date { font-size: 0.75rem; color: var(--text-secondary); }
.session-duration { 
    background: rgba(16, 185, 129, 0.1); 
    color: #10b981; 
    padding: 2px 8px; 
    border-radius: 4px; 
    font-size: 0.85rem; 
    font-weight: 600;
}

/* Modal for Session Complete */
.modal {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8);
    align-items: center; justify-content: center; z-index: 2000;
}
.modal-content {
    background: var(--surface-dark); border: 1px solid var(--border-color);
    padding: 2rem; border-radius: 16px; text-align: center; width: 400px;
}
/* Responsive */
@media (max-width: 900px) {
    .focus-dashboard { grid-template-columns: 1fr; height: auto; }
    .focus-main { min-height: 400px; }
    .history-card { min-height: 300px; }
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
        <a href="goals.php" class="nav-item">🎯 Goals</a>
        <a href="focus.php" class="nav-item active">🧘 Focus</a>
    </nav>
    <div class="sidebar-footer">
        🔥 Day Streak: <span id="currentStreak">0</span>
    </div>
</aside>

<!-- MAIN -->
<main class="main-content">
    <header class="page-header">
        <h2>Deep Focus</h2>
    </header>

    <div class="focus-dashboard">
        
        <!-- TIMER AREA -->
        <div class="focus-main">
            <div class="focus-pulse" id="pulse" style="display:none"></div>
            
            <div class="timer-display" id="timerDisplay">00:00</div>
            
            <div class="focus-controls">
                <button class="btn-primary btn-large" id="startBtn" onclick="toggleFocus()">Start Focus</button>
                <button class="btn-secondary btn-large" id="stopBtn" onclick="stopFocus()" style="display:none">Stop</button>
            </div>

            <div class="task-input-container">
                <input type="text" id="focusTask" class="task-input-large" placeholder="What are you working on?">
            </div>
        </div>

        <!-- HISTORY -->
        <div class="history-panel">
            <div class="history-card">
                <div class="history-header">Session History (Last 30 Days)</div>
                <div class="session-list" id="sessionList">
                    <!-- Sessions injected here -->
                </div>
            </div>
            
            <div class="card" style="padding:1.5rem; text-align:center">
                <div style="font-size:0.9rem; color:var(--text-secondary)">Total Focus Today</div>
                <div style="font-size:2rem; font-weight:700; margin-top:0.5rem" id="todayTotal">0m</div>
            </div>
        </div>

    </div>
</main>
</div>

<!-- COMPLETION MODAL -->
<div class="modal" id="finishModal">
    <div class="modal-content">
        <h2>Session Complete! 🎉</h2>
        <p style="color:var(--text-secondary); margin:1rem 0">You focused for <span id="finalDuration" style="color:#fff; font-weight:bold">0</span> minutes.</p>
        <button class="btn-primary" onclick="closeModal()">Awesome!</button>
    </div>
</div>

<script>
// --- STATE ---
let timerInterval = null;
let seconds = 0;
let isRunning = false;
const api = 'backend/api.php';

window.onload = () => {
    fetchSessions();
};

async function fetchSessions() {
    try {
        const res = await fetch(`${api}?action=get_focus_sessions`);
        const sessions = await res.json(); // Returns array of sessions
        renderSessions(sessions);
    } catch (e) { console.error(e); }
}

function renderSessions(sessions) {
    const list = document.getElementById('sessionList');
    list.innerHTML = '';
    
    let totalToday = 0;
    const today = new Date().toISOString().split('T')[0];

    sessions.forEach(s => {
        const date = new Date(s.created_at);
        const dateStr = date.toISOString().split('T')[0];
        const displayDate = date.toLocaleDateString('en-US', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' });
        
        if (dateStr === today) totalToday += parseInt(s.duration);

        const item = document.createElement('div');
        item.className = 'session-item';
        item.innerHTML = `
            <div class="session-info">
                <span class="session-task">${s.task_name || 'Focus Session'}</span>
                <span class="session-date">${displayDate}</span>
            </div>
            <span class="session-duration">${s.duration}m</span>
        `;
        list.appendChild(item);
    });

    // Update today total
    const hours = Math.floor(totalToday / 60);
    const mins = totalToday % 60;
    document.getElementById('todayTotal').textContent = hours > 0 ? `${hours}h ${mins}m` : `${mins}m`;
}

// --- TIMER LOGIC ---
function toggleFocus() {
    if (isRunning) return;
    
    // Start
    const task = document.getElementById('focusTask').value.trim();
    if(!task) {
        document.getElementById('focusTask').focus();
        return; // Prompt user to enter task optional but good UX
    }

    isRunning = true;
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('stopBtn').style.display = 'inline-block';
    document.getElementById('pulse').style.display = 'block';
    document.getElementById('focusTask').disabled = true;

    timerInterval = setInterval(() => {
        seconds++;
        updateDisplay();
    }, 1000);
}

function stopFocus() {
    if (!isRunning) return;

    // Stop
    clearInterval(timerInterval);
    isRunning = false;
    
    const durationMinutes = Math.floor(seconds / 60);
    
    // Only save if > 1 minute (to avoid accidental clicks)
    if (durationMinutes >= 1) {
        saveSession(durationMinutes);
    } else {
        resetUI();
    }
}

async function saveSession(duration) {
    const taskName = document.getElementById('focusTask').value;
    
    // API Call
    await fetch(`${api}?action=add_focus_session`, {
        method: 'POST',
        body: JSON.stringify({
            id: Date.now(),
            taskName: taskName,
            duration: duration
        })
    });
    
    // Show Modal
    document.getElementById('finalDuration').textContent = duration;
    document.getElementById('finishModal').style.display = 'flex';
    
    // Refresh List
    fetchSessions();
}

function updateDisplay() {
    const min = Math.floor(seconds / 60);
    const sec = seconds % 60;
    document.getElementById('timerDisplay').textContent = 
        `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;
}

function resetUI() {
    seconds = 0;
    updateDisplay();
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('stopBtn').style.display = 'none';
    document.getElementById('pulse').style.display = 'none';
    document.getElementById('focusTask').disabled = false;
    document.getElementById('focusTask').value = '';
}

function closeModal() {
    document.getElementById('finishModal').style.display = 'none';
    resetUI();
}
</script>

</body>
</html>