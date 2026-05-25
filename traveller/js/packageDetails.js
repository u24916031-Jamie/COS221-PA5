document.addEventListener('DOMContentLoaded', () => {
	const packageId = new URLSearchParams(window.location.search).get('id');
	const modal = document.getElementById('bookingModal');
	const bookBtn = document.getElementById('bookBtn');
	const closeBtn = document.getElementById('closeModal');
	const bookingForm = document.getElementById('bookingForm');
	const loadingState = document.getElementById('loadingState');
	const packageContent = document.getElementById('packageContent');
	const reviewAgencyBtn = document.getElementById("reviewAgencyBtn");



	let currentSlide = 0;
	let slideInterval;
	let basePrice = 0;

	if (bookBtn) bookBtn.addEventListener('click', () => modal.classList.remove('hidden'));
	if (closeBtn) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

	const guestInput = document.querySelector('input[name="guests"]');
	const submitBtn = document.querySelector('#bookingForm .btn-primary');

	if (guestInput && submitBtn) {
		guestInput.addEventListener('input', (e) => {
			const guests = parseInt(e.target.value) || 1;
			const total = basePrice * guests;
			submitBtn.textContent = `Confirm & Book (ZAR ${total.toLocaleString('en-ZA', { minimumFractionDigits: 2 })})`;
		});
	}

	async function fetchPackageData(id) {
		try {
			const response = await fetch('../api.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ type: 'viewPackage', package_id: id })
			});
			const result = await response.json();
			if (result.status === 'success' && result.data) {
				populateUI(result.data.packageInfo, result.data.services, result.data.images, id);
			}
		} catch (error) {
			console.error(error);
		}
	}

	function populateUI(pkg, services, images, id) {
		document.getElementById('pkgIdBadge').textContent = `ID: ${id}`;
		document.getElementById('pkgTitle').textContent = pkg.name;
		document.getElementById('pkgAgency').textContent = pkg.agency_name;
		document.getElementById('pkgDescription').textContent = pkg.description;

		basePrice = parseFloat(pkg.price || 0);
		document.getElementById('pkgPrice').textContent = basePrice.toLocaleString('en-ZA', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		}) + " / person";

		if (submitBtn) {
			submitBtn.textContent = `Confirm & Book (ZAR ${basePrice.toLocaleString('en-ZA', { minimumFractionDigits: 2 })})`;
		}

		const slidesContainer = document.getElementById('slides');
		slidesContainer.innerHTML = '';
		images.forEach((img, index) => {
			const slide = document.createElement('img');
			slide.src = img;
			slide.className = index === 0 ? 'slide active' : 'slide';
			slidesContainer.appendChild(slide);
		});
		if (images.length > 0) slideInterval = setInterval(() => changeSlide(1), 5000);

		const servicesContainer = document.getElementById('pkgServices');
		servicesContainer.innerHTML = '';
		services.forEach(srv => {
			const name = srv.name || srv.flight_number || srv.description || 'Service Included';
			const serviceCard = document.createElement('div');
			serviceCard.className = 'service-item';
			serviceCard.innerHTML = `<span>${srv.type}</span><h3>${name}</h3>`;
			servicesContainer.appendChild(serviceCard);
		});

		loadingState.style.display = 'none';
		packageContent.classList.remove('hidden');
	}

	window.changeSlide = (n) => {
		const slides = document.querySelectorAll('.slide');
		if (slides.length === 0) return;
		slides[currentSlide].classList.remove('active');
		currentSlide = (currentSlide + n + slides.length) % slides.length;
		slides[currentSlide].classList.add('active');
	};

	bookingForm.addEventListener('submit', async (e) => {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(bookingForm).entries());
		data.type = 'bookPackage';
		data.package_id = packageId;

		try {
			const response = await fetch('../api.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(data)
			});
			const result = await response.json();
			if (result.status === 'success') {
				alert('Booking confirmed!');
				window.location.href = 'myBookings.php';
			} else {
				alert('Booking error: ' + result.data);
			}
		} catch (err) {
			alert('Could not process booking.');
		}
	});

	fetchPackageData(packageId);
});
