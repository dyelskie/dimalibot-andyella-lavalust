<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD.SYS — Student Access Console</title>
    <style>
        * { box-sizing:border-box; }
        @keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
        @keyframes glow-pulse {
            0%,100% { box-shadow:0 0 20px rgba(139,92,246,0.2); }
            50% { box-shadow:0 0 40px rgba(168,85,247,0.4); }
        }
        @keyframes blink { 50% { opacity:0; } }
        @keyframes scanline {
            0% { transform:translateY(-100%); }
            100% { transform:translateY(100vh); }
        }
        body {
            margin:0; min-height:100vh; font-family:'Segoe UI', Arial, sans-serif;
            background:#050507; color:#fff; overflow-x:hidden; position:relative;
            background-image:
                radial-gradient(circle at 15% 10%, rgba(139,92,246,0.12), transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(109,40,217,0.12), transparent 45%),
                linear-gradient(rgba(139,92,246,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,92,246,0.04) 1px, transparent 1px);
            background-size: auto, auto, 40px 40px, 40px 40px;
        }
        .scanline {
            position:fixed; left:0; right:0; height:2px; z-index:5; pointer-events:none;
            background:linear-gradient(90deg, transparent, rgba(168,85,247,0.5), transparent);
            animation:scanline 6s linear infinite;
        }
        nav {
            display:flex; align-items:center; justify-content:space-between;
            padding:18px 32px; border-bottom:1px solid rgba(139,92,246,0.18);
            background:rgba(10,10,15,0.75); backdrop-filter:blur(6px);
            position:sticky; top:0; z-index:10;
        }
        nav .brand { font-weight:800; letter-spacing:2px; color:#A855F7; font-family:'Consolas',monospace; }
        nav .brand::before { content:'</> '; color:#6D28D9; }
        nav .links a {
            color:#A1A1AA; text-decoration:none; margin-left:24px; font-size:0.9rem;
            padding:6px 12px; border-radius:8px; transition:all .2s ease;
        }
        nav .links a:hover { color:#fff; background:rgba(139,92,246,0.15); }
        nav .links a.active {
            color:#fff; background:rgba(139,92,246,0.15);
            border:1px solid rgba(168,85,247,0.55); animation:glow-pulse 2.5s ease-in-out infinite;
        }
        .hero { max-width:760px; margin:70px auto 40px; padding:0 24px; text-align:center; position:relative; z-index:1; }
        .badge {
            display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:20px;
            background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.35);
            color:#4ADE80; font-size:0.78rem; letter-spacing:1px; margin-bottom:22px;
            font-family:'Consolas',monospace;
        }
        .badge .pulse-dot {
            width:8px; height:8px; border-radius:50%; background:#4ADE80;
            box-shadow:0 0 8px #4ADE80; animation:blink 1.4s ease-in-out infinite;
        }
        .terminal-box {
            text-align:left; max-width:520px; margin:0 auto 30px; background:#0A0A0F;
            border:1px solid rgba(139,92,246,0.25); border-radius:10px; overflow:hidden;
            font-family:'Consolas','Courier New',monospace; font-size:0.82rem;
        }
        .terminal-box .bar {
            display:flex; gap:8px; padding:9px 14px; background:#101016;
            border-bottom:1px solid rgba(139,92,246,0.18);
        }
        .terminal-box .bar span { width:10px; height:10px; border-radius:50%; }
        .terminal-box .bar .r{background:#EF4444;} .terminal-box .bar .y{background:#EAB308;} .terminal-box .bar .g{background:#22C55E;}
        .terminal-box .body { padding:16px 18px; color:#A1A1AA; line-height:1.9; }
        .terminal-box .body .ok { color:#4ADE80; }
        .terminal-box .body .cmd { color:#A855F7; }
        .terminal-box .body .cursor { display:inline-block; width:7px; background:#A855F7; animation:blink 1s step-end infinite; margin-left:2px; }
        h1 { font-size:2.4rem; margin:0 0 14px; letter-spacing:-0.5px; }
        h1 span { background:linear-gradient(135deg,#A855F7,#6D28D9); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .hero > p { color:#A1A1AA; font-size:1.05rem; line-height:1.6; margin-bottom:34px; }
        .cta {
            display:inline-flex; align-items:center; gap:10px; padding:13px 30px; border-radius:12px;
            background:linear-gradient(135deg,#8B5CF6,#6D28D9); color:#fff;
            text-decoration:none; font-weight:700; box-shadow:0 0 30px rgba(139,92,246,0.3);
            transition:filter .2s ease, transform .2s ease; font-size:0.95rem;
        }
        .cta:hover { filter:brightness(1.15); transform:translateY(-2px); }
        .stack {
            display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-top:40px; padding:0 24px 60px;
        }
        .chip {
            padding:8px 16px; border-radius:20px; font-size:0.8rem; font-family:'Consolas',monospace;
            background:#101016; border:1px solid rgba(139,92,246,0.18); color:#C4B5FD;
            animation:float 3.5s ease-in-out infinite;
        }
        .chip:nth-child(2){animation-delay:.3s;} .chip:nth-child(3){animation-delay:.6s;}
        .chip:nth-child(4){animation-delay:.9s;} .chip:nth-child(5){animation-delay:1.2s;}
    </style>
</head>
<body>
    <div class="scanline"></div>
    <nav aria-label="Main navigation">
        <span class="brand">AD.SYS</span>
        <span class="links">
            <a href="<?= site_url('student'); ?>" class="active">Home</a>
            <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
        </span>
    </nav>

    <div class="hero">
        <span class="badge"><span class="pulse-dot"></span> SYSTEM ONLINE — NODE STABLE</span>
        <h1>Welcome to the <span>Student Access Console</span></h1>
        <p>A secured, session-authenticated terminal for a single verified student record. Initialize the profile module below to pull full clearance-level data.</p>

        <div class="terminal-box">
            <div class="bar"><span class="r"></span><span class="y"></span><span class="g"></span></div>
            <div class="body">
                <div>$ <span class="cmd">init</span> student_console.php</div>
                <div><span class="ok">✓</span> route registered: /student</div>
                <div><span class="ok">✓</span> route registered: /student/profile</div>
                <div><span class="ok">✓</span> middleware: StudentMiddleware — armed</div>
                <div><span class="ok">✓</span> session token issued</div>
                <div>$ awaiting navigation<span class="cursor">&nbsp;</span></div>
            </div>
        </div>

        <a class="cta" href="<?= site_url('student/profile'); ?>">Enter Student Profile →</a>

        <div class="stack">
            <span class="chip">PHP 8.2</span>
            <span class="chip">LavaLust MVC</span>
            <span class="chip">Session Auth</span>
            <span class="chip">BSIT · MinSU</span>
            <span class="chip">Middleware Layer</span>
        </div>
    </div>
</body>
</html>