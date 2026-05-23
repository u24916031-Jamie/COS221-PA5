document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('bookingsTableBody');

    async function fetchAgencyBookings() {
        const response = await fetch('../api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'getAgencyBookings' })
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            tableBody.innerHTML = '';
            result.data.forEach(booking => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="padding: 10px;">${booking.Fname} ${booking.Lname}</td>
                    <td style="padding: 10px;">${booking.Email}</td>
                    <td style="padding: 10px;">${booking.PackageName}</td>
                    
                    <td style="padding: 10px; font-weight: bold; color: #1F80FF;">ZAR ${parseFloat(booking.TotalPrice).toLocaleString('en-ZA', {minimumFractionDigits: 2})}</td>
                    <td style="padding: 10px;">${booking.start_date || 'N/A'} to ${booking.end_date || 'N/A'}</td>
                    <td style="padding: 10px;">${booking.IsGroupTrip}</td>
                    <td style="padding: 10px;">${booking.Guests}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    }
    fetchAgencyBookings();
});