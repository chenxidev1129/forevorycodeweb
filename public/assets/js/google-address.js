
var autocomplete;
var address1Field;
var postalField;

function initAutocomplete() {

  address1Field = document.querySelector("#address");
  postalField = document.querySelector("#zipCode");
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    //componentRestrictions: { country: ["us","in", "ca"] },
    // fields: ["address_components", "geometry"],
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
  var shortname = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
  
  for (const component of place.address_components) {
    const componentType = component.types[0];

    switch (componentType) {
      
      case "postal_code":
        postcode = `${component.long_name}${postcode}`;
        $('#zipCode').valid();
        break;
      
      case "locality":
        document.querySelector("#city").value = component.long_name;
        $('#city').valid();
        break;

      case "administrative_area_level_1": {
        document.querySelector("#state").value = component.long_name;
        $('#state').valid();
        break;
      }
      case "country":
        document.querySelector("#country").value = component.long_name;
        shortname = component.short_name;
        $('#country').valid();
        break;
     }
  }

  postalField.value = postcode;

  $("#country_sortname").val(shortname);
  $('#zipCode').valid();

  var lat =  place.geometry.location.lat();
  var lng = place.geometry.location.lng();
  $("#lat").val(lat);
  $("#lng").val(lng);
  
}