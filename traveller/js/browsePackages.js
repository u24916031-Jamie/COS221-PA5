async function fetchPackages() {
    try {
        const response = await fetch('../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: 'searchPackages',
                search: document.getElementById('locationSearch').value || '',
                sort: document.getElementById('sortBy').value || 'price',
                order: document.getElementById('sortOrder').value || 'ASC'
            })
        });

        const textResponse = await response.text();
        
        if (textResponse.includes("<br />") || textResponse.includes("<b>")) {
            const grid = document.getElementById('packagesGrid');
            grid.innerHTML = `<div style="color:red; padding:20px; background:#fff0f0; border:1px solid red; width:100%;">
                <h3>PHP Backend Error Encountered:</h3>
                <code>${textResponse}</code>
            </div>`;
            return;
        }

        const result = JSON.parse(textResponse);

        if (result.status === 'success') {
            renderPackages(result.data);
        } else {
            console.error('API Error:', result.data);
            document.getElementById('packagesGrid').innerHTML = `<p>Error: ${result.data}</p>`;
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        document.getElementById('packagesGrid').innerHTML = '<p>Failed to connect to server.</p>';
    }
}

function renderPackages(data) {
    const grid = document.getElementById('packagesGrid');
    grid.innerHTML = '';

    if (!data || data.length === 0) {
        grid.innerHTML = '<p style="text-align:center; width:100%; color: white;">No packages found.</p>';
        return;
    }

    data.forEach(pkg => {
        const price = pkg.Price ? parseFloat(pkg.Price) : 0;
        const rating = pkg.Rating ? parseFloat(pkg.Rating).toFixed(1) : '0.0';
        const image = pkg.Image || '../img/tripBack.avif';
        const name = pkg.Name || pkg.name || 'Unnamed Package';
        const desc = pkg.Description || pkg.description || '';
        const agencyName = pkg.Agency_name || 'Unknown Agency';
        const agencyId = pkg.Agency_id; 
        const packageId = pkg.Package_id;

        const card = document.createElement('div');
        card.className = 'package-card';
        card.innerHTML = `
            <img src="${image}" class="package-img" alt="${name}">
            <div class="package-info">
                <h3 style="margin-top: 0; font-size: 16px; color: #555;">${agencyName}</h3>
                <span class="rating-badge">★ ${rating}</span>
                <h3 style="font-size: 18px; margin: 10px 0;">${name}</h3>
                <p class="package-price">ZAR ${price.toLocaleString('en-ZA', {minimumFractionDigits: 2})}</p>
                <p class="package-desc">${desc.substring(0, 100)}...</p>
                
                <div class="card-actions">
                    <button class="view-agency-btn" onclick="window.location.href='agencyDetails.php?id=${agencyId}'">
                        View Agency
                    </button>
                    <button class="book-btn" onclick="window.location.href='packageDetails.php?id=${packageId}'">
                        View Details
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

let searchTimeout;
document.getElementById('locationSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fetchPackages, 300);
});

document.getElementById('searchBtn').addEventListener('click', function(e) {
    e.preventDefault();
    fetchPackages();
});

document.getElementById('sortBy').addEventListener('change', fetchPackages);
document.getElementById('sortOrder').addEventListener('change', fetchPackages);

fetchPackages();
