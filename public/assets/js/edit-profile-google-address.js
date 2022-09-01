
var autocomplete;
var address1Field;
var postalField;

function initAutocomplete() {
  address1Field = document.querySelector("#graveAddress");
  postalField = document.querySelector("#graveZipCode");
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    //componentRestrictions: { country: ["us","in", "ca"] },
    // fields: ["address_components", "geometry","formatted_address"],
    // types: ["address"],
  });

 

  address1Field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

// [START maps_places_autocomplete_addressform_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  
  var postcode = "";
  var graveCity = "";
  var graveState = "";
  var gravecountry = ""; 


  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr

  for (const component of place.address_components) {
    const componentType = component.types[0];

    switch (componentType) {
      case "postal_code":
        postcode = `${component.long_name}${postcode}`;
        $('#graveZipCode').valid();
        break;

      case "locality":
        graveCity = document.querySelector("#graveCity").value = component.long_name;
        $('#graveCity').valid();
        break;

      case "administrative_area_level_1":
        graveState = document.querySelector("#graveState").value = component.long_name;
        $('#graveState').valid();
        break;

      case "country":
        gravecountry = document.querySelector("#gravecountry").value = component.long_name;
        $('#gravecountry').valid();
        break;
    }
  }

  postalField.value = postcode;
  $('#graveZipCode').valid();

  var lat =  place.geometry.location.lat();
  var lng = place.geometry.location.lng();
  document.querySelector("#graveLat").value = lat;
  document.querySelector("#graveLng").value = lng;

  const location = { lat: lat, lng: lng };
  // The map, centered at given lat lang
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 14,
    center: location,
  });
  // The marker, positioned at location
  const marker = new google.maps.Marker({
    position: location,
    map: map,
  });

  /* Show update address in detail page */
  document.querySelector("#showGraveSiteAddress").textContent = address1Field.value + ' ' + postcode;

}