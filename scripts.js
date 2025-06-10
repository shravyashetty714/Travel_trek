// Google Maps API Initialization
function initMap() {
    const myLocation = { lat: 48.8566, lng: 2.3522 };  // Coordinates for Paris, France
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: myLocation,
    });

    const marker = new google.maps.Marker({
        position: myLocation,
        map: map,
        title: "Paris, France",
    });
}

// Fetch weather data from OpenWeatherMap API
async function getWeather(city) {
    const apiKey = "YOUR_OPENWEATHERMAP_API_KEY";  // Replace with your OpenWeatherMap API Key
    const weatherUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

    try {
        const response = await fetch(weatherUrl);
        const data = await response.json();

        if (data.cod === 200) {
            const temperature = data.main.temp;
            const weatherDescription = data.weather[0].description;
            const cityName = data.name;

            // Display weather info
            document.getElementById("weatherData").innerHTML = `
                In ${cityName}, the temperature is ${temperature}°C with ${weatherDescription}.
            `;
        } else {
            document.getElementById("weatherData").innerHTML = "Weather data not available.";
        }
    } catch (error) {
        document.getElementById("weatherData").innerHTML = "Error fetching weather data.";
    }
}

// Example usage: Get weather for "Paris"
getWeather("Paris");
