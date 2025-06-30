<?php
session_start();
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Leaderboardu - Styl Akademia AI (v14 - PHP)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ai-dark': '#0f172a',
                        'ai-card': '#1e293b',
                        'ai-accent': '#06b6d4',
                        'ai-accent-hover': '#0891b2',
                        'ai-text': '#e2e8f0',
                        'ai-text-secondary': '#94a3b8',
                        'ai-border': '#334155',
                        'ai-input': '#334155',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: theme('colors.ai-dark'); color: theme('colors.ai-text'); }
        select { appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23E2E8F0%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right .7em top 50%, 0 0; background-size: .65em auto, 100%; padding-right: 2.5em; }
        select::-ms-expand { display: none; }
        .message-box { position: fixed; top: 20px; right: 20px; padding: 1rem; border-radius: 0.5rem; color: white; z-index: 100; opacity: 0; transition: opacity 0.5s ease-in-out; min-width: 200px; text-align: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); }
        .message-box.success { background-color: #166534; border: 1px solid #22c55e; }
        .message-box.error { background-color: #991b1b; border: 1px solid #ef4444; }
        .message-box.info { background-color: #1e40af; border: 1px solid #60a5fa; }
        .message-box.show { opacity: 1; }
        .hidden { display: none; }
        #logoutButton { cursor: pointer; }
        table { border-collapse: separate; border-spacing: 0; }
        th, td { border-bottom: 1px solid theme('colors.ai-border'); }
        thead th { border-bottom-width: 2px; }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background-color: theme('colors.ai-card / 75%'); }
        .reward-card { transition: transform 0.2s ease-in-out; }
        .reward-card:hover { transform: translateY(-2px); }
        .inventory-item { cursor: pointer; transition: background-color 0.2s; }
        .inventory-item:hover { background-color: theme('colors.ai-card / 75%'); }
        .task-item { transition: background-color 0.2s; }
        .task-item:hover { background-color: theme('colors.ai-card / 75%'); }
        .tab-button { padding: 0.5rem 1rem; border: 1px solid theme('colors.ai-border'); border-bottom: none; border-radius: 0.375rem 0.375rem 0 0; background-color: theme('colors.ai-dark'); cursor: pointer; transition: background-color 0.2s, color 0.2s; font-size: 0.875rem; font-weight: 600; }
        .tab-button:hover { background-color: theme('colors.ai-card'); }
        .tab-button.active { background-color: theme('colors.ai-card'); color: theme('colors.ai-accent'); border-color: theme('colors.ai-border'); position: relative; bottom: -1px; }
        .tab-content { border: 1px solid theme('colors.ai-border'); border-radius: 0 0.375rem 0.375rem 0.375rem; padding: 1rem; background-color: theme('colors.ai-card'); margin-top: -1px; }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto max-w-5xl bg-ai-card p-6 rounded-lg shadow-xl border border-ai-border">

        <div id="messageBox" class="message-box"></div>

        <div class="flex justify-between items-start mb-6 pb-4 border-b border-ai-border">
            <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Leaderboard & Zadania</h1>
            <div class="text-right text-sm">
                 <div id="userInfo" class="text-ai-text-secondary"></div>
                 <div id="logoutArea" class="hidden mt-1">
                    <button id="logoutButton" class="bg-gray-600 hover:bg-gray-500 text-white text-xs font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out shadow-md">
                        Wyloguj
                    </button>
                </div>
            </div>
        </div>

        <div id="loginSection" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
             <div class="p-4 border border-ai-border rounded-lg bg-ai-dark shadow-lg">
                <h2 class="text-xl font-semibold mb-3 text-ai-accent uppercase">Logowanie Admina</h2>
                <input type="text" id="adminLogin" placeholder="Login admina" value="sar3th" class="w-full mb-2 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                <input type="password" id="adminPassword" placeholder="Hasło admina" class="w-full mb-3 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                <button onclick="adminLogin()" class="w-full bg-ai-accent hover:bg-ai-accent-hover text-black font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-md">Zaloguj jako Admin</button>
            </div>

            <div class="p-4 border border-ai-border rounded-lg bg-ai-dark shadow-lg">
                <h2 class="text-xl font-semibold mb-3 text-ai-accent uppercase">Logowanie Użytkownika</h2>
                 <select id="userLoginSelect" class="w-full mb-2 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text">
                    <option value="">-- Wybierz użytkownika --</option>
                    </select>
                <input type="password" id="userPassword" placeholder="Hasło użytkownika" class="w-full mb-3 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                <button onclick="userLogin()" class="w-full bg-ai-accent hover:bg-ai-accent-hover text-black font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-md">Zaloguj się</button>
                 <p class="text-xs text-ai-text-secondary mt-2">Nie masz hasła? Poproś admina o dodanie Cię lub ustawienie hasła.</p>
            </div>
        </div>

        <div id="adminPanel" class="hidden mb-6 p-4 border border-ai-border rounded-lg bg-ai-dark shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-red-500 uppercase">Panel Admina</h2>
            <div class="space-y-4">
                 <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Dodaj Uczestnika</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" id="newParticipantName" placeholder="Imię nowego uczestnika" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <input type="password" id="newParticipantPassword" placeholder="Hasło (opcjonalne)" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <button onclick="addParticipant()" class="bg-ai-accent hover:bg-ai-accent-hover text-black font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Dodaj</button>
                    </div>
                    <p class="text-xs text-ai-text-secondary mt-1">Jeśli hasło nie zostanie podane, użytkownik będzie musiał je ustawić sam.</p>
                </div>
                <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Modyfikuj Punkty</h3>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <select id="adminParticipantSelect" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text">
                            <option value="">-- Wybierz uczestnika --</option>
                            </select>
                        <input type="number" id="adminPointsValue" placeholder="Liczba punktów" class="p-2 border border-ai-border rounded-md w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button onclick="addPoints()" class="flex-1 bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-3 rounded-md transition duration-150 ease-in-out text-sm shadow-sm">Dodaj</button>
                            <button onclick="subtractPoints()" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-2 px-3 rounded-md transition duration-150 ease-in-out text-sm shadow-sm">Odejmij</button>
                        </div>
                    </div>
                </div>
                 <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Zmień Hasło Użytkownika</h3>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <select id="adminChangePasswordSelect" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text">
                            <option value="">-- Wybierz użytkownika --</option>
                            </select>
                        <input type="password" id="adminNewPassword" placeholder="Nowe hasło" class="p-2 border border-ai-border rounded-md w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <button onclick="adminSetPassword()" class="bg-orange-600 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Zmień Hasło</button>
                    </div>
                    <p class="text-xs text-ai-text-secondary mt-1">Umożliwia zmianę hasła wybranego użytkownika.</p>
                    <p class="text-xs text-red-400 mt-1 font-semibold">Uwaga: Zmiana hasła jest nieodwracalna dla użytkownika bez wiedzy admina.</p>
                </div>
                 <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Usuń Uczestnika</h3>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <select id="deleteParticipantSelect" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text">
                            <option value="">-- Wybierz uczestnika do usunięcia --</option>
                            </select>
                        <button onclick="deleteParticipant()" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Usuń Uczestnika</button>
                    </div>
                </div>
                <div id="adminRewardsPanel" class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-3 text-ai-accent">Dodaj Nową Nagrodę</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" id="rewardName" placeholder="Nazwa nagrody" class="p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <input type="number" id="rewardCost" placeholder="Koszt w punktach" class="p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <input type="text" id="rewardLink" placeholder="Link do produktu (URL)" class="md:col-span-2 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <textarea id="rewardDescription" placeholder="Opis nagrody" rows="2" class="md:col-span-2 p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary"></textarea>
                        <input type="text" id="rewardPricePLN" placeholder="Orientacyjna cena (np. ok. 100 zł)" class="p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <button onclick="addReward()" class="md:col-start-2 bg-ai-accent hover:bg-ai-accent-hover text-black font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Dodaj Nagrodę</button>
                    </div>
                </div>

                <div id="adminAddTaskPanel" class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-3 text-ai-accent">Dodaj Nowe Zadanie</h3>
                    <div class="space-y-2">
                         <textarea id="taskDescription" placeholder="Treść zadania" rows="3" class="w-full p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary"></textarea>
                         <div class="flex flex-col sm:flex-row gap-3">
                            <input type="number" id="taskPoints" placeholder="Punkty za zadanie" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                            <button onclick="addTask()" class="bg-ai-accent hover:bg-ai-accent-hover text-black font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Dodaj Zadanie</button>
                         </div>
                    </div>
                </div>

                 <div id="adminTakenTasksPanel" class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-3 text-ai-accent">Zadania do Zatwierdzenia</h3>
                    <div id="adminTakenTasksList" class="space-y-2">
                        <p class="text-ai-text-secondary">Brak zadań do zatwierdzenia.</p>
                    </div>
                </div>

            </div>
        </div>

        <div id="userPanel" class="hidden mb-6 p-4 border border-ai-border rounded-lg bg-ai-dark shadow-lg">
             <h2 class="text-2xl font-bold mb-1 text-blue-400 uppercase">Panel Użytkownika: <span id="loggedInUserName"></span></h2>
             <div id="currentUserPointsDisplay" class="mb-4 text-lg text-ai-accent font-bold">Ładowanie punktów...</div>
             <div class="space-y-6">
                <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Ustaw/Zmień Hasło</h3>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <input type="password" id="newUserPassword" placeholder="Nowe hasło" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <button onclick="setPassword()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Ustaw Hasło</button>
                    </div>
                    <p class="text-xs text-ai-text-secondary mt-1">Ustaw swoje hasło, aby móc się logować.</p>
                </div>
                <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-2 text-ai-accent">Przekaż Punkty</h3>
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <select id="transferRecipientSelect" class="flex-grow p-2 border border-ai-border rounded-md focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text">
                            <option value="">-- Wybierz odbiorcę --</option>
                            </select>
                        <input type="number" id="transferPointsValue" placeholder="Liczba punktów" class="p-2 border border-ai-border rounded-md w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-ai-accent bg-ai-input text-ai-text placeholder-ai-text-secondary">
                        <button onclick="transferPoints()" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm">Przekaż</button>
                    </div>
                    <p class="text-xs text-ai-text-secondary mt-1">Możesz przekazać punkty innemu użytkownikowi.</p>
                </div>

                <div id="myCurrentTaskPanel" class="p-3 border border-ai-border rounded-lg bg-ai-card">
                     <h3 class="text-lg font-semibold mb-2 text-ai-accent uppercase">Moje Aktualne Zadanie</h3>
                     <div id="myCurrentTaskContent">
                         <p class="text-ai-text-secondary">Nie masz aktualnie podjętego żadnego zadania.</p>
                     </div>
                 </div>

                 <div id="availableTasksPanel" class="p-3 border border-ai-border rounded-lg bg-ai-card">
                     <h3 class="text-lg font-semibold mb-3 text-ai-accent uppercase">Dostępne Zadania</h3>
                     <div id="availableTasksList" class="space-y-2">
                         <p class="text-ai-text-secondary">Brak dostępnych zadań.</p>
                     </div>
                 </div>


                <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-4 text-ai-accent uppercase">Sklep z Nagrodami</h3>
                    <div id="rewardsShop" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <p class="text-ai-text-secondary col-span-full">Ładowanie nagród...</p>
                    </div>
                </div>

                 <div class="p-3 border border-ai-border rounded-lg bg-ai-card">
                    <h3 class="text-lg font-semibold mb-4 text-ai-accent uppercase">Moje Nagrody</h3>
                    <div id="userInventory" class="space-y-3">
                         <p class="text-ai-text-secondary">Nie masz jeszcze żadnych nagród.</p>
                    </div>
                </div>
             </div>
        </div>

        <div class="mt-8">
            <div class="mb-0 border-b border-ai-border flex justify-center gap-1">
                 <button id="tabBtnLeaderboard" class="tab-button active text-ai-text" onclick="switchTab('leaderboard')">Leaderboard</button>
                 <button id="tabBtnAvailableTasks" class="tab-button text-ai-text" onclick="switchTab('availableTasks')">Dostępne Zadania</button>
                 <button id="tabBtnTakenTasks" class="tab-button text-ai-text" onclick="switchTab('takenTasks')">Wykonywane Zadania</button>
            </div>

            <div id="tabContentLeaderboard" class="tab-content">
                <div class="overflow-x-auto rounded-lg border border-ai-border shadow-lg">
                    <table class="min-w-full bg-ai-card">
                        <thead class="bg-ai-dark">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-ai-accent uppercase tracking-wider">Miejsce</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-ai-accent uppercase tracking-wider">Imię</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-ai-accent uppercase tracking-wider">Punkty</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboardBody" class="text-ai-text">
                            <tr><td colspan="3" class="text-center py-4 text-ai-text-secondary">Ładowanie rankingu...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tabContentAvailableTasks" class="tab-content hidden">
                 <h2 class="text-xl font-semibold mb-3 text-ai-accent uppercase">Dostępne Zadania (Podgląd)</h2>
                 <div id="publicAvailableTasksList" class="space-y-2">
                     <p class="text-ai-text-secondary">Ładowanie zadań...</p>
                 </div>
            </div>

            <div id="tabContentTakenTasks" class="tab-content hidden">
                 <h2 class="text-xl font-semibold mb-3 text-ai-accent uppercase">Zadania w Trakcie (Podgląd)</h2>
                 <div id="publicTakenTasksList" class="space-y-2">
                      <p class="text-ai-text-secondary">Ładowanie zadań...</p>
                 </div>
            </div>
        </div>
    </div>
<script>
        const ADMIN_LOGIN = 'sar3th';
        const STORAGE_KEY_PARTICIPANTS = 'leaderboardParticipants';
        const STORAGE_KEY_REWARDS = 'leaderboardRewards';
        const STORAGE_KEY_TASKS = 'leaderboardTasks';

        let participants = [];
        let rewards = [];
        let tasks = [];
        let loggedInUser = null;
        let isAdminLoggedIn = <?php echo $isAdmin ? 'true' : 'false'; ?>;

        function saveData() {
            try {
                localStorage.setItem(STORAGE_KEY_PARTICIPANTS, JSON.stringify(participants));
                localStorage.setItem(STORAGE_KEY_REWARDS, JSON.stringify(rewards));
                localStorage.setItem(STORAGE_KEY_TASKS, JSON.stringify(tasks));
            } catch (e) {
                console.error('Failed to save data', e);
                showMessage('Błąd zapisu danych.', 'error');
            }
        }

        function loadData() {
            try {
                const p = localStorage.getItem(STORAGE_KEY_PARTICIPANTS);
                if (p) {
                    participants = JSON.parse(p);
                    participants.forEach(pp => { if(pp.password===undefined) pp.password=null; if(!pp.redeemedRewards) pp.redeemedRewards=[];});
                } else { participants = []; }
                const r = localStorage.getItem(STORAGE_KEY_REWARDS);
                rewards = r ? JSON.parse(r) : [];
                const t = localStorage.getItem(STORAGE_KEY_TASKS);
                tasks = t ? JSON.parse(t) : [];
            } catch(e){
                console.error('load error', e);
                participants=[]; rewards=[]; tasks=[];
                showMessage('Błąd odczytu danych.', 'error');
            }
        }

        function showMessage(msg,type='success',dur=3000){
            const box=document.getElementById('messageBox');
            if(!box)return;
            box.classList.remove('show');
            box.className='message-box';
            box.textContent=msg;
            setTimeout(()=>{box.className=`message-box ${type} show`;},50);
            setTimeout(()=>{box.classList.remove('show');},dur);
        }

        function updateUI(){
            const loginSection=document.getElementById('loginSection');
            const adminPanel=document.getElementById('adminPanel');
            const userPanel=document.getElementById('userPanel');
            const userInfo=document.getElementById('userInfo');
            const logoutArea=document.getElementById('logoutArea');
            const loggedInUserNameSpan=document.getElementById('loggedInUserName');
            const rewardsShop=document.getElementById('rewardsShop');
            const userInventory=document.getElementById('userInventory');
            const currentUserPointsDisplay=document.getElementById('currentUserPointsDisplay');
            const adminTakenTasksPanel=document.getElementById('adminTakenTasksPanel');
            const availableTasksPanel=document.getElementById('availableTasksPanel');
            const myCurrentTaskPanel=document.getElementById('myCurrentTaskPanel');
            const tabContentLeaderboard=document.getElementById('tabContentLeaderboard');
            const tabContentAvailableTasks=document.getElementById('tabContentAvailableTasks');
            const tabContentTakenTasks=document.getElementById('tabContentTakenTasks');

            loginSection.classList.add('hidden');
            adminPanel.classList.add('hidden');
            userPanel.classList.add('hidden');
            logoutArea.classList.add('hidden');
            userInfo.innerHTML='';
            currentUserPointsDisplay.innerHTML='';

            if(isAdminLoggedIn){
                adminPanel.classList.remove('hidden');
                logoutArea.classList.remove('hidden');
                const strong=document.createElement('strong');
                strong.classList.add('text-red-500');
                strong.textContent=`Admin (${ADMIN_LOGIN})`;
                userInfo.appendChild(document.createTextNode('Zalogowano jako: '));
                userInfo.appendChild(strong);
                renderAdminTakenTasks();
            } else if(loggedInUser){
                userPanel.classList.remove('hidden');
                logoutArea.classList.remove('hidden');
                loggedInUserNameSpan.textContent=loggedInUser;
                const strong=document.createElement('strong');
                strong.classList.add('text-blue-400');
                strong.textContent=loggedInUser;
                userInfo.appendChild(document.createTextNode('Zalogowano jako: '));
                userInfo.appendChild(strong);
                const currentUser=participants.find(p=>p.name===loggedInUser);
                if(currentUser){currentUserPointsDisplay.textContent=`Twoje punkty: ${currentUser.points}`;}
                renderRewardsShop();
                renderUserInventory();
                renderAvailableTasks();
                renderMyCurrentTask();
            } else {
                loginSection.classList.remove('hidden');
                rewardsShop.innerHTML='<p class="text-ai-text-secondary col-span-full">Zaloguj się, aby zobaczyć sklep.</p>';
                userInventory.innerHTML='<p class="text-ai-text-secondary">Zaloguj się, aby zobaczyć swoje nagrody.</p>';
                document.getElementById('availableTasksList').innerHTML='<p class="text-ai-text-secondary">Zaloguj się, aby zobaczyć dostępne zadania.</p>';
                document.getElementById('myCurrentTaskContent').innerHTML='<p class="text-ai-text-secondary">Zaloguj się, aby zobaczyć swoje zadanie.</p>';
            }
            renderLeaderboard();
            renderPublicAvailableTasks();
            renderPublicTakenTasks();
            populateSelects();
        }

        function populateSelects(){
            const configs=[
                {id:'userLoginSelect',excludeAdmin:true,excludeCurrentUser:false},
                {id:'adminParticipantSelect',excludeAdmin:true,excludeCurrentUser:false},
                {id:'deleteParticipantSelect',excludeAdmin:true,excludeCurrentUser:false},
                {id:'transferRecipientSelect',excludeAdmin:true,excludeCurrentUser:true},
                {id:'adminChangePasswordSelect',excludeAdmin:true,excludeCurrentUser:false}
            ];
            configs.forEach(cfg=>{
                const sel=document.getElementById(cfg.id); if(!sel)return;
                const val=sel.value; const first=sel.options.length>0?sel.options[0]:null; sel.innerHTML=''; if(first){sel.appendChild(first); first.selected=true;}
                participants.forEach(p=>{ if(cfg.excludeAdmin && p.name===ADMIN_LOGIN) return; if(cfg.excludeCurrentUser && p.name===loggedInUser) return; const opt=document.createElement('option'); opt.value=p.name; opt.textContent=p.name; sel.appendChild(opt); });
                if(val && Array.from(sel.options).some(o=>o.value===val)){ sel.value=val; }
            });
        }

        function renderLeaderboard(){
            const body=document.getElementById('leaderboardBody');
            body.innerHTML='';
            const sorted=[...participants].sort((a,b)=>{ if(a.name===ADMIN_LOGIN)return -1; if(b.name===ADMIN_LOGIN)return 1; return b.points-a.points; });
            if(sorted.length===0){ body.innerHTML='<tr><td colspan="3" class="text-center py-4 text-ai-text-secondary">Brak uczestników.</td></tr>'; return; }
            sorted.forEach((p,i)=>{ const row=body.insertRow(); const rank=row.insertCell(); rank.className='py-3 px-4 text-sm'; rank.innerHTML=p.name===ADMIN_LOGIN?'👑':i+1; const name=row.insertCell(); name.className='py-3 px-4 text-sm font-medium'; name.textContent=p.name; if(p.name===ADMIN_LOGIN){name.classList.add('text-red-500','font-bold');} const points=row.insertCell(); points.className='py-3 px-4 text-sm font-semibold text-ai-accent'; points.textContent=p.name===ADMIN_LOGIN?'∞':p.points; });
        }

        function renderRewardsShop(){
            const shop=document.getElementById('rewardsShop'); shop.innerHTML='';
            if(rewards.length===0){ shop.innerHTML='<p class="text-ai-text-secondary col-span-full">Brak dostępnych nagród w sklepie.</p>'; return; }
            rewards.forEach(r=>{ const card=document.createElement('div'); card.className='reward-card bg-ai-dark border border-ai-border rounded-lg p-4 flex flex-col justify-between shadow-md'; const name=document.createElement('h4'); name.className='text-lg font-semibold text-white mb-1'; name.textContent=r.name; const desc=document.createElement('p'); desc.className='text-sm text-ai-text-secondary mb-2 flex-grow'; desc.textContent=r.description; const price=document.createElement('div'); price.className='text-xs text-ai-text-secondary mb-3'; price.textContent=`Cena orientacyjna: ${r.pricePLN||'Brak danych'}`; const costInfo=document.createElement('div'); costInfo.className='flex justify-between items-center mt-auto'; const cost=document.createElement('span'); cost.className='text-lg font-bold text-ai-accent'; cost.textContent=`${r.cost} pkt`; const btn=document.createElement('button'); btn.className='bg-ai-accent hover:bg-ai-accent-hover text-black text-sm font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out shadow-sm'; btn.textContent='Wymień'; btn.dataset.rewardId=r.id; btn.addEventListener('click',()=>redeemReward(r.id)); costInfo.appendChild(cost); costInfo.appendChild(btn); card.appendChild(name); card.appendChild(desc); card.appendChild(price); card.appendChild(costInfo); shop.appendChild(card); });
        }

        function renderUserInventory(){
            const container=document.getElementById('userInventory'); if(!loggedInUser){container.innerHTML=''; return;} container.innerHTML=''; const current=participants.find(p=>p.name===loggedInUser); if(!current||!current.redeemedRewards||current.redeemedRewards.length===0){ container.innerHTML='<p class="text-ai-text-secondary">Nie masz jeszcze żadnych nagród.</p>'; return; }
            current.redeemedRewards.forEach(id=>{ const reward=rewards.find(r=>r.id===id); const item=document.createElement('div'); item.className='inventory-item bg-ai-dark border border-ai-border rounded-lg p-3 shadow-sm'; const info=document.createElement('div'); if(reward&&reward.link){ info.className='cursor-pointer'; info.addEventListener('click',()=>{ const det=document.getElementById(`reward-link-details-${reward.id}`); if(det) det.classList.toggle('hidden'); }); }
                const name=document.createElement('h5'); name.className='text-md font-semibold text-white'; name.textContent=reward?reward.name:`(Usunięta nagroda - ID: ${id})`; const price=document.createElement('p'); price.className='text-xs text-ai-text-secondary'; price.textContent=reward?`(Cena orientacyjna: ${reward.pricePLN||'Brak danych'})`:''; if(reward&&reward.link){ price.textContent+=' - Kliknij, aby zobaczyć link'; }
                info.appendChild(name); info.appendChild(price); item.appendChild(info);
                if(reward&&reward.link){ const det=document.createElement('div'); det.id=`reward-link-details-${reward.id}`; det.className='hidden mt-2 pt-2 border-t border-ai-border'; const label=document.createElement('p'); label.className='text-sm font-medium text-ai-text mb-1'; label.textContent='Twój prywatny link:'; const code=document.createElement('code'); code.id=`reward-link-text-${reward.id}`; code.className='block text-xs text-blue-400 bg-ai-input p-1 rounded break-all'; code.textContent=reward.link; det.appendChild(label); det.appendChild(code); item.appendChild(det); } else if(reward){ const no=document.createElement('p'); no.className='text-xs text-ai-text-secondary italic mt-1'; no.textContent='(Brak linku dla tej nagrody)'; item.appendChild(no);} container.appendChild(item); });
        }

        function redeemReward(id){
            if(!loggedInUser){ showMessage('Musisz być zalogowany, aby wymieniać nagrody.','error'); return; }
            const user=participants.find(p=>p.name===loggedInUser); const reward=rewards.find(r=>r.id===id); if(!user||!reward){ showMessage('Wystąpił błąd.','error'); return; } if(user.points<reward.cost){ showMessage(`Nie masz wystarczającej liczby punktów (${user.points}), aby wymienić "${reward.name}" (${reward.cost} pkt).`,'error'); return; }
            user.points-=reward.cost; if(!Array.isArray(user.redeemedRewards)) user.redeemedRewards=[]; user.redeemedRewards.push(reward.id); showMessage(`Wymieniono ${reward.cost} pkt na "${reward.name}"!`, 'success'); saveData(); updateUI();
        }

        function renderAvailableTasks(){
            const container=document.getElementById('availableTasksList'); container.innerHTML=''; const available=tasks.filter(t=>t.status==='available'); if(available.length===0){ container.innerHTML='<p class="text-ai-text-secondary">Brak dostępnych zadań.</p>'; return; } const userHasActive=tasks.some(t=>t.assignedTo===loggedInUser && t.status==='taken'); available.forEach(task=>{ const item=document.createElement('div'); item.className='task-item bg-ai-dark border border-ai-border rounded-lg p-3 shadow-sm flex justify-between items-center'; const info=document.createElement('div'); const desc=document.createElement('p'); desc.className='text-sm text-ai-text'; desc.textContent=task.description; const pts=document.createElement('span'); pts.className='text-xs text-ai-accent font-semibold ml-2'; pts.textContent=`(+${task.points} pkt)`; desc.appendChild(pts); info.appendChild(desc); const btn=document.createElement('button'); btn.className='bg-green-600 hover:bg-green-500 text-white text-xs font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out shadow-sm disabled:opacity-50 disabled:cursor-not-allowed'; btn.textContent='Weź zadanie'; btn.dataset.taskId=task.id; btn.addEventListener('click',()=>takeTask(task.id)); if(userHasActive){ btn.disabled=true; btn.title='Możesz mieć tylko jedno aktywne zadanie na raz.'; } item.appendChild(info); item.appendChild(btn); container.appendChild(item); }); if(userHasActive){ const info=document.createElement('p'); info.className='text-xs text-yellow-400 mt-2'; info.textContent='Zakończ swoje obecne zadanie, aby móc wziąć kolejne.'; container.appendChild(info); }
        }

        function renderMyCurrentTask(){
            const container=document.getElementById('myCurrentTaskContent'); if(!loggedInUser){container.innerHTML=''; return;} container.innerHTML=''; const myTask=tasks.find(t=>t.assignedTo===loggedInUser && t.status==='taken'); if(!myTask){ container.innerHTML='<p class="text-ai-text-secondary">Nie masz aktualnie podjętego żadnego zadania.</p>'; return; } const item=document.createElement('div'); item.className='bg-ai-dark border border-yellow-500 rounded-lg p-3 shadow-sm'; const desc=document.createElement('p'); desc.className='text-sm text-ai-text'; desc.textContent=myTask.description; const pts=document.createElement('span'); pts.className='text-xs text-ai-accent font-semibold ml-2'; pts.textContent=`(+${myTask.points} pkt)`; desc.appendChild(pts); const st=document.createElement('p'); st.className='text-xs text-yellow-400 mt-1 italic'; st.textContent='Zadanie w toku. Poczekaj na zatwierdzenie przez admina po wykonaniu.'; item.appendChild(desc); item.appendChild(st); container.appendChild(item);
        }

        function renderAdminTakenTasks(){
            const container=document.getElementById('adminTakenTasksList'); container.innerHTML=''; const taken=tasks.filter(t=>t.status==='taken'); if(taken.length===0){ container.innerHTML='<p class="text-ai-text-secondary">Brak zadań do zatwierdzenia.</p>'; return; } taken.forEach(task=>{ const item=document.createElement('div'); item.className='task-item bg-ai-dark border border-ai-border rounded-lg p-3 shadow-sm flex flex-col sm:flex-row justify-between sm:items-center'; const info=document.createElement('div'); const desc=document.createElement('p'); desc.className='text-sm text-ai-text'; desc.textContent=task.description; const pts=document.createElement('span'); pts.className='text-xs text-ai-accent font-semibold ml-2'; pts.textContent=`(+${task.points} pkt)`; desc.appendChild(pts); const assigned=document.createElement('p'); assigned.className='text-xs text-ai-text-secondary mt-1'; assigned.textContent=`Wzięte przez: ${task.assignedTo||'Błąd?'}`; info.appendChild(desc); info.appendChild(assigned); const btn=document.createElement('button'); btn.className='mt-2 sm:mt-0 bg-green-600 hover:bg-green-500 text-white text-xs font-bold py-1 px-3 rounded-md transition duration-150 ease-in-out shadow-sm'; btn.textContent='Zatwierdź wykonanie'; btn.dataset.taskId=task.id; btn.addEventListener('click',()=>approveTask(task.id)); item.appendChild(info); item.appendChild(btn); container.appendChild(item); });
        }

        function takeTask(id){
            if(!loggedInUser){ showMessage('Musisz być zalogowany, aby podjąć zadanie.','error'); return; }
            const hasActive=tasks.some(t=>t.assignedTo===loggedInUser && t.status==='taken'); if(hasActive){ showMessage('Możesz mieć tylko jedno aktywne zadanie na raz.','error'); return; }
            const task=tasks.find(t=>t.id===id); if(!task||task.status!=='available'){ showMessage('To zadanie nie jest już dostępne.','error'); renderAvailableTasks(); return; }
            task.status='taken'; task.assignedTo=loggedInUser; showMessage(`Podjęto zadanie: "${task.description}"!`,'success'); saveData(); updateUI();
        }

        function approveTask(id){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może zatwierdzać zadania.','error'); return; }
             const task=tasks.find(t=>t.id===id); if(!task){ showMessage('Nie znaleziono zadania.','error'); return; }
             if(task.status!=='taken'||!task.assignedTo){ showMessage('To zadanie nie jest w stanie "podjęte".','warning'); return; }
             const user=participants.find(p=>p.name===task.assignedTo); if(!user){ showMessage('Nie znaleziono użytkownika.','error'); return; }
             user.points+=task.points; task.status='completed'; showMessage(`Zatwierdzono wykonanie zadania "${task.description}" przez ${user.name}. Przyznano ${task.points} pkt.`,'success'); saveData(); updateUI();
        }

        function renderPublicAvailableTasks(){
            const container=document.getElementById('publicAvailableTasksList'); container.innerHTML=''; const available=tasks.filter(t=>t.status==='available'); if(available.length===0){ container.innerHTML='<p class="text-ai-text-secondary">Aktualnie brak dostępnych zadań.</p>'; return; } const table=document.createElement('table'); table.className='min-w-full'; const tbody=table.createTBody(); tbody.className='text-ai-text'; available.forEach(task=>{ const row=tbody.insertRow(); row.className='task-item'; const desc=row.insertCell(); desc.className='py-2 px-4 text-sm'; desc.textContent=task.description; const pts=row.insertCell(); pts.className='py-2 px-4 text-sm text-right text-ai-accent font-semibold'; pts.textContent=`+${task.points} pkt`; }); container.appendChild(table);
        }

        function renderPublicTakenTasks(){
             const container=document.getElementById('publicTakenTasksList'); container.innerHTML=''; const taken=tasks.filter(t=>t.status==='taken'); if(taken.length===0){ container.innerHTML='<p class="text-ai-text-secondary">Aktualnie nikt nie wykonuje żadnego zadania.</p>'; return; } const table=document.createElement('table'); table.className='min-w-full'; const tbody=table.createTBody(); tbody.className='text-ai-text'; taken.forEach(task=>{ const row=tbody.insertRow(); row.className='task-item'; const desc=row.insertCell(); desc.className='py-2 px-4 text-sm'; desc.textContent=task.description; const user=row.insertCell(); user.className='py-2 px-4 text-sm text-ai-text-secondary'; user.textContent=`Wykonuje: ${task.assignedTo||'???'}`; const pts=row.insertCell(); pts.className='py-2 px-4 text-sm text-right text-ai-accent font-semibold'; pts.textContent=`+${task.points} pkt`; }); container.appendChild(table);
        }

        function switchTab(tab){
            document.getElementById('tabContentLeaderboard').classList.add('hidden');
            document.getElementById('tabContentAvailableTasks').classList.add('hidden');
            document.getElementById('tabContentTakenTasks').classList.add('hidden');
            document.getElementById('tabBtnLeaderboard').classList.remove('active');
            document.getElementById('tabBtnAvailableTasks').classList.remove('active');
            document.getElementById('tabBtnTakenTasks').classList.remove('active');
            if(tab==='leaderboard'){ document.getElementById('tabContentLeaderboard').classList.remove('hidden'); document.getElementById('tabBtnLeaderboard').classList.add('active'); }
            else if(tab==='availableTasks'){ document.getElementById('tabContentAvailableTasks').classList.remove('hidden'); document.getElementById('tabBtnAvailableTasks').classList.add('active'); }
            else if(tab==='takenTasks'){ document.getElementById('tabContentTakenTasks').classList.remove('hidden'); document.getElementById('tabBtnTakenTasks').classList.add('active'); }
        }

        function adminLogin(){
            const login=document.getElementById('adminLogin').value;
            const pass=document.getElementById('adminPassword').value;
            fetch('login.php',{
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:`login=${encodeURIComponent(login)}&password=${encodeURIComponent(pass)}`
            })
            .then(r=>r.json())
            .then(d=>{
                if(d.success){
                    isAdminLoggedIn=true;
                    loggedInUser=null;
                    showMessage('Zalogowano jako Admin','success');
                    updateUI();
                }else{
                    showMessage('Nieprawidłowy login lub hasło admina.','error');
                }
            })
            .catch(()=>{
                showMessage('Błąd połączenia z serwerem.','error');
            });
        }

        function userLogin(){
            const select=document.getElementById('userLoginSelect');
            const passwordInput=document.getElementById('userPassword');
            const selectedName=select.value;
            const password=passwordInput.value;
            if(!selectedName){ showMessage('Wybierz użytkownika.','error'); return; }
            const participant=participants.find(p=>p.name===selectedName);
            if(!participant){ showMessage('Wybrany użytkownik nie istnieje.','error'); return; }
            if(participant.password===null){ showMessage('Ten użytkownik nie ma ustawionego hasła. Poproś admina o jego ustawienie.','info',5000); passwordInput.value=''; return; }
            if(participant.password===password){ loggedInUser=participant.name; isAdminLoggedIn=false; passwordInput.value=''; showMessage(`Zalogowano jako ${loggedInUser}`,'success'); updateUI(); } else { showMessage('Nieprawidłowe hasło.','error'); passwordInput.value=''; }
        }

        function logout(){
            fetch('logout.php').then(()=>{ isAdminLoggedIn=false; loggedInUser=null; showMessage('Wylogowano.','info'); updateUI(); switchTab('leaderboard'); });
        }

        function addTask(){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może dodawać zadania.','error'); return; }
             const descriptionInput=document.getElementById('taskDescription');
             const pointsInput=document.getElementById('taskPoints');
             const description=descriptionInput.value.trim();
             const points=parseInt(pointsInput.value,10);
             if(!description){ showMessage('Treść zadania jest wymagana.','error'); return; }
             if(isNaN(points)||points<=0){ showMessage('Punkty za zadanie muszą być poprawną liczbą dodatnią.','error'); return; }
             const newTask={id:Date.now().toString(),description,points,assignedTo:null,status:'available'};
             tasks.push(newTask); showMessage(`Dodano nowe zadanie: "${description}" (${points} pkt).`,'success'); saveData(); descriptionInput.value=''; pointsInput.value=''; renderPublicAvailableTasks();
        }

        function addReward(){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może dodawać nagrody.','error'); return; }
            const nameInput=document.getElementById('rewardName');
            const costInput=document.getElementById('rewardCost');
            const linkInput=document.getElementById('rewardLink');
            const descriptionInput=document.getElementById('rewardDescription');
            const pricePLNInput=document.getElementById('rewardPricePLN');
            const name=nameInput.value.trim();
            const cost=parseInt(costInput.value,10);
            const link=linkInput.value.trim();
            const description=descriptionInput.value.trim();
            const pricePLN=pricePLNInput.value.trim();
            if(!name){ showMessage('Nazwa nagrody jest wymagana.','error'); return; }
            if(isNaN(cost)||cost<=0){ showMessage('Koszt w punktach musi być poprawną liczbą dodatnią.','error'); return; }
            if(link && !link.startsWith('http://') && !link.startsWith('https://')){ showMessage('Link do produktu musi być poprawnym adresem URL (zaczynającym się od http:// lub https://).','error'); return; }
            const newReward={id:Date.now().toString(),name,cost,link:link||null,description,pricePLN};
            rewards.push(newReward); showMessage(`Dodano nową nagrodę: "${name}" (${cost} pkt).`,'success'); saveData(); nameInput.value=''; costInput.value=''; linkInput.value=''; descriptionInput.value=''; pricePLNInput.value=''; if(loggedInUser){ renderRewardsShop(); }
        }

        function addParticipant(){
            if(!isAdminLoggedIn){ showMessage('Tylko admin może dodawać uczestników.','error'); return; }
            const nameInput=document.getElementById('newParticipantName');
            const passwordInput=document.getElementById('newParticipantPassword');
            const name=nameInput.value.trim();
            const password=passwordInput.value;
            if(!name){ showMessage('Imię uczestnika nie może być puste.','error'); return; }
            if(name===ADMIN_LOGIN){ showMessage('Nie można dodać uczestnika o loginie admina.','error'); return; }
            if(participants.some(p=>p.name.toLowerCase()===name.toLowerCase())){ showMessage('Uczestnik o tym imieniu już istnieje.','error'); return; }
            if(password && password.length<4){ showMessage('Hasło musi mieć co najmniej 4 znaki.','error'); return; }
            participants.push({name,points:0,password:password||null,redeemedRewards:[]});
            nameInput.value=''; passwordInput.value=''; showMessage(`Dodano uczestnika: ${name}. ${password?'Hasło zostało ustawione.':'Nie ustawiono hasła.'}`,'success'); saveData(); updateUI();
        }

        function deleteParticipant(){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może usuwać uczestników.','error'); return; }
            const select=document.getElementById('deleteParticipantSelect');
            const nameToDelete=select.value;
             if(!nameToDelete){ showMessage('Wybierz uczestnika do usunięcia.','error'); return; }
             if(nameToDelete===ADMIN_LOGIN){ showMessage('Nie można usunąć konta administratora.','error'); return; }
             const userHasActive=tasks.some(task=>task.assignedTo===nameToDelete && task.status==='taken');
             if(userHasActive){ showMessage(`Nie można usunąć użytkownika "${nameToDelete}", ponieważ ma aktywne zadanie. Zatwierdź lub odrzuć zadanie najpierw.`,'error',5000); return; }
            const initLen=participants.length; participants=participants.filter(p=>p.name!==nameToDelete); if(participants.length<initLen){ showMessage(`Usunięto uczestnika: ${nameToDelete}`,'success'); saveData(); updateUI(); } else { showMessage('Nie znaleziono uczestnika do usunięcia.','error'); }
        }

        function adminSetPassword(){
            if(!isAdminLoggedIn){ showMessage('Tylko admin może zmieniać hasła użytkowników.','error'); return; }
            const select=document.getElementById('adminChangePasswordSelect');
            const passwordInput=document.getElementById('adminNewPassword');
            const selectedName=select.value;
            const newPassword=passwordInput.value;
            if(!selectedName){ showMessage('Wybierz użytkownika, któremu chcesz zmienić hasło.','error'); return; }
            if(!newPassword || newPassword.length<4){ showMessage('Nowe hasło musi mieć co najmniej 4 znaki.','error'); return; }
            const participant=participants.find(p=>p.name===selectedName);
            if(participant){ participant.password=newPassword; passwordInput.value=''; select.value=''; showMessage(`Hasło dla użytkownika ${selectedName} zostało zmienione przez admina.`,'success'); saveData(); } else { showMessage('Błąd: Nie znaleziono wybranego użytkownika.','error'); }
        }

        function addPoints(){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może modyfikować punkty.','error'); return; }
            const select=document.getElementById('adminParticipantSelect');
            const pointsInput=document.getElementById('adminPointsValue');
            const selectedName=select.value;
            const pointsToAdd=parseInt(pointsInput.value,10);
            if(!selectedName){ showMessage('Wybierz uczestnika.','error'); return; }
             if(selectedName===ADMIN_LOGIN){ showMessage('Nie można modyfikować punktów admina.','error'); return; }
            if(isNaN(pointsToAdd)||pointsToAdd<=0){ showMessage('Wprowadź poprawną, dodatnią liczbę punktów.','error'); return; }
            const participant=participants.find(p=>p.name===selectedName);
            if(participant){ participant.points+=pointsToAdd; showMessage(`Admin dodał ${pointsToAdd} pkt. dla ${participant.name}. Nowy wynik: ${participant.points}`,'success'); pointsInput.value=''; saveData(); renderLeaderboard(); if(loggedInUser===selectedName){ updateUI(); }} else { showMessage('Nie znaleziono wybranego uczestnika.','error'); }
        }

        function subtractPoints(){
             if(!isAdminLoggedIn){ showMessage('Tylko admin może modyfikować punkty.','error'); return; }
            const select=document.getElementById('adminParticipantSelect');
            const pointsInput=document.getElementById('adminPointsValue');
            const selectedName=select.value;
            const pointsToSubtract=parseInt(pointsInput.value,10);
            if(!selectedName){ showMessage('Wybierz uczestnika.','error'); return; }
             if(selectedName===ADMIN_LOGIN){ showMessage('Nie można modyfikować punktów admina.','error'); return; }
            if(isNaN(pointsToSubtract)||pointsToSubtract<=0){ showMessage('Wprowadź poprawną, dodatnią liczbę punktów do odjęcia.','error'); return; }
            const participant=participants.find(p=>p.name===selectedName);
            if(participant){ participant.points-=pointsToSubtract; if(participant.points<0) participant.points=0; showMessage(`Admin odjął ${pointsToSubtract} pkt. od ${participant.name}. Nowy wynik: ${participant.points}`,'success'); pointsInput.value=''; saveData(); renderLeaderboard(); if(loggedInUser===selectedName){ updateUI(); }} else { showMessage('Nie znaleziono wybranego uczestnika.','error'); }
        }

        function setPassword(){
            if(!loggedInUser){ showMessage('Musisz być zalogowany, aby ustawić hasło.','error'); return; }
            const passwordInput=document.getElementById('newUserPassword');
            const newPassword=passwordInput.value;
            if(!newPassword||newPassword.length<4){ showMessage('Hasło musi mieć co najmniej 4 znaki.','error'); return; }
            const participant=participants.find(p=>p.name===loggedInUser);
            if(participant){ participant.password=newPassword; passwordInput.value=''; showMessage('Hasło zostało ustawione/zmienione.','success'); saveData(); } else { showMessage('Błąd: Nie znaleziono zalogowanego użytkownika.','error'); }
        }

        function transferPoints(){
             if(!loggedInUser){ showMessage('Musisz być zalogowany, aby przekazywać punkty.','error'); return; }
            const recipientSelect=document.getElementById('transferRecipientSelect');
            const pointsInput=document.getElementById('transferPointsValue');
            const recipientName=recipientSelect.value; const pointsToTransfer=parseInt(pointsInput.value,10);
            if(!recipientName){ showMessage('Wybierz odbiorcę punktów.','error'); return; }
            if(isNaN(pointsToTransfer)||pointsToTransfer<=0){ showMessage('Wprowadź poprawną, dodatnią liczbę punktów do przekazania.','error'); return; }
            const sender=participants.find(p=>p.name===loggedInUser); const recipient=participants.find(p=>p.name===recipientName);
            if(!sender||!recipient){ showMessage('Błąd: Nie znaleziono nadawcy lub odbiorcy.','error'); return; }
             if(sender.name===ADMIN_LOGIN||recipient.name===ADMIN_LOGIN){ showMessage('Admin nie może uczestniczyć w transferach punktów.','error'); return; }
            if(sender.points<pointsToTransfer){ showMessage(`Nie masz wystarczającej liczby punktów (${sender.points}), aby przekazać ${pointsToTransfer}.`,'error'); return; }
            sender.points-=pointsToTransfer; recipient.points+=pointsToTransfer; showMessage(`Przekazano ${pointsToTransfer} pkt. do ${recipient.name}. Twój nowy stan: ${sender.points} pkt.`,'success'); pointsInput.value=''; recipientSelect.value=''; saveData(); updateUI();
        }

        document.addEventListener('DOMContentLoaded',()=>{
            try{ loadData(); const logoutButton=document.getElementById('logoutButton'); if(logoutButton){ logoutButton.addEventListener('click',logout); } updateUI(); switchTab('leaderboard'); } catch(e){ console.error('CRITICAL',e); document.body.innerHTML='<div style="color:#ef4444; background-color:#1e293b; border:1px solid #ef4444; padding:20px; margin:20px; border-radius:5px; font-family:sans-serif;'><h2 style="margin-top:0; color:white;">Wystąpił krytyczny błąd aplikacji!</h2><p>Spróbuj wyczyścić dane strony lub skontaktuj się z administratorem.</p><p><strong>Komunikat błędu:</strong> '+e.message+'</p></div>'; }
        });
</script>
</body>
</html>
