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

            // Lógica: Se (categoria é "todos" OU igual à do produto) E (nome contém o texto da pesquisa)
            const matchesCategory = (activeCategory === 'todos' || productCategory === activeCategory);
            const matchesSearch = productName.includes(searchText);

            if (matchesCategory && matchesSearch) {
                product.style.display = 'flex'; // Mostra
            } else {
                product.style.display = 'none'; // Esconde
            }
        });
    }

    // Evento para a Pesquisa (digitação)
    searchInput.addEventListener('input', filterProducts);

    // Evento para os Botões de Categoria
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove classe ativa de todos e mete no clicado
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            filterProducts();
        });
    });
});