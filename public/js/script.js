// Setelah halaman selesai dimuat...
document.addEventListener('DOMContentLoaded', function () {
    // ...cari semua kotak dengan class 'pesan-sukses'
    var pesan = document.querySelectorAll('.pesan-sukses');

    // ...lalu sembunyikan masing-masing setelah 4 detik (4000 milidetik)
    pesan.forEach(function (kotak) {
        setTimeout(function () {
            kotak.style.display = 'none';
        }, 4000);
    });
});