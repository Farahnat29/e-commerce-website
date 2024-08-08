// JavaScript code for fetching countries and populating dropdowns
fetch("https://restcountries.com/v3.1/all")
  .then((response) => response.json())
  .then((data) => {
    const countries = data.map((country) => country.name.common);
    populateDropdown("fromSelect", countries);
    populateDropdown("toSelect", countries);
  })
  .catch((error) => console.error("Error fetching countries:", error));

function populateDropdown(dropdownId, countriesArray) {
  var dropdown = document.getElementById(dropdownId);
  countriesArray.forEach((country) => {
    var option = document.createElement("option");
    option.text = country;
    option.value = country;
    dropdown.appendChild(option);
  });
}

// JavaScript code for handling availability check
document
  .getElementById("checkAvailabilityBtn")
  .addEventListener("click", function () {
    // Get user input values
    var fromCountry = document.getElementById("fromSelect").value;
    var toCountry = document.getElementById("toSelect").value;
    var departureDate = document.getElementById("departureDate").value;
    var arrivalDate = document.getElementById("arrivalDate").value;

    // Perform the availability check and display flight list (sample data)
    displayFlightList([
      {
        departureTime: "18:30",
        arrivalTime: "7:30",
        price: "$2,345",
        flightDuration: "02H 45M",
        departureCity: "Your Departure City",
        arrivalCity: "Your Arrival City",
        departureDate: departureDate,
        arrivalDate: arrivalDate,
      },
      // Add more flight data here
    ]);
  });

// Function to display flight list items
function displayFlightList(flights) {
  var flightListContainer = document.getElementById("flightListContainer");
  var flightTemplate = document.getElementById("flightTemplate");

  // Clear existing flight list items
  flightListContainer.innerHTML = "";

  // Iterate through flights and create HTML for each flight item
  flights.forEach((flight) => {
    var flightItemHtml = `
    <div class="item">
      <div class="row d-flex align-items-center justify-content-between">
        <div class="col-lg-2 col-md-3 col-sm-12">
          <div class="item-inner-image text-center">
            <img src="images/flights/flight_grid_2.png" alt="image">
          </div>
        </div>
        <div class="col-lg-3 col-md-2 col-sm-12">
          <div class="item-inner">
            <div class="content">
              <h4 class="mb-0 departure-time">${flight.departureTime}</h4>
              <h4 class="mb-0 pink">${flight.departureCity}</h4>
              <p class="mb-0 text-uppercase">${flight.departureDate}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="item-inner flight-time">
            <p class="mb-0">${flight.flightDuration} FLIGHT</p>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="item-inner">
            <div class="content">
              <h4 class="mb-0">${flight.arrivalTime}</h4>
              <h4 class="mb-0 pink">${flight.arrivalCity}</h4>
              <p class="mb-0 text-uppercase">${flight.arrivalDate}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="item-inner flight-btn text-center p-0 bordernone mb-0">
            <p class="navy">${flight.price}</p>
            <a href="#" class="nir-btn-black">Book Now</a>
          </div>
        </div>
      </div>
    </div>
  `;

    // Append flight item HTML to flight list container
    flightListContainer.innerHTML += flightItemHtml;
  });

  // Show the flight list section
  document.getElementById("flightListSection").style.display = "block";
}
// Function to handle booking when "Book Now" button is clicked
function handleBookNow(flightItem) {
  // Retrieve flight information from the selected flight item
  const departureTime = flightItem.querySelector(".departure-time").textContent;
  const arrivalTime = flightItem.querySelector(".arrival-time").textContent;
  const price = flightItem.querySelector(".flight-price").textContent;
  const distance = flightItem.querySelector(".flight-distance").textContent;
  const ticketType = flightItem.querySelector(".ticket-type").textContent;

  // Update the booking details section with the retrieved flight information
  document.getElementById("flight_booking_date").textContent =
    new Date().toLocaleDateString(); // Example for booking date
  document.getElementById("ticket_type").textContent = ticketType;
  document.getElementById("flight_distance").textContent = distance;
  document.getElementById("flight_price").textContent = price;

  // Display the booking details section
  document.getElementById("booking_detail_section").style.display = "block";
}

// Add event listeners to "Book Now" buttons in flight list items
document.addEventListener("DOMContentLoaded", function () {
  const bookNowButtons = document.querySelectorAll(".flight-btn a");
  bookNowButtons.forEach((button) => {
    button.addEventListener("click", function () {
      handleBookNow(button.closest(".item"));
    });
  });
});
