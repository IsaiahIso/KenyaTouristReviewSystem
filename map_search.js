 // ===============================
// MAP + SEARCH FUNCTIONALITY
// Leaflet + OpenStreetMap (via PHP proxy)
// ===============================

// -------- MAP INITIALIZATION --------

// Ensure map container exists
document.addEventListener("DOMContentLoaded", () => {

    // Default map center (Kenya)
    const map = L.map('map').setView([-1.286389, 36.817223], 6);

    // Load OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker reference
    let searchMarker = null;

    // -------- SEARCH FUNCTION --------
    window.performSearch = async function () {
        const input = document.getElementById('searchInput');
        if (!input) {
            alert("Search input not found");
            return;
        }

        const query = input.value.trim();

        if (!query) {
            alert("Please enter a place name");
            return;
        }

        try {
            const response = await fetch(
                `search_location.php?q=${encodeURIComponent(query)}`,
                {
                    headers: { 'Accept': 'application/json' }
                }
            );

            if (!response.ok) {
                throw new Error("Server response failed");
            }

            const data = await response.json();

            if (!Array.isArray(data) || data.length === 0) {
                alert("Location not found. Try another name.");
                return;
            }

            const place = data[0];
            const lat = parseFloat(place.lat);
            const lon = parseFloat(place.lon);

            if (isNaN(lat) || isNaN(lon)) {
                alert("Invalid coordinates received.");
                return;
            }

            // Move map
            map.setView([lat, lon], 13);

            // Remove old marker
            if (searchMarker) {
                map.removeLayer(searchMarker);
            }

            // Add marker
            searchMarker = L.marker([lat, lon]).addTo(map);

            searchMarker.bindPopup(
                `<strong>${place.display_name}</strong>`
            ).openPopup();

        } catch (error) {
            console.error("Search error:", error);
            alert("Error searching for location. Please try again.");
        }
    };

    // -------- ENTER KEY SUPPORT --------
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

});
