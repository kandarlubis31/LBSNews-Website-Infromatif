</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/script.js"></script>
<script>
    if (document.getElementById('konten')) {
        CKEDITOR.replace('konten');
    }

    function konfirmasiHapus(event, url) {
        event.preventDefault(); // Mencegah link berjalan langsung

        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: isDarkMode ? '#242526' : '#ffffff',
            color: isDarkMode ? '#e4e6eb' : '#1c1e21'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url; // Lanjutkan ke URL hapus jika dikonfirmasi
            }
        });
    }
</script>
</body>

</html>