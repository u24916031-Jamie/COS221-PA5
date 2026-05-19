const state = {
  packages: [
    {
      id: 1,
      name: "Island Escape",
      destination: "Mauritius",
      description: "Relax on white sand beaches and enjoy guided island tours.",
      duration: "5 days",
      price: 1450,
      rating: 4.8,
      agency: {
        name: "Wild Safari Tours",
        contact: "Sarah Khumalo",
        email: "bookings@wildsafari.co.za",
        phone: "0115556666"
      },
      itinerary: [
        "Arrival and welcome dinner",
        "Beach day and snorkeling",
        "Island tour and market visit",
        "Forest adventure and spa",
        "Departure"
      ],
      images: [
        "../img/tripBack.avif",
        "../img/tripBack.avif"
      ],
      reviews: [
        { rating: 5, author: "Alice", comment: "Beautiful island package.", date: "2026-05-10" },
        { rating: 4, author: "Bob", comment: "Loved the itinerary and service.", date: "2026-05-12" }
      ],
      groupTrips: [
        { date: "2026-06-15", capacity: 20 },
        { date: "2026-07-01", capacity: 12 }
      ],
      resources: {
        destinations: ["Mauritius"],
        flights: [{ code: "FA100", note: "Direct airport transfer" }],
        accommodations: [{ name: "Sun Coast Hotel", note: "Sea-view rooms" }],
        restaurants: [{ name: "Mozambik", note: "Fresh seafood" }],
        attractions: [{ name: "uShaka Marine World", note: "Ocean adventure park" }]
      }
    },
    {
      id: 2,
      name: "Safari Adventure",
      destination: "Kruger Park",
      description: "7-day safari with luxury lodge stays and game drives.",
      duration: "7 days",
      price: 2180,
      rating: 4.9,
      agency: {
        name: "Wild Safari Tours",
        contact: "Sarah Khumalo",
        email: "bookings@wildsafari.co.za",
        phone: "0115556666"
      },
      itinerary: [
        "Game drive at sunrise",
        "Bush breakfast",
        "Cultural village visit",
        "Sunset safari",
        "Luxury lodge evening"
      ],
      images: [
        "../img/tripBack.avif"
      ],
      reviews: [
        { rating: 5, author: "Cara", comment: "Amazing wildlife experience.", date: "2026-05-08" }
      ],
      groupTrips: [{ date: "2026-08-10", capacity: 16 }],
      resources: {
        destinations: ["Kruger National Park"],
        flights: [{ code: "KF210", note: "Charter flight included" }],
        accommodations: [{ name: "River Lodge", note: "Luxury tents" }],
        restaurants: [{ name: "Savannah Bistro", note: "Local cuisine" }],
        attractions: [{ name: "Guided safari", note: "Big five sightings" }]
      }
    },
    {
      id: 3,
      name: "City Explorer",
      destination: "Cape Town",
      description: "4-day city break with curated experiences and restaurant tasting.",
      duration: "4 days",
      price: 930,
      rating: 4.5,
      agency: {
        name: "Coastal Tours",
        contact: "Mark Van Wyk",
        email: "info@coastaltours.com",
        phone: "0319998888"
      },
      itinerary: [
        "City walking tour",
        "Table Mountain cable car",
        "Vineyard lunch",
        "Coastal drive"
      ],
      images: [
        "../img/tripBack.avif"
      ],
      reviews: [
        { rating: 4, author: "Diana", comment: "Great atmosphere and value.", date: "2026-05-14" }
      ],
      groupTrips: [{ date: "2026-09-05", capacity: 10 }],
      resources: {
        destinations: ["Cape Town"],
        flights: [{ code: "CT305", note: "Return flight" }],
        accommodations: [{ name: "Harbor Hotel", note: "Downtown location" }],
        restaurants: [{ name: "Harborside", note: "Waterfront dining" }],
        attractions: [{ name: "Robben Island", note: "Historical tour" }]
      }
    }
  ],
  stats: {
    newLeads: 8
  },
  filters: {
    search: "",
    sort: "priceAsc",
    destination: "all"
  },
  nextPackageId: 4,
  activePackageId: null,
  editingPackageId: null
};

