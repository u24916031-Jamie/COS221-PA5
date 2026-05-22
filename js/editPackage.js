document.addEventListener('DOMContentLoaded', () => 
    {
    const urlParams = new URLSearchParams(window.location.search);
    const packageId = urlParams.get('id');

    if (!packageId) {
        alert("No package specified.");
        window.location.href = 'agentPackages.php';
        return;
    }

    const servicesContainer = document.getElementById('services-container');
    const previewContainer = document.getElementById('image-preview-container-single');

    async function loadPackageData() 
    {
        try {
            const response = await fetch('../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'viewPackage', package_id: packageId })
            });

            const result = await response.json();
            if (result.status === 'success' && result.data.packageInfo) {
                const pkg = result.data.packageInfo;
                document.getElementById('editPackageId').value = packageId;
                document.getElementById('editName').value = pkg.name;
                document.getElementById('editPrice').value = pkg.price;
                document.getElementById('editDescription').value = pkg.description;

                if (result.data.images) 
                    {
                    result.data.images.forEach(img => renderImagePreview(img, true));
                }
                if (result.data.services) 
                    {
                    result.data.services.forEach(srv => addServiceBlock(srv));
                }
            }
        } catch (err) { console.error(err); }
    }

    function renderImagePreview(src, isExisting = false) 
    {
        const wrapper = document.createElement('div');
        wrapper.className = 'img-wrapper';

        const img = document.createElement('img');
        img.src = src;
        img.className = 'preview-image';

        const btn = document.createElement('button');
        btn.innerHTML = '&times;';
        btn.className = 'remove-btn';
        btn.type = 'button';
        btn.onclick = () => wrapper.remove();

        wrapper.appendChild(img);
        wrapper.appendChild(btn);

        if (isExisting) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'existing_images[]';
            hidden.value = src;
            wrapper.appendChild(hidden);
        }
        previewContainer.appendChild(wrapper);
    }

    function addServiceBlock(existingData = null) 
    {
        if (servicesContainer.children.length >= 10) return alert("Max 10 services.");

        const index = servicesContainer.children.length;
        const block = document.createElement('div');
        block.className = 'service-block';
        
        block.innerHTML = `
            <select name="services[${index}][type]" class="service-type" required>
                <option value="" disabled ${!existingData ? 'selected' : ''}>Category</option>
                <option value="accommodation" ${existingData?.type === 'accommodation' ? 'selected' : ''}>Accommodation</option>
                <option value="flight" ${existingData?.type === 'flight' ? 'selected' : ''}>Flight</option>
                <option value="restaurant" ${existingData?.type === 'restaurant' ? 'selected' : ''}>Restaurant</option>
                <option value="attraction" ${existingData?.type === 'attraction' ? 'selected' : ''}>Attraction</option>
                <option value="destination" ${existingData?.type === 'destination' ? 'selected' : ''}>Destination</option>
            </select>
            <div class="dynamic-fields"></div>
            <button type="button" class="remove-service">Remove</button>
        `;

        servicesContainer.appendChild(block);
        
        const typeSelect = block.querySelector('.service-type');
        typeSelect.addEventListener('change', (e) => renderFields(block, e.target.value, index));
        
        if (existingData) renderFields(block, existingData.type, index, existingData);
        block.querySelector('.remove-service').onclick = () => block.remove();
    }

    function renderFields(block, type, index, data = {}) 
    {
        const fieldsDiv = block.querySelector('.dynamic-fields');
        const nameVal = data.name || data.flight_number || data.description || '';
        
        let html = '';
        if(type === 'flight') html = `<input type="text" name="services[${index}][flight_number]" value="${nameVal}" placeholder="Flight #" required>`;
        else if(type === 'destination') html = `<textarea name="services[${index}][description]" placeholder="Overview" required>${nameVal}</textarea>`;
        else html = `<input type="text" name="services[${index}][name]" value="${nameVal}" placeholder="Name" required>`;
        
        fieldsDiv.innerHTML = `
            ${html}
            <input type="text" name="services[${index}][street]" value="${data.street || ''}" placeholder="Street">
            <input type="text" name="services[${index}][city]" value="${data.city || ''}" placeholder="City">
            <input type="text" name="services[${index}][code]" value="${data.code || ''}" placeholder="Code">
        `;
    }

    document.getElementById('package-images-single').onchange = (e) => 
        {
        previewContainer.innerHTML = '';
        Array.from(e.target.files).forEach(f => {
            const reader = new FileReader();
            reader.onload = (e) => renderImagePreview(e.target.result);
            reader.readAsDataURL(f);
        });
    };

    document.getElementById('add-service-btn').onclick = () => addServiceBlock();
    loadPackageData();
});

document.getElementById('editPackageForm').addEventListener('submit', async (e) => 
    {
    e.preventDefault(); 
    
    const formData = new FormData(e.target);

    try {
        const response = await fetch('../api.php', {
            method: 'POST',
            body: formData 
        });

        const textResponse = await response.text();
        if (!textResponse.trim().startsWith('{')) {
            console.error("Server error response:", textResponse);
            alert("A server error occurred. Check the console for details.");
            return;
        }

        const result = JSON.parse(textResponse);
        if (result.status === 'success') 
            {
            alert("Package updated successfully!");
            window.location.href = 'agentPackages.php';
        } else 
            {
            alert("Error: " + (result.data || "Failed to update package."));
        }
    } 
    catch (err) 
    {
        console.error("Fetch error:", err);
        alert("Connection error: Could not reach the server.");
    }
});