document.addEventListener('DOMContentLoaded', () => {
    // Fetch properties from the server when the page loads
    fetchProperties();

    // Add event listener to the "+" button
    document.getElementById('add-property').addEventListener('click', () => {
        window.location.href = 'add-property.php'; 
    });
});

function fetchProperties() {
    fetch('fetch_properties.php') 
        .then(response => response.json())
        .then(data => {
            const propertyCardsContainer = document.getElementById('property-cards');
            propertyCardsContainer.innerHTML = '';  
            if (data.length === 0) {
                propertyCardsContainer.innerHTML = '<p>No properties added. Click "+" to add a new property.</p>';
            } else {
                data.forEach(property => {
                    const card = document.createElement('div');
                    card.classList.add('card');
                    card.innerHTML = `
                        <h3>${property.location}</h3>
                        <p>Price: $${property.price}</p>
                        <p>Bedrooms: ${property.bedrooms}</p>
                        <p>Bathrooms: ${property.bathrooms}</p>
                        <img src="${property.image_url}" alt="Property Image" style="width:100px; height:auto;">
                        <button onclick="viewProperty(${property.id})">View Details</button>
                        <button onclick="editProperty(${property.id})">Edit</button>
                        <button onclick="deleteProperty(${property.id})">Delete</button>
                    `;
                    propertyCardsContainer.appendChild(card);
                });
            }
        })
        .catch(error => console.error('Error fetching properties:', error));
}

function viewProperty(propertyId) {
    window.location.href = `property-details.php?id=${propertyId}`;  // Redirect to the property details page
}

function editProperty(propertyId) {
    window.location.href = `edit-property.php?id=${propertyId}`;  // Redirect to the Edit Property page
}

function deleteProperty(propertyId) {
    fetch(`delete_property.php?id=${propertyId}`, {
        method: 'GET',  
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Property deleted successfully');
            // Reload the page to remove the deleted property
            location.reload(); 
        } else {
            alert('Failed to delete property: ' + data.error);
        }
    })
    .catch(error => console.error('Error deleting property:', error));
}
