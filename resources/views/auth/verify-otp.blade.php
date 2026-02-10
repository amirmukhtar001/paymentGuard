@extends('layouts.auth')

@push('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endpush

@push('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endpush

@push('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endpush

@push('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script src="{{asset('assets/js/pages-auth-two-steps.js')}}"></script>
<script>
  const resendContainer = document.getElementById('resendContainer');
  const resendLink = document.getElementById('resendLink');
  const countdownTimer = document.getElementById('countdownTimer');

  // Set the initial countdown duration in seconds
  let countdownDuration = 5;

  // Function to update the countdown timer display
  function updateCountdownDisplay() {
    const minutes = Math.floor(countdownDuration / 60);
    const seconds = countdownDuration % 60;
    countdownTimer.textContent = ` (${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')})`;
  }

  // Function to start the resend timer
  function startResendTimer() {
    // Disable the link
    resendLink.style.pointerEvents = 'none';

    // Reset the countdown duration to 5 seconds
    countdownDuration = 600;

    // Update the countdown display initially
    updateCountdownDisplay();

    // Set an interval to update the countdown every second
    const countdownInterval = setInterval(() => {
      countdownDuration--;

      // Update the countdown display
      updateCountdownDisplay();

      if (countdownDuration <= 0) {
        // Re-enable the link when the countdown reaches zero
        clearInterval(countdownInterval);
        resendLink.style.pointerEvents = 'auto';
        countdownTimer.textContent = ''; // Optional: Remove the countdown display
      }
    }, 1000);

    // Add any additional logic for triggering the resend action here
  }

  // Start the resend timer immediately when the page loads
  startResendTimer();


  $(document).ready(function() {
    // Handle the click event on the Resend link
    $('#resendLink').on('click', function() {
      // Make an AJAX request to the /resend endpoint
      $.ajax({
        type: 'POST',
        url: '{{ route("otp.verify.resend") }}',
        data: {
          _token: '{{ csrf_token() }}'
        }, // Include CSRF token if you're using it
        success: function(response) {
          // Handle the success response
          console.log(response);
          // You can update the UI or perform additional actions here
        },
        error: function(xhr, status, error) {
          // Handle the error response
          console.error(xhr.responseText);
          // You can show an error message or perform additional actions here
        }
      });
    });

    // verify Otp 
    $('#verificationForm').submit(function(event) {
      event.preventDefault();

      // Combine the OTP values from the input fields
      const otpValue = $('.numeral-mask').map(function() {
        return $(this).val();
      }).get().join('');

      // Check if the OTP value is empty
      if (otpValue.trim() === '') {
        // Show an error message or handle it as needed
        $('#errorMessage').text('Please enter OTP');
        return; // Stop further processing
      } else if (otpValue.length < 4) {
        // Show an error message for less than 4 digits
        $('#errorMessage').text('OTP must be 4 digits');
        return; // Stop further processing
      }
      // Set the combined OTP value to the hidden field
      $('[name="otp"]').val(otpValue);


      // Make an AJAX request to submit the form
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response?.success === true) {
            window.location.href = response?.data?.redirect ?? window;
          } else {
            $('#errorMessage').text(response?.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle the error response
          console.error(xhr.responseText);
          $('#errorMessage').text(xhr.responseText?.message);
          // You can show an error message or perform additional actions here
        }
      });
    });
  });
</script>
@endpush


@section('content')
<div class="authentication-wrapper authentication-basic px-4">
  <div class="authentication-inner py-4">
    <!--  Two Steps Verification -->
    <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">

            </span>
            <span class="app-brand-text demo h3 mb-0 fw-bold">{{ config('variables.templateName') }}</span>
          </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-2">Two Step Verification 💬</h4>
        <p class="text-start mb-4">
          We sent a verification code to your mobile. Enter the code from the mobile in the field below.
          <span class="fw-bold d-block mt-2">{{ str_repeat('*', max(strlen(auth()?->user()?->contact_number ?? '') - 3, 0)) . substr(auth()?->user()?->contact_number ?? '', -3) }}</span>
        </p>
        <p class="mb-0 fw-semibold">Type your 4 digit security code</p>
        <form id="verificationForm" action="{{route('otp.verify.submit')}}" method="POST">
          <div class="mb-3">
            <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
              <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
              <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
              <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
              <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
            </div>
            <!-- Create a hidden field which is combined by 3 fields above -->
            <input type="hidden" name="otp" />
          </div>
          <button class="btn btn-primary d-grid w-100 mb-3">
            Verify my account
          </button>
          <span class="text-center text-danger w-100 d-block" id="errorMessage"></span>
          <div class="text-center" id="resendContainer">
            Didn't get the code?
            <a href="javascript:void(0);" id="resendLink" onclick="startResendTimer()">
              Resend
            </a>
            <span id="countdownTimer"></span>
          </div>
        </form>
      </div>
    </div>
    <!-- / Two Steps Verification -->
  </div>
</div>
@endsection