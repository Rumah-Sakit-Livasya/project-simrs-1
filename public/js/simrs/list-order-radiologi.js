// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

document.addEventListener("DOMContentLoaded", function () {

    // handle nota click
    const notaButtons = document.querySelectorAll("a.nota-btn");
    notaButtons.forEach((button) => {
        button.addEventListener("click", handleNotaClick);
    });

    // handle edit click
    const editButtons = document.querySelectorAll("a.edit-btn");
    editButtons.forEach((button) => {
        button.addEventListener("click", handleEditClick);
    });

    // handle pay click
    const payButtons = document.querySelectorAll("a.pay-btn");
    payButtons.forEach((button) => {
        button.addEventListener("click", handlePayClick);
    });

});

/**
 * Handle pay button click
 * @param {Event} event 
 */
function handlePayClick(event){
    event.preventDefault();
    const target = /** @type {HTMLElement} */ (event.target);
    const id = target.getAttribute("data-id");
    if(!id) return;

    const formData = new FormData();
    formData.append("id", id);

    Swal.fire(
        {
            title: "Konfirmasi Tagihan",
            html: "Konfirmasi order Radiologi menjadi tagihan pasien?",
            icon: "question",
            focusConfirm: true,
            showCancelButton: true,
            confirmButtonText: "Konfirmasi",
            cancelButtonText: "Batal"
        }
    )
        .then(result=>{
            if(result.isConfirmed){
                fetch('/api/simrs/konfirmasi-tagihan-order-radiologi', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
                    }
                })
                    .then(response => {
                        if (response.status != 200) {   
                            throw new Error('Error: ' + response.statusText);
                        }
                        showSuccessAlert('Data berhasil disimpan');
                        setTimeout(() => window.location.reload(), 2000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorAlertNoRefresh(`Error: ${error}`);
                    });
            }
            // else ignore
        })
}

/**
 * Handle edit button click
 * @param {Event} event 
 */
function handleEditClick(event){
    event.preventDefault();
    const target = /** @type {HTMLElement} */ (event.target);
    const id = target.getAttribute("data-id");
    if(!id) return;

    const url = `/simrs/radiologi/edit-order/${id}`;
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

/**
 * Handle print nota button click
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