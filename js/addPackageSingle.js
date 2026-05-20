// Multiple image selector and preview
document.getElementById('package-images-single').addEventListener('change', function(event)
{
  const previewContainer = document.getElementById('image-preview-container-single');
  previewContainer.innerHTML = ''; 
  const files = event.target.files;
  Array.from(files).forEach(file =>
  {
    if (!file.type.startsWith('image/')) return; 
    const reader = new FileReader();
    reader.onload = function(e)
    {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.classList.add('preview-image');
      previewContainer.appendChild(img);
    }
    reader.readAsDataURL(file);
  });
});

// Add as many services as you want
document.getElementById('add-service-btn').addEventListener('click', function()
{
  const container = document.getElementById('services-container');
  const index = container.children.length;
  
  const block = document.createElement('div');
  block.style.cssText = 'border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.1); padding: 20px; border-radius: 15px; margin: 15px auto; width: 85%; box-sizing: border-box;';
  
  block.innerHTML = `
    <select name="services[${index}][type]" class="service-type" style="width: 100%; margin: 0 auto 15px auto;" required>
      <option value="" disabled selected>Select Service Category</option>
      <option value="accommodation">Accommodation</option>
      <option value="flight">Flight</option>
      <option value="restaurant">Restaurant</option>
      <option value="attraction">Attraction</option>
      <option value="destination">Destination</option>
    </select>
    <div class="dynamic-fields" style="width: 100%;"></div>
    <button type="button" class="remove-service" style="background: rgba(255, 50, 50, 0.7); color: white; border: none; padding: 8px 15px; border-radius: 10px; cursor: pointer; margin-top: 15px; width: 100%;">Remove Service</button>
  `;
  
  container.appendChild(block);
  
  block.querySelector('.service-type').addEventListener('change', function(e)
  {
    const fieldsDiv = block.querySelector('.dynamic-fields');
    const type = e.target.value;
    
    let specificHTML = '';
    
    if(type === 'flight')
    {
       specificHTML = `<input type="text" name="services[${index}][flight_number]" placeholder="Flight Number (e.g. FA100)" style="width: 100%; margin: 10px 0;" required>`;
    }
    else if(type === 'destination')
    {
       specificHTML = `<textarea name="services[${index}][description]" placeholder="Destination Overview" style="width: 100%; margin: 10px 0; height: 60px;" required></textarea>`;
    }
    else
    {
       const capitalizedType = type.charAt(0).toUpperCase() + type.slice(1);
       specificHTML = `<input type="text" name="services[${index}][name]" placeholder="${capitalizedType} Name" style="width: 100%; margin: 10px 0;" required>`;
    }
    
    fieldsDiv.innerHTML = `
      ${specificHTML}
      <input type="text" name="services[${index}][street]" placeholder="Street Address" style="width: 100%; margin: 10px 0;">
      <div style="display: flex; gap: 10px; margin: 10px 0;">
        <input type="text" name="services[${index}][city]" placeholder="City" style="width: 50%; margin: 0;">
        <input type="text" name="services[${index}][code]" placeholder="Postal Code" style="width: 50%; margin: 0;">
      </div>
    `;
  });
  
  block.querySelector('.remove-service').addEventListener('click', function()
  {
    block.remove();
  });
});

document.getElementById('createPackageForm').addEventListener('submit', async function(e) 
{
    e.preventDefault(); 
    const formData = new FormData(this);

    try {
        const response = await fetch('../api.php', 
          {
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
        if (result.status === 'success') 
          {
            alert('Package Created Successfully!');
            window.location.reload(); 
        } else {
            alert('Error: ' + (result.data || 'Unknown error'));
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        alert('Failed to communicate with the server.');
    }
});
