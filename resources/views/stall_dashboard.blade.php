<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MELA - Live Stall Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #01040a; 
            --secondary-bg: #021d4d; 
            --glass-bg: rgba(255, 255, 255, 0.05); 
            --text-main: #ffffff;
            --text-dim: #b0c4de;
            --available-color: #00f2ff; 
            --sold-color: #ff4757;    /* Red for Sold */
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, var(--secondary-bg), var(--primary-bg));
            min-height: 100vh; margin: 0; padding: 40px;
            color: var(--text-main);
        }

        .header-container { text-align: center; margin-bottom: 50px; }
        .mela-logo { 
            font-family: 'Poppins', sans-serif; font-size: 42px; font-weight: 700;
            background: linear-gradient(to right, #00f2ff, #3a7bd5);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: 3px; margin: 0;
        }
        .page-subtitle { font-size: 18px; color: var(--text-dim); margin-top: 5px; }

        /* The Grid System */
        .stall-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Base Stall Card */
        .stall-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border-radius: 20px; padding: 25px 15px;
            text-align: center; transition: all 0.3s ease;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .stall-number { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 600; margin-bottom: 10px; }
        .stall-status { font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }

        
        .stall-card.available {
            border: 2px solid rgba(0, 242, 255, 0.4);
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.1);
            cursor: pointer;
        }
        .stall-card.available:hover {
            transform: translateY(-5px);
            border-color: var(--available-color);
            box-shadow: 0 10px 25px rgba(0, 242, 255, 0.3);
        }
        .stall-card.available .stall-number { color: var(--text-main); }
        .stall-card.available .stall-status { color: var(--available-color); }

        /* Status: Sold (Red/Dimmed) */
        .stall-card.sold {
            border: 1px solid rgba(255, 71, 87, 0.3);
            background: rgba(255, 71, 87, 0.03);
            cursor: not-allowed; opacity: 0.8;
        }
        .stall-card.sold .stall-number { color: #8892b0; text-decoration: line-through; }
        .stall-card.sold .stall-status { color: var(--sold-color); }

        /* Loading State */
        #loader { text-align: center; font-size: 20px; color: var(--available-color); margin-top: 50px; }
    </style>
</head>
<body>

    <div class="header-container">
        <h1 class="mela-logo">MELA</h1>
        <div class="page-subtitle">Live Stall Availability Map</div>
    </div>

    <div id="loader"><i class="fas fa-spinner fa-spin"></i> Loading Stall Data...</div>
    
    <div class="stall-grid" id="gridContainer"></div>

    <script>
        // Page load howar sathe sathe data anbe
        document.addEventListener("DOMContentLoaded", fetchStalls);

        async function fetchStalls() {
            const container = document.getElementById('gridContainer');
            const loader = document.getElementById('loader');

            try {
                // Backend theke data anchi
                const response = await fetch('/get-all-stalls');
                const result = await response.json();

                if (result.status === 'success') {
                    loader.style.display = 'none'; // loading hide
                    
                    // Loop chaliye ek ekta stall banachhi
                    result.data.forEach(stall => {
                       
                        const isAvailable = stall.status.toLowerCase() === 'available';
                        const cardClass = isAvailable ? 'available' : 'sold';
                        const icon = isAvailable ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-lock"></i>';
                        
                        const cardHTML = `
                            <div class="stall-card ${cardClass}" onclick="handleStallClick(${stall.stall_id}, '${stall.status}')">
                                <div class="stall-number">${stall.stall_number}</div>
                                <div class="stall-status">${icon} ${stall.status}</div>
                            </div>
                        `;
                       
                        container.innerHTML += cardHTML;
                    });
                } else {
                    loader.innerHTML = "❌ Failed to load data.";
                    loader.style.color = 'red';
                }
            } catch (error) {
                loader.innerHTML = "❌ Server Connection Error!";
                loader.style.color = 'red';
            }
        }

        // Available stall e click korle buy page e jabe
        function handleStallClick(stallId, status) {
            if(status.toLowerCase() === 'available') {
                
            }
        }
    </script>
</body>
</html>