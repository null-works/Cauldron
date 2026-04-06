<?php
session	_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Handle logout
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
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .welcome-card h2 {
            color: #2D1B69;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .welcome-card p {
            color: #666;
            font-size: 1.1rem;
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
        <div class="welcome-card">
            <h2>Hello World</h2>
            <p>Your dashboard is ready to be customized!</p>
        </div>
    </div>
    
    <script>
        // Bokeh Particle Generation (from your carousel code)
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('bokehContainer');
            const colors = ['#8B5CF6', '#E879F9', '#06B6D4']; // Purple, Pink, Cyan from your theme
            const numParticles = 150; // Fewer particles for dashboard background
            
            for (let i = 0; i < numParticles; i++) {
                const particle = document.createElement('div');
                const size = Math.random() * 180 + 10;
                const x = Math.random() * 100;
                const y = Math.random() * 100;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const opacity = Math.random() * 0.4 + 0.1;
                const blur = Math.random() * 12;
                
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
                
                if (size > 100 && blur > 6) {
                    particle.style.border = `2px solid rgba(255, 255, 255, 0.1)`;
                }
                
                container.appendChild(particle);
            }
        });
    </script>
</body>
</html>
