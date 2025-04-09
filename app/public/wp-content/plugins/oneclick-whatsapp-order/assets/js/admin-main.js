// Shortcode Benerator
function generateWAshortcode(form) {
	var Vselected_wa_number = document.getElementById("selected_wa_number").value
	var VWAbuttonText = document.getElementById("WAbuttonText").value
	var VWAcustomMessage = document.getElementById("WAcustomMessage").value
	var VWAnewTab = document.getElementById("WAnewTab").value
	var generatedWAbuttonData = '[waorder phone="'+Vselected_wa_number+'" button="'+VWAbuttonText+'" message="'+VWAcustomMessage+'" target="'+VWAnewTab+'"]';
	document.getElementById("generatedShortcode").innerHTML = generatedWAbuttonData;
}

jQuery(document).ready(function ($) {
  // Function to toggle the full-width option visibility
  function toggleFullWidthOption() {
      var buttonPosition = $('#wa_order_single_product_button_position').val();

      if (buttonPosition === 'after_atc') {
          $('#force_fullwidth_container').hide(); // Hide checkbox
          $('#wa_order_single_force_fullwidth').prop('checked', false).val('no'); // Reset to 'No'
      } else {
          $('#force_fullwidth_container').show(); // Show checkbox
      }
  }

  // Run on page load
  toggleFullWidthOption();

  // Attach event listener to dropdown change
  $('#wa_order_single_product_button_position').change(function () {
      toggleFullWidthOption();
  });
});

// Single Product Shortcode Generator
document.addEventListener('DOMContentLoaded', function() {
    // Select DOM elements
    const productSelect = document.getElementById('SingleWAWhichPage');
    const productIdField = document.getElementById('SingleWAProductID');
    const buttonText = document.getElementById('SingleWAbuttonText');
    const customMessage = document.getElementById('SingleWAcustomMessage');
    const shortcodeOutput = document.getElementById('generatedSingleWAShortcode');
    const waNumberSelect = document.getElementById('selected_wa_number');
    const buttonForceFullwidth = document.getElementById('SingleWAFullwidth');

    // Check if all required elements exist
    if (!productSelect || !productIdField || !buttonText || !customMessage || !shortcodeOutput || !waNumberSelect || !buttonForceFullwidth) {
        console.error('One or more required elements are missing. Shortcode generator cannot initialize.');
        return;  // Exit the function if any element is missing
    }

    // Function to hide an element
    function hideElement(element) {
        if (element && element.parentElement && element.parentElement.parentElement) {
            element.parentElement.parentElement.style.display = 'none';
        }
    }

    // Function to show an element
    function showElement(element) {
        if (element && element.parentElement && element.parentElement.parentElement) {
            element.parentElement.parentElement.style.display = '';
        }
    }

    // Hide Product ID field initially
    hideElement(productIdField);

    // Function to generate the shortcode
    function generateSingleWAshortcode() {
        let productValue = productSelect.value;
        let productId = productIdField.value;
        let buttonTextValue = buttonText.value.trim();
        let customMessageValue = customMessage.value.trim();
        let selectedWaNumber = waNumberSelect.value;
        let isFullwidth = buttonForceFullwidth.value;

        // Default shortcode
        let shortcode = '[oneclick single="true"';

        // Add phone attribute (WhatsApp number)
        if (selectedWaNumber !== '') {
            shortcode += ` phone="${selectedWaNumber}"`;
        }

        // Handle product attribute
        if (productValue === 'current') {
            shortcode += ' product="current"';
        } else if (productValue === 'product_id' && productId !== '') {
            shortcode += ` product="${productId}"`;
        }

        // Handle text attribute
        if (buttonTextValue !== '') {
            shortcode += ` text="${buttonTextValue}"`;
        }

        // Handle message attribute
        if (customMessageValue !== '') {
            shortcode += ` message="${customMessageValue}"`;
        }

        // Force fullwidth
        if (isFullwidth !== '') {
            shortcode += ` fullwidth="${isFullwidth}"`;
        }

        // Close the shortcode
        shortcode += ']';

        // Output the generated shortcode
        shortcodeOutput.value = shortcode;
    }

    // Event listeners for changes
    productSelect.addEventListener('change', function() {
        if (productSelect.value === 'product_id') {
            showElement(productIdField);
        } else {
            hideElement(productIdField);
            productIdField.value = ''; // Clear the Product ID field
        }
        generateSingleWAshortcode();
    });

    productIdField.addEventListener('input', generateSingleWAshortcode);
    buttonText.addEventListener('input', generateSingleWAshortcode);
    customMessage.addEventListener('input', generateSingleWAshortcode);
    waNumberSelect.addEventListener('change', generateSingleWAshortcode);
    buttonForceFullwidth.addEventListener('change', generateSingleWAshortcode);

    // Initial generation of shortcode
    generateSingleWAshortcode();
});