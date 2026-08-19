<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware
{
    public function handle(Closure $next)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (($_SESSION['ad_access_granted'] ?? false) !== true) {
            http_response_code(403);
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>ACCESS DENIED // SECURE NODE</title>
                <style>
                    * { box-sizing:border-box; }
                    @keyframes pulse-red {
                        0%,100% { box-shadow:0 0 30px rgba(239,68,68,0.15); }
                        50% { box-shadow:0 0 55px rgba(239,68,68,0.35); }
                    }
                    @keyframes scan {
                        0% { transform:translateY(-100%); }
                        100% { transform:translateY(100%); }
                    }
                    @keyframes blink { 50% { opacity:0; } }
                    @keyframes flicker {
                        0%,100% { opacity:1; }
                        92% { opacity:1; }
                        93% { opacity:0.4; }
                        94% { opacity:1; }
                    }
                    body {
                        margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center;
                        background:#050507;
                        background-image:
                            radial-gradient(circle at 50% 20%, rgba(239,68,68,0.10), transparent 55%),
                            linear-gradient(rgba(139,92,246,0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(139,92,246,0.05) 1px, transparent 1px);
                        background-size: auto, 36px 36px, 36px 36px;
                        font-family:'Consolas','Courier New',monospace;
                        color:#E4E4E7;
                    }
                    .term {
                        position:relative; width:92%; max-width:520px; border-radius:12px;
                        background:#0A0A0F; border:1px solid rgba(239,68,68,0.35);
                        animation:pulse-red 2.4s ease-in-out infinite, flicker 6s linear infinite;
                        overflow:hidden;
                    }
                    .term::after {
                        content:''; position:absolute; left:0; right:0; height:40%;
                        background:linear-gradient(180deg, transparent, rgba(239,68,68,0.06), transparent);
                        animation:scan 3.5s linear infinite; pointer-events:none;
                    }
                    .titlebar {
                        display:flex; align-items:center; gap:8px; padding:10px 14px;
                        background:#101016; border-bottom:1px solid rgba(239,68,68,0.25);
                    }
                    .dot { width:10px; height:10px; border-radius:50%; }
                    .dot.r{background:#EF4444;} .dot.y{background:#EAB308;} .dot.g{background:#22C55E;}
                    .titlebar span.label {
                        margin-left:auto; font-size:0.72rem; color:#71717A; letter-spacing:1px;
                    }
                    .body-content { padding:26px 24px 30px; }
                    .line { font-size:0.85rem; margin:0 0 6px; color:#71717A; }
                    .line b { color:#A855F7; font-weight:600; }
                    .error-code {
                        display:inline-block; margin:14px 0 10px; padding:5px 12px; border-radius:6px;
                        background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.4);
                        color:#F87171; font-size:0.78rem; letter-spacing:1.5px; font-weight:700;
                    }
                    h1 {
                        font-size:1.3rem; color:#fff; margin:6px 0 14px; letter-spacing:0.5px;
                    }
                    .desc {
                        font-size:0.85rem; color:#A1A1AA; line-height:1.7; margin:0 0 22px;
                        border-left:2px solid rgba(239,68,68,0.4); padding-left:12px;
                    }
                    .cursor-line { font-size:0.85rem; color:#4ADE80; margin:0 0 22px; }
                    .cursor { display:inline-block; width:8px; background:#4ADE80; margin-left:2px; animation:blink 1s step-end infinite; }
                    a.btn {
                        display:inline-flex; align-items:center; gap:8px; padding:11px 22px; border-radius:8px;
                        background:linear-gradient(135deg,#8B5CF6,#6D28D9); color:#fff;
                        text-decoration:none; font-weight:600; font-size:0.85rem;
                        font-family:'Segoe UI',Arial,sans-serif; box-shadow:0 0 20px rgba(139,92,246,0.25);
                        transition:filter .2s ease, transform .2s ease;
                    }
                    a.btn:hover { filter:brightness(1.15); transform:translateY(-1px); }
                </style>
            </head>
            <body>
                <div class="term">
                    <div class="titlebar">
                        <span class="dot r"></span><span class="dot y"></span><span class="dot g"></span>
                        <span class="label">SECURE_NODE :: PORT 443</span>
                    </div>
                    <div class="body-content">
                        <p class="line">$ <b>request</b> --route /student/profile</p>
                        <p class="line">$ <b>auth.verify_session()</b> → false</p>
                        <span class="error-code">⛔ ERR_403 · ACCESS_DENIED</span>
                        <h1>Intrusion Attempt Blocked</h1>
                        <p class="desc">
                            No active session token was found for this request. This endpoint is
                            protected by session-based access control. The attempt has been logged
                            and the connection terminated.
                        </p>
                        <p class="cursor-line">status: awaiting_authorization<span class="cursor">&nbsp;</span></p>
                        <a class="btn" href="<?= site_url('student'); ?>">↩ Return to Entry Point</a>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit;
        }

        return $next();
    }
}