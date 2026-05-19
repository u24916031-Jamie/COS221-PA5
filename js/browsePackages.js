async function fetchPackages()
{
  try
  {
    const response = await fetch('server/api.php?type=getPackages');
    const data = await response.json();
    renderPackages(data);
  }
  catch (error)
  {
    console.error('Error fetching data:', error);
  }
}

function renderPackages(data)
{
  const grid = document.getElementById('packagesGrid');
  grid.innerHTML = '';
  data.forEach(pkg =>
  {
    const card = document.createElement('div');
    card.className = 'package-card';
    card.setAttribute('data-price', pkg.price);
    card.setAttribute('data-rating', pkg.rating);
    card.innerHTML = `
      <img src="${pkg.img}" class="package-img">
      <div class="package-info">
        <span class="rating-badge">★ ${pkg.rating}</span>
        <h3>${pkg.name}</h3>
        <p class="package-price">ZAR ${pkg.price.toLocaleString()}</p>
        <p class="package-desc">${pkg.desc}</p>
        <button class="book-btn">View Details</button>
      </div>
    `;
    grid.appendChild(card);
  });
}

document.getElementById('searchBtn').addEventListener('click', function()
{
  const sortBy = document.getElementById('sortBy').value;
  const sortOrder = document.getElementById('sortOrder').value;
  const grid = document.getElementById('packagesGrid');
  const cards = Array.from(grid.getElementsByClassName('package-card'));

  cards.sort((a, b) =>
  {
    let valA = parseFloat(a.getAttribute(sortBy === 'price' ? 'data-price' : 'data-rating'));
    let valB = parseFloat(b.getAttribute(sortBy === 'price' ? 'data-price' : 'data-rating'));
    return sortOrder === 'asc' ? valA - valB : valB - valA;
  });

  cards.forEach(card =>
  {
    grid.appendChild(card);
  });
});

fetchPackages();