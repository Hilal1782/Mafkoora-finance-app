const ccCodeSelect = document.getElementById('cc_code');
const ccDescription = document.getElementById('cc_description');
const ccDescriptionHidden = document.getElementById('cc_description_hidden'); // Hidden input field
const form = document.getElementById("expense-form");

// Code and description mapping
const codeDescriptions = {
    '01': 'Donations+',
    '02': 'Donations-',
    '03': 'Ranaban+',
    '04': 'Ranaban-',
    '05': 'Anzoorgallery+',
    '06': 'Anzoorgallery-',
    '07': 'Gatha+',
    '08': 'Gatha-',
    '09': 'Organic Musica+',
    '10': 'Organic Musica-',
    '11': 'Projects+',
    '12': 'Projects-',
    '13': 'Sardaryab+',
    '14': 'Sardaryab-',
    '15': 'Rent (Mafkoora)',
    '16': 'Rent (Hostel)',
    '17': 'Rent (Stall)+',
    '18': 'Rent (Stall)-',
    '19': 'WAPDA',
    '20': 'PTCL',
    '21': 'SNGPL',
    '22': 'Food',
    '23': 'Conveyance/ Travel',
    '24': 'Hostel/ Trip',
    '25': 'Salaries/ Honaranium',
    '26': 'Stationary',
    '27': 'Electronics',
    '28': 'Accessories',
    '29': 'Computers & Printers',
    '30': 'Composing+',
    '31': 'Composing-',
    '32': 'Printing (Eelai)+',
    '33': 'Printing (Eelai)-',
    '34': 'Advertisement+',
    '35': 'Advertisement-',
    '36': 'Library',
    '37': 'Museum',
};

ccCodeSelect.addEventListener('change', () => {
    const selectedCode = ccCodeSelect.value;
    ccDescription.textContent = codeDescriptions[selectedCode] || 'Description';
    ccDescriptionHidden.value = ccDescription.textContent; // Set the value of the hidden input
});

form.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent the default form submission behavior

    // Check for empty fields
    if (isFormEmpty()) {
        alert("Please fill in all fields.");
        return;
    }

    submitForm();
});

ccCodeSelect.addEventListener('change', () => {
    ccDescription.textContent = codeDescriptions[ccCodeSelect.value] || 'Description';
});

function isFormEmpty() {
    const inputs = form.querySelectorAll('input, select, textarea');
    for (const input of inputs) {
        if (input.value.trim() === '') {
            return true;
        }
    }
    return false;
}

function submitForm() {
    if (isFormEmpty()) {
        alert("Please fill in all fields.");
        return;
    }

    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "store_expense.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showPopup(response.message);
                } else {
                    showPopup("An error occurred.");
                }
            } else {
                showPopup("An error occurred.");
            }
        }
    };
    xhr.send(formData);
}

function showPopup(message) {
    const popup = document.getElementById("popup");
    const popupMessage = document.getElementById("popup-message");

    popupMessage.textContent = message;
    popup.style.display = "block";

    setTimeout(() => {
        popup.style.display = "none";
        clearFormFields();
    }, 1000); // 1000 milliseconds (1 second)
}

function clearFormFields() {
    document.getElementById("cc_code").value = "";
    document.getElementById("date").value = "<?php echo date('Y-m-d'); ?>";
    document.getElementById("ref_bill").value = "";
    document.getElementById("amount").value = "";
    document.getElementById("comment").value = "";
}