const elements = {
  packageList: document.getElementById("packageList"),
  newDealForm: document.getElementById("newDealForm"),
  packageName: document.getElementById("packageName"),
  packageDestination: document.getElementById("packageDestination"),
  packageDescription: document.getElementById("packageDescription"),
  packageDuration: document.getElementById("packageDuration"),
  packagePrice: document.getElementById("packagePrice"),
  packageImage: document.getElementById("packageImage"),
  searchInput: document.getElementById("searchInput"),
  sortSelect: document.getElementById("sortSelect"),
  destinationFilter: document.getElementById("destinationFilter"),
  activePackagesCard: document.getElementById("activePackagesCard"),
  groupTripsCard: document.getElementById("groupTripsCard"),
  reviewsCard: document.getElementById("reviewsCard"),
  packageDetail: document.getElementById("packageDetail"),
  detailTitle: document.getElementById("detailTitle"),
  detailSubtitle: document.getElementById("detailSubtitle"),
  detailDescription: document.getElementById("detailDescription"),
  detailPrice: document.getElementById("detailPrice"),
  detailDuration: document.getElementById("detailDuration"),
  detailDestination: document.getElementById("detailDestination"),
  detailImages: document.getElementById("detailImages"),
  detailItinerary: document.getElementById("detailItinerary"),
  detailGroupTrips: document.getElementById("detailGroupTrips"),
  detailAgencyName: document.getElementById("detailAgencyName"),
  detailContact: document.getElementById("detailContact"),
  detailReviews: document.getElementById("detailReviews"),
  resourceDestinations: document.getElementById("resourceDestinations"),
  resourceFlights: document.getElementById("resourceFlights"),
  resourceAccommodations: document.getElementById("resourceAccommodations"),
  resourceRestaurants: document.getElementById("resourceRestaurants"),
  resourceAttractions: document.getElementById("resourceAttractions"),
  closeDetail: document.getElementById("closeDetail"),
  detailEdit: document.getElementById("detailEdit"),
  detailDelete: document.getElementById("detailDelete"),
  addGroupTrip: document.getElementById("addGroupTrip")
};

