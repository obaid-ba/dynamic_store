const decreaseBtn = document.getElementById('decreaseBtn');
const increaseBtn = document.getElementById('increaseBtn');
const quantityDisplay = document.getElementById('quantity');
const priceDisplay = document.getElementById('price');
const buyBtn = document.getElementById('buyBtn');

let quantity = 1;
const unitPrice = document.getElementById('price').textContent.replace('$', '');


function updateDisplay() {
    quantityDisplay.textContent = quantity;
    const totalPrice = (quantity * unitPrice).toFixed(2);
    priceDisplay.textContent = totalPrice;
}

decreaseBtn.addEventListener('click', () => {
    if (quantity > 1) {
        quantity--;
        updateDisplay();
    }
});

increaseBtn.addEventListener('click', () => {
    quantity++;
    updateDisplay();
});

updateDisplay();



document.querySelector('.add-model').onclick =  openModal;

function openModal() {
    document.getElementById('descriptionModal').classList.add('active');
}

function closeModal() {
    document.getElementById('descriptionModal').classList.remove('active');
}

function selectStyle(style) {
    document.querySelectorAll('.template-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    document.querySelector(`[data-style="${style}"]`).classList.add('selected');
    document.getElementById('selectedStyle').value = style;
}

function deleteDescription(id, productId) {
    if (confirm('Are you sure you want to delete this description section?')) {
        window.location.href = `delete_description.php?id=${id}&product_id=${productId}`;
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('descriptionModal');
    if (event.target === modal) {
        closeModal();
    }
}