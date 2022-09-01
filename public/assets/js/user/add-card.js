/* Card detail hide */
$(".cardDetail").hide();

$(".addPayment").click(function() {
    $(".checkout_payment").hide(200);
    
    $(".cardDetail").show();
});


$(".cancel").click(function() {
    $('#addCardForm').trigger("reset");
    $(".checkout_payment").show(200);
    $(".cardDetail").hide();
    
});


/* Card expiry date field format */
function formatString(e) {
    var inputChar = String.fromCharCode(event.keyCode);
    var code = event.keyCode;
    var allowedKeys = [8];
    if (allowedKeys.indexOf(code) !== -1) {
        return;
    }

    event.target.value = event.target.value.replace(
        /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
    ).replace(
        /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
    ).replace(
        /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
    ).replace(
        /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
    ).replace(
        /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
    ).replace(
        /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
    ).replace(
        /\/\//g, '/' // Prevent entering more than 1 `/`
    );
}
    
/* Card number field format */
var txtCardNumber = document.querySelector(".creditCardText");
txtCardNumber.addEventListener("input", onChangeTxtCardNumber);

function onChangeTxtCardNumber(e) {
    var cardNumber = txtCardNumber.value;

    // Do not allow users to write invalid characters
    var formattedCardNumber = cardNumber.replace(/[^\d]/g, "");
    formattedCardNumber = formattedCardNumber.substring(0, 16);

    // Split the card number is groups of 4
    var cardNumberSections = formattedCardNumber.match(/\d{1,4}/g);
    if (cardNumberSections !== null) {
        formattedCardNumber = cardNumberSections.join(' ');
    }
    // If the formmattedCardNumber is different to what is shown, change the value
    if (cardNumber !== formattedCardNumber) {
        txtCardNumber.value = formattedCardNumber;
    }
}
