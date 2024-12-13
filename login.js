document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Collect form data
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Retrieve existing users from localStorage
    let users = JSON.parse(localStorage.getItem("users")) || [];

    // Find the user with the matching username
    const user = users.find((user) => user.username === username);

    // Check if the user exists and the password matches
    if (user && user.password === btoa(password)) {
      // Successful login: Redirect to ListingsPage
      window.location.href = "ListingsPage.html";
    } else {
      // Show error message if login failed
      alert("Invalid username or password!");
    }
  });