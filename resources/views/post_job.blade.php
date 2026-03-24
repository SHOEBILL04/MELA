<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>MELA - Post a Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #01040a; 
            --secondary-bg: #021d4d; 
            --glass-bg: rgba(255, 255, 255, 0.05); 
            --glass-border: rgba(0, 242, 255, 0.2);
            --text-main: #ffffff;
            --text-dim: #b0c4de;
            --accent: #00f2ff; 
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, var(--secondary-bg), var(--primary-bg));
            min-height: 100vh; margin: 0; padding: 40px 20px;
            color: var(--text-main);
            display: flex; justify-content: center; align-items: center;
        }

        .form-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px; width: 100%; max-width: 500px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        .mela-logo { 
            font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 700;
            background: linear-gradient(to right, #00f2ff, #3a7bd5);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; 
            margin: 0 0 5px 0; text-align: center;
        }
        
        .page-title {
            text-align: center; font-size: 16px; color: var(--text-dim); margin-bottom: 30px;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: var(--text-dim); }
        
        input[type="text"], input[type="number"], select {
            width: 100%; padding: 12px 15px; border-radius: 8px;
            background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);
            color: white; font-size: 15px; font-family: 'Inter', sans-serif;
            box-sizing: border-box; transition: all 0.3s;
        }
        input:focus, select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 10px rgba(0,242,255,0.2); }

        .submit-btn {
            width: 100%; padding: 14px; background: linear-gradient(to right, #00f2ff, #0088ff);
            color: #000; border: none; border-radius: 8px; font-family: 'Poppins', sans-serif;
            font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s; margin-top: 10px;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 242, 255, 0.4); }

        #notification {
            display: none; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;
        }
        .success-msg { background: rgba(0, 255, 128, 0.1); border: 1px solid #00ff80; color: #00ff80; }
        .error-msg { background: rgba(255, 71, 87, 0.1); border: 1px solid #ff4757; color: #ff4757; }
    </style>
</head>
<body>

    <div class="form-container">
        <h1 class="mela-logo">MELA</h1>
        <div class="page-title">Post a Job for Your Stall</div>

        <div id="notification"></div>

        <form id="jobForm">
            <div class="form-group">
                <label for="stall_id"><i class="fas fa-store"></i> Select Your Stall</label>
                <select id="stall_id" required>
                    <option value="1">A-01 (Mela Main Branch)</option>
                    <option value="2">A-02 (Food Court)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title"><i class="fas fa-briefcase"></i> Job Title</label>
                <input type="text" id="title" placeholder="e.g. Sales Representative" required>
            </div>

            <div class="form-group">
                <label for="salary"><i class="fas fa-money-bill-wave"></i> Monthly Salary (BDT)</label>
                <input type="number" id="salary" placeholder="e.g. 15000" min="0" required>
            </div>

            <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Publish Job Post</button>
        </form>
    </div>

    <script>
        document.getElementById('jobForm').addEventListener('submit', async function(e) {
            e.preventDefault(); 
            
            const btn = document.querySelector('.submit-btn');
            const notif = document.getElementById('notification');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            const formData = {
                stall_id: document.getElementById('stall_id').value,
                title: document.getElementById('title').value,
                salary: document.getElementById('salary').value
            };

            try {
                const response = await fetch('/api/create-job', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Ekhane token-ta pathachhi
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                notif.style.display = 'block';
                if(response.ok) {
                    notif.className = 'success-msg';
                    notif.innerHTML = '<i class="fas fa-check-circle"></i> ' + result.message;
                    document.getElementById('jobForm').reset();
                } else {
                    notif.className = 'error-msg';
                    notif.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + result.message;
                }
            } catch (error) {
                notif.style.display = 'block';
                notif.className = 'error-msg';
                notif.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Connection Error!';
            }

            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Publish Job Post';
            btn.disabled = false;
        });
    </script>
</body>
</html>