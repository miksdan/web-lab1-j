"use strict";
let x, y, r;

window.onload = function () {
    let buttons = document.querySelectorAll("input[name=x-in]");
    buttons.forEach(click);

    function click(element) {
        element.onclick = function () {
            r = this.value;
        }
    }
};

document.getElementById("checkButton").onclick = function () {
    if (validateX() && validateY() && validateR()) {
        let str = '?x=' + x + '&y=' + y + '&r=' + r;
        fetch("script.php" + str, {
            method: "GET",
            headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"},
        }).then(response => response.text()).then(function (serverAnswer) {
            setPointer();
            document.getElementById("output").innerHTML = serverAnswer;
        }).catch(err => createNotification("HTTP error. Please try again later." + err));
    }
};

function createNotification(message) {
    let outputContainer = document.getElementById("output");
    if (outputContainer.contains(document.querySelector(".notification"))) {
        let querySelector = document.querySelector(".notification");
        querySelector.textContent = message;
        querySelector.classList.replace("outputNote", "errorNote");
    } else {
        let notificationTableRow = document.createElement("p");
        notificationTableRow.innerHTML = "<span class='notification errorNote'/>";
        outputContainer.prepend(notificationTableRow);
        let span = document.querySelector(".notification");
        span.textContent = message;
    }
}

function validateX() {
    try {
        x = document.querySelector("select[name=x-in]").value;
        return true;
    } catch (err) {
        createNotification("The X value is not selected!");
        return false;
    }
}

function validateY() {
    y = document.querySelector("input[name=y-in]").value.replace(",", ".");
    if (y === undefined) {
        createNotification("Y not entered!");
        return false;
    } else if (!isNumeric(y)) {
        createNotification("Y is not a number!");
        return false;
    } else if (!((y > -3) && (y < 3))) {
        createNotification("Y is out of the range of admissible values!");
        return false;
    } else return true;
}

function validateR() {
    try {
        r = document.querySelector("input[type=radio]:checked").value;
        return true;
    } catch (err) {
        createNotification("R value is not selected!");
        return false;
    }
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function setPointer() {
    let pointer = document.getElementById("pointer");
    pointer.style.visibility = "visible";
    pointer.setAttribute("cx", x/r*2 * 60 + 150);
    pointer.setAttribute("cy", -y/r*2 * 60 + 150);
}