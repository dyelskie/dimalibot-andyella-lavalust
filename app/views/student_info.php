<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile — Access Console</title>
    <style>
        * { box-sizing:border-box; }
        @keyframes glow-pulse {
            0%,100% { box-shadow:0 0 20px rgba(139,92,246,0.15); }
            50% { box-shadow:0 0 40px rgba(168,85,247,0.35); }
        }
        @keyframes blink { 50% { opacity:0; } }
        @keyframes scanline {
            0% { transform:translateY(-100%); }
            100% { transform:translateY(100vh); }
        }
        @keyframes barfill { from { width:0; } }
        body {
            margin:0; min-height:100vh; font-family:'Segoe UI', Arial, sans-serif;
            background:#050507; color:#fff; position:relative;
            background-image:
                radial-gradient(circle at 15% 5%, rgba(139,92,246,0.12), transparent 40%),
                radial-gradient(circle at 85% 90%, rgba(109,40,217,0.12), transparent 45%),
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

        .wrap { max-width:860px; margin:0 auto; padding:40px 20px 70px; position:relative; z-index:1; }

        .profile-card {
            background:#101016; border:1px solid rgba(139,92,246,0.22);
            border-radius:18px; padding:32px; display:flex; gap:26px; align-items:center;
            flex-wrap:wrap; margin-bottom:20px; position:relative; overflow:hidden;
            animation:glow-pulse 4s ease-in-out infinite;
        }
        .avatar {
            width:84px; height:84px; border-radius:50%; flex-shrink:0;
            background:linear-gradient(135deg,#A855F7,#6D28D9);
            display:flex; align-items:center; justify-content:center;
            font-size:1.7rem; font-weight:800; box-shadow:0 0 30px rgba(139,92,246,0.4);
            border:2px solid rgba(255,255,255,0.1);
        }
        .profile-card h1 { margin:0 0 4px; font-size:1.6rem; }
        .profile-card .sub { color:#A1A1AA; font-size:0.9rem; font-family:'Consolas',monospace; }
        .status-badge {
            display:inline-flex; align-items:center; gap:6px; margin-top:10px; padding:5px 13px; border-radius:20px;
            background:rgba(34,197,94,0.13); color:#4ADE80; font-size:0.75rem;
            border:1px solid rgba(34,197,94,0.35); font-weight:700; letter-spacing:0.5px;
            font-family:'Consolas',monospace;
        }
        .status-badge .d { width:7px; height:7px; border-radius:50%; background:#4ADE80; box-shadow:0 0 8px #4ADE80; animation:blink 1.4s infinite; }

        .card {
            background:#101016; border:1px solid rgba(139,92,246,0.18);
            border-radius:14px; padding:24px; margin-bottom:20px;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        .card:hover { border-color:rgba(168,85,247,0.4); box-shadow:0 0 24px rgba(139,92,246,0.08); }
        .card h2 {
            font-size:0.85rem; text-transform:uppercase; letter-spacing:1.5px;
            color:#C4B5FD; margin:0 0 18px; display:flex; align-items:center; gap:8px;
            font-family:'Consolas',monospace;
        }
        .card h2::before { content:'//'; color:#6D28D9; }

        dl { display:grid; grid-template-columns:150px 1fr; gap:14px 16px; margin:0; }
        dt { color:#71717A; font-size:0.82rem; font-family:'Consolas',monospace; }
        dd { margin:0; color:#F4F4F5; font-size:0.95rem; }

        .desc { color:#A1A1AA; line-height:1.7; margin:0; }

        .tags { display:flex; flex-wrap:wrap; gap:9px; }
        .tag {
            padding:7px 15px; border-radius:20px; font-size:0.82rem;
            background:rgba(139,92,246,0.1); border:1px solid rgba(139,92,246,0.28);
            color:#DDD6FE; transition:all .2s ease;
        }
        .tag:hover { background:rgba(139,92,246,0.2); border-color:rgba(168,85,247,0.55); transform:translateY(-2px); }

        .skillbars { display:flex; flex-direction:column; gap:12px; }
        .skillbar .label { display:flex; justify-content:space-between; font-size:0.82rem; margin-bottom:6px; color:#E4E4E7; }
        .skillbar .track { height:6px; border-radius:6px; background:#1A1A22; overflow:hidden; }
        .skillbar .fill { height:100%; border-radius:6px; background:linear-gradient(90deg,#8B5CF6,#A855F7); animation:barfill 1.2s ease-out; }

        .socials { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px; }
        .social-link {
            display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none;
            background:#15151D; border:1px solid rgba(139,92,246,0.18); transition:all .2s ease;
        }
        .social-link:hover { border-color:rgba(168,85,247,0.6); box-shadow:0 0 18px rgba(139,92,246,0.15); transform:translateY(-2px); }
        .social-link .icon {
            width:36px; height:36px; border-radius:9px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            background:rgba(139,92,246,0.15); border:1px solid rgba(139,92,246,0.3);
        }
        .social-link .icon svg { width:18px; height:18px; fill:#C4B5FD; }
        .social-link .txt { display:flex; flex-direction:column; }
        .social-link .txt .plat { color:#fff; font-size:0.85rem; font-weight:600; }
        .social-link .txt .handle { color:#71717A; font-size:0.78rem; font-family:'Consolas',monospace; }

        @media (max-width:560px) {
            dl { grid-template-columns:1fr; }
            .profile-card { text-align:center; justify-content:center; }
        }
    </style>
</head>
<body>
    <div class="scanline"></div>
    <nav aria-label="Main navigation">
        <span class="brand">AD.SYS</span>
        <span class="links">
            <a href="<?= site_url('student'); ?>">Home</a>
            <a href="<?= site_url('student/profile'); ?>" class="active">Student Profile</a>
        </span>
    </nav>

    <div class="wrap">

        <div class="profile-card">
            <div class="avatar"><?= htmlspecialchars($avatar_initials) ?></div>
            <div>
                <h1><?= htmlspecialchars($name) ?></h1>
                <div class="sub"><?= htmlspecialchars($course) ?> · <?= htmlspecialchars($year) ?> — Section <?= htmlspecialchars($section) ?></div>
                <span class="status-badge"><span class="d"></span> <?= htmlspecialchars($status) ?></span>
            </div>
        </div>

        <div class="card">
            <h2>About</h2>
            <p class="desc"><?= htmlspecialchars($description) ?></p>
        </div>

        <div class="card">
            <h2>Student Information</h2>
            <dl>
                <dt>Student ID</dt><dd><?= htmlspecialchars($student_id) ?></dd>
                <dt>Name</dt><dd><?= htmlspecialchars($name) ?></dd>
                <dt>Course</dt><dd><?= htmlspecialchars($course) ?></dd>
                <dt>Year Level</dt><dd><?= htmlspecialchars($year) ?></dd>
                <dt>Section</dt><dd><?= htmlspecialchars($section) ?></dd>
                <dt>Email</dt><dd><?= htmlspecialchars($email) ?></dd>
                <dt>Contact</dt><dd><?= htmlspecialchars($contact) ?></dd>
                <dt>Address</dt><dd><?= htmlspecialchars($address) ?></dd>
            </dl>
        </div>

                <div class="card">
            <h2>Skills</h2>
            <div class="skillbars">
                <?php foreach ($skills as $skill): ?>
                <div class="skillbar">
                    <div class="label"><span><?= htmlspecialchars($skill['name']) ?></span></div>
                    <div class="track"><div class="fill" style="width:<?= (int) $skill['level'] ?>%"></div></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="card">
            <h2>Hobbies</h2>
            <div class="tags">
                <?php foreach ($hobbies as $hobby): ?>
                    <span class="tag"><?= htmlspecialchars($hobby) ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <h2>Connect</h2>
            <div class="socials">
                <?php
                $icons = [
                    'facebook'  => '<path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/>',
                    'instagram' => '<path d="M12 2c2.7 0 3.1 0 4.1.1 1.1 0 1.8.2 2.4.4a5 5 0 0 1 1.8 1.2 5 5 0 0 1 1.2 1.8c.2.6.4 1.3.4 2.4.1 1 .1 1.4.1 4.1s0 3.1-.1 4.1c0 1.1-.2 1.8-.4 2.4a5 5 0 0 1-1.2 1.8 5 5 0 0 1-1.8 1.2c-.6.2-1.3.4-2.4.4-1 .1-1.4.1-4.1.1s-3.1 0-4.1-.1c-1.1 0-1.8-.2-2.4-.4a5 5 0 0 1-1.8-1.2 5 5 0 0 1-1.2-1.8c-.2-.6-.4-1.3-.4-2.4C2 15.1 2 14.7 2 12s0-3.1.1-4.1c0-1.1.2-1.8.4-2.4a5 5 0 0 1 1.2-1.8A5 5 0 0 1 5.5 2.5c.6-.2 1.3-.4 2.4-.4C8.9 2 9.3 2 12 2zm0 5a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 8.2a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4zm5.2-8.4a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4z"/>',
                    'telegram'  => '<path d="M21.9 4.6 18.5 20c-.3 1.1-1 1.4-2 .9l-5.5-4-2.6 2.5c-.3.3-.5.5-1 .5l.4-5.6L18 5.7c.5-.4-.1-.6-.7-.2L7 12.4l-5.5-1.7c-1.2-.4-1.2-1.2.3-1.8l21.4-4.8c1-.3 1.9.2 1.6 1.5z"/>',
                    'discord'   => '<path d="M20.3 4.9A18 18 0 0 0 15.7 3l-.3.5a15 15 0 0 1 3.9 1.4 15.3 15.3 0 0 0-13.7 0A15 15 0 0 1 9.4 3.5l-.3-.5a18 18 0 0 0-4.6 1.9C1.6 8.7.9 12.4 1.2 16a18.5 18.5 0 0 0 5.4 2.6l.7-1.1a12 12 0 0 1-1.9-.9l.5-.4a13 13 0 0 0 11.2 0l.5.4c-.6.4-1.2.6-1.9.9l.7 1.1a18.4 18.4 0 0 0 5.4-2.6c.4-4.3-.6-7.9-2.9-11.1zM8.9 13.8c-.9 0-1.6-.8-1.6-1.8s.7-1.8 1.6-1.8 1.6.8 1.6 1.8-.7 1.8-1.6 1.8zm6.3 0c-.9 0-1.6-.8-1.6-1.8s.7-1.8 1.6-1.8 1.6.8 1.6 1.8-.7 1.8-1.6 1.8z"/>',
                ];
                foreach ($socials as $s): ?>
                    <a class="social-link" href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener">
                        <span class="icon"><svg viewBox="0 0 24 24"><?= $icons[$s['icon']] ?? '' ?></svg></span>
                        <span class="txt">
                            <span class="plat"><?= htmlspecialchars($s['platform']) ?></span>
                            <span class="handle"><?= htmlspecialchars($s['handle']) ?></span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</body>
</html>