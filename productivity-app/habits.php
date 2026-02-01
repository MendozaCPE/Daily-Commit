<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Habits</title>
<link rel="stylesheet" href="css/global.css">
<!-- Chart.js is not needed as we use custom charts.js, but let's ensure we reference it if needed or usage charts.js -->
<script src="charts.js"></script> 

<style>
/* --- LAYOUT GRID --- */
.habits-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

/* --- STATS CARDS --- */
.stats-overview {
    grid-column: span 2;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-title { font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; }
.stat-value { font-size: 1.75rem; font-weight: 700; margin-top: 0.5rem; color: var(--text-primary); }
.stat-sub { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; }

/* --- HABIT LIST & CONTROLS --- */
.habits-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.controls-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--surface-dark);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.filter-group {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
}

.habit-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem; /* Compact padding */
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.habit-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.habit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.habit-info {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.habit-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    background: rgba(255,255,255,0.05);
}

.habit-title-group {
    display: flex;
    flex-direction: column;
}

.habit-name { font-weight: 600; font-size: 1rem; }
.habit-streak { font-size: 0.75rem; color: var(--text-secondary); display: flex; align-items: center; gap: 4px; }

.habit-actions {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.check-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    color: transparent;
}

.check-btn.checked {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
}

.menu-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0 0.5rem;
}

/* --- PROGRESS BAR --- */
.progress-container {
    height: 6px;
    background: rgba(255,255,255,0.05);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 0.2rem;
}
.progress-bar {
    height: 100%;
    border-radius: 3px;
    transition: width 0.4s ease;
}

/* --- SIDE CHARTS --- */
.charts-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.chart-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    min-height: 250px;
}
.chart-card h3 {
    margin-bottom: 1rem;
    font-size: 1rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

/* --- MODALS --- */
.modal {
    position: fixed; inset: 0; background: rgba(0,0,0,0.7);
    display: none; align-items: center; justify-content: center; z-index: 1000;
}
.modal-content {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    width: 450px;
    padding: 2rem;
    border-radius: 12px;
}

/* Calendar Grid */
.history-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-top: 1rem;
}
.history-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border-radius: 4px;
    background: rgba(255,255,255,0.05);
    color: var(--text-secondary);
}
.history-day.active {
    background: var(--primary-color);
    color: #fff;
    font-weight: bold;
}
.history-day.header {
    background: transparent;
    font-weight: 600;
}

