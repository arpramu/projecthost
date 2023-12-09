// main.js

function q1() {
    fetchData("SELECT username FROM users ORDER BY username ASC", 'answerContainer1');
    hideAndDisplay('answerContainer1');
}

function q2() {
    fetchData("SELECT r.room_id as difference FROM users u INNER JOIN reservation r ON u.user_id = r.user_id", 'answerContainer2');
    hideAndDisplay('answerContainer2');
}

// Add other functions for additional queries (q3, q4, etc.) if needed
// ...

// You can also add a generic function to handle fetching data and hiding/displaying containers
function fetchData(query, containerId) {
    // AJAX call or fetch data from the server using the provided query
    // Once data is received, update the HTML of the container
    // Example AJAX call:
    $.ajax({
       type: 'POST',
       url: 'ads.php',
       data: { query: query },
       success: function(data) {
        var answerContainer = document.getElementById(containerId);
        answerContainer.innerHTML = data;
      }
 });
}

function hideAndDisplay(containerId) {
    var answerContainer = document.getElementById(containerId);
    if (answerContainer.style.display == 'block') {
        answerContainer.style.display = 'none';
    } else {
        answerContainer.style.display = 'block';
    }
}
