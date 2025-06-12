<?php
include './include/header.php';

// Generate a unique form number (e.g., using current timestamp)
$form_no = 'SGNK-' . time();
?>

<div class="container bordered">
  <!-- Back to Home Button -->
 

  <form id="admissionForm" method="post">
    <div class="form-title text-center">
      <h4>SRI GURU NANAK KHALSA P.G. COLLEGE</h4>
      <p class="mb-1">SRIGANGANAGAR-335001 (RAJ.)</p>
      <h5 class="mt-3">ADMISSION FORM</h5>
      <div class="year-inputs">
        <!-- Hidden Year of Admission -->
        <input type="hidden" name="admission_year" value="<?php echo date('Y'); ?>">
      </div>
    </div>

    <div class="row mb-3 form-meta">
      <div class="col-md-4">
        Form No.: <strong><?php echo $form_no; ?></strong>
        <input type="hidden" name="form_no" value="<?php echo $form_no; ?>">
      </div>
      <div class="col-md-4">
        <label for="roll_no">Class Roll No.</label>
        <input type="text" name="roll_no" id="roll_no" class="form-control numonly" placeholder="Enter Passout Roll No." autofocus aria-label="Class Roll No.">
      </div>
      <div class="col-md-4">
        <label for="id_card_no">ID Card No.</label>
        <input type="text" name="id_card_no" id="id_card_no" class="form-control numonly" placeholder="For Passout Students" aria-label="ID Card No.">
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-6 ">
        <label class="form-label">Religion:</label>
        <div class="checkbox-group" role="radiogroup" aria-label="Religion">
          <label><input type="radio" name="religion" value="Hindu" required> Hindu</label>
          <label><input type="radio" name="religion" value="Muslim"> Muslim</label>
          <label><input type="radio" name="religion" value="Sikh"> Sikh</label>
          <label><input type="radio" name="religion" value="Christian"> Christian</label>
        </div>
      </div>
      <div class="col-6">
        <label class="form-label">Category:</label>
        <div class="checkbox-group" role="radiogroup" aria-label="Category">
          <label><input type="radio" name="category" value="General" required> Gen.</label>
          <label><input type="radio" name="category" value="SC"> S.C.</label>
          <label><input type="radio" name="category" value="ST"> S.T.</label>
          <label><input type="radio" name="category" value="OBC"> OBC</label>
        </div>
      </div>
      </div>
      <div class="row mt-2">
        <label class="form-label">Admission Details:</label>
        <div class="col-md-4 mb-2">
          <label>Stream:</label>
          <select name="stream" id="stream" class="form-control" required>
            <option value="">-- Select Stream --</option>
            <option value="Arts-Stream">Arts</option>
            <option value="Computer-Stream">Computer</option>
            <option value="Commerce&Management-Stream">Commerce & Management</option>
            <option value="Education-Stream">Education</option>
            <option value="Science-Stream">Science</option>
          </select>
        </div>
        <div class="col-md-4 mb-2">
          <label>Course:</label>
          <select name="course" id="course" class="form-control" disabled required >
            <option value="">-- Select Course --</option>
            <!-- Arts -->
            <optgroup label="Arts" data-stream="Arts-Stream">
              <option value="B.A.">B.A.</option>
              <option value="M.A-English">M.A. English</option>
              <option value="M.A-Pol-Sci">M.A. Political Science</option>
              <option value="M.A-Sociology">M.A. Sociology</option>
              <option value="M.A-Geography">M.A. Geography</option>
              <option value="M.A-Math">M.A. Math</option>
              <option value="M.A-Punjabi">M.A. Punjabi</option>
            </optgroup>
            <!-- Computer -->
            <optgroup label="Computer" data-stream="Computer-Stream">
              <option value="BCA">BCA</option>
              <option value="MSC-Comp-Sci">M.Sc. Computer-Science</option>
            </optgroup>
            <!-- Commerce & Management -->
            <optgroup label="Commerce & Management" data-stream="Commerce&Management-Stream">
              <option value="B.Com.">B.Com.</option>
              <option value="M.Com.">M.Com. (ABST)</option>
              <option value="M.Com">M.Com. (Business Management)</option>
              <option value="BBA">BBA</option>
            </optgroup>
            <!-- Education -->
            <optgroup label="Education" data-stream="Education-Stream">
              <option value="B.A-B.Ed.">B.A./B.Ed.</option>
              <option value="B.Sc-B.Ed.">B.Sc./B.Ed.</option>
            </optgroup>
            <!-- Science -->
            <optgroup label="Science" data-stream="Science-Stream">
              <option value="B.Sc.-Med">B.Sc. (Medical)</option>
              <option value="B.Sc.-Non-Med">B.Sc. (Non-Medical)</option>
              <option value="M.Sc.-Physics">M.Sc. Physics</option>
              <option value="M.Sc.-Chemistry">M.Sc. Chemistry</option>
              <option value="M.Sc.-Geography">M.Sc. Geography</option>
              <option value="M.Sc.-Math">M.Sc. Math</option>
              <option value="M.Sc.-Botany">M.Sc. Botany</option>
              <option value="M.Sc.-Zoology">M.Sc. Zoology</option>
            </optgroup>
          </select>
        </div>

        <div class="col-md-4 mb-2">
          <label>Medium:</label>
          <select name="medium" class="form-control" required>
            <option value="">Select</option>
            <option value="Hindi">HINDI</option>
            <option value="English">ENGLISH</option>
          </select>
        </div>
      </div>
    

    <div class="section-title">STUDENT'S DETAILS</div>

    <div class="row mb-3">
      <div class="col-sm-6">
        <label>1. Scholar No. (if any):</label>
        <input type="text" name="scholar_no" class="form-control">
      </div>
      <div class="col-sm-6">
        <label>2. University Enrollment No. (If any):</label>
        <input type="text" name="enrollment_no" class="form-control">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-sm-6">
        <label>3. Gender:</label>
        <div class="checkbox-group">
          <label><input type="radio" name="gender" value="Male"> Male</label>
          <label><input type="radio" name="gender" value="Female"> Female</label>
          <label><input type="radio" name="gender" value="Transgender"> TG</label>
        </div>
      </div>
      <div class="col-sm-6 mt-2" id="">
        <label>4. Date of Birth:</label>
        <input type="date" name="dob" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label>5. Name of Candidate:</label>
      <input type="text" name="candidate_name" class="form-control charonly" placeholder="Full Name" required aria-label="Name of Candidate" >
    </div>

    <div class="row mb-3">
      <div class="col-md-8">
        <label>6. Father's Name:</label>
        <input type="text" name="father_name" class="form-control charonly" placeholder="Father's Name" required aria-label="Father's Name">
      </div>
      <div class="col-sm-4">
        <label>Occupation:</label>
        <input type="text" name="father_occupation" class="form-control charonly">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-8">
        <label>7. Mother's Name:</label>
        <input type="text" name="mother_name" class="form-control charonly" placeholder="Mother's Name" required aria-label="Mother's Name">
      </div>
      <div class="col-sm-4">
        <label>Occupation:</label>
        <input type="text" name="mother_occupation" class="form-control charonly">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label>A. Permanent Address:</label>
        <textarea name="permanent_address" class="form-control" rows="3"required></textarea>
      </div>
      <div class="col-md-6">
        <label>B. Address for Correspondence:</label>
        <textarea name="correspondence_address" class="form-control" rows="3"></textarea>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label>C. Whatsapp No.:</label>
        <input type="tel" name="whatsapp" class="form-control numonly">
      </div>
      <div class="col-md-4">
        <label>Mobile No.:</label>
        <input type="tel" name="mobile" maxlength="10" class="form-control numonly">
      </div>
        <div class="col-md-4">
        <label>D. Parents Mobile No.:</label>
        <input type="tel" name="parents_mobile" class="form-control numonly" maxlength="10" required>
      </div>
    </div>
    <div class="mb-3">
      <label>8. Name of Institution Last Attended:</label>
      <input type="text" name="last_institution" class="form-control charonly" placeholder="Institution Name" aria-label="Last Institution Attended" required>
    </div>

    <div class="section-title">Details of Previous Exam. Passed</div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="text-center">
          <tr>
            <th rowspan="2">Name of Examination Passed</th>
            <th rowspan="2">Year</th>
            <th rowspan="2">Semester (if any)</th>
            <th rowspan="2">University or Board</th>
            <th rowspan="2">% Age of Marks</th>
            <th colspan="2">Subject</th>
          </tr>
          <tr>
            <th>Compulsory</th>
            <th>Optional</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td>
              <select name="last-exam" id="last-exam-detail" class="form-control" required>
                <option value="">-- Last Exam --</option>
                <option value="12th">Sr./Hr. Sec.(10+2)</option>
                <option value="B.A.">B.A.</option>
                <option value="B.Sc.">B.Sc.</option>
                <option value="B.Com">B.Com</option>
                <option value="BCA">BCA</option>
                <option value="BBA">BBA</option>
                <option value="B.A./B.Ed">B.A./B.Ed</option>
                <option value="B.Sc./B.Ed">B.Sc./B.Ed</option>
                <option value="M.A.">M.A.</option>
                <option value="M.Sc.">M.Sc.</option>
                <option value="M.Com">M.Com</option>
                <option value="Other">Other</option>
              </select>
            <input type="text" placeholder="Other" class="form-control d-none charonly" id="other" name="other" required>
            </td>
            <td><input type="text" name="exam_year" class="form-control numonly"required></td>
            <td><input type="tel" name="exam_sem" class="form-control numonly" maxlength="1" placeholder=""></td>
            <td><input type="text" name="exam_board" class="form-control"required></td>
            <td><input type="text" name="exam_percentage" class="form-control numonly"required></td>
            <td><input type="text" name="exam_compulsory" class="form-control"required></td>
            <td><input type="text" name="exam_optional" class="form-control"required></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="section-title">Subjects Offered:</div>
    <div class="row mb-3">
      <div class="col-md-4">
        <h6>Compulsory (For UG)</h6>
        <div class="checkbox-group">
          <label><input type="checkbox" name="subjects[]" value="Gen. Hindi"> Gen. Hindi</label><br>
          <label><input type="checkbox" name="subjects[]" value="Gen. English"> Gen. English</label><br>
          <label><input type="checkbox" name="subjects[]" value="Environmental Studies"> Environmental Studies</label><br>
          <label><input type="checkbox" name="subjects[]" value="Elementary Comp. App."> Elementary Comp. App.</label>
        </div>
      </div>
      <div class="col-md-4">
        <h6>Optional (For UG)</h6>
        <input type="text" name="ug_optional1" class="form-control mb-2" placeholder="1. Subject">
        <input type="text" name="ug_optional2" class="form-control mb-2" placeholder="2. Subject">
        <input type="text" name="ug_optional3" class="form-control" placeholder="3. Subject">
        <button class="btn btn-primary" id="add_ug">Add More +</button>
      </div>
      <div class="col-md-4">
        <h6>Optional (For PG)</h6>
        <input type="text" name="pg_optional1" class="form-control" placeholder="1. Subject">
        <input type="text" name="pg_optional2" class="form-control" placeholder="2. Subject">
        <input type="text" name="pg_optional3" class="form-control" placeholder="3. Subject">
          <button class="btn btn-primary" id="add_pg">Add More +</button>
      </div>
    </div>

    <div class="section-title">Preference of Interest:</div>
    <div class="checkbox-group mb-4">
      <label><input type="checkbox" name="interests[]" value="NCC"> NCC</label>
      <label><input type="checkbox" name="interests[]" value="NSS"> NSS</label>
      <label><input type="checkbox" name="interests[]" value="Rover/Ranger"> Rover/Ranger</label>
      <label><input type="checkbox" name="interests[]" value="Yuva Vikas Kendra"> Yuva Vikas Kendra / Human Rights Club / Women Cell</label>
    </div>

    <div class="form-footer text-center">
      <button type="button" id="submit" class="btn btn-primary">Submit Form</button>
      <button type="reset" class="btn btn-secondary ml-2">Reset Form</button>
     <a href="./admission-form/user_pdf.php?form_no=<?php echo $form_no; ?>" id="download" class="btn btn-success ml-2" style="display:none;">
  <i class="fa-solid fa-download"></i> Download Admission PDF
</a>

    </div>
     <div class="mb-3">
    <a href="/" class="btn btn-secondary back-home" aria-label="Back to Home">&larr; Back to Home</a>
  </div>
  </form>
</div>


<?php include './include/footer.php'; ?>
<script>
$('#submit').on('click', function(e) {
    e.preventDefault();
    var form = $('#admissionForm');
    $.ajax({
        url: './admission-form/submit.php',
        type: 'POST',
        data: form.serialize(), // This will include checked checkboxes
        dataType: 'json',
        success: function(response) {
            // ...existing code...
        }
    });
});
</script>