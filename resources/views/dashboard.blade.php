<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PHP MELA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }
        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 28px;
        }
        p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .btn {
            display: block;
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-employee { 
            background-color: #3b82f6; 
            border: 2px solid #3b82f6;
        }
        .btn-employee:hover { 
            background-color: #2563eb; 
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }

        .btn-visitor { 
            background-color: #10b981; 
            border: 2px solid #10b981;
        }
        .btn-visitor:hover { 
            background-color: #059669; 
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }

        .btn-logout {
            background-color: #f87171;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-top: 30px;
        }
        .btn-logout:hover { 
            background-color: #ef4444; 
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>

    <div class="dashboard-card">
        <h1>Welcome to Dashboard! 🎉</h1>
        <p>You have successfully logged in. Choose your portal below:</p>

        <div class="button-group">
            <a href="/employee/positions" class="btn btn-employee">🧑‍💼 Go to Employee Portal</a>
            <a href="/visitor/fairs" class="btn btn-visitor">🎪 Go to Visitor Portal</a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout">🚪 Logout</button>
        </form>
    </div>

</body>
</html>