document.getElementById('addPropertyForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
  
    // Create a new FormData object to collect form data
    const formData = new FormData(this);
  
    // Send the form data to the back-end (e.g., via a POST request to add_property.php)
    fetch('/api/add_property.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json()) // Parse the JSON response from the server
    .then(data => {
      if (data.success) {
        alert('Property added successfully!');
        window.location.href = 'seller_dashboard.html'; // Redirect to the seller dashboard after successful submission
      } else {
        alert('Failed to add property. Please try again.');
      }
    })
    .catch(error => {
      console.error('Error adding property:', error);
      alert('An error occurred while adding the property.');
    });
  });
  
  