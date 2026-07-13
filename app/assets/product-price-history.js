import {showMask, hideMask} from './loader.js';
import 'chart.js';
import { Chart, registerables } from './vendor/chart.js/chart.js.index.js';

Chart.register(...registerables);

const priceHistorySwitchNode = document.getElementById("price_history_switch");
let cachedPrices = null;

const reloadPriceHistoryNode = document.getElementById("price_history").querySelector("button");

priceHistorySwitchNode
    .addEventListener("change", togglePriceHistory);

reloadPriceHistoryNode
    .addEventListener("click", reloadPriceHistory);

/**
 * Fetches the price's history.
 */
function fetchPriceHistory() {
    const priceHistoryUrl = document.getElementById("price_history_url").value;
    showMask();

    fetch(priceHistoryUrl, {
        method: "GET"
    })
    .then(saveFetchedPrices)
    .then(displayPrices)
    .catch(error => {
        console.error("An error occured.");
        hideMask();
    });
}

/**
 * Saves the fetched prices.
 * @param {Promise} response - the response.
 * @returns {object} - the cached prices.
 */
async function saveFetchedPrices(response) {
    cachedPrices = await response.json();

    return cachedPrices;
}

/**
 * Displays the prices.
 * @param {object} prices - the prices.
 */
function displayPrices(prices) {
    hideMask();
    let priceChartNode = document.getElementById('price_chart');

    if(priceChartNode === null) {
        createPriceHistoryCanvas();
        priceChartNode = document.getElementById('price_chart');
    }

    new Chart(priceChartNode, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Prices',
                data: prices,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

/**
 * Creates the canvas
 * for the price's history.
 */
function createPriceHistoryCanvas() {
    const canvasNode = document.createElement("canvas");
    canvasNode.id = "price_chart";

    const priceHistoryNode = document.getElementById("price_history");
    priceHistoryNode.appendChild(canvasNode);
}

/**
 * Toggles the price history.
 */
function togglePriceHistory() {

    if(priceHistorySwitchNode.classList.contains("collapsed") === false
        && cachedPrices === null
    ) {
        fetchPriceHistory();
    }
}

/**
 * Reloads the price history.
 */
function reloadPriceHistory() {
    document.getElementById('price_chart').remove();
    fetchPriceHistory();
}
