<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])) {
    // Replace these with your actual server details for the catch-all mailbox
    $hostname = '{cam1002.sitnd.biz:993/imap/ssl}INBOX';
    $username = 'mega@mdmail.com';
    $password = 'abc123!';
    
    // The recipient email to filter by
    $recipientEmail = $_POST['email'];
    
    // Try to connect to the mail server
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail server: ' . imap_last_error());

    // Fetch an overview of all emails in the inbox
    $emails = imap_search($inbox, 'ALL');

    // Output email details
    if ($emails) {
        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $message = imap_fetchbody($inbox, $email_number, 1.2);
            if (empty($message)) {
                $message = imap_fetchbody($inbox, $email_number, 1);
            }
            
            // Filter by recipient email
            if (strpos($overview[0]->to, $recipientEmail) !== false) {
                echo "<h2>From: " . htmlspecialchars($overview[0]->from) . "</h2>";
                echo "<h3>To: " . htmlspecialchars($overview[0]->to) . "</h3>";
                echo "<h3>Subject: " . htmlspecialchars($overview[0]->subject) . "</h3>";
                echo "<div>Content: " . nl2br(htmlspecialchars($message)) . "</div><hr>";
            }
        }
    } else {
        echo "No emails found.";
    }

    // Close the connection
    imap_close($inbox);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Email</title>
</head>
<body>
    <form method="post">
        <label for="email">Enter Email:</label>
        <input type="text" id="email" name="email" required>
        <input type="submit" value="Check Email">
    </form>
</body>
</html>
