document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const packageId = urlParams.get('id');

    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');
    const packageContent = document.getElementById('packageContent');
    const errorMessage = document.getElementById('errorMessage');
    const errorDescription = document.getElementById('errorDescription');

    let currentSlide = 0;
    let packageImages = [];
    let slideInterval;

    if (!packageId) {
        showError("Invalid Request", "No package ID was specified in the URL.");
        return;
    }

    fetchPackageData(packageId);

    async function fetchPackageData(id) {
        try {
            const response = await fetch('../api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'viewPackage', package_id: id })
            });

            const textResponse = await response.text();
            
            if (!textResponse.trim().startsWith('{')) {
                throw new Error("Server returned an invalid format.");
            }

            const result = JSON.parse(textResponse);

            if (result.status === 'success' && result.data && result.data.packageInfo) {
                populateUI(result.data.packageInfo, result.data.services, result.data.images, id);
            } else {
                showError("Package Not Found", result.data || "The requested package does not exist.");
            }

        } catch (error) {
            console.error("Fetch Error:", error);
            showError("Connection Error", "Failed to communicate with the server.");
        }
    }

    function populateUI(pkg, services, images, id) { 
        packageImages = images;
        
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
        if (packageImages && packageImages.length > 0) {
            packageImages.forEach((img, index) => {
                const slide = document.createElement('img');
                slide.src = img;
                slide.className = index === 0 ? 'slide active' : 'slide';
                slidesContainer.appendChild(slide);
            });
            startAutoSlide(); 
        } else {
            slidesContainer.innerHTML = '<div class="no-image">No images available</div>';
        }

        const servicesContainer = document.getElementById('pkgServices');
        servicesContainer.innerHTML = ''; 

        if (!services || services.length === 0) {
            servicesContainer.innerHTML = '<p class="text-body">No specific services added yet.</p>';
        } else {
            services.forEach(srv => {
                const serviceName = srv.name || srv.flight_number || srv.description || 'Service Included';
                const serviceCard = document.createElement('div');
                serviceCard.className = 'service-item';
                serviceCard.innerHTML = `
                    <span class="service-type">${srv.type}</span>
                    <h3 class="service-name">${serviceName}</h3>
                    <p class="service-location">📍 ${srv.street || ''} ${srv.city || 'Location unspecified'}</p>
                `;
                servicesContainer.appendChild(serviceCard);
            });
        }

        loadingState.classList.add('hidden');
        packageContent.classList.remove('hidden');
    }

    function startAutoSlide() {
        clearInterval(slideInterval); 
        slideInterval = setInterval(() => changeSlide(1), 5000);
    }

    window.changeSlide = function(n) {
        const slides = document.querySelectorAll('.slide');
        if (slides.length === 0) return;
        
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        
        startAutoSlide(); 
    };

    function showError(title, desc) {
        loadingState.classList.add('hidden');
        packageContent.classList.add('hidden');
        errorMessage.textContent = title;
        errorDescription.textContent = desc;
        errorState.classList.remove('hidden');
    }

    document.getElementById('bookBtn').addEventListener('click', () => {
        alert(`Booking flow initiated for Package ID: ${packageId}`);
    });
});
