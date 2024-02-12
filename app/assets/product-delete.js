const formNode = document.getElementById('delete_product_form');

formNode
    .addEventListener('submit', confirmDelete);

/**
 * Ask for confirmation before deleting a product.
 * @param {Event} event - the event.
 */
function confirmDelete(event) {
    const toBeDeleted = confirm(this.dataset.deleteMessage);

    if(toBeDeleted === false) {
        event.preventDefault();
    }
}
