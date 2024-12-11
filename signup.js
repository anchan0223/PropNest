document
  .getElementById("registrationForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Collect form data
    const firstName = document.getElementById("first_name").value;
    const lastName = document.getElementById("last_name").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    // Basic password hashing (for demonstration purposes, in real applications use bcrypt or another secure hashing method)
    const hashedPassword = btoa(password); // Using base64 encoding as a simple example (NOT secure in real-world apps!)

    // Prepare user data
    const userData = {
      first_name: firstName,
      last_name: lastName,
      email: email,
      username: username,
      password: hashedPassword, // Store hashed password
      role: role,
    };

    // Retrieve existing users from localStorage, or create a new array
    let users = JSON.parse(localStorage.getItem("users")) || [];

    // Check if username or email already exists
    const existingUser = users.find(
      (user) => user.username === username || user.email === email
    );
    if (existingUser) {
      alert("Username or email already exists!");
      return;
    }

    // Add new user to the array
    users.push(userData);

    // Save updated users list to localStorage
    localStorage.setItem("users", JSON.stringify(users));

    // Show success message and clear the form
    alert("Registration successful!");
    document.getElementById("registrationForm").reset();

    // Optionally, redirect to another page (e.g., the home page)
    window.location.href = "index.html";
  });
