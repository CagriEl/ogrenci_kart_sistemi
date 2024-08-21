document.getElementById('fillForm').addEventListener('click', async () => {
    let [tab] = await chrome.tabs.query({ active: true, currentWindow: true });

    chrome.scripting.executeScript({
        target: { tabId: tab.id },
        function: fillFormFromLocalStorage
    });
});

function fillFormFromLocalStorage() {
    document.querySelector('input[name="x_name"]').value = localStorage.getItem('x_name');
    document.querySelector('input[name="x_father_name"]').value = localStorage.getItem('x_father_name');
    document.querySelector('input[name="x_birth_date"]').value = localStorage.getItem('x_birth_date');
    document.querySelector('input[name="x_birth_place"]').value = localStorage.getItem('x_birth_place');
    document.querySelector('input[name="x_address"]').value = localStorage.getItem('x_address');
    document.querySelector('input[name="x_tel_mobile"]').value = localStorage.getItem('x_tel_mobile');
    document.querySelector('input[name="x_email"]').value = localStorage.getItem('x_email');
}