function formatPrice(value) {
  if (value === "" || value === null || value === undefined) {
    return "$0";
  }
  const number = Number(value.toString().replace(/[^0-9.]/g, ""));
  if (Number.isNaN(number)) {
    return "$0";
  }
  return `$${number.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
}

function parsePrice(value) {
  if (!value) {
    return 0;
  }
  const parsed = Number(value.toString().replace(/[^0-9.]/g, ""));
  return Number.isNaN(parsed) ? 0 : parsed;
}

function escapeHtml(text) {
  return String(text)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function sortPackages(packages) {
  const sorted = [...packages];
  const sortKey = state.filters.sort;
  if (sortKey === "priceAsc") {
    sorted.sort((a, b) => a.price - b.price);
  } else if (sortKey === "priceDesc") {
    sorted.sort((a, b) => b.price - a.price);
  } else if (sortKey === "durationAsc") {
    sorted.sort((a, b) => parseInt(a.duration) - parseInt(b.duration));
  } else if (sortKey === "durationDesc") {
    sorted.sort((a, b) => parseInt(b.duration) - parseInt(a.duration));
  } else if (sortKey === "ratingDesc") {
    sorted.sort((a, b) => b.rating - a.rating);
  }
  return sorted;
}

function filterPackages(packages) {
  return packages.filter((pkg) => {
    const search = state.filters.search.toLowerCase();
    const destination = state.filters.destination;
    const matchesSearch = [pkg.name, pkg.destination, pkg.description, pkg.agency.name]
      .join(" ")
      .toLowerCase()
      .includes(search);
    const matchesDestination = destination === "all" || pkg.destination === destination;
    return matchesSearch && matchesDestination;
  });
}

function getDestinations() {
  const destinations = new Set(state.packages.map((pkg) => pkg.destination));
  return ["all", ...Array.from(destinations).sort()];
}

function renderDestinationFilter() {
  elements.destinationFilter.innerHTML = "";
  getDestinations().forEach((destination) => {
    const option = document.createElement("option");
    option.value = destination;
    option.textContent = destination === "all" ? "All destinations" : destination;
    elements.destinationFilter.appendChild(option);
  });
  elements.destinationFilter.value = state.filters.destination;
}

function renderPackageList() {
  const filtered = filterPackages(state.packages);
  const sorted = sortPackages(filtered);
  elements.packageList.innerHTML = "";
  sorted.forEach((pkg) => {
    const card = document.createElement("div");
    card.className = "package-card";
    card.dataset.packageId = pkg.id;
    card.innerHTML = `
      <div class="package-summary">
        <div>
          <h3>${escapeHtml(pkg.name)}</h3>
          <p>${escapeHtml(pkg.destination)} • ${escapeHtml(pkg.duration)}</p>
          <p class="package-meta">${escapeHtml(pkg.description)}</p>
        </div>
        <div class="package-actions">
          <span class="price">${formatPrice(pkg.price)}</span>
          <div class="button-row">
            <button type="button" class="button secondary detail-button">Details</button>
            <button type="button" class="button edit-button">Update</button>
            <button type="button" class="button secondary delete-button">Delete</button>
          </div>
        </div>
      </div>
      <div class="package-tags">
        <span>${pkg.rating.toFixed(1)} ★</span>
        <span>${pkg.destination}</span>
      </div>
    `;
    elements.packageList.appendChild(card);
  });
  renderStats();
  renderDestinationFilter();
}

function renderStats() {
  elements.activePackagesCard.querySelector("p").textContent = state.packages.length;
  const groupTripCount = state.packages.reduce((count, pkg) => count + pkg.groupTrips.length, 0);
  elements.groupTripsCard.querySelector("p").textContent = groupTripCount;
  const averageRating =
    state.packages.reduce((sum, pkg) => sum + pkg.rating, 0) / state.packages.length;
  elements.reviewsCard.querySelector("p").textContent = averageRating.toFixed(1);
}

function openPackageDetail(id) {
  const pkg = state.packages.find((item) => item.id === id);
  if (!pkg) {
    showMessage("Package not found.", "error");
    return;
  }
  state.activePackageId = id;
  elements.detailTitle.textContent = pkg.name;
  elements.detailSubtitle.textContent = `${pkg.destination} • ${pkg.duration} • ${pkg.rating.toFixed(1)} ★`;
  elements.detailDescription.textContent = pkg.description;
  elements.detailPrice.textContent = formatPrice(pkg.price);
  elements.detailDuration.textContent = pkg.duration;
  elements.detailDestination.textContent = pkg.destination;
  elements.detailAgencyName.textContent = pkg.agency.name;
  elements.detailContact.textContent = `${pkg.agency.contact} · ${pkg.agency.email} · ${pkg.agency.phone}`;
  elements.detailImages.innerHTML = pkg.images
    .map((src) => `<img src="${escapeHtml(src)}" alt="${escapeHtml(pkg.name)}" />`)
    .join("");
  elements.detailItinerary.innerHTML = pkg.itinerary
    .map((step) => `<li>${escapeHtml(step)}</li>`)
    .join("");
  elements.detailGroupTrips.innerHTML = pkg.groupTrips
    .map(
      (trip, index) =>
        `<div class="trip-row"><span>${escapeHtml(trip.date)}</span><span>${trip.capacity} seats</span><button type="button" class="button secondary remove-trip" data-trip-index="${index}">Remove</button></div>`
    )
    .join("");
  elements.detailReviews.innerHTML = pkg.reviews
    .map(
      (review) =>
        `<div class="review-card"><strong>${escapeHtml(review.author)}</strong><span>${review.rating} ★</span><p>${escapeHtml(review.comment)}</p><small>${escapeHtml(review.date)}</small></div>`
    )
    .join("");
  renderResourceSection(elements.resourceDestinations, "Destinations", pkg.resources.destinations);
  renderResourceSection(elements.resourceFlights, "Flights", pkg.resources.flights.map((item) => `${item.code} — ${item.note}`));
  renderResourceSection(elements.resourceAccommodations, "Accommodations", pkg.resources.accommodations.map((item) => `${item.name} — ${item.note}`));
  renderResourceSection(elements.resourceRestaurants, "Restaurants", pkg.resources.restaurants.map((item) => `${item.name} — ${item.note}`));
  renderResourceSection(elements.resourceAttractions, "Attractions", pkg.resources.attractions.map((item) => `${item.name} — ${item.note}`));
  elements.packageDetail.classList.remove("hidden");
}

function renderResourceSection(container, title, items) {
  container.innerHTML = `
    <h4>${title}</h4>
    ${items.length ? items.map((item) => `<p>${escapeHtml(item)}</p>`).join("") : `<p class="empty">No ${escapeHtml(title.toLowerCase())} added yet.</p>`}
  `;
}

function closePackageDetail() {
  elements.packageDetail.classList.add("hidden");
  state.activePackageId = null;
}

function deletePackage(id) {
  const index = state.packages.findIndex((pkg) => pkg.id === id);
  if (index === -1) {
    showMessage("Cannot delete package.", "error");
    return;
  }
  state.packages.splice(index, 1);
  if (state.activePackageId === id) {
    closePackageDetail();
  }
  renderPackageList();
  showMessage("Package removed.");
}

function promptNewGroupTrip() {
  const pkg = state.packages.find((item) => item.id === state.activePackageId);
  if (!pkg) {
    return;
  }
  const date = prompt("Enter trip date (YYYY-MM-DD):");
  const capacity = prompt("Enter capacity:");
  if (!date || !capacity) {
    return;
  }
  const capacityNumber = Number(capacity);
  if (!date.trim() || Number.isNaN(capacityNumber) || capacityNumber <= 0) {
    showMessage("Invalid group trip details.", "error");
    return;
  }
  pkg.groupTrips.push({ date: date.trim(), capacity: capacityNumber });
  renderPackageList();
  openPackageDetail(pkg.id);
  showMessage("Group trip added.");
}

function handlePackageListClick(event) {
  const button = event.target.closest("button");
  if (!button) {
    return;
  }
  const card = event.target.closest(".package-card");
  if (!card) {
    return;
  }
  const packageId = Number(card.dataset.packageId);
  if (button.classList.contains("detail-button")) {
    openPackageDetail(packageId);
    return;
  }
  if (button.classList.contains("delete-button")) {
    deletePackage(packageId);
    return;
  }
  if (button.classList.contains("edit-button")) {
    openPackageDetail(packageId);
    return;
  }
}

function handleDetailClick(event) {
  const button = event.target.closest("button");
  if (!button) {
    return;
  }
  if (button.id === "closeDetail") {
    closePackageDetail();
    return;
  }
  if (button.id === "detailDelete") {
    deletePackage(state.activePackageId);
    return;
  }
  if (button.id === "detailEdit") {
    promptEditPackage();
    return;
  }
  if (button.id === "addGroupTrip") {
    promptNewGroupTrip();
    return;
  }
  if (button.classList.contains("remove-trip")) {
    const index = Number(button.dataset.tripIndex);
    const pkg = state.packages.find((item) => item.id === state.activePackageId);
    if (!pkg || Number.isNaN(index)) {
      return;
    }
    pkg.groupTrips.splice(index, 1);
    openPackageDetail(pkg.id);
    showMessage("Group trip removed.");
  }
}

function promptEditPackage() {
  const pkg = state.packages.find((item) => item.id === state.activePackageId);
  if (!pkg) {
    showMessage("Package not found.", "error");
    return;
  }
  const name = prompt("Package name:", pkg.name);
  const destination = prompt("Destination:", pkg.destination);
  const description = prompt("Description:", pkg.description);
  const duration = prompt("Duration:", pkg.duration);
  const price = prompt("Price:", pkg.price.toString());
  if (!name || !destination || !description || !duration || !price) {
    showMessage("Edit cancelled or invalid values.", "error");
    return;
  }
  const parsedPrice = parsePrice(price);
  if (parsedPrice <= 0) {
    showMessage("Enter a valid price.", "error");
    return;
  }
  pkg.name = name.trim();
  pkg.destination = destination.trim();
  pkg.description = description.trim();
  pkg.duration = duration.trim();
  pkg.price = parsedPrice;
  renderPackageList();
  openPackageDetail(pkg.id);
  showMessage("Package updated successfully.");
}

function addNewPackage(event) {
  event.preventDefault();
  const name = elements.packageName.value.trim();
  const destination = elements.packageDestination.value.trim();
  const description = elements.packageDescription.value.trim();
  const duration = elements.packageDuration.value.trim();
  const price = parsePrice(elements.packagePrice.value);
  const image = elements.packageImage.value.trim();
  if (!name || !destination || !description || !duration || price <= 0) {
    showMessage("Please complete all fields and enter a valid price.", "error");
    return;
  }
  const newPackage = {
    id: state.nextPackageId,
    name,
    destination,
    description,
    duration,
    price,
    rating: 4.5,
    agency: {
      name: "Coastal Tours",
      contact: "Mark Van Wyk",
      email: "info@coastaltours.com",
      phone: "0319998888"
    },
    itinerary: ["Day 1: Welcome", "Day 2: Experience", "Day 3: Explore"],
    images: image ? [image] : ["../img/tripBack.avif"],
    reviews: [],
    groupTrips: [],
    resources: {
      destinations: [destination],
      flights: [],
      accommodations: [],
      restaurants: [],
      attractions: []
    }
  };
  state.packages.unshift(newPackage);
  state.nextPackageId += 1;
  renderPackageList();
  renderDestinationFilter();
  elements.newDealForm.reset();
  showMessage("New package published successfully.");
}

function handleSearchChange(event) {
  state.filters.search = event.target.value;
  renderPackageList();
}

function handleSortChange(event) {
  state.filters.sort = event.target.value;
  renderPackageList();
}

function handleDestinationFilter(event) {
  state.filters.destination = event.target.value;
  renderPackageList();
}

function showMessage(text, type = "success") {
  const container = getMessageContainer();
  const message = document.createElement("div");
  message.className = `message ${type === "error" ? "error" : "success"}`;
  message.textContent = text;
  container.appendChild(message);
  window.setTimeout(() => {
    message.remove();
  }, 3000);
}

function getMessageContainer() {
  let container = document.getElementById("messageContainer");
  if (!container) {
    container = document.createElement("div");
    container.id = "messageContainer";
    container.className = "message-container";
    document.body.appendChild(container);
  }
  return container;
}

function connectEvents() {
  elements.packageList.addEventListener("click", handlePackageListClick);
  elements.newDealForm.addEventListener("submit", addNewPackage);
  elements.searchInput.addEventListener("input", handleSearchChange);
  elements.sortSelect.addEventListener("change", handleSortChange);
  elements.destinationFilter.addEventListener("change", handleDestinationFilter);
  elements.packageDetail.addEventListener("click", handleDetailClick);
}

function init() {
  renderDestinationFilter();
  renderPackageList();
  connectEvents();
}

init();
