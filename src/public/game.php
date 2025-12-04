<?php
// Simple mini-game page: generate topics and write AI-like articles.
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redaktions-Game</title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --accent: #38bdf8;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --success: #22c55e;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 20%, rgba(56, 189, 248, 0.12), transparent 25%),
                        radial-gradient(circle at 80% 10%, rgba(34, 197, 94, 0.12), transparent 25%),
                        linear-gradient(135deg, #0b1222, #0f172a);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 24px;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            margin-left: 12px;
            transition: color 0.2s ease;
        }
        .nav-links a:hover { color: var(--text); }
        main {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .card {
            background: rgba(17, 24, 39, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        .card h2 {
            margin: 0 0 8px;
            font-size: 1.2rem;
        }
        .card p {
            margin: 0 0 12px;
            color: var(--muted);
        }
        button {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        button.primary {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: #0b1222;
            box-shadow: 0 12px 30px rgba(56, 189, 248, 0.35);
        }
        button.secondary {
            background: rgba(148, 163, 184, 0.15);
            color: var(--text);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }
        button:active { transform: translateY(1px); }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .pill {
            padding: 8px 12px;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.35);
            border-radius: 10px;
            color: var(--text);
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .stat {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
        }
        .stat small { color: var(--muted); font-weight: 600; }
        textarea {
            width: 100%;
            min-height: 320px;
            background: rgba(15, 23, 42, 0.6);
            color: var(--text);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            padding: 14px;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.95rem;
            line-height: 1.6;
            resize: vertical;
            outline: none;
        }
        textarea:focus { border-color: rgba(56, 189, 248, 0.55); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15); }
        .status {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #bbf7d0;
            border-radius: 10px;
            padding: 10px 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .log {
            margin-top: 12px;
            padding: 12px;
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 12px;
            max-height: 120px;
            overflow-y: auto;
            font-size: 0.92rem;
        }
        .log-entry { margin: 0 0 8px; color: var(--muted); }
        .log-entry strong { color: var(--text); }
        @media (max-width: 900px) {
            main { grid-template-columns: 1fr; }
            textarea { min-height: 240px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">🧠 KI-Redaktion</div>
        <div class="nav-links">
            <a href="/">Zurück zur Startseite</a>
        </div>
    </header>

    <main>
        <section class="card" id="briefing">
            <h2>1. Thema ziehen</h2>
            <p>Hol dir ein zufälliges Thema und lass die KI einen schnellen Artikel skizzieren. Feile bei Bedarf manuell nach.</p>
            <div class="row" style="margin-bottom: 12px;">
                <button class="primary" id="newTopic">Neues Thema</button>
                <div class="pill" id="currentTopic">Noch kein Thema</div>
            </div>
            <div class="row" style="margin-bottom: 12px;">
                <button class="secondary" id="generate">Artikel von KI schreiben lassen</button>
                <span class="stat"><small>Status:</small> <span id="status">Wartet auf Thema</span></span>
            </div>
            <div class="row">
                <span class="stat"><small>Budget:</small> <span id="credits">0 €</span></span>
                <span class="stat"><small>Abgaben:</small> <span id="submissions">0</span></span>
            </div>
            <div class="log" id="log" aria-live="polite"></div>
        </section>

        <section class="card">
            <h2>2. Artikel überarbeiten & abgeben</h2>
            <p>Du kannst den KI-Entwurf anpassen. Sobald du zufrieden bist, reiche den Artikel beim Redakteur ein und kassiere dein Honorar.</p>
            <textarea id="article" placeholder="Hier landet der KI-Entwurf..."></textarea>
            <div class="row" style="justify-content: space-between; margin-top: 10px;">
                <small id="hint" style="color: var(--muted);">Tipp: Füge Zahlen, Zitate oder Bulletpoints hinzu, um den Artikel glaubwürdig wirken zu lassen.</small>
                <button class="primary" id="submit">Artikel abgeben</button>
            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="status" id="payout">Kein Honorar erhalten</div>
            </div>
        </section>
    </main>

    <script>
        const topics = [
            'Weltrettung durch Algenfarmen',
            'Streetfood-Trends 2025',
            'Mondkolonien als Tourismusziel',
            'Zero-Waste-Mode aus Pilzleder',
            'Retro-Gaming im Unterricht',
            'Hyperloop statt Kurzstrecke',
            'Vertikale Farmen in Wolkenkratzern',
            'KI-Coaches im Profisport',
            'Energiespeicher aus Sand',
            'Mikroabenteuer vor der Haustür',
            'Arbeitswoche mit 4 Tagen',
            'Biohacking für Einsteiger',
            'Roboter als Pflegekräfte',
            'Digitale Zwillinge in der Stadtplanung'
        ];

        const openers = [
            'Die Redaktion will es wissen: ',
            'Der Chefredakteur fordert Fakten: ',
            'Zwischen Deadline und Kaffeeduft entsteht dieser Draft: ',
            'Mit knapper Zeit und viel Neugier: ',
            'Die KI spuckt erste Ideen aus: '
        ];

        const closers = [
            'Am Ende bleibt vor allem eins: eine überraschend optimistische Perspektive.',
            'Damit wird ein Trend sichtbar, der unsere Städte und Köpfe verändern dürfte.',
            'Es zeigt sich: Mutige Pilotprojekte werden zum neuen Standard.',
            'Wer jetzt experimentiert, wird morgen zum Vorreiter.',
            'Das Fazit: Technik trifft Alltag – und beide profitieren.'
        ];

        const article = document.getElementById('article');
        const currentTopic = document.getElementById('currentTopic');
        const status = document.getElementById('status');
        const credits = document.getElementById('credits');
        const submissions = document.getElementById('submissions');
        const log = document.getElementById('log');
        const payout = document.getElementById('payout');

        let wallet = 0;
        let submissionCount = 0;
        let topic = '';

        function addLog(message) {
            const entry = document.createElement('p');
            entry.className = 'log-entry';
            entry.innerHTML = `<strong>${new Date().toLocaleTimeString()}:</strong> ${message}`;
            log.prepend(entry);
        }

        function pickRandom(list) {
            return list[Math.floor(Math.random() * list.length)];
        }

        function newTopic() {
            topic = pickRandom(topics);
            currentTopic.textContent = topic;
            status.textContent = 'Thema ausgewählt – bereit für KI-Entwurf';
            article.placeholder = `Schreibe über: ${topic}`;
            addLog(`Neues Thema gezogen: <strong>${topic}</strong>`);
        }

        function generateArticle() {
            if (!topic) {
                status.textContent = 'Bitte zuerst ein Thema ziehen';
                addLog('Versuch, ohne Thema zu generieren.');
                return;
            }
            const opener = pickRandom(openers);
            const closer = pickRandom(closers);
            const angle = pickRandom([
                'Vorreiter berichten von ersten Erfolgen und warnen zugleich vor Kinderkrankheiten.',
                'Expertinnen sehen darin eine stille Revolution, die gerade Fahrt aufnimmt.',
                'Start-ups liefern Prototypen, während Behörden noch nach Regeln suchen.',
                'Lokale Projekte zeigen, dass das Konzept auch mit kleinem Budget funktioniert.',
                'Investor:innen sprechen von einem Markt, der sich gerade erst erfindet.'
            ]);

            const sample = `${opener}${topic}. ${angle}\n\n` +
                `Stakeholder skizzieren Roadmaps, Bürgerinitiativen liefern Gegenwind und Forschende sammeln Daten. ` +
                `Besonders spannend: Wie schnell Regeln nachgezogen werden, entscheidet über Tempo und Akzeptanz.\n\n` +
                `Zahlen & Fakten: Pilotregionen melden zweistellige Wachstumsraten, ` +
                `Studien prognostizieren Milliardenvolumen und neue Jobs. ${closer}`;

            article.value = sample;
            status.textContent = 'KI-Entwurf erstellt – passe ihn nach Belieben an';
            addLog('KI-Entwurf generiert.');
        }

        function submitArticle() {
            if (!topic) {
                status.textContent = 'Ohne Thema keine Abgabe';
                addLog('Abgabe fehlgeschlagen: kein Thema.');
                return;
            }
            const content = article.value.trim();
            if (!content) {
                status.textContent = 'Artikel ist leer – bitte ergänzen';
                addLog('Abgabe fehlgeschlagen: leerer Artikel.');
                return;
            }
            const payment = 120 + Math.floor(Math.random() * 180);
            wallet += payment;
            submissionCount += 1;
            credits.textContent = `${wallet} €`;
            submissions.textContent = submissionCount;
            payout.textContent = `Honorar erhalten: ${payment} € für "${topic}"`;
            status.textContent = 'Artikel abgegeben – neuer Auftrag wartet!';
            addLog(`Artikel abgegeben und ${payment} € erhalten.`);
            topic = '';
            currentTopic.textContent = 'Noch kein Thema';
            article.value = '';
            article.placeholder = 'Ziehe ein neues Thema und lass die KI starten';
        }

        document.getElementById('newTopic').addEventListener('click', newTopic);
        document.getElementById('generate').addEventListener('click', generateArticle);
        document.getElementById('submit').addEventListener('click', submitArticle);
    </script>
</body>
</html>
