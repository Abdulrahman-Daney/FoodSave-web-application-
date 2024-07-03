// Contact Us button action
function contactUs() {
  alert("Contact us at info@foodsave.com");
}

// Read More button action
function readMore() {
  window.location.href = "howwework.html";
}

// Form validation for Signup, Login, and Donation forms
document.addEventListener("DOMContentLoaded", function () {
  // Signup form validation
  const signupForm = document.querySelector('form[action="signup.php"]');
  if (signupForm) {
    signupForm.addEventListener("submit", function (event) {
      const restaurantName = document
        .getElementById("restaurantName")
        .value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const repeatPassword = document
        .getElementById("repeatPassword")
        .value.trim();

      if (
        restaurantName === "" ||
        email === "" ||
        password === "" ||
        repeatPassword === ""
      ) {
        alert("Please fill in all fields.");
        event.preventDefault();
      } else if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
      } else if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        event.preventDefault();
      } else if (password !== repeatPassword) {
        alert("Passwords do not match.");
        event.preventDefault();
      }
    });
  }

  // Login form validation
  const loginForm = document.querySelector('form[action="login.php"]');
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (email === "" || password === "") {
        alert("Please fill in all fields.");
        event.preventDefault();
      } else if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
      }
    });
  }

  // Donation form validation
  const donateForm = document.querySelector('form[action="donate.php"]');
  if (donateForm) {
    donateForm.addEventListener("submit", function (event) {
      const foodType = document.getElementById("foodType").value.trim();
      const quantity = document.getElementById("quantity").value.trim();
      const pickupTime = document.getElementById("pickupTime").value.trim();

      if (foodType === "" || quantity === "" || pickupTime === "") {
        alert("Please fill in all fields.");
        event.preventDefault();
      }
    });
  }
});

// Email validation function
function validateEmail(email) {
  const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return re.test(email);
}

function showPopup() {
  var pickupTime = document.getElementById("pickupTime").value;
  alert("We are sending a truck to your location at " + pickupTime);
  return true;
}
