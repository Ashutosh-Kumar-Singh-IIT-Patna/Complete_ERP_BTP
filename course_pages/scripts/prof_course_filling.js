document.querySelector('form').addEventListener('submit', function (event) {
    const inputs = document.querySelectorAll('input[name^="grades"]');
    for (const input of inputs) {
        if (input.value && !/^[A-FN]{1,2}$/i.test(input.value)) {
            alert("Invalid grade entered. Use A-F, N, or leave blank.");
            event.preventDefault();
            return;
        }
    }
});
