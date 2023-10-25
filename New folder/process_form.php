<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $phone = $_POST['Phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $idDetails = $_POST['id-details'];
    $vehicleDetails = $_POST['Vehicle-details'];
    $personOnSite = $_POST['Person on-site'];
    $price = $_POST['Price'];
    $paymentMethod = $_POST['payment-method'];
    $bankAccountNumber = $_POST['bank-account'];
    $declaration = isset($_POST['declaration']) ? 'Yes' : 'No';

    // Process uploaded ID document (file)
    if (isset($_FILES['files'])) {
        $uploadedFile = $_FILES['files'];
        $fileTmpName = $uploadedFile['tmp_name'];
        $fileName = $uploadedFile['name'];
        $fileType = $uploadedFile['type'];
        
        // Move the uploaded file to a designated folder (adjust the path as needed)
        $uploadDirectory = 'uploads/';
        move_uploaded_file($fileTmpName, $uploadDirectory . $fileName);
    }

    // Process user's signature
    if (isset($_POST['signature-data'])) {
        $signatureData = $_POST['signature-data'];

        // Convert the signature data to an image file (PNG format)
        $signatureImage = base64_decode(str_replace('data:image/png;base64,', '', $signatureData));
        
        // Save the signature as an image file (adjust the path as needed)
        $signatureFilePath = 'signatures/';
        file_put_contents($signatureFilePath . $firstName . '_' . $lastName . '_signature.png', $signatureImage);
    }

    // Prepare email content
    $to = 'your_email@example.com';  // Replace with your email address
    $subject = 'Purchase Form Submission';

    $message = "First Name: $firstName\n";
    $message .= "Last Name: $lastName\n";
    $message .= "Phone: $phone\n";
    $message .= "Email Address: $email\n";
    $message .= "Address: $address\n";
    $message .= "ID Details: $idDetails\n";
    $message .= "Vehicle Details: $vehicleDetails\n";
    $message .= "Person on-site: $personOnSite\n";
    $message .= "Price: $price\n";
    $message .= "Payment Method: $paymentMethod\n";
    $message .= "Bank Account Number: $bankAccountNumber\n";
    $message .= "Declaration: $declaration\n";

    // Attach the ID document (uploaded file)
    $attachmentPath = $uploadDirectory . $fileName;
    if (file_exists($attachmentPath)) {
        $fileContent = file_get_contents($attachmentPath);
        $fileData = base64_encode($fileContent);
        $message .= "Attachment: $fileName\n";
        $message .= $fileData;
    }

    // Send the email with the attached ID document
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";

    mail($to, $subject, $message, $headers);

    // Redirect back to the form with a success message
    header('Location: form.html?success=1');
} else {
    // Handle the case when the form is accessed directly
    echo 'Access denied.';
}
?>