/* Color Picker */
.color-picker { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
.color-option { width: 24px; height: 24px; border-radius: 50%; cursor: pointer; border: 2px solid transparent; }
.color-option.selected { border-color: #fff; transform: scale(1.1); }

/* Responsive */
@media (max-width: 900px) {
    .habits-dashboard { grid-template-columns: 1fr; }
    .stats-overview { grid-template-columns: repeat(2, 1fr); }
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
        <a href="habits.php" class="nav-item active">✨ Habits</a>
        <a href="tasks.php" class="nav-item">✓ Tasks</a>
        <a href="goals.php" class="nav-item">🎯 Goals</a>
        <a href="focus.php" class="nav-item">🧘 Focus</a>
    </nav>
</aside>

<!-- MAIN -->
<main class="main-content">
    <header class="page-header">
        <h2>Habit Tracker</h2>
        <div class="user-profile">
            <span>Welcome Back</span>
        </div>
    </header>

    <!-- TOP STATS -->
    <div class="stats-overview">
        <div class="stat-card">
            <span class="stat-title">Completion Rate</span>
            <div class="stat-value" id="globalRate">0%</div>
            <span class="stat-sub">For today's habits</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Perfect Days</span>
            <div class="stat-value" id="perfectDays">0</div>
            <span class="stat-sub">Last 30 days</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Longest Streak</span>
            <div class="stat-value" id="topStreak">0</div>
            <span class="stat-sub" id="topStreakName">-</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Best Day</span>
            <div class="stat-value" id="bestDay">-</div>
            <span class="stat-sub">Most productive day</span>
        </div>
    </div>

    <div class="habits-dashboard">
        
        <!-- LEFT: HABIT LIST -->
        <div class="habits-section">
            <div class="controls-bar">
                <div class="filter-group">
                    <button class="filter-btn active" onclick="setFilter('all')">All</button>
                    <button class="filter-btn" onclick="setFilter('pending')">Pending</button>
                    <button class="filter-btn" onclick="setFilter('completed')">Completed</button>
                </div>
                <button class="btn-primary" onclick="openEditModal()">+ New Habit</button>
            </div>

            <div id="habitsListContainer">
                <!-- Habits injected here -->
            </div>
        </div>

        <!-- RIGHT: CHARTS -->
        <div class="charts-sidebar">
            <div class="chart-card">
                <h3>Weekly Completeness</h3>
                <canvas id="weeklyChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Monthly Trend</h3>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

</main>
</div>

<!-- EDIT/ADD MODAL -->
<div class="modal" id="habitModal">
    <div class="modal-content">
        <h3 id="modalTitle">New Habit</h3>
        <input type="hidden" id="habitId">
        
        <label class="form-label">Habit Name</label>
        <input type="text" id="habitName" class="form-input" placeholder="e.g. Read 30 mins">
        
        <label class="form-label">Color Code</label>
        <div class="color-picker" id="colorPicker">
            <div class="color-option selected" data-color="#6366f1" style="background:#6366f1"></div>
            <div class="color-option" data-color="#14b8a6" style="background:#14b8a6"></div>
            <div class="color-option" data-color="#10b981" style="background:#10b981"></div>
            <div class="color-option" data-color="#f59e0b" style="background:#f59e0b"></div>
            <div class="color-option" data-color="#ef4444" style="background:#ef4444"></div>
            <div class="color-option" data-color="#ec4899" style="background:#ec4899"></div>
        </div>

        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button class="btn-secondary" onclick="closeModal('habitModal')">Cancel</button>
            <button class="btn-primary" onclick="saveHabit()">Save Habit</button>
        </div>
    </div>
</div>

<!-- HISTORY MODAL -->
<div class="modal" id="historyModal">
    <div class="modal-content">
        <h3 id="historyHabitName">Habit History</h3>
        <p class="text-sm text-muted">Last 30 days check-ins</p>
        
        <div id="historyGrid" class="history-grid"></div>

        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button class="btn-secondary" onclick="closeModal('historyModal')">Close</button>
            <button class="btn-danger" id="deleteHabitBtn" onclick="confirmDelete()">Delete Habit</button>
        </div>
    </div>
</div>

<script>
// --- STATE ---
let habits = [];
let currentFilter = 'all';
let currentHabitIdForHistory = null;
const api = 'backend/api.php';

// --- INITIALIZATION ---
window.onload = () => {
    fetchHabits();
    // Setup color picker
    document.querySelectorAll('.color-option').forEach(c => {
        c.onclick = () => {
            document.querySelectorAll('.color-option').forEach(x => x.classList.remove('selected'));
            c.classList.add('selected');
        }
    });
};

function today() {
    return new Date().toISOString().split('T')[0];
}

async function fetchHabits() {
    try {
        const res = await fetch(`${api}?action=get_data`);
        const data = await res.json();
        
        // Ensure habits are loaded, structure should match DB format
        habits = data.habits || [];
        // Map old structure if needed, but DB returns completedDates
        
        renderHabits();
        updateStats();
        renderCharts();
    } catch (e) {
        console.error("Failed to load habits:", e);
    }
}

// --- RENDERING ---
function renderHabits() {
    const list = document.getElementById('habitsListContainer');
    list.innerHTML = '';

    const d = today();
    
    // Filter logic
    const filtered = habits.filter(h => {
        const isCompleted = h.completedDates && h.completedDates.includes(d);
        if (currentFilter === 'pending') return !isCompleted;
        if (currentFilter === 'completed') return isCompleted;
        return true;
    });

    if (filtered.length === 0) {
        list.innerHTML = `<div style="text-align:center; padding: 2rem; color: var(--text-secondary)">No habits found for this filter.</div>`;
        return;
    }

    filtered.forEach(h => {
        const isCompleted = h.completedDates && h.completedDates.includes(d);
        const streak = calculateStreak(h);
        const completionLast30 = calculateCompletionRate(h, 30);
        
        const card = document.createElement('div');
        card.className = 'habit-card';
        card.innerHTML = `
            <div class="habit-header">
                <div class="habit-info">
                    <div class="habit-icon" style="color:${h.color}">★</div>
                    <div class="habit-title-group">
                        <div class="habit-name">${h.name}</div>
                        <div class="habit-streak">
                            <span>🔥 ${streak} day streak</span>
                            <span>• ${completionLast30}% in last 30 days</span>
                        </div>
                    </div>
                </div>
                <div class="habit-actions">
                    <button class="check-btn ${isCompleted ? 'checked' : ''}" 
                            style="${isCompleted ? 'background:'+h.color+'; border-color:'+h.color : ''}"
                            onclick="toggleHabit(${h.id})">
                        ${isCompleted ? '✓' : ''}
                    </button>
                    <button class="menu-btn" onclick="openHistory(${h.id})">⋮</button>
                    <button class="menu-btn" onclick="openEditModal(${h.id})">✎</button>
                </div>
            </div>
            <!-- Progress Bar visually showing last 7 days mini-graph could go here, 
                 but keeping it simple with a completion line for now -->
            <div class="progress-container">
                <div class="progress-bar" style="width: ${completionLast30}%; background: ${h.color}"></div>
            </div>
        `;
        list.appendChild(card);
    });
}

function setFilter(type) {
    currentFilter = type;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    renderHabits();
}

// --- ACTIONS ---
async function toggleHabit(id) {
    const d = today();
    const habit = habits.find(h => h.id == id);
    if (!habit) return;

    // Optimistic Update
    if (!habit.completedDates) habit.completedDates = [];
    const idx = habit.completedDates.indexOf(d);
    
    if (idx > -1) {
        habit.completedDates.splice(idx, 1);
    } else {
        habit.completedDates.push(d);
    }

    renderHabits();
    updateStats();
    
    // API Call
    await fetch(`${api}?action=update_habit_dates`, {
        method: 'POST',
        body: JSON.stringify({ id: habit.id, completedDates: habit.completedDates })
    });
}

async function saveHabit() {
    const id = document.getElementById('habitId').value;
    const name = document.getElementById('habitName').value.trim();
    if (!name) return;
    
    const color = document.querySelector('.color-option.selected').dataset.color;
    
    if (id) {
        // Update
        await fetch(`${api}?action=update_habit`, {
            method: 'POST',
            body: JSON.stringify({ id: parseInt(id), name, color })
        });
    } else {
        // Create
        // Generate pseudo-unique ID for now or let DB handle autoincrement
        // However, the current API expects ID passed from frontend for consistency with old logic
        // Let's use Date.now() for ID
        const newId = Date.now();
        await fetch(`${api}?action=add_habit`, {
            method: 'POST',
            body: JSON.stringify({ id: newId, name, color })
        });
    }
    
    closeModal('habitModal');
    fetchHabits();
}

async function confirmDelete() {
    if (!currentHabitIdForHistory) return;
    if (!confirm('Are you sure you want to delete this habit?')) return;
    
    await fetch(`${api}?action=delete_habit`, {
        method: 'POST',
        body: JSON.stringify({ id: currentHabitIdForHistory })
    });
    
    closeModal('historyModal');
    fetchHabits();
}

// --- MODALS ---
function openEditModal(id = null) {
    const modal = document.getElementById('habitModal');
    modal.style.display = 'flex';
    
    if (id) {
        const h = habits.find(x => x.id == id);
        document.getElementById('modalTitle').textContent = 'Edit Habit';
        document.getElementById('habitId').value = h.id;
        document.getElementById('habitName').value = h.name;
        // select color
        document.querySelectorAll('.color-option').forEach(c => {
            if (c.dataset.color === h.color) c.classList.add('selected');
            else c.classList.remove('selected');
        });
    } else {
        document.getElementById('modalTitle').textContent = 'New Habit';
        document.getElementById('habitId').value = '';
        document.getElementById('habitName').value = '';
    }
}

function openHistory(id) {
    currentHabitIdForHistory = id;
    const h = habits.find(x => x.id == id);
    document.getElementById('historyHabitName').textContent = h.name;
    document.getElementById('historyModal').style.display = 'flex';
    
    const grid = document.getElementById('historyGrid');
    grid.innerHTML = '';
    
    // Generate headers (S M T W T F S)
    const days = ['S','M','T','W','T','F','S'];
    days.forEach(d => grid.innerHTML += `<div class="history-day header">${d}</div>`);
    
    // Generate last 28 days (4 weeks)
    const todayDate = new Date();
    // Align to start of a visual block if we wanted perfect calendar, 
    // but simple last 28 days is fine. Let's do 28 days descending.
    
    for (let i = 27; i >= 0; i--) {
        const d = new Date();
        d.setDate(todayDate.getDate() - i);
        const dateStr = d.toISOString().split('T')[0];
        
        const isDone = h.completedDates && h.completedDates.includes(dateStr);
        
        const cell = document.createElement('div');
        cell.className = `history-day ${isDone ? 'active' : ''}`;
        if(isDone) cell.style.background = h.color;
        
        cell.textContent = d.getDate();
        cell.title = dateStr;
        grid.appendChild(cell);
    }
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// --- CALCULATIONS & STATS ---

function calculateStreak(habit) {
    if (!habit.completedDates || habit.completedDates.length === 0) return 0;
    
    let streak = 0;
    const todayStr = today();
    
    // Check if done today, if so, streak starts today. If not, check yesterday.
    let date = new Date();
    let dateStr = date.toISOString().split('T')[0];
    
    // If not done today, start checking from yesterday
    if (!habit.completedDates.includes(todayStr)) {
         date.setDate(date.getDate() - 1);
         dateStr = date.toISOString().split('T')[0];
         // If yesterday also not done, streak is 0
         if (!habit.completedDates.includes(dateStr)) return 0;
    }
    
    // Use a while loop to go backwards
    while (true) {
        if (habit.completedDates.includes(dateStr)) {
            streak++;
            date.setDate(date.getDate() - 1);
            dateStr = date.toISOString().split('T')[0];
        } else {
            break;
        }
    }
    return streak;
}

function calculateCompletionRate(habit, days) {
    if (!habit.completedDates) return 0;
    let count = 0;
    const now = new Date();
    
    for(let i=0; i<days; i++) {
        const d = new Date();
        d.setDate(now.getDate() - i);
        const s = d.toISOString().split('T')[0];
        if (habit.completedDates.includes(s)) count++;
    }
    return Math.round((count / days) * 100);
}

function updateStats() {
    if (!habits.length) return;
    
    const d = today();
    
    // 1. Completion Rate Today
    const doneToday = habits.filter(h => h.completedDates && h.completedDates.includes(d)).length;
    const rate = Math.round((doneToday / habits.length) * 100);
    document.getElementById('globalRate').textContent = rate + '%';
    
    // 2. Best Streak
    let bestStreak = 0;
    let bestName = '-';
    habits.forEach(h => {
        const s = calculateStreak(h);
        if (s > bestStreak) {
            bestStreak = s;
            bestName = h.name;
        }
    });
    document.getElementById('topStreak').textContent = bestStreak;
    document.getElementById('topStreakName').textContent = bestName;
    
    // 3. Perfect Days (Last 30)
    let perfectDays = 0;
    for (let i=0; i<30; i++) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const s = date.toISOString().split('T')[0];
        
        // Count how many habits exist/done on that day
        // Note: For simplicity assuming all current habits existed back then
        const doneOnDay = habits.filter(h => h.completedDates && h.completedDates.includes(s)).length;
        if (doneOnDay === habits.length && habits.length > 0) perfectDays++;
    }
    document.getElementById('perfectDays').textContent = perfectDays;

    // 4. Best Day of Week
    // Aggregate all completions by day index (0-6)
    const dayCounts = [0,0,0,0,0,0,0];
    habits.forEach(h => {
        (h.completedDates || []).forEach(dateStr => {
            const dayIdx = new Date(dateStr).getDay();
            dayCounts[dayIdx]++;
        });
    });
    const maxDayVal = Math.max(...dayCounts);
    const dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const bestDayIdx = dayCounts.indexOf(maxDayVal);
    document.getElementById('bestDay').textContent = maxDayVal > 0 ? dayNames[bestDayIdx] : '-';
}

function renderCharts() {
    if (!window.ChartRenderer) return;
    const renderer1 = new ChartRenderer('weeklyChart');
    const renderer2 = new ChartRenderer('monthlyChart');
    
    // Weekly Data: Last 7 days, Percentage Completed
    const weeklyLabels = [];
    const weeklyValues = [];
    for(let i=6; i>=0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        const label = d.toLocaleDateString('en-US', {weekday:'short'});
        const s = d.toISOString().split('T')[0];
        
        const count = habits.filter(h => h.completedDates && h.completedDates.includes(s)).length;
        const total = habits.length;
        const val = total ? Math.round((count/total)*100) : 0;
        
        weeklyLabels.push(label);
        weeklyValues.push(val);
    }
    
    renderer1.drawBarChart({
        labels: weeklyLabels,
        values: weeklyValues,
        colors: ['#6366f1', '#818cf8', '#a5b4fc']
    });

    // Monthly Trend: Last 30 days, aggregated by week roughly or just a smoothed line
    // Let's do a line chart for last 14 days for cleaner view
    const trendLabels = [];
    const trendValues = [];
    for(let i=13; i>=0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        const label = d.getDate(); // Just day number
        const s = d.toISOString().split('T')[0];
        
        const count = habits.filter(h => h.completedDates && h.completedDates.includes(s)).length;
        const total = habits.length;
        const val = total ? Math.round((count/total)*100) : 0;
        
        trendLabels.push(label);
        trendValues.push(val);
    }
    
    renderer2.drawLineChart({
        labels: trendLabels,
        datasets: [{
            label: 'Completion %',
            values: trendValues,
            color: '#10b981'
        }]
    });
}
</script>

</body>
</html>
