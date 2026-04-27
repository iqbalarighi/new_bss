ln -s /home/u378694189/domains/bpbsmartsystem.com/new_bss/storage/app/public /home/u378694189/domains/bpbsmartsystem.com/new_bss/public/storage
ln -s /home/u378694189/domains/bpbsmartsystem.com/new_bss/public/storage /home/u378694189/domains/bpbsmartsystem.com/public_html/storage

Gunakan path yang benar ke binary composer, misalnya, /usr/bin/php /usr/local/bin/composer.

Cronjob mungkin gagal menjalankan perintah artisan karena berbagai alasan:
Masalah Path: Pastikan path yang benar ke PHP dan file artisan digunakan, mengikuti format ini:

/usr/bin/php /home/u378694189/domains/bpbsmartsystem.com/public_html/artisan schedule:run 

Ganti your_username dan your_domain dengan nilai yang sebenarnya.Izin: Verifikasi bahwa file artisan dan direktori tempatnya memiliki izin eksekusi yang benar.Kesalahan Skrip: Uji perintah artisan secara manual melalui SSH atau browser web untuk memastikan tidak ada kesalahan.Variabel Lingkungan: Pastikan variabel lingkungan yang diperlukan telah diatur dengan benar untuk perintah tersebut.Konfigurasi Cron: Periksa kembali pengaturan cron job untuk memastikan keakuratannya.

Ya, cronjob dapat menjalankan perintah Composer. Pastikan hal-hal berikut agar dapat berfungsi:
Gunakan path yang benar ke binary composer, misalnya, /usr/bin/php /usr/local/bin/composer.
Tentukan path lengkap ke direktori proyek Anda.
Contoh perintah cronjob:
/usr/bin/php /usr/local/bin/composer update -d /home/your_username/domains/your_domain/public_html 

Ganti your_username dan your_domain dengan nilai yang sebenarnya. Pastikan izin dan variabel lingkungan telah diatur dengan benar.

/usr/bin/php /usr/local/bin/composer require barryvdh/laravel-dompdf -d /home/u378694189/domains/bpbsmartsystem.com/bss

Saat menggunakan hPanel untuk membuat repositori Git di luar folder public_html:
Pergi ke Websites → Manage → Git di sidebar kiri.
Di bagian "Create a New Repository", atur Install Path ke folder yang diinginkan di luar public_html, misalnya, /home/your_username/your_folder.
Pastikan direktori kosong dan dapat diakses. Konfigurasikan alamat repositori dan cabang sesuai kebutuhan.