<?php
require __DIR__ . '/../include/db.php';
$pdo = new PDO('mysql:host=localhost;dbname=student_addmission', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
header("Content-Type:application/json");

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

    // Insert into admissions table
    $stmt = $pdo->prepare("INSERT INTO admissions (form_no, admission_year, roll_no, id_card_no, religion, category, stream,course, medium, scholar_no, enrollment_no, gender, dob, candidate_name, father_name, father_occupation, mother_name, mother_occupation, permanent_address, correspondence_address, whatsapp, mobile, parents_mobile, last_institution) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $form_no,
        $admission_year,
        $roll_no,
        $id_card_no,
        $religion,
        $category,
        $course,
        $stream,
        $medium,
        $scholar_no,
        $enrollment_no,
        $gender,
        $dob,
        $candidate_name,
        $father_name,
        $father_occupation,
        $mother_name,
        $mother_occupation,
        $permanent_address,
        $correspondence_address,
        $whatsapp,
        $mobile,
        $parents_mobile,
        $last_institution
    ]);
    $admission_id = $pdo->lastInsertId();

    // Insert previous exams
    if (!empty($_POST['exam_year'])) {
        $exam_name = $_POST['last-exam'] ?? '';
        if ($exam_name === 'Other' && !empty($_POST['other'])) {
            $exam_name = $_POST['other'];
        }
        $exam_year = $_POST['exam_year'] ?? '';
        $exam_sem = $_POST['exam_sem'] ?? '';
        $exam_board = $_POST['exam_board'] ?? '';
        $exam_percentage = $_POST['exam_percentage'] ?? '';
        $exam_compulsory = $_POST['exam_compulsory'] ?? '';
        $exam_optional = $_POST['exam_optional'] ?? '';
        $stmt_exam = $pdo->prepare("INSERT INTO admission_exams (admission_id, exam_name, exam_year, exam_sem, exam_board, exam_percentage, exam_compulsory, exam_optional) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_exam->execute([
            $admission_id,
            $exam_name,
            $exam_year,
            $exam_sem,
            $exam_board,
            $exam_percentage,
            $exam_compulsory,
            $exam_optional
        ]);
    }

    // Insert subjects (UG compulsory)
    if (!empty($_POST['subjects'])) {
        $stmt_subj = $pdo->prepare("INSERT INTO admission_subjects (admission_id, subject_type, subject_name) VALUES (?, 'UG_Compulsory', ?)");
        foreach ($_POST['subjects'] as $subject) {
            $stmt_subj->execute([$admission_id, $subject]);
        }
    }
    // Insert UG optional
    for ($i = 1; $i <= 3; $i++) {
        $key = 'ug_optional' . $i;
        if (!empty($_POST[$key])) {
            $stmt_subj = $pdo->prepare("INSERT INTO admission_subjects (admission_id, subject_type, subject_name) VALUES (?, 'UG_Optional', ?)");
            $stmt_subj->execute([$admission_id, $_POST[$key]]);
        }
    }
    // Insert PG optional
    for ($i = 1; $i <= 3; $i++) {
        $key = 'pg_optional' . $i;
        if (!empty($_POST[$key])) {
            $stmt_subj = $pdo->prepare("INSERT INTO admission_subjects (admission_id, subject_type, subject_name) VALUES (?, 'PG_Optional', ?)");
            $stmt_subj->execute([$admission_id, $_POST[$key]]);
        }
    }
    // Insert interests
    if (!empty($_POST['interests'])) {
        $stmt_int = $pdo->prepare("INSERT INTO admission_interests (admission_id, interest_name) VALUES (?, ?)");
        foreach ($_POST['interests'] as $interest) {
            $stmt_int->execute([$admission_id, $interest]);
        }
    }

    $response['success'] = true;
    $response['message'] = "Form submitted successfully.";
    echo json_encode($response);
    exit;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit;
}
