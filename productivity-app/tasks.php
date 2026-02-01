<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Tasks</title>
<link rel="stylesheet" href="css/global.css">

<style>
.tasks-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem}
.task-top-stats{grid-column:span 2;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.task-stat-box{background:var(--surface-dark);border:1px solid var(--border-color);border-radius:8px;padding:1.2rem;text-align:center}
.task-stat-label{font-size:.75rem;color:var(--text-secondary)}
.task-stat-value{font-size:1.8rem;font-weight:700;margin-top:.5rem}

.task-row{display:flex;align-items:center;justify-content:space-between;padding:1rem;border:1px solid var(--border-color);border-radius:8px;margin-bottom:.75rem;background:var(--surface-dark)}
.task-left{display:flex;align-items:center;gap:.75rem}
.task-checkbox{width:20px;height:20px;cursor:pointer}
.priority-dot{width:10px;height:10px;border-radius:50%}

.modal{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:1000}
.modal-content{background:var(--surface-dark);border:1px solid var(--border-color);padding:2rem;width:420px;border-radius:8px}
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
    <div class="sidebar-footer">
        🔥 Day Streak: <span id="currentStreak">0</span>
    </div>
</aside>

<!-- MAIN -->
<main class="main-content">
<header class="page-header">
    <h2>Task Manager</h2>
</header>

<div class="tasks-grid">

    <!-- STATS -->
    <div class="task-top-stats">
        <div class="task-stat-box">
            <div class="task-stat-label">Total Tasks</div>
            <div class="task-stat-value" id="totalTasks">0</div>
        </div>
        <div class="task-stat-box">
            <div class="task-stat-label">Completed Today</div>
            <div class="task-stat-value" id="tasksDoneToday">0</div>
        </div>
        <div class="task-stat-box">
            <div class="task-stat-label">Pending Tasks</div>
            <div class="task-stat-value" id="pendingTasks">0</div>
        </div>
    </div>

    <!-- TASK LIST -->
    <div class="card" style="grid-column:span 2;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
            <h3>My Tasks</h3>
            <button class="btn-primary" onclick="openModal()">Add Task</button>
        </div>
        <div id="tasksList"></div>
    </div>

</div>
</main>
</div>

<!-- MODAL -->
<div class="modal" id="taskModal">
    <div class="modal-content">
        <h3>Add New Task</h3>
        <input type="text" id="taskName" placeholder="Task name" class="form-input" style="margin-bottom:1rem">
        <select id="taskPriority" class="form-input">
            <option value="low">Low Priority</option>
            <option value="medium" selected>Medium Priority</option>
            <option value="high">High Priority</option>
        </select>
        <button class="btn-primary" id="saveTaskBtn" style="margin-top:1rem">Save Task</button>
    </div>
</div>

<script>
let tasks=JSON.parse(localStorage.getItem('tasks')||'[]');
function today(){return new Date().toISOString().split('T')[0]}
function save(){localStorage.setItem('tasks',JSON.stringify(tasks));render()}

function render(){
    const list=document.getElementById('tasksList');
    list.innerHTML='';
    tasks.forEach((t,i)=>{
        const done=t.dateDone===today();
        const color=t.priority==='high'?'#ef4444':t.priority==='medium'?'#f59e0b':'#10b981';
        list.innerHTML+=`
        <div class="task-row">
            <div class="task-left">
                <div class="priority-dot" style="background:${color}"></div>
                <div>${t.name}</div>
            </div>
            <input type="checkbox" class="task-checkbox" ${done?'checked':''}
            onchange="toggleTask(${i})">
        </div>`;
    });
    updateStats();
}

function toggleTask(i){
    if(tasks[i].dateDone===today()) tasks[i].dateDone=null;
    else tasks[i].dateDone=today();
    save();
}

function updateStats(){
    document.getElementById('totalTasks').textContent=tasks.length;
    const doneToday=tasks.filter(t=>t.dateDone===today()).length;
    document.getElementById('tasksDoneToday').textContent=doneToday;
    document.getElementById('pendingTasks').textContent=tasks.length-doneToday;
}

function openModal(){taskModal.style.display='flex'}
window.onclick=e=>{if(e.target.id==='taskModal')taskModal.style.display='none'}

saveTaskBtn.onclick=()=>{
    const name=taskName.value.trim();
    if(!name)return;
    const priority=taskPriority.value;
    tasks.push({name,priority,dateDone:null});
    taskName.value='';
    taskModal.style.display='none';
    save();
}

render();
</script>

</body>
</html>
