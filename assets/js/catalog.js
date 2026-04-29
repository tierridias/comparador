document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const filterButtons = document.querySelectorAll('.btn-filter');
    const products = document.querySelectorAll('.card');

    function filterProducts() {
        const searchText = searchInput.value.toLowerCase();
        const activeCategory = document.querySelector('.btn-filter.active').getAttribute('data-category');

        products.forEach(product => {
            const productName = product.getAttribute('data-name');
            const productCategory = product.getAttribute('data-category');

            const matchesCategory = (activeCategory === 'todos' || productCategory === activeCategory);
            const matchesSearch = productName.includes(searchText);

            if (matchesCategory && matchesSearch) {
                product.style.display = 'flex';
            } else {
                product.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterProducts);

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            filterProducts();
        });
    });
});