<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Tasks</title>
<link rel="stylesheet" href="css/global.css">

<style>
/* --- LAYOUT & STATS --- */
.tasks-dashboard {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

.stats-overview {
    grid-column: span 2;
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 stats for tasks */
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

/* --- CONTROLS & LIST --- */
.tasks-section {
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

.filter-group { display: flex; gap: 0.5rem; }

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

/* --- TASK ITEM --- */
.task-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 0.8rem;
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.task-left { display: flex; align-items: center; gap: 1rem; flex: 1; }

.priority-indicator {
    width: 4px;
    height: 32px;
    border-radius: 2px;
}
.priority-high { background: #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.4); }
.priority-medium { background: #f59e0b; }
.priority-low { background: #10b981; }

.task-content { display: flex; flex-direction: column; }
.task-text { font-size: 1rem; font-weight: 500; transition: color 0.2s; }
.task-meta { font-size: 0.75rem; color: var(--text-secondary); display: flex; gap: 0.5rem; margin-top: 2px; }

.task-card.completed .task-text {
    text-decoration: line-through;
    color: var(--text-secondary);
}

.task-actions { display: flex; align-items: center; gap: 0.8rem; }

.check-btn {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    border: 2px solid var(--border-color);
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.2s;
}

.check-btn.checked {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
    font-size: 0.8rem;
}

.delete-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1.1rem;
    opacity: 0.5;
    transition: opacity 0.2s;
}
.delete-btn:hover { opacity: 1; color: #ef4444; }

/* --- RIGHT SIDEBAR --- */
.tasks-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.mini-card {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
}
.mini-card h3 {
    margin-bottom: 1rem;
    font-size: 0.95rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

/* --- MODAL --- */
.modal {
    position: fixed; inset: 0; background: rgba(0,0,0,0.7);
    display: none; align-items: center; justify-content: center; z-index: 1000;
}
.modal-content {
    background: var(--surface-dark);
    border: 1px solid var(--border-color);
    width: 400px;
    padding: 2rem;
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 900px) {
    .tasks-dashboard { grid-template-columns: 1fr; }
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
        <a href="tasks.php" class="nav-item active">✓ Tasks</a>
        <a href="goals.php" class="nav-item">🎯 Goals</a>
        <a href="focus.php" class="nav-item">🧘 Focus</a>
    </nav>
</aside>

<!-- MAIN -->
<main class="main-content">
    <header class="page-header">
        <h2>Task Manager</h2>
        <div class="user-profile">
            <span>Keep pushing! 🚀</span>
        </div>
    </header>

    <!-- STATS -->
    <div class="stats-overview">
        <div class="stat-card">
            <span class="stat-title">Total Tasks</span>
            <div class="stat-value" id="totalCount">0</div>
            <span class="stat-sub">Active & Completed</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Completed</span>
            <div class="stat-value" id="completedCount">0</div>
            <span class="stat-sub" style="color: #10b981;">Nice work!</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Pending</span>
            <div class="stat-value" id="pendingCount">0</div>
            <span class="stat-sub" style="color: #f59e0b;">Let's do this</span>
        </div>
    </div>

    <div class="tasks-dashboard">
        
        <!-- LEFT: TASK LIST -->
        <div class="tasks-section">
            <div class="controls-bar">
                <div class="filter-group">
                    <button class="filter-btn active" onclick="setFilter('pending')">Pending</button>
                    <button class="filter-btn" onclick="setFilter('completed')">Completed</button>
                    <button class="filter-btn" onclick="setFilter('all')">All Tasks</button>
                </div>
                <button class="btn-primary" onclick="openModal()">+ Add Task</button>
            </div>

            <div id="tasksListContainer">
                <!-- Tasks injected here -->
            </div>
        </div>

        <!-- RIGHT: INFO & TIPS -->
        <div class="tasks-sidebar">
            <div class="mini-card">
                <h3>Priority Legend</h3>
                <div style="display:flex; flex-direction:column; gap:0.5rem; margin-top:0.5rem">
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <div class="priority-indicator priority-high" style="height:12px; width:12px; border-radius:50%"></div>
                        <span style="font-size:0.9rem">High Priority</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <div class="priority-indicator priority-medium" style="height:12px; width:12px; border-radius:50%"></div>
                        <span style="font-size:0.9rem">Medium Priority</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <div class="priority-indicator priority-low" style="height:12px; width:12px; border-radius:50%"></div>
                        <span style="font-size:0.9rem">Low Priority</span>
                    </div>
                </div>
            </div>

            <div class="mini-card">
                <h3>Productivity Tip</h3>
                <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.5">
                    Start with the hardest task first (Eat the Frog) or build momentum with quick wins. Focus on one thing at a time!
                </p>
            </div>
        </div>
    </div>

</main>
</div>

<!-- MODAL -->
<div class="modal" id="taskModal">
    <div class="modal-content">
        <h3>New Task</h3>
        
        <label class="form-label">Task Description</label>
        <input type="text" id="taskName" class="form-input" placeholder="e.g. Finish report">
        
        <label class="form-label">Priority</label>
        <select id="taskPriority" class="form-input">
            <option value="high">High 🔥</option>
            <option value="medium" selected>Medium ⚡</option>
            <option value="low">Low ☕</option>
        </select>

        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button class="btn-secondary" onclick="closeModal()">Cancel</button>
            <button class="btn-primary" onclick="saveTask()">Add Task</button>
        </div>
    </div>
</div>

<script>
// --- STATE ---
let tasks = [];
let currentFilter = 'pending';
const api = 'backend/api.php';

// --- INIT ---
window.onload = () => {
    fetchTasks();
};

async function fetchTasks() {
    try {
        const res = await fetch(`${api}?action=get_data`);
        const data = await res.json();
        tasks = data.tasks || [];
        renderTasks();
        updateStats();
    } catch (e) {
        console.error("Error loading tasks:", e);
    }
}

// --- RENDER ---
function renderTasks() {
    const list = document.getElementById('tasksListContainer');
    list.innerHTML = '';
    
    // Filter
    const filtered = tasks.filter(t => {
        if (currentFilter === 'pending') return !t.completed;
        if (currentFilter === 'completed') return t.completed;
        return true;
    });

    if (filtered.length === 0) {
        list.innerHTML = `<div style="text-align:center; padding: 3rem; color: var(--text-secondary)">No ${currentFilter} tasks found.</div>`;
        return;
    }
    
    // Sort: High priority first, then medium, then low
    const pVal = { 'high': 3, 'medium': 2, 'low': 1 };
    filtered.sort((a, b) => pVal[b.priority] - pVal[a.priority]);

    filtered.forEach(t => {
        const card = document.createElement('div');
        card.className = `task-card ${t.completed ? 'completed' : ''}`;
        card.innerHTML = `
            <div class="task-left">
                <div class="priority-indicator priority-${t.priority}" title="${t.priority} priority"></div>
                <button class="check-btn ${t.completed ? 'checked' : ''}" onclick="toggleTask('${t.id}')">
                    ${t.completed ? '✓' : ''}
                </button>
                <div class="task-content">
                    <span class="task-text">${t.text}</span>
                    <span class="task-meta">${t.priority} priority</span>
                </div>
            </div>
            <div class="task-actions">
                <button class="delete-btn" onclick="deleteTask('${t.id}')">🗑️</button>
            </div>
        `;
        list.appendChild(card);
    });
}

function updateStats() {
    document.getElementById('totalCount').textContent = tasks.length;
    document.getElementById('completedCount').textContent = tasks.filter(t => t.completed).length;
    document.getElementById('pendingCount').textContent = tasks.filter(t => !t.completed).length;
}

function setFilter(type) {
    currentFilter = type;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    renderTasks();
}

// --- ACTIONS ---
async function toggleTask(id) {
    // Optimistic
    const t = tasks.find(x => x.id == id);
    if(t) {
        t.completed = !t.completed;
        renderTasks();
        updateStats();
        
        await fetch(`${api}?action=toggle_task`, {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        // Reload to sync state just in case (optional, relying on optimistic for speed)
    }
}

async function saveTask() {
    const text = document.getElementById('taskName').value.trim();
    if (!text) return;
    const priority = document.getElementById('taskPriority').value;
    const id = Date.now().toString();
    
    // Add locally
    const newTask = { id, text, priority, completed: false };
    tasks.unshift(newTask); // Add to top
    
    closeModal();
    renderTasks();
    updateStats();
    
    // Save DB
    await fetch(`${api}?action=add_task`, {
        method: 'POST',
        body: JSON.stringify({ id, text, priority })
    });
}

async function deleteTask(id) {
    if(!confirm("Delete this task?")) return;
    
    // Remove locally
    tasks = tasks.filter(t => t.id != id);
    renderTasks();
    updateStats();
    
    await fetch(`${api}?action=delete_task`, {
        method: 'POST',
        body: JSON.stringify({ id })
    });
}

// --- MODAL ---
function openModal() {
    document.getElementById('taskModal').style.display = 'flex';
    document.getElementById('taskName').focus();
}
function closeModal() {
    document.getElementById('taskModal').style.display = 'none';
    document.getElementById('taskName').value = '';
}
</script>

</body>
</html>
