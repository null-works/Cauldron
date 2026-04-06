<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SystemPanic Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #2D1B69;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        .bokeh-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #2D1B69 0%, #1a0f3a 50%, #8B5CF6 100%);
        }
        
        .header {
            background: #2D1B69;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logout-btn {
            background: #8B5CF6;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background: #7C3AED;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 120px);
        }
        
        .app-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }
        
        .app-row {
            display: flex;
            gap: 2rem;
            align-items: center;
            justify-content: center;
        }
        
        .mega-app-icon {
            width: 180px;
            height: 180px;
            background: linear-gradient(135deg, #E879F9 0%, #8B5CF6 50%, #06B6D4 100%);
            border-radius: 40px;
            position: relative;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
            overflow: visible;
        }
        
        .mega-app-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(139, 92, 246, 0.4);
        }
        
        .mega-app-icon:active {
            transform: scale(0.95);
        }
        
        .mega-app-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 40%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
            border-radius: 40px 40px 0 0;
        }
        
        .icon-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
        }
        
        .big-gear {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 130px;
            height: 130px;
            fill: rgba(255, 255, 255, 0.9);
        }
        
        .mail-icon {
            width: 180px;
            height: 180px;
            background: conic-gradient(from 45deg, #E879F9 0%, #8B5CF6 30%, #06B6D4 60%, #E879F9 100%);
            border-radius: 40px;
            position: relative;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
            overflow: visible;
        }
        
        .mail-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(139, 92, 246, 0.4);
        }
        
        .mail-icon:active {
            transform: scale(0.95);
        }
        
        .mail-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 40%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
            border-radius: 40px 40px 0 0;
        }
        
        .big-envelope {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 130px;
            height: 130px;
            fill: rgba(255, 255, 255, 0.9);
        }
        
        .red-notification {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #FCA5A5 0%, #EF4444 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.9);
            font-weight: bold;
            color: white;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }
        
        .golden-pen {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
        }
        
        .pen-icon {
            width: 45px;
            height: 45px;
            fill: url(#penGradient);
        }
        
        .app-label {
            margin-top: 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }
        
        @media (max-width: 768px) {
            .mega-app-icon {
                width: 140px;
                height: 140px;
                border-radius: 30px;
            }
            
            .big-gear {
                width: 80px;
                height: 80px;
            }
            
            .golden-pen {
                bottom: 15px;
                right: 15px;
                width: 35px;
                height: 35px;
            }
            
            .pen-icon {
                width: 18px;
                height: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="bokeh-background" id="bokehContainer"></div>
    
    <header class="header">
        <h1>SystemPanic Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($username) ?></span>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="container">
        <div class="app-grid">
            <div class="app-row">
                <!-- Website Editor Icon -->
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <a href="https://systempanic.ca/backend" class="mega-app-icon">
                        <div class="icon-content">
                            <!-- Large gear SVG -->
                            <svg class="big-gear" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="penGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#FEF3C7;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#F59E0B;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/>
                            </svg>
                            
                            <!-- Golden pen -->
                            <div class="golden-pen">
                                <svg class="pen-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="app-label">Website Editor</div>
                </div>

                <!-- Webmail Icon -->
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <a href="https://systempanic.ca/webmail" class="mail-icon">
                        <div class="icon-content">
                            <!-- Large envelope SVG -->
                            <svg class="big-envelope" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/>
                            </svg>
                        </div>
                    </a>
                    <div class="app-label">Webmail</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('bokehContainer');
            const colors = ['#8B5CF6', '#E879F9', '#06B6D4'];
            const numParticles = 120;
            
            for (let i = 0; i < numParticles; i++) {
                const particle = document.createElement('div');
                const size = Math.random() * 160 + 15;
                const x = Math.random() * 100;
                const y = Math.random() * 100;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const opacity = Math.random() * 0.3 + 0.1;
                const blur = Math.random() * 10;
                
                particle.style.position = 'absolute';
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${x}%`;
                particle.style.top = `${y}%`;
                particle.style.backgroundColor = color;
                particle.style.borderRadius = '50%';
                particle.style.opacity = opacity;
                particle.style.filter = `blur(${blur}px)`;
                particle.style.transform = 'translate(-50%, -50%)';
                particle.style.pointerEvents = 'none';
                
                if (size > 80 && blur > 5) {
                    particle.style.border = `1px solid rgba(255, 255, 255, 0.1)`;
                }
                
                container.appendChild(particle);
            }
        });
    </script>
</body>
</html>
