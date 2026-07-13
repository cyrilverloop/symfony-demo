import './styles/loader.css';

/**
 * Affiche le masque.
 * @throws {Error} si maskNode n'est pas un objet HTMLDivElement.
 */
function showMask() {
    const maskNode = document.getElementById('mask');

    if((maskNode instanceof HTMLDivElement) === false) {
        throw new Error('maskNode must be a HTMLDivElement object.');
    }

    maskNode
        .classList
        .remove('d-none');
}

/**
 * Cache le masque.
 * @throws {Error} si maskNode n'est pas un objet HTMLDivElement.
 */
function hideMask() {
    const maskNode = document.getElementById('mask');

    if((maskNode instanceof HTMLDivElement) === false) {
        throw new Error('maskNode must be a HTMLDivElement object.');
    }

    maskNode
        .classList
        .add('d-none');
}

export {showMask, hideMask};
