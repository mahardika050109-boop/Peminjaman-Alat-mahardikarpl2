(function () {
    // Konfirmasi sebelum aksi berbahaya: <form data-confirm="Hapus data ini?">
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute && e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) {
            e.preventDefault();
        }
    });

    // Buka/tutup menu samping di layar kecil.
    var toggles = document.querySelectorAll('[data-menu-toggle]');
    for (var i = 0; i < toggles.length; i++) {
        toggles[i].addEventListener('click', function () {
            document.body.classList.toggle('menu-open');
        });
    }
    document.addEventListener('click', function (e) {
        if (document.body.classList.contains('menu-open') && !e.target.closest('.side') && !e.target.closest('[data-menu-toggle]')) {
            document.body.classList.remove('menu-open');
        }
    });

    // Pesan sukses hilang sendiri.
    var ok = document.querySelectorAll('.alert.success');
    if (ok.length) {
        setTimeout(function () {
            for (var j = 0; j < ok.length; j++) { ok[j].style.display = 'none'; }
        }, 6000);
    }
})();
