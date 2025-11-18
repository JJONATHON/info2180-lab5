// world.js

window.addEventListener("DOMContentLoaded", function () {
    const lookupBtn = document.getElementById("lookup");
    const lookupCitiesBtn = document.getElementById("lookup-cities");
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    function sendRequest(type) {
        const country = countryInput.value.trim();

        // Build query string
        const params = new URLSearchParams();
        params.append("country", country);
        params.append("lookup", type); // "country" or "cities"

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "world.php?" + params.toString(), true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;
                } else {
                    resultDiv.innerHTML =
                        '<div class="message">Error loading data. Please try again.</div>';
                }
            }
        };

        xhr.send();
    }

    // Lookup Country button
    lookupBtn.addEventListener("click", function () {
        sendRequest("country");
    });

    // Lookup Cities button
    lookupCitiesBtn.addEventListener("click", function () {
        sendRequest("cities");
    });
});

