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
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Source+Serif+4:wght@400;600&display=swap');

        :root {
            --bg: #f7f2ed;
            --ink: #2c2742;
            --muted: #6b5d7a;
            --accent: #b18acb;
            --accent-2: #8ac6d1;
            --card: rgba(255, 255, 255, 0.95);
            --border: rgba(44, 39, 66, 0.12);
            --shadow: 0 25px 80px rgba(44, 39, 66, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 15% 20%, rgba(177, 138, 203, 0.12), transparent 25%),
                        radial-gradient(circle at 80% 10%, rgba(138, 198, 209, 0.12), transparent 25%),
                        linear-gradient(135deg, #fdfbf8, var(--bg));
            color: var(--ink);
            font-family: 'Source Serif 4', 'Georgia', serif;
            padding: 32px 20px 40px;
        }

        .page { max-width: 1200px; margin: 0 auto; }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 14px 18px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .brand {
            font-family: 'Playfair Display', 'Georgia', serif;
            font-size: 1.4rem;
            letter-spacing: 0.04em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand span {
            padding: 8px 12px;
            border-radius: 12px;
            background: linear-gradient(120deg, rgba(177, 138, 203, 0.15), rgba(138, 198, 209, 0.18));
            border: 1px solid var(--border);
        }

        .nav-links { display: flex; gap: 12px; flex-wrap: wrap; }

        .nav-links a {
            color: var(--ink);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .nav-links a:hover {
            border-color: var(--border);
            background: rgba(177, 138, 203, 0.08);
        }

        main {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .card {
            background: var(--card);
            border-radius: 18px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        h1, h2 {
            font-family: 'Playfair Display', 'Georgia', serif;
            margin: 0 0 10px;
        }

        p { margin: 0 0 10px; color: var(--muted); line-height: 1.7; }

        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

        button {
            border: none;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
            font-family: inherit;
        }

        button.primary {
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #0f0c1f;
            box-shadow: 0 12px 30px rgba(44, 39, 66, 0.18);
        }

        button.secondary {
            background: rgba(177, 138, 203, 0.12);
            color: var(--ink);
            border: 1px solid rgba(44, 39, 66, 0.12);
        }

        button:active { transform: translateY(1px); }

        .pill {
            padding: 8px 12px;
            background: rgba(138, 198, 209, 0.16);
            border: 1px solid rgba(138, 198, 209, 0.35);
            border-radius: 10px;
            color: var(--ink);
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .stat { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; }
        .stat small { color: var(--muted); font-weight: 600; }

        textarea {
            width: 100%;
            min-height: 320px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            border: 1px solid rgba(44, 39, 66, 0.16);
            border-radius: 12px;
            padding: 14px;
            font-family: 'Source Serif 4', 'Georgia', serif;
            font-size: 1rem;
            line-height: 1.7;
            resize: vertical;
            outline: none;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        textarea:focus { border-color: rgba(177, 138, 203, 0.55); box-shadow: 0 0 0 3px rgba(177, 138, 203, 0.2); }

        .status {
            background: rgba(138, 198, 209, 0.14);
            border: 1px solid rgba(138, 198, 209, 0.3);
            color: var(--ink);
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
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(44, 39, 66, 0.12);
            border-radius: 12px;
            max-height: 150px;
            overflow-y: auto;
            font-size: 0.95rem;
        }

        .log-entry { margin: 0 0 8px; color: var(--muted); }
        .log-entry strong { color: var(--ink); }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(177, 138, 203, 0.14);
            border: 1px solid rgba(177, 138, 203, 0.3);
            padding: 8px 10px;
            border-radius: 12px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            main { grid-template-columns: 1fr; }
            textarea { min-height: 240px; }
        }
    </style>
</head>
<body>
    <div class="page">
        <nav>
            <div class="brand">
                <span>Bridgerton Society</span>
                <strong>Marketing-Salon</strong>
            </div>
            <div class="nav-links">
                <a href="/">Startseite</a>
                <a href="/highscore/">Highscores</a>
            </div>
        </nav>

        <main>
            <section class="card" id="briefing">
                <div class="badge">🧠 KI-Redaktion</div>
                <h1>Zieh ein Thema & lass die Feder tanzen</h1>
                <p>Hol dir ein zufälliges Thema und lass die KI einen ersten Entwurf liefern. Du bringst die elegante Note hinein.</p>
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
                <div class="badge">✒️ Redaktion</div>
                <h2>Artikel überarbeiten & abgeben</h2>
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
    </div>

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
