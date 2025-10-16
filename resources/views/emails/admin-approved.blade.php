<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Approval - CRM System</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .approval-notice {
            background: #d4edda;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .google-button {
            background: #db4437;
        }
        .login-options {
            margin: 30px 0;
        }
        .option {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .next-steps {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Congratulations!</h1>
        <p>Your Admin Account Has Been Approved</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->full_name }}!</h2>
        
        <div class="approval-notice">
            <h3>✅ Admin Approval Confirmed</h3>
            <p>Great news! Your admin account has been approved by {{ $superAdminName ?? 'the SuperAdmin' }}. You can now access the CRM system with full admin privileges.</p>
        </div>
        
        <div class="login-options">
            <h3>How to Login:</h3>
            <div class="option">
                <h4>Option 1: Email & Password</h4>
                <p>Use your email and password to login to the system.</p>
                <a href="{{ url('/login') }}" class="button">Login with Email & Password</a>
            </div>
            
            <div class="option">
                <h4>Option 2: Google Sign-In</h4>
                <p>You can also sign in using your Google account with the same email address.</p>
                <a href="{{ url('/auth/google/signin') }}" class="button google-button">Sign in with Google</a>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>What You Can Do Now:</h3>
            <ol>
                <li><strong>Access Admin Dashboard</strong> - Manage users and system settings</li>
                <li><strong>Create Users</strong> - Add new users to your organization</li>
                <li><strong>Manage Modules</strong> - Control access to different system modules</li>
                <li><strong>View Reports</strong> - Access comprehensive system reports</li>
                <li><strong>Configure Settings</strong> - Customize system preferences</li>
            </ol>
        </div>
        
        <p><strong>Welcome to the team!</strong> You now have full admin access to the CRM system.</p>
        
        <p>If you have any questions or need assistance, please contact the SuperAdmin.</p>
    </div>
    
    <div class="footer">
        <p>Best regards,<br>CRM Team</p>
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
