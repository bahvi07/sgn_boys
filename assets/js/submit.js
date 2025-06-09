const streamSelect = document.getElementById("stream");
const course = document.getElementById("course");
const other=  document.getElementById('other');
streamSelect.addEventListener("change", () => {
  const selectedStream = streamSelect.value;
  if(selectedStream==="Other"){
    other.classList.remove('d-none');
  }
  course.disabled = !selectedStream;
  Array.from(course.children).forEach((optgrp) => {
    if (optgrp.tagName === "OPTGROUP") {
      // Show if data-stream matches selected stream, otherwise hide
      optgrp.style.display =
        optgrp.getAttribute("data-stream") === selectedStream ? "" : "none";
    }
  });
  // Reset selected course value
  course.value = "";
 
});


// Shift Focus to Next filed 
const inputs = document.querySelectorAll('#admissionForm input, #admissionForm textarea');
inputs.forEach((input,index)=>{
input.addEventListener('keydown',(e)=>{
if(e.key==='Enter'){
  e.preventDefault();
  const nextInput=inputs[index+1];
  if(nextInput){
    nextInput.focus();
  }
}
});
});

// Form Submit using AJAX
const btn = document.getElementById("submit");
btn.addEventListener("click", async (e) => {
  e.preventDefault();
  const form = document.getElementById("admissionForm");
  //Custom validation
 //d 1. Religion
  if (!form.religion.value) {
    Swal.fire("Validation Error", "Please select your Religion.", "warning");
    return;

  }
  // 2. Category
  if (!form.category.value) {
    Swal.fire("Validation Error", "Please select your Category.", "warning");
    return;
  }
  // 3. Name of Candidate
  const name = form.candidate_name.value.trim();
  if (!name) {
    Swal.fire(
      "Validation Error",
      "Please enter the Name of Candidate.",
      "warning"
    );
   // form.candidate_name.focus();
    return;
  }
  // 4. Mobile No.
  const mobile = form.mobile.value.trim();
  if (!/^\d{10}$/.test(mobile)) {
    Swal.fire(
      "Validation Error",
      "Please enter a valid 10-digit Mobile No.",
      "warning"
    );
    //form.mobile.focus();
    return;
  }
  // 4a. Phone No. (must start with 6-9 and be 10 digits)
  const phone = form.parents_mobile.value.trim();
  const pattern = /^[6-9]\d{9}$/;
  if (phone && !pattern.test(phone)) {
    Swal.fire(
      "Validation Error",
      "Please enter a valid 10-digit Phone No. starting with 6-9.",
      "warning"
    );
   // form.phone.focus();
    return;
  }
if(mobile===phone){
  Swal.fire("Validation Error","Parent and Candidate Phone number should not be same","warning");
}
  const last_exam = form["last-exam"].value.trim();
  if (!last_exam) {
    Swal.fire(
      "Validation Error",
      "Please enter valid details for Last Passed Exam",
      "warning"
    );
    form["last-exam"].focus();
    return;
  }

  // HTML5 validation fallback
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  // Directly submit form data via AJAX (no modal, no payment screenshot)
  const formData = new FormData(form);

  // Disable submit button and show waiting text
  btn.disabled = true;
  btn.textContent = "Please wait...";

  try {
    const response = await fetch("admission-form/submit.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.text();
    //console.log("Raw server response:", result);
    try {
      const data = JSON.parse(result);
      if (data.success === true) {
        Swal.fire("Success", "Admission Successful", "success");
        // Show and update the download button with the correct form_no
        const formNo = form.form_no.value;
        const downloadBtn = document.getElementById('download');
        downloadBtn.href = `./admission-form/user_pdf.php?form_no=${encodeURIComponent(formNo)}`;
        downloadBtn.style.display = 'inline-block';

        // --- HIGHLIGHT: Add this block to handle redirect after download ---
        const backHomeBtn = document.querySelector('.back-home');
        if (backHomeBtn) {
          backHomeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
              icon: "info",
              title: "Redirecting...",
              text: "You will be redirected to the home page shortly.",
              timer: 2000,
              showConfirmButton: false
            });
            setTimeout(function () {
              window.location.href = "/";
            }, 2000);
          });
        }
        // --- END HIGHLIGHT ---
      } else {
        console.error("Server error message:", data.message);
        Swal.fire({
          title: "Error",
          html: `<div style="text-align:left"><b>Reason:</b> ${data.message || "Unknown error occurred"}</div>`,
          icon: "error"
        });
      }
    } catch (e) {
      console.error("Non-JSON response:", result);
      Swal.fire("Server Error", "Unexpected server response.<br><pre style='text-align:left'>" + result + "</pre>", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire("Failed", "Failed to submit Form" + error.message, "error");
  } finally {
    btn.disabled = false;
    btn.textContent = "Submit Form";
  }
});

const lastExamSelect = document.getElementById('last-exam-detail');
lastExamSelect.addEventListener('change', function() {
  if (this.value === 'Other') {
    other.classList.remove('d-none');
    other.required = true;
  } else {
    other.classList.add('d-none');
    other.required = false;
    other.value = '';
  }
});


// Add dynamic  Input for Subject
const add_ug = document.getElementById('add_ug');
let ug_count = 3;
const ug_container = add_ug.parentElement;
add_ug.addEventListener('click', (e) => {
  e.preventDefault();
  ug_count++;
  const input = document.createElement('input');
  input.type = 'text';
  input.name = `ug_optional${ug_count}`;
  input.className = 'form-control mb-2';
  input.placeholder = `${ug_count}. Subject`;
  ug_container.insertBefore(input, add_ug);
});

const add_pg = document.getElementById('add_pg');
let pg_count = 3;
const pg_container = add_pg.parentElement;
add_pg.addEventListener('click', (e) => {
  e.preventDefault();
  pg_count++;
  const input = document.createElement('input');
  input.type = 'text';
  input.name = `pg_optional${pg_count}`;
  input.className = 'form-control mb-2';
  input.placeholder = `${pg_count}. Subject`;
  pg_container.insertBefore(input, add_pg);
});

// Close modal
const close=document.getElementById('close');
if(close){
close.addEventListener('click', () => {
  setTimeout(() => {
    window.location.reload();
  }, 300); // gives time for modal to close properly
});

}

// Char only and Num only input restrictions with SweetAlert2 feedback
document.querySelectorAll('.charonly').forEach(function(input) {
  input.addEventListener('input', function(e) {
    const oldValue = this.value;
    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    if (oldValue !== this.value && typeof Swal !== "undefined") {
      Swal.fire({
        icon: "warning",
        title: "Only alphabets allowed",
        text: "Please enter letters and spaces only.",
        timer: 1200,
        showConfirmButton: false
      });
    }
  });
});
document.querySelectorAll('.numonly').forEach(function(input) {
  input.addEventListener('input', function(e) {
    
    const oldValue = this.value;
    this.value = this.value.replace(/[^0-9]/g, '');
    if (oldValue !== this.value && typeof Swal !== "undefined") {
      Swal.fire({
        icon: "warning",
        title: "Only numbers allowed",
        text: "Please enter digits only.",
        timer: 1200,
        showConfirmButton: false
      });
    }
  });
});

