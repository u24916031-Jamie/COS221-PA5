document.addEventListener('DOMContentLoaded', () => {
	const grid = document.getElementById('bookingsGrid');


	async function fetchMyBookings() {
		const response = await fetch('../api.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ type: 'getMyBookings' })
		});
		const result = await response.json();
		if (result.status === 'success') {
			renderBookings(result.data);
		}
	}

	function renderBookings(data) {
		grid.innerHTML = '';
		if (!data || data.length === 0) {
			grid.innerHTML = '<p style="color:white; font-size: 18px;">No active bookings.</p>';
			return;
		}

		const today = new Date();
		today.setHours(0, 0, 0, 0);

		data.forEach(pkg => {
			const endDate = new Date(pkg.end_date);
			const canReview = today > endDate;

			const card = document.createElement('div');
			card.className = 'package-card';
			card.innerHTML = `
                <img src="${pkg.Image || '../img/tripBack.avif'}" class="package-img">
                <div class="package-info">
                    <h3>${pkg.Name}</h3>
                    <p style="color: #1F80FF; font-weight: bold; font-size: 18px; margin: 5px 0;">
                        ZAR ${parseFloat(pkg.TotalPrice).toLocaleString('en-ZA', { minimumFractionDigits: 2 })} 
                        <span style="color: #666; font-size: 12px; font-weight: normal;">(${pkg.Guests} Guests)</span>
                    </p>
                    <p style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">Dates: ${pkg.start_date || 'N/A'} to ${pkg.end_date || 'N/A'}</p>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button class="book-btn" onclick="window.location.href='viewBookedPackage.php?id=${pkg.Package_id}'" style="flex: 1;">View</button>
                    </div>
                </div>
            `;



			grid.appendChild(card);
		});
	}

	fetchMyBookings();
});