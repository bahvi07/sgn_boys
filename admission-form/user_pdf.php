<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// DB connection
$mysqli = new mysqli("localhost", "sgn_user", "DKC9=x.KLH&-", "sgn_boys_db");

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

// Check if sections have data
$hasUGSubjects = false;
$hasPGSubjects = false;
$hasInterests = !empty($data['interests']);

// Check UG subjects
for ($i = 1; $i <= 6; $i++) {
    if (!empty($data["ug_optional$i"])) {
        $hasUGSubjects = true;
        break;
    }
}

// Check PG subjects
for ($i = 1; $i <= 6; $i++) {
    if (!empty($data["pg_optional$i"])) {
        $hasPGSubjects = true;
        break;
    }
}

$hasSubjectsSection = !empty($data['comp_sub']) || $hasUGSubjects || $hasPGSubjects;

// Build HTML content with conditional sections
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/admission/assets/images/logo.png';
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$imageData = file_get_contents($logoPath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
    .logo { height: 60px; vertical-align: middle; }
    .college-name { font-size: 18px; font-weight: bold; margin: 5px 0; }
    .form-title { font-size: 16px; font-weight: bold; margin: 10px 0; }
    .section-header { background-color: #f0f0f0; padding: 8px; font-weight: bold; font-size: 14px; margin: 15px 0 10px 0; border: 1px solid #ccc; }
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .info-table td { padding: 6px; border: 1px solid #ddd; vertical-align: top; }
    .info-table .label { font-weight: bold; background-color: #f9f9f9; width: 30%; }
    .meta-info { display: flex; justify-content: space-between; margin: 15px 0; }
    .meta-item { flex: 1; text-align: center; border: 1px solid #ccc; padding: 5px; margin: 0 2px; }
    .exam-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    .exam-table th, .exam-table td { border: 1px solid #333; padding: 6px; text-align: center; }
    .exam-table th { background-color: #f0f0f0; font-weight: bold; }
    .subjects-section { display: flex; justify-content: space-between; margin: 10px 0; }
    .subject-column { flex: 1; margin: 0 5px; }
    .subject-list { list-style: none; padding: 0; }
    .subject-list li { padding: 3px 0; border-bottom: 1px dotted #ccc; }
</style>

<div class="header">
    <img src="' . $base64 . '" alt="College Logo" class="logo">
    <div class="college-name">SRI GURU NANAK KHALSA P.G. COLLEGE</div>
    <div>SRIGANGANAGAR-335001 (RAJ.)</div>
    <div class="form-title">ADMISSION FORM</div>
</div>

<table class="info-table" style="margin-bottom:10px;">
    <tr>
        <td class="label">Form No</td>
        <td>' . htmlspecialchars($data['form_no'] ?? '') . '</td>
        <td class="label">Admission Year</td>
        <td>' . htmlspecialchars($data['admission_year'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Roll No</td>
        <td>' . htmlspecialchars($data['roll_no'] ?? '') . '</td>
        <td class="label">ID Card No</td>
        <td>' . htmlspecialchars($data['id_card_no'] ?? '') . '</td>
    </tr>
</table>

<div class="section-header">ADMISSION DETAILS</div>
<table class="info-table">
    <tr>
        <td class="label">Religion</td>
        <td>' . htmlspecialchars($data['religion'] ?? '') . '</td>
        <td class="label">Category</td>
        <td>' . htmlspecialchars($data['category'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Stream</td>
        <td>' . htmlspecialchars($data['stream'] ?? '') . '</td>
        <td class="label">Course</td>
        <td>' . htmlspecialchars($data['course'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Medium</td>
        <td>' . htmlspecialchars($data['medium'] ?? '') . '</td>
        <td class="label">Scholar No.</td>
        <td>' . htmlspecialchars($data['scholar_no'] ?? '') . '</td>
    </tr>
</table>

<div class="section-header">STUDENT\'S DETAILS</div>
<table class="info-table">
    <tr>
        <td class="label">Name of Candidate</td>
        <td colspan="3">' . htmlspecialchars($data['candidate_name'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Gender</td>
        <td>' . htmlspecialchars($data['gender'] ?? '') . '</td>
        <td class="label">Date of Birth</td>
        <td>' . htmlspecialchars($data['dob'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Father\'s Name</td>
        <td>' . htmlspecialchars($data['father_name'] ?? '') . '</td>
        <td class="label">Occupation</td>
        <td>' . htmlspecialchars($data['father_occupation'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Mother\'s Name</td>
        <td>' . htmlspecialchars($data['mother_name'] ?? '') . '</td>
        <td class="label">Occupation</td>
        <td>' . htmlspecialchars($data['mother_occupation'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Permanent Address</td>
        <td>' . nl2br(htmlspecialchars($data['permanent_address'] ?? '')) . '</td>
        <td class="label">Correspondence Address</td>
        <td>' . nl2br(htmlspecialchars($data['correspondence_address'] ?? '')) . '</td>
    </tr>
    <tr>
        <td class="label">WhatsApp No.</td>
        <td>' . htmlspecialchars($data['whatsapp'] ?? '') . '</td>
        <td class="label">Mobile No.</td>
        <td>' . htmlspecialchars($data['mobile'] ?? '') . '</td>
    </tr>
    <tr>
        <td class="label">Parents Mobile</td>
        <td>' . htmlspecialchars($data['parents_mobile'] ?? '') . '</td>
        <td class="label">Last Institution</td>
        <td>' . htmlspecialchars($data['last_institution'] ?? '') . '</td>
    </tr>
</table>

<div class="section-header">DETAILS OF PREVIOUS EXAM PASSED</div>
<table class="exam-table">
    <thead>
        <tr>
            <th rowspan="2">Name of Examination</th>
            <th rowspan="2">Year</th>
            <th rowspan="2">Semester</th>
            <th rowspan="2">University/Board</th>
            <th rowspan="2">% of Marks</th>
            <th colspan="2">Subjects</th>
        </tr>
        <tr>
            <th>Compulsory</th>
            <th>Optional</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>' . htmlspecialchars($data['exam_name'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_year'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_sem'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_board'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_percentage'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_compulsory'] ?? '') . '</td>
            <td>' . htmlspecialchars($data['exam_optional'] ?? '') . '</td>
        </tr>
    </tbody>
</table>';

// Only show SUBJECTS OFFERED section if there are subjects
if ($hasSubjectsSection) {
    $html .= '<div class="section-header">SUBJECTS OFFERED</div>
    <div class="subjects-section">';

    // ✅ Compulsory UG Subjects
    if (!empty($data['comp_sub'])) {
        $compSubs = explode(',', $data['comp_sub']); // Convert back to array
        $html .= '<div class="subject-column">
            <h4>UG Compulsory Subjects:</h4>
            <ul class="subject-list">';
        foreach ($compSubs as $sub) {
            $html .= '<li>' . htmlspecialchars(trim($sub)) . '</li>';
        }
        $html .= '</ul></div>';
    }

    // ✅ UG Optional Subjects
    if ($hasUGSubjects) {
        $html .= '<div class="subject-column">
            <h4>UG Optional Subjects:</h4>
            <ul class="subject-list">';
        for ($i = 1; $i <= 6; $i++) {
            $subject = $data["ug_optional$i"] ?? '';
            if (!empty($subject)) {
                $html .= '<li>' . htmlspecialchars($subject) . '</li>';
            }
        }
        $html .= '</ul></div>';
    }

    // ✅ PG Optional Subjects
    if ($hasPGSubjects) {
        $html .= '<div class="subject-column">
            <h4>PG Optional Subjects:</h4>
            <ul class="subject-list">';
        for ($i = 1; $i <= 6; $i++) {
            $subject = $data["pg_optional$i"] ?? '';
            if (!empty($subject)) {
                $html .= '<li>' . htmlspecialchars($subject) . '</li>';
            }
        }
        $html .= '</ul></div>';
    }

    $html .= '</div>'; // End subjects-section
}


// Only show PREFERENCE OF INTEREST section if there are interests
if ($hasInterests) {
    $html .= '<div class="section-header">PREFERENCE OF INTEREST</div>
    <table class="info-table">
        <tr>
            <td class="label">Interests</td>
            <td>' . htmlspecialchars($data['interests'] ?? '') . '</td>
        </tr>
    </table>';
}

$html .= '<div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
    Form submitted on: ' . htmlspecialchars($data['created_at'] ?? '') . '
</div>';

// Load & render
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output to browser
$dompdf->stream("Admission_Form_{$form_no}.pdf", ["Attachment" => true]);
?>