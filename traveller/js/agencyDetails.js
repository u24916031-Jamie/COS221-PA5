document.addEventListener('DOMContentLoaded', () => {
	const urlParams = new URLSearchParams(window.location.search);
	const agencyId = urlParams.get('id');
	const grid = document.getElementById('agencyPackagesGrid');

	async function fetchAgencyData() {
		try {
			const response = await fetch('../api.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ type: 'viewTravelAgency', agency_id: agencyId })
			});
			//console.log(await response.text())
			const result = await response.json();

			if (result.status === 'success') {
				const info = result.data.info;
				const packages = result.data.packages;
				console.log(info);
				console.log(packages);

				document.getElementById('agencyName').textContent = info.Agency_name || "Unknown Agency";
				document.getElementById('agencyContact').textContent = `Agent Contact: ${info.Contact_Fname || ''} ${info.contact_lname || ''}`;
				document.getElementById('agencyEmail').textContent = `Email: ${info.Email || 'N/A'}`;

				grid.innerHTML = '';
				if (!packages || packages.length === 0) {
					grid.innerHTML = '<p style="text-align:center; width:100%; color: white; font-size: 18px;">This agency has no packages yet.</p>';
					return;
				}

				packages.forEach(pkg => {
					const price = pkg.Price ? parseFloat(pkg.Price) : 0;
					const image = pkg.Image || '../img/tripBack.avif';
					const name = pkg.Name || 'Unnamed Package';
					const desc = pkg.Description || 'No description provided.';
					const packageId = pkg.Package_id;

					const card = document.createElement('div');
					card.className = 'package-card';
					card.innerHTML = `
                        <img src="${image}" class="package-img" alt="${name}">
                        <div class="package-info">
                            <h3 style="margin-top: 0; font-size: 22px;">${name}</h3>
                            <p class="package-price">ZAR ${price.toLocaleString('en-ZA', { minimumFractionDigits: 2 })}</p>
                            <p class="package-desc">${desc.substring(0, 100)}...</p>
                            <button class="book-btn" onclick="window.location.href='packageDetails.php?id=${packageId}'">
                                View Details
                            </button>
                        </div>
                    `;
					grid.appendChild(card);
				});
			}
		} catch (error) {
			console.error("Fetch Error:", error);
		}
	}
	fetchAgencyData();
});