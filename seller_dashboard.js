document.addEventListener('DOMContentLoaded', () => {
    // Fetch the properties from the server (e.g., via API or PHP)
    fetch('/api/get_properties.php')
      .then(response => response.json())
      .then(properties => {
        const propertiesGrid = document.getElementById('properties-grid');
  
        if (properties.length === 0) {
          const noPropertiesMessage = document.createElement('p');
          noPropertiesMessage.textContent = 'You have no properties listed. Click the + button to add a property.';
          propertiesGrid.appendChild(noPropertiesMessage);
        }
  
        properties.forEach(property => {
          const propertyCard = document.createElement('div');
          propertyCard.className = 'property-card';
          propertyCard.innerHTML = `
            <img src="${property.image_url}" alt="${property.location}" class="property-image" />
            <h3>${property.location}</h3>
            <p class="price">${property.price}</p>
            <p class="location">${property.location}</p>
            <p>Bedrooms: ${property.bedrooms}</p>
            <p>Bathrooms: ${property.bathrooms}</p>
            <p>Parking: ${property.parking ? 'Available' : 'Not Available'}</p>
            <p>Garden: ${property.garden ? 'Yes' : 'No'}</p>
            <p>Proximity to Facilities: ${property.facilities}</p>
            <button class="view-button" onclick="viewPropertyDetails(${property.id})">View Details</button>
            <button class="delete-button" onclick="deleteProperty(${property.id})">Delete</button>
          `;
          propertiesGrid.appendChild(propertyCard);
        });
      })
      .catch(error => console.error('Error fetching properties:', error));
  });
  
  function viewPropertyDetails(propertyId) {
    window.location.href = `property_detail.html?id=${propertyId}`;
  }
  
  function deleteProperty(propertyId) {
    if (confirm('Are you sure you want to delete this property?')) {
      fetch(`/api/delete_property.php?id=${propertyId}`, { method: 'DELETE' })
        .then(response => response.json())
        .then(data => {
          alert('Property deleted successfully!');
          window.location.reload();
        })
        .catch(error => console.error('Error deleting property:', error));
    }
  }
  
  