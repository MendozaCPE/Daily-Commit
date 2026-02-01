<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productivity Hub - Goals</title>
<link rel="stylesheet" href="css/global.css">

<style>
.goals-dashboard{display:grid;grid-template-columns:2fr 1fr;gap:1.5rem}

/* Glass cards */
.glass{
    background:rgba(255,255,255,0.03);
    backdrop-filter:blur(12px);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:14px;
}

/* Quote glow */
.quote-card{grid-column:span 2;padding:2rem;text-align:center;position:relative;overflow:hidden}
.quote-text{font-size:1.6rem;font-style:italic;margin-bottom:.5rem}
.quote-author{font-size:.8rem;color:var(--text-secondary);letter-spacing:1px}
.quote-card::after{
    content:'';
    position:absolute;
    width:320px;height:320px;
    background:radial-gradient(circle,#8b5cf6,transparent 60%);
    filter:blur(90px);
    top:-80px;left:50%;
    transform:translateX(-50%);
    opacity:.25;
    animation:floatGlow 8s ease-in-out infinite alternate;
}
@keyframes floatGlow{
    from{transform:translateX(-50%) translateY(0)}
    to{transform:translateX(-50%) translateY(30px)}
}

/* Input */
.input-card{display:flex;gap:.5rem;padding:1rem}
.goal-list-container{display:flex;flex-direction:column;gap:.8rem;margin-top:1rem}

/* Goal card animation */
.goal-card{
    padding:1rem;
    display:flex;
    align-items:center;
    justify-content:space-between;
    transition:.25s;
    position:relative;
    animation:popIn .35s ease;
}
@keyframes popIn{
    from{opacity:0;transform:translateY(10px)}
    to{opacity:1;transform:translateY(0)}
}
.goal-card:hover{transform:translateX(6px)}
.goal-card::before{
    content:'';
    position:absolute;
    left:0;top:0;bottom:0;
    width:4px;
    background:#8b5cf6;
}
.goal-left{display:flex;align-items:center;gap:1rem;flex:1}

/* Ripple check */
.goal-check{
    width:26px;height:26px;border-radius:50%;
    border:2px solid var(--border-color);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;position:relative;overflow:hidden;
}
.goal-check.checked{background:#10b981;border-color:#10b981;color:#fff}
.goal-check::after{
    content:'';
    position:absolute;
    width:0;height:0;
    border-radius:50%;
    background:rgba(16,185,129,.4);
    top:50%;left:50%;
    transform:translate(-50%,-50%);
}
.goal-check:active::after{
    width:80px;height:80px;
    transition:.4s;
    opacity:0;
}

.goal-text{font-size:1.1rem}
.goal-card.completed .goal-text{text-decoration:line-through;color:var(--text-secondary)}
.delete-btn{background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem}

/* Progress ring */
.progress-card{padding:2rem;text-align:center}
.ring-container{position:relative;width:170px;height:170px;margin:auto}
.progress-ring-circle{transform:rotate(-90deg);transform-origin:50% 50%;transition:stroke-dashoffset .6s ease}
.ring-text{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}
.ring-percent{font-size:2.2rem;font-weight:700}
.ring-label{font-size:.8rem;color:var(--text-secondary);text-transform:uppercase}

/* Empty */
.empty-state{text-align:center;padding:2rem;color:var(--text-secondary);opacity:.7}

@media(max-width:900px){
    .goals-dashboard{grid-template-columns:1fr}
    .quote-card{grid-column:span 1}
}
</style>
</head>

<body>
<div class="container">

<aside class="sidebar">
    <div class="logo"><div class="logo-icon">⚡</div><h1>ProductivityHub</h1></div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item">📊 Dashboard</a>
        <a href="pomodoro.php" class="nav-item">☕ Pomodoro</a>
        <a href="habits.php" class="nav-item">✨ Habits</a>
        <a href="tasks.php" class="nav-item">✓ Tasks</a>
        <a href="goals.php" class="nav-item active">🎯 Goals</a>
        <a href="focus.php" class="nav-item">🧘 Focus</a>
    </nav>
    <div class="sidebar-footer">🔥 Day Streak: <span id="currentStreak">0</span></div>
</aside>

<main class="main-content">
<header class="page-header"><h2>Daily Goals</h2></header>

<div class="goals-dashboard">

    <div class="quote-card glass">
        <div class="quote-text" id="dailyQuote"></div>
        <div class="quote-author" id="quoteAuthor"></div>
    </div>

    <div class="goals-section">
        <div class="input-card glass">
            <input type="text" id="goalInput" class="form-input" placeholder="What is your main focus for today?">
            <button class="btn-primary" onclick="addGoal()">Add</button>
        </div>
        <div id="goalsList" class="goal-list-container"></div>
    </div>

    <div class="progress-card glass">
        <h3>Daily Progress</h3>
        <div class="ring-container">
            <svg width="170" height="170">
                <circle cx="85" cy="85" r="75" stroke="rgba(255,255,255,0.08)" stroke-width="12" fill="none"></circle>
                <circle id="ringCircle" class="progress-ring-circle"
                        cx="85" cy="85" r="75"
                        stroke="#8b5cf6" stroke-width="12"
                        fill="none" stroke-dasharray="471"
                        stroke-dashoffset="471" stroke-linecap="round"></circle>
            </svg>
            <div class="ring-text">
                <div class="ring-percent" id="ringPercent">0%</div>
                <div class="ring-label">Done</div>
            </div>
        </div>
        <div style="color:var(--text-secondary);font-size:.9rem">
            <span id="completedCount">0</span> of <span id="totalCount">0</span> goals completed
        </div>
    </div>

</div>
</main>
</div>

<script>
let goals=[];
const api='backend/api.php';

const quotes=[
{text:"The secret of getting ahead is getting started.",author:"Mark Twain"},
{text:"It always seems impossible until it's done.",author:"Nelson Mandela"},
{text:"Don't watch the clock; do what it does. Keep going.",author:"Sam Levenson"},
{text:"Quality is not an act, it is a habit.",author:"Aristotle"},
{text:"Believe you can and you're halfway there.",author:"Theodore Roosevelt"}
];

window.onload=()=>{
const q=quotes[Math.floor(Math.random()*quotes.length)];
dailyQuote.textContent=`"${q.text}"`;
quoteAuthor.textContent=`- ${q.author}`;
fetchGoals();
};

async function fetchGoals(){
const res=await fetch(`${api}?action=get_data`);
const data=await res.json();
goals=data.goals||[];
renderGoals();
}

function renderGoals(){
goalsList.innerHTML='';
if(goals.length===0){
goalsList.innerHTML=`<div class="empty-state">No goals yet. Add your main focus for today.</div>`;
}
goals.forEach(g=>{
const card=document.createElement('div');
card.className=`goal-card glass ${g.completed?'completed':''}`;
card.innerHTML=`
<div class="goal-left">
<button class="goal-check ${g.completed?'checked':''}" onclick="toggleGoal('${g.id}')">${g.completed?'✓':''}</button>
<span class="goal-text">${g.text}</span>
</div>
<button class="delete-btn" onclick="deleteGoal('${g.id}')">✕</button>`;
goalsList.appendChild(card);
});
updateProgress();
}

async function addGoal(){
const text=goalInput.value.trim();
if(!text)return;
const newGoal={id:Date.now().toString(),text,completed:false};
goals.unshift(newGoal);
goalInput.value='';
renderGoals();
await fetch(`${api}?action=add_goal`,{method:'POST',body:JSON.stringify(newGoal)});
}

async function toggleGoal(id){
const g=goals.find(x=>x.id==id);
g.completed=!g.completed;
renderGoals();
updateProgress();
await fetch(`${api}?action=toggle_goal`,{method:'POST',body:JSON.stringify({id})});
}

async function deleteGoal(id){
goals=goals.filter(g=>g.id!=id);
renderGoals();
updateProgress();
await fetch(`${api}?action=delete_goal`,{method:'POST',body:JSON.stringify({id})});
}

function updateProgress(){
const total=goals.length;
const done=goals.filter(g=>g.completed).length;
const percent=total?Math.round(done/total*100):0;
ringPercent.textContent=`${percent}%`;
completedCount.textContent=done;
totalCount.textContent=total;

const circle=document.getElementById('ringCircle');
const circumference=2*Math.PI*75;
const offset=circumference-(percent/100)*circumference;
circle.style.strokeDashoffset=offset;
circle.style.stroke=percent===100?'#10b981':'#8b5cf6';

if(percent===100 && total>0) confettiBurst();
}

/* Confetti */
function confettiBurst(){
for(let i=0;i<40;i++){
const c=document.createElement('div');
c.style.position='fixed';
c.style.width='6px';
c.style.height='6px';
c.style.background=['#10b981','#8b5cf6','#f59e0b'][Math.floor(Math.random()*3)];
c.style.top='50%';c.style.left='50%';
c.style.pointerEvents='none';
document.body.appendChild(c);
const angle=Math.random()*2*Math.PI;
const dist=200+Math.random()*200;
c.animate([
{transform:'translate(-50%,-50%)'},
{transform:`translate(${Math.cos(angle)*dist}px,${Math.sin(angle)*dist}px)`}
],{duration:800,easing:'ease-out'});
setTimeout(()=>c.remove(),800);
}
}
</script>

</body>
</html>
