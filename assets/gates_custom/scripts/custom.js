/*------------------------------
            Custom js
--------------------------------*/

/* snackbar */
function showSnackbar(text) {
    var x = document.getElementById("snackbar");
    x.innerHTML = text;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 6000);
}


/* modal */
/*
<button class="gates__open-modal" data-modal-id="myModal">Open Modal</button>
<div class="gates">
    <div id="myModal" class="modal">

<div class="modal-content">
    <span class="close">&times;</span>
<p>Some text in the Modal..</p>
</div>
</div>
</div>
*/

// Get the button that opens the modal
var btns = document.getElementsByClassName("gates__open-modal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close");

var i;
let modalId;
let modal;

for (i = 0; i < btns.length; i++) {
    if (btns[i]) {
        // When the user clicks the button, open the modal
        btns[i].addEventListener('click', function (ev) {
            modalId = ev.target.getAttribute('data-modal-id');
            modal = document.getElementById(modalId);
            modal.style.display = "block";
        });

        // When the user clicks on <span> (x), close the modal
        span[i].addEventListener('click', function(ev) {
            modal.style.display = "none";
        });
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

//Format number
function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
};


function changeSearchDateType(ev) {
    let value = ev.value;
    let f = document.getElementsByClassName('date_first_type');
    let s = document.getElementsByClassName('date_second_type');

    for (let i = 0; i < f.length; i++) {
        if (value == 1) {
            f[i].style.display = "block";
            s[i].style.display = "none";
        } else {
            f[i].style.display = "none";
            s[i].style.display = "block";
        }
    }
}
