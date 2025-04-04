document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([14.6487, 121.0553], 13); // Initial view
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    const defaultLocation = [14.6487, 121.0553];
    
    // Create a red pin icon
    const redPin = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        shadowSize: [41, 41]
    });
    
    // Create a draggable marker with the red pin icon
    let marker = L.marker(defaultLocation, {
        icon: redPin,
        draggable: true
    }).addTo(map);

    // Update location when marker is dragged
    marker.on('dragend', function(event) {
        const position = marker.getLatLng();
        fetchLocation({ lat: position.lat, lng: position.lng })
            .then(displayName => locationInput.value = displayName)
            .catch(() => locationInput.value = '');
    });

    const locationInput = document.getElementById('location');
    const locationSuggestions = document.createElement('div');
    locationSuggestions.setAttribute('id', 'location-suggestions');
    locationInput.parentNode.appendChild(locationSuggestions);

    // Set default location input
    fetchLocation({ lat: defaultLocation[0], lng: defaultLocation[1] })
        .then(displayName => locationInput.value = displayName)
        .catch(console.error);

    map.on('click', function (e) {
        moveMarker(e.latlng.lat, e.latlng.lng);
    });

    function moveMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], {
            icon: redPin,
            draggable: true
        }).addTo(map);
        
        // Re-add the dragend event listener
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            fetchLocation({ lat: position.lat, lng: position.lng })
                .then(displayName => locationInput.value = displayName)
                .catch(() => locationInput.value = '');
        });

        fetchLocation({ lat, lng })
            .then(displayName => locationInput.value = displayName)
            .catch(() => locationInput.value = '');
    }

    // Image preview
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
        });
    }

    // Fetch location name (Reverse Geocoding)
    function fetchLocation(coords) {
        const { lat, lng } = coords;
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;
        return fetch(url)
            .then(response => response.json())
            .then(data => data.display_name || 'Unknown location');
    }

    // Autocomplete for address input
    let debounceTimer;
    locationInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = locationInput.value.trim();
        if (query.length < 3) {
            locationSuggestions.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });

    function fetchSuggestions(query) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                locationSuggestions.innerHTML = '';
                data.slice(0, 5).forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'suggestion-item';
                    suggestionItem.textContent = item.display_name;
                    suggestionItem.addEventListener('click', () => {
                        locationInput.value = item.display_name;
                        locationSuggestions.innerHTML = '';

                        // Move map marker to selected location
                        map.setView([item.lat, item.lon], 15);
                        moveMarker(item.lat, item.lon);
                    });
                    locationSuggestions.appendChild(suggestionItem);
                });
            })
            .catch(console.error);
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function (e) {
        if (!locationInput.contains(e.target) && !locationSuggestions.contains(e.target)) {
            locationSuggestions.innerHTML = '';
        }
    });
});