// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

document.addEventListener("DOMContentLoaded", function () {

    // handle nota click
    const notaButtons = document.querySelectorAll("a.nota-btn");
    notaButtons.forEach((button) => {
        button.addEventListener("click", handleNotaClick);
    });


});

/**
 * 
 * @param {Event} event 
 */
function handleNotaClick(event){
    event.preventDefault();
    const target = /** @type {HTMLElement} */ (event.target);
    const id = target.getAttribute("data-id");
    if(!id) return;

    const url = `/simrs/radiologi/nota-order/${id}`;
    const popupWidth = 900;
    const popupHeight = 600;
    const left = (screen.width - popupWidth) / 2;
    const top = (screen.height - popupHeight) / 2;

    window.open(
        url,
        "popupWindow",
        "width=" + popupWidth + ",height=" + popupHeight + ",top=" + top + ",left=" + left +
        ",scrollbars=yes,resizable=yes"
    );
}