document.addEventListener('DOMContentLoaded', () => {
	const urlParams = new URLSearchParams(window.location.search);
	const packageId = urlParams.get('id');
	const isBooked = urlParams.get('booked');
	var agencyTargetid = -1;
	var packageTargetid = -1;

	const loadingState = document.getElementById('loadingState');
	const errorState = document.getElementById('errorState');
	const packageContent = document.getElementById('packageContent');
	const errorMessage = document.getElementById('errorMessage');
	const errorDescription = document.getElementById('errorDescription');
	const bookBtn = document.getElementById('bookBtn');
	const guaranteeBox = document.querySelector('.guarantee-box');

	const closeBtn = document.getElementById('closeModal');

	const Rmodal = document.getElementById('reviewModal');


	let currentSlide = 0;
	let slideInterval;

	if (!packageId) {
		showError("Invalid Request", "No package ID specified.");
		return;
	}

	if (isBooked === 'true') {
		if (bookBtn) bookBtn.style.setProperty('display', 'none', 'important');
		if (guaranteeBox) guaranteeBox.style.setProperty('display', 'none', 'important');
	}

	if (closeBtn) closeBtn.addEventListener('click', () => Rmodal.classList.add('hidden'));



	async function fetchPackageData(id) {
		try {
			const response = await fetch('../api.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ type: 'viewPackage', package_id: id })
			});
			const textResponse = await response.text();

			if (!textResponse.trim().startsWith('{')) throw new Error("Invalid server format.");

			const result = JSON.parse(textResponse);
			if (result.status === 'success' && result.data && result.data.packageInfo) {
				document.getElementById("agencyLink").href = `agencyDetails.php?id=${result.data.packageInfo.agency_id}`;
				populateUI(result.data.packageInfo, result.data.services, result.data.images, id);
				packageTargetid = result.data.packageInfo.target_id;
				agencyTargetid = result.data.packageInfo.agency_target_id;
			} else {
				showError("Package Not Found", result.data || "The requested package does not exist.");
			}
		} catch (error) {
			console.error("Fetch Error:", error);
			showError("Connection Error", "Failed to communicate with the server.");
		}
	}
	fetchPackageData(packageId);

	function populateUI(pkg, services, images, id) {
		document.getElementById('pkgIdBadge').textContent = `ID: ${id}`;
		document.getElementById('pkgTitle').textContent = pkg.name || 'Unnamed Package';
		document.getElementById('pkgAgency').textContent = pkg.agency_name || 'Tripistry Verified';
		document.getElementById('pkgDescription').textContent = pkg.description || 'No description available.';

		const priceNum = parseFloat(pkg.price || 0);
		document.getElementById('pkgPrice').textContent = priceNum.toLocaleString('en-ZA', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});

		const slidesContainer = document.getElementById('slides');
		slidesContainer.innerHTML = '';
		if (images && images.length > 0) {
			images.forEach((img, index) => {
				const slide = document.createElement('img');
				slide.src = img;
				slide.className = index === 0 ? 'slide active' : 'slide';
				slidesContainer.appendChild(slide);
			});
			startAutoSlide();
		} else {
			slidesContainer.innerHTML = '<div class="no-image" style="text-align:center; padding: 50px;">No images available</div>';
		}

		const servicesContainer = document.getElementById('pkgServices');
		servicesContainer.innerHTML = '';

		if (services && services.length > 0) {
			services.forEach(srv => {
				const name = srv.name || srv.flight_number || srv.description || 'Service Included';
				let locationParts = [];
				if (srv.street) locationParts.push(srv.street);
				if (srv.city) locationParts.push(srv.city);
				if (srv.code) locationParts.push(srv.code);
				let locationStr = locationParts.join(', ');
				let locationHtml = locationStr ? `<p class="service-location">📍 ${locationStr}</p>` : '';

				const serviceCard = document.createElement('div');
				serviceCard.className = 'service-item';
				serviceCard.innerHTML = `
                    <span class="service-type">${srv.type}</span>
                    <h3 class="service-name">${name}</h3>
                    ${locationHtml}
                `;
				servicesContainer.appendChild(serviceCard);
			});
		} else {
			servicesContainer.innerHTML = '<p class="text-body">No specific services added yet.</p>';
		}

		loadingState.style.display = 'none';
		packageContent.classList.remove('hidden');
	}

	// slideshow Logic
	function startAutoSlide() {
		clearInterval(slideInterval);
		slideInterval = setInterval(() => changeSlide(1), 5000);
	}

	window.changeSlide = function (n) {
		const slides = document.querySelectorAll('.slide');
		if (slides.length === 0) return;

		slides[currentSlide].classList.remove('active');
		currentSlide = (currentSlide + n + slides.length) % slides.length;
		slides[currentSlide].classList.add('active');
		startAutoSlide();
	};

	function showError(title, desc) {
		loadingState.style.display = 'none';
		packageContent.classList.add('hidden');
		errorMessage.textContent = title;
		errorDescription.textContent = desc;
		errorState.classList.remove('hidden');
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
			Rmodal.classList.add('hidden');
			alert('Review Submitted!');
			fetchMyBookings();
		} else {
			alert('Error: ' + result.data);
		}
	});

	document.getElementById('closeModal').onclick = () => Rmodal.classList.add('hidden');



	const reviewPackageBtn = document.querySelector("#reviewPackage");
	const reviewAgencyBtn = document.querySelector("#reviewAgency");
	const reviewTargetType = document.querySelector("#reviewTargetType");

	reviewPackageBtn.addEventListener('click', () => {
		document.getElementById('reviewRating').value = 5;
		document.getElementById('reviewComment').value = "";
		document.getElementById('reviewTargetId').value = packageTargetid;
		document.getElementById('reviewDate').value = new Date().toISOString().split('T')[0];
		reviewTargetType.innerHTML = "Package";
		Rmodal.classList.remove('hidden');
	});
	reviewAgencyBtn.addEventListener('click', () => {
		document.getElementById('reviewRating').value = 5;
		document.getElementById('reviewComment').value = "";
		document.getElementById('reviewTargetId').value = agencyTargetid;
		document.getElementById('reviewDate').value = new Date().toISOString().split('T')[0];
		reviewTargetType.innerHTML = "Travel Agency";
		Rmodal.classList.remove('hidden');
	});

});