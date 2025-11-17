window.addEventListener("load", function () {
    const lookupBtn = document.getElementById("lookup");
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    lookupBtn.addEventListener("click", function () {
        // 1) Read value from text box
        const country = countryInput.value.trim();

        // 2) Build URL for world.php
        let url = "world.php";
        if (country !== "") {
            url += "?country=" + encodeURIComponent(country);
        }

        // 3) Ajax request using XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // 4) Put the PHP output into the result div
                    resultDiv.innerHTML = xhr.responseText;
                } else {
                    resultDiv.innerHTML = "Error: " + xhr.status;
                }
            }
        };

        xhr.open("GET", url, true);
        xhr.send();
    });
});
