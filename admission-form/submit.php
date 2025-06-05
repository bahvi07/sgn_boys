<?php
 require 'C:\\xampp\\vendor\\autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type:application/json");
$conn=new mysqli("localhost","root","","sgn_boys_db");
if($conn->connect_error){
    die("connection failed".$conn->connect_error);
}
$response = ["success" => false, "message" => ""];
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = "Invalid request method.";
        echo json_encode($response);
        exit;
    }

    // Collect and sanitize input
    $form_no = $_POST['form_no'] ?? '';
    $admission_year = $_POST['admission_year'] ?? '';
    $roll_no = $_POST['roll_no'] ?? '';
    $id_card_no = $_POST['id_card_no'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $category = $_POST['category'] ?? '';
    $stream = $_POST['stream'] ?? '';
    $course = $_POST['course'] ?? '';
    $medium = $_POST['medium'] ?? '';
    $scholar_no = $_POST['scholar_no'] ?? '';
    $enrollment_no = $_POST['enrollment_no'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $candidate_name = $_POST['candidate_name'] ?? '';
    $father_name = $_POST['father_name'] ?? '';
    $father_occupation = $_POST['father_occupation'] ?? '';
    $mother_name = $_POST['mother_name'] ?? '';
    $mother_occupation = $_POST['mother_occupation'] ?? '';
    $permanent_address = $_POST['permanent_address'] ?? '';
    $correspondence_address = $_POST['correspondence_address'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $parents_mobile = $_POST['parents_mobile'] ?? '';
    $last_institution = $_POST['last_institution'] ?? '';

    // Handle payment screenshot upload
    $pay_ss_path = null;
    if (isset($_FILES['pay_ss']) && $_FILES['pay_ss']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../assets/images/payments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['pay_ss']['name'], PATHINFO_EXTENSION);
        $filename = 'pay_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['pay_ss']['tmp_name'], $target)) {
            $pay_ss_path = 'assets/images/payments/' . $filename;
        } else {
            $response['message'] = 'Failed to upload payment screenshot.';
            echo json_encode($response);
            exit;
        }
    } else {
        $response['message'] = 'Payment screenshot is required.';
        echo json_encode($response);
        exit;
    }

    // Insert into admissions table (add pay_ss field)
    $fields = [
        'form_no', 'admission_year', 'roll_no', 'id_card_no', 'religion', 'category', 'stream', 'course', 'medium', 'scholar_no', 'enrollment_no', 'gender', 'dob', 'candidate_name', 'father_name', 'father_occupation', 'mother_name', 'mother_occupation', 'permanent_address', 'correspondence_address', 'whatsapp', 'mobile', 'parents_mobile', 'last_institution', 'pay_ss', 'exam_name', 'exam_year', 'exam_sem', 'exam_board', 'exam_percentage', 'exam_compulsory', 'exam_optional',
        'ug_optional1', 'ug_optional2', 'ug_optional3', 'ug_optional4', 'ug_optional5', 'ug_optional6',
        'pg_optional1', 'pg_optional2', 'pg_optional3', 'pg_optional4', 'pg_optional5', 'pg_optional6',
        'interests'
    ];
    $values = [];
    foreach ($fields as $field) {
        if ($field === 'pay_ss') {
            $values[] = $pay_ss_path;
        } elseif ($field === 'interests') {
            $values[] = isset($_POST['interests']) ? implode(',', $_POST['interests']) : '';
        } elseif ($field === 'exam_name') {
            if (isset($_POST['last-exam']) && $_POST['last-exam'] === 'Other' && !empty($_POST['other'])) {
                $values[] = $_POST['other'];
            } else {
                $values[] = $_POST['last-exam'] ?? '';
            }
        } elseif (strpos($field, 'ug_optional') === 0) {
            $index = substr($field, 11); // e.g., '1', '2', ...
            $values[] = $_POST['ug_optional'.$index] ?? '';
        } elseif (strpos($field, 'pg_optional') === 0) {
            $index = substr($field, 11); // e.g., '1', '2', ...
            $values[] = $_POST['pg_optional'.$index] ?? '';
        } else {
            $values[] = $_POST[$field] ?? '';
        }
    }
    $placeholders = rtrim(str_repeat('?,', count($fields)), ',');
    $sql = "INSERT INTO admissions (" . implode(',', $fields) . ") VALUES ($placeholders)";
    
    // Use mysqli from db.php
    global $conn;
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $response['message'] = 'Prepare failed: ' . $conn->error;
        echo json_encode($response);
        exit;
    }
    $types = str_repeat('s', count($fields));
    $stmt->bind_param($types, ...$values);
    if (!$stmt->execute()) {
        $response['message'] = 'Execute failed: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $admission_id = $conn->insert_id;

    $response['success'] = true;
    $response['message'] = "Form submitted successfully.";

    // Fetch the inserted data again using form_no
    $stmt = $conn->prepare("SELECT * FROM admissions WHERE form_no = ?");
    $stmt->bind_param("s", $form_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        // Generate PDF from the data
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/admission/assets/images/logo.png';
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($logoPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

        $html = '<div style="text-align:center;">';
        $html .= '<img src="' . $base64 . '" style="height:70px;margin-bottom:10px;">';
        $html .= '<h2>SGN Khalsa Admission Form Summary</h2>';
        $html .= '</div>';
        $html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
        foreach ($data as $field => $value) {
            if ($field === 'id' || $field === 'pay_ss' || empty($value)) continue;
            $label = ucwords(str_replace('_', ' ', $field));
            $html .= "<tr><td><strong>$label</strong></td><td>$value</td></tr>";
        }
        $html .= '</table>';
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output(); // string for attachment

        // Prepare Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'help40617@gmail.com';
            $mail->Password   = 'lrmuluhlzrohwvoq';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('help40617@gmail.com', 'SGN Khalsa College');
            $mail->addAddress('bhavishyakushwha123@gmail.com'); // you can use $data['email'] if stored

            $mail->isHTML(true);
            $mail->Subject = "Admission Form & Payment Confirmation - {$form_no}";
            $mail->Body    = "Thank you for submitting the admission form. Attached are your admission summary PDF and payment screenshot.";

            // Attach PDF
            $mail->addStringAttachment($pdfOutput, "Admission_Form_{$form_no}.pdf");

            // Attach payment screenshot
            if (!empty($data['pay_ss'])) {
                $paymentScreenshotPath = $_SERVER['DOCUMENT_ROOT'] . '/admission/' . $data['pay_ss'];
                if (file_exists($paymentScreenshotPath)) {
                    $mail->addAttachment($paymentScreenshotPath);
                }
            }

            $mail->send();
            // Optional: you can log or set success message
        } catch (Exception $e) {
            // Optionally log error
            error_log("Mail Error: {$mail->ErrorInfo}");
        }
    }
    // === END EMAIL ===

    // Only output JSON once, at the very end
    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
