<?php
require 'C:\\xampp\\vendor\\autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Fetch Form Data ---
$form_no = $_GET['form_no'] ?? $_POST['form_no'] ?? '';
if (!$form_no) die("Form No is required.");

// Fetch $data for the form_no from DB
$mysqli = new mysqli("localhost", "root", "", "sgn_boys_db");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$stmt = $mysqli->prepare("SELECT * FROM admissions WHERE form_no = ?");
$stmt->bind_param("s", $form_no);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
if (!$data) die("No record found.");

// --- PDF Generation (reuse your working code, but output to string) ---

$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ...existing code to build $html...

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdfOutput = $dompdf->output(); // Get PDF as string

// --- Email Sending ---
$mail = new PHPMailer(true);
try {
    //Server settings
   $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'help40617@gmail.com';
        $mail->Password   = 'lrmuluhlzrohwvoq';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('help40617@gmail.com', 'SGN KHALSA NEW ADDMISSION DETAILS');
    $mail->addAddress('bhavishyakushwha123@gmail.com'); // Add recipient

    // Attachments
    $mail->addStringAttachment($pdfOutput, 'Admission_Form.pdf');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Admission Form PDF';
    $mail->Body    = 'Please find attached your admission form summary PDF.';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
