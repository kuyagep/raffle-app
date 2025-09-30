<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms & Privacy | Teacher’s Day</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .scroll-box {
      height: 400px;
      overflow-y: scroll;
      border: 1px solid #ddd;
      padding: 20px;
      background: #fff;
    }
    .btn-deped {
      background-color: #003399;
      border-color: #003399;
      color: #fff;
    }
    .btn-deped:hover {
      background-color: #002b73;
      border-color: #002b73;
      color: #fff;
    }
    .bg-theme {
            background-color: #003399;
        }
  </style>
</head>
<body class="bg-theme">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card shadow-lg">
          <div class="card-body">
            <h2 class="text-center mb-4">Terms and Conditions & Privacy Policy</h2>

            <!-- Scrollable Text -->
            <div class="scroll-box mb-3">
              <h4>Terms and Conditions</h4>
              <p>
                Welcome to the Teacher’s Day Online Registration and QR Code Attendance System (“System”), operated by the Division of Davao del Sur.
                By registering and using this system, you agree to the following Terms and Conditions:
              </p>
              <ul>
                <li><b>Eligibility:</b> This system is for teachers and authorized participants only.</li>
                <li><b>Use of the System:</b> You agree to use this only for event registration and QR code attendance.</li>
                <li><b>Account Responsibility:</b> You are responsible for your login details.</li>
                <li><b>Event Participation:</b> QR codes are unique and must not be shared or duplicated.</li>
                <li><b>Limitation of Liability:</b> The Division shall not be liable for any issues arising from use of this system.</li>
              </ul>

              <h4>Privacy Policy</h4>
              <p>
                The Division of Davao del Sur is committed to protecting your personal data in compliance with the Data Privacy Act of 2012 (RA 10173).
              </p>
              <ul>
                <li><b>Information We Collect:</b> Name, Email, Contact Info, School, Attendance records.</li>
                <li><b>Purpose:</b> Registration, QR code generation, attendance tracking, and reports.</li>
                <li><b>Data Sharing:</b> Accessible only by authorized personnel; not shared without consent.</li>
                <li><b>Data Protection:</b> Safeguards are in place to prevent unauthorized access.</li>
                <li><b>Your Rights:</b> You may request access, correction, or deletion of your data as permitted by law.</li>
              </ul>

              <p>
                For concerns, please contact the Division of Davao del Sur Data Protection Officer (DPO).
              </p>
            </div>

            <!-- Agreement Checkbox -->
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" id="agreeCheck" required>
              <label class="form-check-label" for="agreeCheck">
                I have read and agree to the Terms & Conditions and Privacy Policy.
              </label>
            </div>

            <!-- Button to Registration -->
            <div class="text-center">
              <a href="/registration" id="proceedBtn" class="btn btn-deped btn-lg disabled">Proceed to Registration</a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Enable the button only if checkbox is checked
    const agreeCheck = document.getElementById('agreeCheck');
    const proceedBtn = document.getElementById('proceedBtn');

    agreeCheck.addEventListener('change', function() {
      if (this.checked) {
        proceedBtn.classList.remove('disabled');
      } else {
        proceedBtn.classList.add('disabled');
      }
    });
  </script>

</body>
</html>
