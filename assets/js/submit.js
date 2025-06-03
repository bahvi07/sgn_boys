// Fee Calculation
// Example fee structure (should be replaced with real data)
/*
const feeStructure = {
  BA: { 1: 5000, 2: 5200, 3: 5400 },
  BSc: { 1: 6000, 2: 6200, 3: 6400 },
  BCom: { 1: 5500, 2: 5700, 3: 5900 },
  BCA: { 1: 7000, 2: 7200, 3: 7400 },
  BBA: { 1: 6500, 2: 6700, 3: 6900 },
  MA: { 1: 8000, 2: 8200 },
  MSc: { 1: 8500, 2: 8700 },
  MCom: { 1: 7500, 2: 7700 },
};
*/

const courseSelect = document.getElementById("courseSelect");
const yearSelect = document.getElementById("yearSelect");
const feeAmount = document.getElementById("feeAmount");
const qrImage = document.getElementById("qrImage");

function updateFeeAndQR() {
  // Only show QR, no fee calculation
  feeAmount.textContent = "";
  qrImage.src = '../images/qr.jpg';
}

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

if(courseSelect||yearSelect){
courseSelect.addEventListener("change", updateFeeAndQR);
yearSelect.addEventListener("change", updateFeeAndQR);

}

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
  console.log(form);
  // Custom validation
  // 1. Religion
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

  const formData = new FormData(form);

  try {
    const response = await fetch("admission-form/submit.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.text();
    try {
      const data = JSON.parse(result);
      if (data.success === true) {
        const paymentModal = new bootstrap.Modal(document.getElementById("payModal"), {
  focus: false,
}); 
        setTimeout(() => {
          paymentModal.show();
        }, 800);
      } else {
        throw new Error(data.message || "Unknown error occurred");
      }
    } catch (e) {
      console.error("Non-JSON response:", result);
      Swal.fire("Server Error", "Unexpected server response.", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire("Failed", "Failed to submit Form" + error.message, "error");
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