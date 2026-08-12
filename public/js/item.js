document.getElementById('itemForm').addEventListener('submit', function(e) {
    let valid = true;

    const name = document.getElementById('name');
    const price = document.getElementById('price');
    
    const nameError = document.getElementById('nameError');
    const priceError = document.getElementById('priceError');

    name.classList.remove('is-invalid');
    price.classList.remove('is-invalid');

    nameError.textContent = '';
    priceError.textContent = '';

    if (name.value.length > 255) {
        nameError.textContent = 'Maximum name length is 255 characters.';
        name.classList.add('is-invalid');
        valid = false;
    }

    if (Number(price.value) < 1) {
        priceError.textContent = 'The minimum price is 1.';
        price.classList.add('is-invalid');
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }
});