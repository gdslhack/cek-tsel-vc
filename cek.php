<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Voucher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Tailwind CSS configuration for dark mode
        tailwind.config = {
            darkMode: 'class', // Enable dark mode class
        };

        // Toggle dark mode
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            const lightIcon = document.getElementById('light-icon');
            const darkIcon = document.getElementById('dark-icon');
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'block';
            } else {
                lightIcon.style.display = 'block';
                darkIcon.style.display = 'none';
            }
        }

        // Ensure the correct icon is displayed on page load
        document.addEventListener('DOMContentLoaded', () => {
            const lightIcon = document.getElementById('light-icon');
            const darkIcon = document.getElementById('dark-icon');
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'block';
            } else {
                lightIcon.style.display = 'block';
                darkIcon.style.display = 'none';
            }
        });
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <!-- Theme toggle button -->
    <div class="fixed top-4 right-4">
        <button onclick="toggleTheme()" class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-300 p-2 rounded">
            <i id="light-icon" class="fas fa-sun"></i>
            <i id="dark-icon" class="fas fa-moon" style="display: none;"></i>
        </button>
    </div>

    <div class="container mx-auto mt-12 max-w-lg p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center dark:text-white">Cek Voucher</h2>
        <form action="" method="GET" class="space-y-4 mt-6">
            <input type="text" name="voucher" placeholder="Masukkan kode voucher" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
            <input type="submit" value="Cek" class="w-full bg-green-500 text-white py-3 rounded-lg cursor-pointer hover:bg-green-600">
        </form>
        <div class="result mt-6 text-center dark:text-gray-300">
            <?php
            header('X-Content-Type-Options: nosniff');
            if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
                $voucher = isset($_REQUEST['voucher']) ? $_REQUEST['voucher'] : null;
                if ($voucher) {
                    $url = 'https://apisik.my.id/Tsel/voucher.php?voucher=' . urlencode($voucher);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($ch);

                    if(curl_errno($ch)){
                        echo "<h3 class='text-xl font-semibold'>Hasil cek:</h3>";
                        echo "<p class='text-red-500'>Error: " . curl_error($ch) . "</p>";
                    } else {
                        echo "<h3 class='text-xl font-semibold'>Hasil cek:</h3>";

                        $responseData = json_decode($response, true);
                        if (isset($responseData['statusCode'])) {
                            echo "<p>Status Kode: " . htmlspecialchars($responseData['statusCode']) . "</p>";
                            echo "<p>Pesan Status: " . htmlspecialchars($responseData['statusMessage']) . "</p>";
                            echo "<p>Nomor Seri: " . htmlspecialchars($responseData['serialNumber']) . "</p>";
                            echo "<p>Nama: " . htmlspecialchars($responseData['name']) . "</p>";
                            echo "<p>Deskripsi: " . htmlspecialchars($responseData['description']) . "</p>";
                            echo "<p>Masa Berlaku: " . htmlspecialchars($responseData['validity']) . " hari</p>";
                            echo "<p>Tanggal Kadaluarsa: " . htmlspecialchars($responseData['expired_date']) . "</p>";
                            echo "<p>Digunakan oleh: " . htmlspecialchars($responseData['used_by']) . "</p>";
                            echo "<p>Tanggal Penggunaan: " . htmlspecialchars($responseData['usedDateTime']) . "</p>";
                            echo "<p>Wilayah: " . htmlspecialchars($responseData['region']) . "</p>";
                        } else {
                            echo "<p>Invalid response from the server</p>";
                        }
                    }

                    curl_close($ch);
                } else {
                    echo "<h3 class='text-xl font-semibold'>Hasil cek:</h3>";
                    echo "<p class='text-red-500'>Parameter voucher tidak diberikan</p>";
                }
            } else {
                echo "<h3 class='text-xl font-semibold'>Hasil cek:</h3>";
                echo "<p class='text-red-500'>Metode yang diperbolehkan hanya GET atau POST</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
