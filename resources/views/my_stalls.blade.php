<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MELA - My Stalls</title>
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
            display: flex; flex-direction: column; align-items: center;
        }

        .header-container { text-align: center; margin-bottom: 40px; }
        .mela-logo { 
            font-family: 'Poppins', sans-serif; font-size: 38px; font-weight: 700;
            background: linear-gradient(to right, #00f2ff, #3a7bd5);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0;
        }
        .page-subtitle { font-size: 18px; color: var(--text-dim); margin-top: 5px; }

        .receipt-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            width: 100%; max-width: 800px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 16px 15px; text-align: left; }
        th { 
            color: var(--accent); font-weight: 600; text-transform: uppercase; 
            font-size: 14px; letter-spacing: 1px;
            border-bottom: 2px solid var(--glass-border);
        }
        td { 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); 
            font-size: 16px; color: #e2e8f0;
        }
        
        .badge {
            padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase;
        }
        .badge.sold { background: rgba(0, 242, 255, 0.1); color: #00f2ff; border: 1px solid #00f2ff; }
        .badge.available { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid #ffc107; }

        .total-section {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 30px; padding-top: 20px;
            border-top: 2px dashed rgba(255, 255, 255, 0.2);
        }
        .total-title { font-size: 20px; font-weight: 600; color: var(--text-dim); }
        .total-amount { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: var(--accent); }

        #loader { text-align: center; color: var(--accent); font-size: 18px; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="header-container">
        <h1 class="mela-logo">MELA</h1>
        <div class="page-subtitle">My Vendor Dashboard & History</div>
    </div>

    <div class="receipt-card">
        <div id="loader"><i class="fas fa-spinner fa-spin"></i> Fetching your records...</div>
        
        <table id="stallsTable" style="display: none;">
            <thead>
                <tr>
                    <th>Stall No.</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th style="text-align: right;">Price (BDT)</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>

        <div class="total-section" id="totalSection" style="display: none;">
            <div class="total-title">Total Investment:</div>
            <div class="total-amount" id="totalAmount">৳ 0.00</div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", fetchMyHistory);

        async function fetchMyHistory() {
            try {
                const response = await fetch('/my-stalls-data');
                const result = await response.json();

                if (result.status === 'success') {
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('stallsTable').style.display = 'table';
                    document.getElementById('totalSection').style.display = 'flex';

                    const tbody = document.getElementById('tableBody');
                    let totalCost = 0;

                    result.data.forEach(stall => {
                        const category = stall.category ? stall.category : '<span style="color: gray;">N/A</span>';
                        
                        const price = parseFloat(stall.price || 0);
                        totalCost += price;

                        const badgeClass = stall.status.toLowerCase() === 'sold' ? 'sold' : 'available';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td><strong>${stall.stall_number}</strong></td>
                            <td>${category}</td>
                            <td><span class="badge ${badgeClass}">${stall.status}</span></td>
                            <td style="text-align: right; font-family: monospace; font-size: 17px;">৳ ${price.toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                        `;
                        tbody.appendChild(tr);
                    });

                    document.getElementById('totalAmount').innerText = '৳ ' + totalCost.toLocaleString('en-IN', {minimumFractionDigits: 2});
                }
            } catch (error) {
                document.getElementById('loader').innerHTML = "❌ Failed to load history.";
                document.getElementById('loader').style.color = 'red';
            }
        }
    </script>
</body>
</html>