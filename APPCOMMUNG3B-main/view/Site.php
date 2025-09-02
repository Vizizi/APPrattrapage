<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Capteur Professionnel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap">
  <style>
    :root {
      --main-bg: linear-gradient(135deg, #f9fafc 0%, #eaf3fc 100%);
      --dashboard-bg: #fff;
      --curve: #1ea7e1;
      --fill: rgba(30,167,225,0.16);
      --success: #19a953;
      --alert: #e23e2d;
      --warn: #ffcd38;
      --table-header: #e2f2fc;
      --stat-bg: #f0f6fe;
    }
    html, body {height:100%;width:100%;margin:0;padding:0;}
    body {
      background: var(--main-bg);
      font-family: 'Montserrat', Arial, sans-serif;
      color: #223;
      min-height: 100vh;
      min-width: 100vw;
    }
    .dashboard {
      background: var(--dashboard-bg);
      width: 100vw;
      min-height: 100vh;
      margin: 0; padding: 0;
      display: flex; flex-direction: column; align-items: center;
      box-sizing: border-box; overflow-x: hidden;
      box-shadow: 0 8px 28px #eaeff7;
    }
    header {
      width: 100vw; display: flex; align-items: center; justify-content: space-between;
      padding: 16px 40px 0 36px;
      box-sizing: border-box;
      border-bottom: 1px solid #e0e9f0;
      background: transparent;
    }
    .logo-titre {
      display: flex; align-items: center; font-size: 1.29em; font-weight: 700;
    }
    .logo-cust {width:42px;height:42px;margin-right:13px;}
    h1 {color: #007acc; font-size:2.18em; margin:34px 0 10px 0;}
    .etat-bar {display:flex;align-items:center;margin-top:9px;margin-bottom:6px;}
    .status-ind {width:19px;height:19px;border-radius:50%;border:2px solid #fff;margin-right:8px;box-shadow:0 1px 7px #8883;}
    .status-ind.ok {background:#22e244;}
    .status-ind.nok {background:#f05353;}
    .etat-bar span {font-weight:500;}
    .valeur {
      font-size:3.4em;display:inline-block;background:#e5f6ff;color:#19a953;
      border-radius:15px;padding:13px 30px;margin-bottom:14px;font-family:inherit;
      box-shadow:0 2px 8px #1eb46e18;transition:.18s;
    }
    .valeur.alert {color:#e23e2d;background:#fff4f1;}
    .valeur.warn {color:#ffcd38;background:#fffbe6;}
    .btns-bar {
      display: flex;
      flex-direction: column;
      gap: 17px;
      align-items: center;
      margin-bottom:20px;margin-top:2px;
    }
    .btns-main {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-bottom: 6px;
      flex-wrap: wrap;
    }
    .btns-options {
      display: flex;
      gap: 13px;
      justify-content: center;
      flex-wrap: wrap;
    }
    button, select {
      font-size:1.08em;border:none;border-radius:8px;padding:8px 16px;
      cursor:pointer;background:#1ea7e1;color:#fff;
      box-shadow:0 2px 7px #007acc1c;transition: background .13s;
      font-family:inherit;
    }
    button:hover, select:hover { background: #007acc;}
    button[aria-pressed="true"] {background:#ededed;color:#333;}
    .btn-compact, .btn-help {background: #e7eaea; color: #217;}
    .btn-help:hover {background: #ffe158; color: #111;}
    #mesureSerie {
      font-size:1.2em;
      background:#f6fafd;
      padding:18px 12px;
      border-radius:10px;
      margin:24px auto 20px auto;
      max-width:520px;
      box-shadow:0 2px 10px #c1e7ff23;
    }
    #resultatSerie {
      white-space:pre-line;
      word-break:break-word;
      margin-top:11px;
      color:#226;
      border-left:3px solid #1ea7e1;
      padding-left:9px;
    }
    #graphique {
      background: #eaf3fc; border-radius:13px; box-shadow:0 3px 17px #9ec4f82a;
      margin:6px auto 14px auto; width:98vw; max-width:1120px; height:265px;
    }
    .statistiques {background:#f0f6fe;color:#1160b2;font-size:1.10em;
      border-radius:11px;box-shadow:0 1px 7px #c5dee629;padding:8px 17px;margin:2px 0 4px 0;}
    .recap-table {
      width:98vw;max-width:920px;border-collapse:collapse;font-size:1.07em;
      box-shadow:0 2px 13px #9ccbf335;border-radius:10px;overflow:hidden; margin-bottom:15px;
      background:#f8fbff;
    }
    .recap-table th,.recap-table td {padding:7px 8px;border-bottom:1px solid #e2ebfa;text-align:center;}
    .recap-table th {background:#e2f2fc; color:#1ea7e1;}
    .recap-table tr:last-child td {border-bottom:none;}
    .recap-table tr.alert td {background:#fceeee;color:#e23e2d;}
    .footer {margin:15px 0 6px 0;font-size:1.09em;color:#337bae;}
    .notif {
      display:none;position:fixed;top:18px;right:24px;background:#19a953;color:#fff;
      font-size:1.13em;padding:11px 19px;border-radius:10px;box-shadow:0 4px 17px #1eb46e38;z-index:9999;
      animation:fadeout 2.8s 1 both;}
    @keyframes fadeout{0%{opacity:1;}90%{opacity:1;}100%{opacity:0;}}
    .help-modal {display:none;position:fixed;z-index:10000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,.44);}
    .help-modal .modal-content {background:#fefefe;color:#1a282f;
      border-radius:13px;padding:22px 23px;position:absolute;top:54%;left:50%;
      transform:translate(-50%,-50%);box-shadow:0 3px 36px #8df;}
    .help-modal .modal-content h2 {margin-top:0;color:#1590d8;}
    .help-modal .close-modal {float:right;color:#462;font-size:1.35em;cursor:pointer;}
    .compact .statistiques,.compact .recap-table{display:none;}
    .compact #graphique{height:390px;max-height:62vh;}
    .compact .valeur{font-size:4.6em;}
    .lang-bar {margin:0 8px;}
    @media (max-width:700px) {
      h1{font-size:1.04em;}.valeur{font-size:1.5em;}
      header{padding:12px 9vw 0 10vw;flex-direction:column;align-items:flex-start;}
      .logo-titre{margin-bottom:7px;}
      .btns-main,.btns-options {flex-direction:column;align-items:center;}
    }
    body.dark {
      background: linear-gradient(135deg, #262e44 0%, #1e2936 100%);
      color: #edf3fa;
    }
    body.dark .dashboard{background: #232736e0;}
    body.dark h1{color:#39c5e0;}
    body.dark .valeur{background:#233848;color:#2ae890;}
    body.dark .valeur.alert{background:#312425;color:#ef8b7a;}
    body.dark .valeur.warn{background:#2c2e22;color:#ffe158;}
    body.dark #graphique{background:#232b32;}
    body.dark .statistiques{background:#232944;color:#39c5e0;}
    body.dark .recap-table{background:#232b3b;color:#dff3fd;}
    body.dark .recap-table th{background:#2f495a;}
    body.dark .recap-table tr.alert td{background:#3d2324;color:#ef8b7a;}
    body.dark .footer{color:#53b4ca;}
    body.dark .help-modal .modal-content{background:#282f3c;color:#d8f3ff;}
  </style>
</head>
<body>
  <header>
    <div class="logo-titre">
      <img src="capteur.png" alt="Logo capteur" class="logo-cust">
      <span id="titrePage">Dashboard Capteur</span>
    </div>
    <div class="lang-bar">
      <label for="langSelect">Langue :</label>
      <select id="langSelect" aria-label="Choix de la langue">
        <option value="fr" selected>FR</option>
        <option value="en">EN</option>
        <option value="es">ES</option>
      </select>
    </div>
  </header>
  <div class="dashboard" id="mainBoard" role="main" aria-label="Tableau de bord capteur">
    <div class="info-capteur" aria-label="Informations Capteur">
      <b>Capteur :</b> DHT22 – Plage : 0–1023 – Précision : ±2 – Calibration : 06/2025
    </div>
    <div class="etat-bar">
      <span id="statusInd" class="status-ind ok" aria-label="État capteur"></span>
      <span id="etatLib">Capteur connecté</span>
    </div>
    <h1>📏 Valeur mesurée</h1>
    <div class="valeur" id="valAffichée" aria-live="polite">—</div>

    <!-- Bloc lecture série direct -->
    <div id="mesureSerie">
      <span style="font-weight:700;">Lecture série en direct&nbsp;:</span>
      <pre id="resultatSerie"></pre>
    </div>

    <div class="btns-bar">
      <div class="btns-main">
        <button id="pauseBtn" aria-pressed="false">⏸️ Pause</button>
        <button id="refreshBtn">🔄 Rafraîchir</button>
        <button id="exportBtn">⬇️ Exporter</button>
      </div>
      <div class="btns-options">
        <button id="themeBtn">🌓 Thème sombre</button>
        <button id="fsBtn">⛶ Plein écran</button>
        <button id="compactBtn">🗕 Mode compact</button>
        <button id="helpBtn">❓ Aide</button>
        <span>
          <label for="nbSelect">Historique</label>
          <select id="nbSelect">
            <option value="20">20</option>
            <option value="40">40</option>
            <option value="80">80</option>
          </select>
        </span>
      </div>
    </div>
    <canvas id="graphique" width="1600" height="340"></canvas>
    <div class="statistiques" id="statistiques"></div>
    <table id="recapTable" class="recap-table">
      <thead>
        <tr><th>#</th><th>Valeur</th><th>Horodatage</th></tr>
      </thead>
      <tbody></tbody>
    </table>
    <div class="footer">Capteur connecté · Données live · Zoom/plein écran · Export · Mode compact</div>
    <div id="notif" class="notif" role="alert"></div>
    <div id="helpModal" class="help-modal" role="dialog" aria-modal="true" aria-labelledby="helpTitle" style="display:none;">
      <div class="modal-content">
        <span class="close-modal" id="closeHelp" tabindex="0" role="button" aria-label="Fermer">✖</span>
        <h2 id="helpTitle">Aide & Raccourcis</h2>
        <ul style="text-align:left;max-width:430px;margin:0 auto;">
          <li><b>Pause/Reprendre :</b> "⏸️"/"▶️" ou barre espace</li>
          <li><b>Rafraîchir :</b> "🔄" ou touche R</li>
          <li><b>Export :</b> "⬇️" ou touche E</li>
          <li><b>Plein écran :</b> "⛶" ou touche F</li>
          <li><b>Thème Sombre/Clair :</b> "🌓" ou touche T</li>
          <li><b>Mode compact :</b> "🗕" ou touche C</li>
          <li><b>Aide & raccourcis :</b> "❓" ou touche H, ? ou F1</li>
          <li><b>Zoom/Pan graphique :</b> Molette + ctrl · Double click pour reset</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- SCRIPTS -->
  <script>
    // Bloc lecture série direct
    function lireSerie() {
      fetch('get_valeur.php?' + Date.now())
        .then(r => r.json())
        .then(data => {
          if (data.ligne) {
            const m = data.ligne.match(/^Brute:\s*(\d+).*Tension:\s*([0-9.]+)\s*V.*Distance:\s*([^\s]+.*)$/);
            if (m) {
              document.getElementById('resultatSerie').innerHTML =
                `<span style="color:#007acc;">Brute :</span> <b>${m[1]}</b> &nbsp; `
                + `<span style="color:#00905c;">Tension :</span> <b>${m[2]} V</b> &nbsp; `
                + `<span style="color:#cb3f00;">Distance :</span> <b>${m[3]}</b>`;
            } else {
              document.getElementById('resultatSerie').textContent = data.ligne;
            }
          } else {
            document.getElementById('resultatSerie').textContent = "—";
          }
        })
        .catch(() => {
          document.getElementById('resultatSerie').textContent = "Erreur de lecture";
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
      lireSerie();
      setInterval(lireSerie, 1000);
    });
  </script>
  <script>
    // Variables et gestion dashboard JS
    document.addEventListener('DOMContentLoaded', function() {
      let nbLignes = 20, paused = false, lastConnected = true;
      let valeurs = Array(nbLignes).fill(null);
      let temps = Array(nbLignes).fill("");
      let labels = Array(nbLignes).fill("");
      const seuilAlert = 950, seuilWarn = 800;

      function updateTable(){
        const tbody = document.getElementById('recapTable').querySelector('tbody');
        tbody.innerHTML = '';
        for(let i=valeurs.length-1; i>=0; i--){
          if(valeurs[i]===null) continue;
          const row = document.createElement('tr');
          if(valeurs[i]>=seuilAlert) row.classList.add("alert");
          row.innerHTML = `<td>${valeurs.length-i}</td><td>${valeurs[i]}</td><td>${temps[i]}</td>`;
          tbody.appendChild(row);
        }
        updateStats();
      }
      function updateStats(){
        let vals = valeurs.filter(v=>v!==null&&!isNaN(v));
        if(!vals.length) {
          document.getElementById('statistiques').innerHTML = '';
          return;
        }
        let min = Math.min(...vals), max = Math.max(...vals);
        let sum = vals.reduce((a,b)=>a+b,0), mean = (sum/vals.length).toFixed(2);
        let std = Math.sqrt(vals.reduce((s,v)=>s+Math.pow(v-mean,2),0)/vals.length).toFixed(2);
        document.getElementById('statistiques').innerHTML =
          `<span class="stat-item">🔻 Min: <b>${min}</b></span>
           <span class="stat-item">🔺 Max: <b>${max}</b></span>
           <span class="stat-item">⌀ Moy: <b>${mean}</b></span>
           <span class="stat-item">σ: <b>${std}</b></span>`;
      }
      function setStatus(online){
        lastConnected = online;
        let ind=document.getElementById('statusInd'),lib=document.getElementById('etatLib');
        ind.className='status-ind '+(online?"ok":"nok");
        lib.textContent=online?"Capteur connecté":"Déconnecté";
        lib.style.color=online?"#19a953":"#e23e2d";
      }
      function setLiveValue(val){
        let v=document.getElementById('valAffichée');
        v.classList.remove("alert",'warn');
        if(val===null) v.textContent='—';
        else {
          v.textContent=val;
          if(val>=seuilAlert) v.classList.add("alert");
          else if(val>=seuilWarn) v.classList.add("warn");
        }
      }
      // Chart.js
      let ctx=document.getElementById('graphique').getContext('2d'), chart;
      function makeChart(){
        chart=new Chart(ctx,{
          type:'line',
          data:{labels:labels,
            datasets:[{
              label:'Historique',
              data:valeurs,
              borderColor:'#1ea7e1',
              backgroundColor:'rgba(30,167,225,0.13)',
              fill:true,tension:0.35,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:'#1ea7e1'
            }]
          },
          options:{
            animation:false,plugins:{
              legend:{display:false},
              zoom:{
                pan:{enabled:true,mode:'x',modifierKey:'ctrl'}, zoom:{wheel:{enabled:true},pinch:{enabled:true},mode:'x'}
              }
            },
            scales:{x:{grid:{color:'#d7e5f7'},ticks:{display:false}},
              y:{beginAtZero:true,max:1023,grid:{color:'#d7e5f7'},ticks:{color:'#64a3ce'}}}
          }
        });
      }
      makeChart();

      async function pollCapteur(forceManual){
        if(paused) return;
        try{
          const response=await fetch('get_valeur.php?t='+Date.now());
          if(!response.ok) throw new Error('fetch failed');
          const data=await response.json();
          const val=(data&&typeof data.valeur!=='undefined')?Number(data.valeur):null;
          const now=new Date().toLocaleTimeString();

          setStatus(true);
          setLiveValue(val);

          labels.push("");labels.shift();
          valeurs.push((val!==null&&!isNaN(val))?val:null); valeurs.shift();
          temps.push((val!==null&&!isNaN(val))?now:""); temps.shift();

          chart.data.labels=labels;
          chart.data.datasets[0].data=valeurs;
          chart.update();
          updateTable();

          if(val!==null && val>=seuilAlert){
            // Notif sonore (désactivé ici)
          }
        }catch(e){
          setStatus(false);setLiveValue(null);
        }
        if(!forceManual&&!paused)setTimeout(pollCapteur,1100);
      }

      document.getElementById('refreshBtn').addEventListener('click',()=>{pollCapteur(true);});
      document.getElementById('pauseBtn').addEventListener('click',function(){
        paused=!paused;this.setAttribute('aria-pressed',paused?'true':'false');
        this.textContent=paused?"▶️ Reprendre":"⏸️ Pause";
        if(!paused) pollCapteur(); setStatus(lastConnected);
      });
      document.getElementById('exportBtn').addEventListener('click',function(){
        let rows=[["#", "Valeur", "Horodatage"]];
        for(let i=valeurs.length-1;i>=0;i--)if(valeurs[i]!==null)rows.push([valeurs.length-i,valeurs[i],temps[i]]);
        let csv=rows.map(e=>e.map(t=>`"${(""+t).replaceAll('"','""')}"`).join(",")).join("\r\n");
        const blob=new Blob([csv],{type:'text/csv'});const a=document.createElement('a');
        a.href=URL.createObjectURL(blob);a.download='mesures_capteur.csv';document.body.appendChild(a);a.click();document.body.removeChild(a);
      });
      document.getElementById('nbSelect').addEventListener('change',function(){
        nbLignes=parseInt(this.value,10);
        valeurs=Array(nbLignes).fill(null);temps=Array(nbLignes).fill("");labels=Array(nbLignes).fill("");
        chart.destroy();makeChart();pollCapteur(true);
      });
      document.getElementById('fsBtn').addEventListener('click',function(){
        let elem=document.querySelector('.dashboard');
        if(!document.fullscreenElement) elem.requestFullscreen();
        else document.exitFullscreen();
      });
      pollCapteur();
      setInterval(()=>{if(!lastConnected){setStatus(false);setLiveValue(null);}},3100);

      // Help modal
      document.getElementById('helpBtn').addEventListener('click',function(){
        document.getElementById('helpModal').style.display="block";
      });
      document.getElementById('closeHelp').addEventListener('click',function(){
        document.getElementById('helpModal').style.display="none";
      });
      document.getElementById('closeHelp').addEventListener('keyup',function(e){if(e.key==="Enter"||e.key===" "){this.click();}});
      document.getElementById('compactBtn').addEventListener('click',function(){
        document.body.classList.toggle('compact');
      });
      document.getElementById('themeBtn').addEventListener('click',function(){
        document.body.classList.toggle('dark');
        this.textContent=document.body.classList.contains('dark')?'☀️ Thème clair':'🌓 Thème sombre';
      });

      // Raccourcis clavier global
      document.addEventListener('keydown',function(e){
        if(document.activeElement.tagName==='INPUT'||document.activeElement.tagName==='SELECT')return;
        if(e.key===' '||e.key==='Spacebar'){e.preventDefault();document.getElementById('pauseBtn').click();}
        if(e.key==='r'||e.key==='R'){document.getElementById('refreshBtn').click();}
        if(e.key==='e'||e.key==='E'){document.getElementById('exportBtn').click();}
        if(e.key==='t'||e.key==='T'){document.getElementById('themeBtn').click();}
        if(e.key==='f'||e.key==='F'){document.getElementById('fsBtn').click();}
        if(e.key==='c'||e.key==='C'){document.getElementById('compactBtn').click();}
        if(e.key==='h'||e.key==='H'||e.key==='?'||e.key==='F1'){e.preventDefault();document.getElementById('helpBtn').click();}
      });
    });
  </script>
</body>
</html>
