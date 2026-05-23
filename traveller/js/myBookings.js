document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('bookingsGrid');
    const modal = document.getElementById('reviewModal');

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
            
            const reviewBtnHtml = canReview 
                ? `<button class="book-btn review-btn" style="flex: 1; background: #4CAF50;">Review</button>`
                : `<button class="book-btn" style="flex: 1; background: #ccc; cursor: not-allowed;" disabled title="Available after ${pkg.end_date}">Review Later</button>`;

            const card = document.createElement('div');
            card.className = 'package-card';
            card.innerHTML = `
                <img src="${pkg.Image || '../img/tripBack.avif'}" class="package-img">
                <div class="package-info">
                    <h3>${pkg.Name}</h3>
                    <p style="color: #1F80FF; font-weight: bold; font-size: 18px; margin: 5px 0;">
                        ZAR ${parseFloat(pkg.TotalPrice).toLocaleString('en-ZA', {minimumFractionDigits: 2})} 
                        <span style="color: #666; font-size: 12px; font-weight: normal;">(${pkg.Guests} Guests)</span>
                    </p>
                    <p style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">Dates: ${pkg.start_date || 'N/A'} to ${pkg.end_date || 'N/A'}</p>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button class="book-btn" onclick="window.location.href='viewBookedPackage.php?id=${pkg.Package_id}'" style="flex: 1;">View</button>
                        ${reviewBtnHtml}
                    </div>
                </div>
            `;

            if (canReview) {
                card.querySelector('.review-btn').addEventListener('click', () => {
                    document.getElementById('reviewTargetId').value = pkg.Target_id;
                    document.getElementById('reviewDate').value = new Date().toISOString().split('T')[0];
                    modal.classList.remove('hidden');
                });
            }
            
            grid.appendChild(card);
        });
    }

    document.getElementById('reviewForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const response = await fetch('../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(Object.fromEntries(new FormData(e.target)))
        });
        const result = await response.json();
        if (result.status === 'success') {
            modal.classList.add('hidden');
            alert('Review Submitted!');
            fetchMyBookings();
        } else {
            alert('Error: ' + result.data);
        }
    });
    
    document.getElementById('closeModal').onclick = () => modal.classList.add('hidden');
    fetchMyBookings();
});