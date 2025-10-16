<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Due Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .user-info {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Payment Due Notification</h1>
        <p>Salary payment is now due for processing</p>
    </div>
    
    <div class="content">
        <div class="alert">
            <strong>⚠️ Action Required:</strong> A salary payment has reached its due date and requires immediate attention.
        </div>
        
        <h2>Employee Information</h2>
        <div class="user-info">
            <p><strong>Name:</strong> {{ $user->full_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Department:</strong> {{ $user->userInfo->department->name ?? 'N/A' }}</p>
            <p><strong>Salary Amount:</strong> ${{ number_format($user->userInfo->salary ?? 0, 2) }}</p>
            <p><strong>Due Date:</strong> {{ now()->format('M d, Y') }}</p>
        </div>
        
        <h3>What You Need to Do:</h3>
        <ol>
            <li>Go to the Finance Dashboard</li>
            <li>Review the pending salary payment</li>
            <li>Mark the payment as "Paid" once processed</li>
            <li>The system will automatically set the next payment date</li>
        </ol>
        
        <div style="text-align: center;">
            <a href="{{ route('finance.dashboard') }}" class="button">Go to Finance Dashboard</a>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from the Finance Management System.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
