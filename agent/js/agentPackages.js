document.addEventListener('DOMContentLoaded', () => {
    const packagesGrid = document.getElementById('packagesGrid');

    async function fetchAgentPackages() 
    {
        try 
        {
            const response = await fetch('../api.php', 
            {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'getAgentPackages' })
            });

            const result = await response.json();
            if (result.status === 'success') {
                renderPackages(result.data);
            } else {
                packagesGrid.innerHTML = `<p>Error: ${result.data}</p>`;
            }
        } catch (error) {
            console.error("Fetch Error:", error);
        }
    }

    function renderPackages(packages) 
    {
        packagesGrid.innerHTML = '';
        if (!packages || packages.length === 0) 
        {
            packagesGrid.innerHTML = '<p>You have not created any packages yet.</p>';
            return;
        }

        packages.forEach(pkg => 
        {
            const card = document.createElement('div');
            card.className = 'package-card';
            card.innerHTML = `
                <img src="${pkg.Image || '../img/placeholder.png'}" class="package-img" alt="${pkg.Name}">
                <div class="package-info">
                    <h3>${pkg.Name}</h3>
                    <p class="package-price">ZAR ${parseFloat(pkg.Price).toLocaleString('en-ZA', {minimumFractionDigits: 2})}</p>
                    <div class="card-actions">
                        <button class="edit-btn" onclick="editPackage(${pkg.Package_id})">Edit</button>
                        <button class="delete-btn" onclick="deletePackage(${pkg.Package_id})">Delete</button>
                    </div>
                </div>
            `;
            packagesGrid.appendChild(card);
        });
    }

    window.editPackage = (id) => {
        window.location.href = `editPackage.php?id=${id}`;
    };

    window.deletePackage = async (id) => 
    {
        if(confirm("Are you sure you want to delete this package? This cannot be undone.")) 
        {
            try {
                const response = await fetch('../api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: 'deletePackage', package_id: id })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    alert("Package deleted successfully.");
                    location.reload(); 
                } else {
                    alert("Error: " + result.data);
                }
            } catch (error) {
                console.error("Delete Error:", error);
            }
        }
    };

    fetchAgentPackages();
});