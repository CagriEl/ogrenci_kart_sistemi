<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const formDiv = document.getElementById('kategori-formu');

    kategoriSelect.addEventListener('change', function () {
        const kategori = kategoriSelect.value;

        if (kategori === '') {
            formDiv.innerHTML = '';
            return;
        }

        fetch(`/kategori-form/${kategori}`)
            .then(response => response.text())
            .then(html => {
                formDiv.innerHTML = html;
            })
            .catch(error => {
                formDiv.innerHTML = '<div class="alert alert-danger">Form yüklenemedi.</div>';
            });
    });
});
</script>
