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
    console.log(style);
    document.querySelector(`[data-style="${style}"]`).classList.add('selected');
    document.getElementById('selectedStyle').value = style;
}

function deleteDescription(id,productId) {
    if (confirm('Are you sure you want to delete this description section?')) {
    const params = new URLSearchParams();
    params.append('product_id', productId);
    params.append('id', id);
    fetch('delete_description.php', {
        method: 'POST',
        // body: `product_id=${productId}&id=${id}`
        body: params
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error deleting section: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        console.log(err.message);
    });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('descriptionModal');
    if (event.target === modal) {
        closeModal();
    }
}

