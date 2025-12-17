<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = "naveenpsingh98@gmail.com"; // Yahan apna email dalen
    $subject = "New CV Submission";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    if(isset($_FILES['cv']) && $_FILES['cv']['error'] == 0){
        $file_tmp = $_FILES['cv']['tmp_name'];
        $file_name = $_FILES['cv']['name'];
        $file_type = $_FILES['cv']['type'];

        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        $boundary = md5(time());

        // Headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";

        // Message body
        $message = "--$boundary\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= "Name: $name\n";
        $message .= "Email: $email\n";
        $message .= "Position Applying For: $position\n\n";

        // Attachment
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= $file_content . "\r\n";
        $message .= "--$boundary--";

        // Send email
        if(mail($to, $subject, $message, $headers)){
            echo "<p style='color:green;'>CV sent successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error sending CV. Please try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>Please upload a valid CV file.</p>";
    }
} else {
    echo "<p style='color:red;'>Invalid request.</p>";
}
?>
