document.getElementById('package-images-single').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('image-preview-container-single');
    previewContainer.innerHTML = ''; 
    
    const files = Array.from(event.target.files).slice(0, 10); 
    
    files.forEach(file => {
        if (!file.type.startsWith('image/')) return; 
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image');
            img.onclick = () => img.remove();
            previewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});

document.getElementById('add-service-btn').addEventListener('click', function() 
{
    const container = document.getElementById('services-container');
    
    if (container.children.length >= 10) 
        {
        alert("Maximum of 10 services allowed.");
        return;
    }
    
    const index = container.children.length;
    
    const block = document.createElement('div');
    block.className = 'service-block'; 
    
    block.innerHTML = `
        <select name="services[${index}][type]" class="service-type" required>
            <option value="" disabled selected>Select Service Category</option>
            <option value="accommodation">Accommodation</option>
            <option value="flight">Flight</option>
            <option value="restaurant">Restaurant</option>
            <option value="attraction">Attraction</option>
            <option value="destination">Destination</option>
        </select>
        <div class="dynamic-fields"></div>
        <button type="button" class="remove-service">Remove Service</button>
    `;
    
    container.appendChild(block);
    
    block.querySelector('.service-type').addEventListener('change', function(e) {
        const fieldsDiv = block.querySelector('.dynamic-fields');
        const type = e.target.value;
        
        let specificHTML = '';
        
        if(type === 'flight') {
            specificHTML = `<input type="text" name="services[${index}][flight_number]" placeholder="Flight Number (e.g. FA100)" required>`;
        } else if(type === 'destination') {
            specificHTML = `<textarea name="services[${index}][description]" placeholder="Destination Overview" required></textarea>`;
        } else {
            const capitalizedType = type.charAt(0).toUpperCase() + type.slice(1);
            specificHTML = `<input type="text" name="services[${index}][name]" placeholder="${capitalizedType} Name" required>`;
        }
        
        fieldsDiv.innerHTML = `
            ${specificHTML}
            <input type="text" name="services[${index}][street]" placeholder="Street Address">
            <div class="address-grid">
                <input type="text" name="services[${index}][city]" placeholder="City">
                <input type="text" name="services[${index}][code]" placeholder="Postal Code">
            </div>
        `;
    });
    
    block.querySelector('.remove-service').addEventListener('click', function() {
        block.remove();
    });
});

document.getElementById('createPackageForm').addEventListener('submit', async function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);

    try {
        const response = await fetch('../api.php', {
            method: 'POST',
            body: formData
        });

        const textResponse = await response.text();
        if (!textResponse.trim().startsWith('{')) {
            console.error("Server crashed:", textResponse);
            alert("A server error occurred. Check the console.");
            return;
        }

        const result = JSON.parse(textResponse);
        if (result.status === 'success') {
            alert('Package Created Successfully!');
            window.location.href = 'agentPackages.php'; 
        } else {
            alert('Error: ' + (result.data || 'Unknown error'));
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        alert('Failed to communicate with the server.');
    }
});
