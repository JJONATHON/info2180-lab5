// world.js
window.addEventListener('DOMContentLoaded', () => {
  const countryInput = document.getElementById('country');
  const lookupCountryBtn = document.getElementById('lookup-country');
  const lookupCitiesBtn = document.getElementById('lookup-cities');
  const resultDiv = document.getElementById('result');

  function fetchData(type) {
    const country = countryInput.value.trim();
    let url = `world.php?country=${encodeURIComponent(country)}`;

    if (type === 'cities') {
      url += '&lookup=cities';
    }

    fetch(url)
      .then(response => response.text())   // <-- IMPORTANT: text(), not json()
      .then(html => {
        resultDiv.innerHTML = html;
      })
      .catch(err => {
        console.error('Fetch error:', err);
        resultDiv.innerHTML = '<div class="message error">Error loading data. Please try again.</div>';
      });
  }

  lookupCountryBtn.addEventListener('click', (e) => {
    e.preventDefault();
    fetchData('country');
  });

  lookupCitiesBtn.addEventListener('click', (e) => {
    e.preventDefault();
    fetchData('cities');
  });
});

