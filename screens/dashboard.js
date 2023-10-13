document.getElementById("coach1").addEventListener("click", function() {
    openSeatSelection(1);
});

document.getElementById("coach2").addEventListener("click", function() {
    openSeatSelection(2);
});

document.getElementById("coach3").addEventListener("click", function() {
    openSeatSelection(3);
});

document.getElementById("coach4").addEventListener("click", function() {
    openSeatSelection(4);
});

document.getElementById("coach5").addEventListener("click", function() {
    openSeatSelection(5);
});

document.getElementById("coach6").addEventListener("click", function() {
    openSeatSelection(6);
});

function openSeatSelection(selectedCoach) {
    window.open("seats.php?train_id=" + selectedCoach, "SeatSelection", "width=600, height=400");
}