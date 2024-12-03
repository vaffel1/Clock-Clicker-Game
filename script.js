// script.js

let timeSpent = 0; // Initialize the time spent variable
const baseTimeIncrement = 1; // Base time increment per click (seconds)
const growthFactor = 1.005; // Growth factor for multiplier

// Load totalTime and bestTime from sessionStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    let totalTimeFromStorage = sessionStorage.getItem('totalTime');
    if (totalTimeFromStorage) {
        document.getElementById('total-time').textContent = totalTimeFromStorage;
    } else {
        // Initialize totalTime to 0 if not available in sessionStorage
        document.getElementById('total-time').textContent = '0';
    }

    // Fetch best time from the database on page load
    fetchBestTime();
});

function fetchBestTime() {
    const xhrLogin = new XMLHttpRequest();
    xhrLogin.open('GET', 'checkLogin.php', true);

    xhrLogin.onreadystatechange = function () {
        if (xhrLogin.readyState === 4) {
            if (xhrLogin.status === 200 && xhrLogin.responseText.trim() === 'true') {
                // User is logged in, proceed to fetch best time
                const xhr = new XMLHttpRequest();

                xhr.open('GET', 'getBestTime.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let bestTime = parseInt(xhr.responseText, 10);
                        document.getElementById('best-time').textContent = bestTime;
                        sessionStorage.setItem('bestTime', bestTime.toString());
                    }
                };

                xhr.send();
            } else {
                // User is not logged in, handle accordingly
                console.log('User is not logged in. Best time cannot be fetched.');
                document.getElementById('best-time').textContent = '0';
                sessionStorage.setItem('bestTime', '0');
            }
        }
    };

    xhrLogin.send();
}

setInterval(() => {
    timeSpent++;
    let roundedMultiplier = Math.round(growthFactor ** timeSpent);

    // Update the displayed multiplier with animation
    updateNumberWithAnimation('multiplier', roundedMultiplier);
}, 1000);

// Array of sound sources
const sounds = [
    'assets/tick.mp3',
    'assets/tick2.mp3',
    'assets/tick3.mp3'
];

document.getElementById('clock').addEventListener('click', () => {
    console.log('Clock element clicked');

    // Select a random sound from the array
    const randomIndex = Math.floor(Math.random() * sounds.length);
    const randomSound = sounds[randomIndex];

    // Play the selected random sound
    const audio = new Audio(randomSound);
    if (audio.paused || audio.ended) {
        audio.play();
    } else {
        audio.currentTime = 0;
        audio.play();
    }

    // Add the 'clicked' class to trigger the animation
    document.getElementById('clock').classList.add('clicked');

    let roundedMultiplier = Math.round(growthFactor ** timeSpent);
    let addedTime = baseTimeIncrement * roundedMultiplier;
    let currentTotalTime = parseInt(document.getElementById('total-time').textContent, 10);
    const newTotalTime = Math.round(currentTotalTime + addedTime);

    // Update the displayed total time with animation
    updateNumberWithAnimation('total-time', newTotalTime);

    // Check if the new total time is higher or equal to the best time and update it
    let currentBestTime = parseInt(document.getElementById('best-time').textContent, 10);
    if (newTotalTime >= currentBestTime) {
        document.getElementById('best-time').textContent = newTotalTime;
        updateScore(newTotalTime);
        updateNumberWithAnimation('best-time', newTotalTime);

        // Save updated totalTime and bestTime to sessionStorage
        sessionStorage.setItem('totalTime', newTotalTime.toString());
        sessionStorage.setItem('bestTime', newTotalTime.toString());

        // Call the function to update the leaderboard
        updateLeaderboard(newTotalTime);
    }

    // Reset time and multiplier after each click
    timeSpent = 0;
    document.getElementById('multiplier').textContent = '1';

    // Remove the 'clicked' class immediately to allow future clicks
    document.getElementById('clock').classList.remove('clicked');
});

function updateNumberWithAnimation(id, newValue) {
    const element = document.getElementById(id);
    // Remove existing animation classes if they exist
    element.classList.remove('animated');

    // Force reflow to reset the animation
    void element.offsetWidth;

    // Update the text content
    element.textContent = newValue;

    // Add the animated class to trigger the animation
    element.classList.add('animated');
}

function updateScore(bestTime) {
    // Create an XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('POST', 'scoreUpdate.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Set up a function to handle the response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Log the response from the server
        }
    };

    // Send the request with the bestTime data
    xhr.send('best_time=' + encodeURIComponent(bestTime));
}

function updateLeaderboard(bestTime) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "updateLeaderboard.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Log the request payload for debugging
    console.log('Sending AJAX request with bestTime:', bestTime);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Log the response from the server
        }
    };

    xhr.send("bestTime=" + encodeURIComponent(bestTime));
}

function openInfo() {
    const infoDiv = document.getElementById('info');
    const bodyElement = document.body;

    infoDiv.style.opacity = '0';
    infoDiv.style.display = 'block'
    infoDiv.classList.remove('closed');
    setTimeout(() => {
        infoDiv.style.opacity = '1';
    }, 10);

    bodyElement.classList.add('dimmed');
}

function closeInfo() {
    const infoDiv = document.getElementById('info');
    const closeButton = document.querySelector('.close-button');
    const bodyElement = document.body;

    closeButton.classList.add('clickAnimation');
    setTimeout(() => {
        infoDiv.style.opacity = '0';
        setTimeout(() => {
            infoDiv.classList.add('closed');
            bodyElement.classList.remove('dimmed');
            closeButton.classList.remove('clickAnimation');
        }, 100);
    }, 10);

    // Set a flag in local storage that the popup has been shown
    localStorage.setItem('popupShown', 'true');
}

// Check if the popup has already been shown
window.onload = function() {
    const popupShown = localStorage.getItem('popupShown');
    if (!popupShown) {
        openInfo();
        infoDiv.style.display = 'block'
    }
};

// Function to open (show) the leaderboard container
function openLeaderboard() {
    // Get the leaderboard container element by its ID
    var leaderboardContainer = document.getElementById('leaderboard-container');

    // Set the display style to block to show the leaderboard
    leaderboardContainer.style.display = 'block';
}

// Function to close (hide) the leaderboard container
function closeLeaderboard() {
    // Get the leaderboard container element by its ID
    var leaderboardContainer = document.getElementById('leaderboard-container');

    // Set the display style to none to hide the leaderboard
    leaderboardContainer.style.display = 'none';
}
