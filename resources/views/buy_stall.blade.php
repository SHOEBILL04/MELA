<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MELA - Buy Your Perfect Stall</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #01040a; 
            --secondary-bg: #021d4d; 
            --glass-bg: rgba(255, 255, 255, 0.1); /* Opacity baralam */
            --glass-border: rgba(0, 210, 255, 0.3); /* Border cyan joljol korbe */
            --input-bg: rgba(255, 255, 255, 0.12); 
            --text-main: #ffffff;
            --text-dim: #b0c4de;
            --accent: #00f2ff; /* Pura neon cyan */
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, var(--secondary-bg), var(--primary-bg));
            min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 45px; border-radius: 28px;
            border: 1px solid var(--glass-border);
            width: 100%; max-width: 420px;
            box-shadow: 0 0 40px rgba(0, 210, 255, 0.15); /* Outer glow added */
        }

        .mela-logo { 
            font-family: 'Poppins', sans-serif; font-size: 36px; font-weight: 700;
            background: linear-gradient(to right, #00f2ff, #3a7bd5);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; /* Logo-ta gradient joljole */
            letter-spacing: 3px; 
        }

        input {
            width: 100%; padding: 15px 15px 15px 45px;
            background: var(--input-bg); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px; color: #fff; font-size: 16px; box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus { 
            outline: none; border-color: var(--accent); 
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.3); /* Focus glow baralam */
        }

        button {
            width: 100%; padding: 18px;
            background: linear-gradient(135deg, #00f2ff, #0072ff);
            border: none; border-radius: 14px; color: #fff; font-weight: 700; font-size: 18px;
            cursor: pointer; transition: 0.4s; display: flex; align-items: center;
            justify-content: center; gap: 12px; margin-top: 15px;
            box-shadow: 0 8px 25px rgba(0, 242, 255, 0.3); /* Button-er nicher glow */
        }

        button:hover { transform: scale(1.03); box-shadow: 0 12px 30px rgba(0, 242, 255, 0.5); }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <h1 class="mela-logo">MELA</h1>
            <div class="card-title">Buy Your Perfect Stall</div>
        </div>
        
        <div class="form-group">
            <label>Vendor ID</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="number" id="vendor_id" placeholder="e.g. 2">
            </div>
        </div>
        
        <div class="form-group">
            <label>Stall ID</label>
            <div class="input-wrapper">
                <i class="fas fa-store"></i>
                <input type="number" id="stall_id" placeholder="e.g. 7">
            </div>
        </div>
        
        <button id="buyBtn" onclick="buyStall()">
            <i class="fas fa-shopping-cart"></i>
            <span>Buy Now</span>
        </button>

        <p id="message"></p>
    </div>

    <script>
        async function buyStall() {
            const vId = document.getElementById('vendor_id').value;
            const sId = document.getElementById('stall_id').value;
            const msgBox = document.getElementById('message');
            const btn = document.getElementById('buyBtn');

            if(!vId || !sId) {
                msgBox.style.color = '#ff6b6b';
                msgBox.innerHTML = "Please fill in all fields!";
                return;
            }

            btn.disabled = true;
            msgBox.style.color = '#00d2ff';
            msgBox.innerHTML = "Processing...";

            try {
                const response = await fetch('/buy-stall', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ vendor_id: parseInt(vId), stall_id: parseInt(sId) })
                });

                const data = await response.json();

                if (response.ok) {
                    msgBox.style.color = '#51cf66';
                    msgBox.innerHTML = "🎉 " + data.message;
                    document.getElementById('vendor_id').value = '';
                    document.getElementById('stall_id').value = '';
                } else {
                    msgBox.style.color = '#ff6b6b';
                    msgBox.innerHTML = "❌ " + (data.message || "Failed!");
                }
            } catch (error) {
                msgBox.style.color = '#ff6b6b';
                msgBox.innerHTML = "❌ Server Error! Port 9000 chalu ache to?";
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>