<?php
require 'C:\\xampp\\vendor\\autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// DB connection
$mysqli = new mysqli("localhost", "root", "", "sgn_boys_db");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$form_no = $_GET['form_no'] ?? '';
if (!$form_no) die("Form No is required.");

$stmt = $mysqli->prepare("SELECT * FROM admissions WHERE form_no = ?");
$stmt->bind_param("s", $form_no);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
if (!$data) die("No record found.");

// Build HTML content
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/admission/assets/images/logo.png';
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$imageData = file_get_contents($logoPath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

$html = '<div style="text-align:center;">';
$html .= '<img src="' . $base64 . '" alt="Icon" style="height:70px;vertical-align:middle;margin-bottom:5px;">';
$html .= '<h2 style="display:inline-block;vertical-align:middle;margin-left:10px;">SGN Khalsa Admission Form Summary</h2>';
$html .= '</div>';

// Manual labels for each field
$fieldLabels = [
    'form_no' => 'Form No',
    'admission_year' => 'Admission Year',
    'roll_no' => 'Roll No',
    'id_card_no' => 'Id Card No',
    'religion' => 'Religion',
    'category' => 'Category',
    'stream' => 'Stream',
    'course' => 'Course',
    'medium' => 'Medium',
    'scholar_no' => 'Scholar No',
    'enrollment_no' => 'Enrollment No',
    'gender' => 'Gender',
    'dob' => 'Date of Birth',
    'candidate_name' => 'Candidate Name',
    'father_name' => 'Father Name',
    'father_occupation' => 'Father Occupation',
    'mother_name' => 'Mother Name',
    'mother_occupation' => 'Mother Occupation',
    'permanent_address' => 'Permanent Address',
    'correspondence_address' => 'Correspondence Address',
    'whatsapp' => 'Whatsapp',
    'mobile' => 'Mobile',
    'parents_mobile' => 'Parents Mobile',
    'last_institution' => 'Last Institution',
    'exam_name' => 'Exam Name',
    'exam_year' => 'Exam Year',
    'exam_sem' => 'Exam Sem',
    'exam_board' => 'Exam Board',
    'exam_percentage' => 'Exam Percentage',
    'exam_compulsory' => 'Exam Compulsory',
    'exam_optional' => 'Exam Optional',
    'ug_optional1' => 'UG Optional Subject 1',
    'ug_optional2' => 'UG Optional Subject 2',
    'ug_optional3' => 'UG Optional Subject 3',
    'ug_optional4' => 'UG Optional Subject 4',
    'ug_optional5' => 'UG Optional Subject 5',
    'ug_optional6' => 'UG Optional Subject 6',
    'pg_optional1' => 'PG Optional Subject 1',
    'pg_optional2' => 'PG Optional Subject 2',
    'pg_optional3' => 'PG Optional Subject 3',
    'pg_optional4' => 'PG Optional Subject 4',
    'pg_optional5' => 'PG Optional Subject 5',
    'pg_optional6' => 'PG Optional Subject 6',
    'interests' => 'Interests',
    'created_at' => 'Time of Form Submission'
];

// List of all fields to show (except id and pay_ss)
$fieldsToShow = [
    'form_no', 'admission_year', 'roll_no', 'id_card_no', 'religion', 'category', 'stream', 'course', 'medium',
    'scholar_no', 'enrollment_no', 'gender', 'dob', 'candidate_name', 'father_name', 'father_occupation',
    'mother_name', 'mother_occupation', 'permanent_address', 'correspondence_address', 'whatsapp', 'mobile',
    'parents_mobile', 'last_institution', 'exam_name', 'exam_year', 'exam_sem', 'exam_board', 'exam_percentage',
    'exam_compulsory', 'exam_optional', 'ug_optional1', 'ug_optional2', 'ug_optional3', 'ug_optional4',
    'ug_optional5', 'ug_optional6', 'pg_optional1', 'pg_optional2', 'pg_optional3', 'pg_optional4',
    'pg_optional5', 'pg_optional6', 'interests', 'created_at'
];

$html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
foreach ($fieldsToShow as $field) {
    $value = isset($data[$field]) ? $data[$field] : '';
    if (empty($value)) continue; // skip empty/null values
    $label = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field));
    $html .= "<tr><td><strong>$label</strong></td><td>$value</td></tr>";
}
$html .= '</table>';

// Load & render
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output to browser
$dompdf->stream("Admission_Form_{$form_no}.pdf", ["Attachment" => true]);
