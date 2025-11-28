<!-- <?php
// include 'db.php'; 

// if (isset($_GET['code'])) {
//     $code = $_GET['code'];
    
//     // 1. Dapatkan Access Token menggunakan cURL
//     $token_url = 'https://oauth2.googleapis.com/token';
//     $post_data = [
//         'code' => $code,
//         'client_id' => "163189570072-ue30g5086kaps43upgmo5mf5upg54apps.googleusercontent.com",
//         'client_secret' => "V9VCto8SwwcaQjQlRHh9FfmRD",
//         'redirect_uri' => 'http://localhost/PemWeb/TugasPwebClassroom/callback.php',
//         'grant_type' => 'authorization_code',
//     ];

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $token_url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
//     $token_response = curl_exec($ch);
//     $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     if ($http_code != 200) {
//         // Gagal mendapatkan token (e.g., code expired)
//         header('Location: login.php?error=token_failed');
//         exit();
//     }
    
//     $token_data = json_decode($token_response, true);
//     $access_token = $token_data['access_token'];

//     // 2. Dapatkan Info Pengguna menggunakan Access Token (cURL)
//     $userinfo_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $userinfo_url);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
//     $user_response = curl_exec($ch);
//     $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     if ($http_code != 200) {
//         // Gagal mendapatkan info pengguna
//         header('Location: login.php?error=userinfo_failed');
//         exit();
//     }
    
//     $google_account_info = json_decode($user_response, true);
    
//     $email = $google_account_info['email'];
//     $username = explode('@', $email)[0];
    
//     // --- 3. LOGIKA CEK DAN DAFTAR PENGGUNA ---
//     $email_clean = clean_input($conn, $email);
//     $username_clean = clean_input($conn, $username);
    
//     $sql = "SELECT id, password FROM users WHERE email='$email_clean'";
//     $result = mysqli_query($conn, $sql);

//     if (mysqli_num_rows($result) > 0) {
//         // Pengguna sudah terdaftar
//         $user = mysqli_fetch_assoc($result);
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['username'] = $username_clean; 
//         header('Location: dashboard.php');
//         exit();
        
//     } else {
//         // Pengguna baru, daftarkan
//         $dummy_password = password_hash(time(), PASSWORD_DEFAULT); 
        
//         $sql_insert = "INSERT INTO users (username, email, password) VALUES ('$username_clean', '$email_clean', '$dummy_password')";
        
//         if (mysqli_query($conn, $sql_insert)) {
//             $new_user_id = mysqli_insert_id($conn);
            
//             $_SESSION['user_id'] = $new_user_id;
//             $_SESSION['username'] = $username_clean;
//             header('Location: dashboard.php');
//             exit();
//         } else {
//             echo "Gagal menyimpan data pengguna Google: " . mysqli_error($conn);
//         }
//     }

// } else {
//     // 4. Redirect ke halaman otorisasi Google (Langkah Pertama)
//     $params = [
//         'response_type' => 'code',
//         'client_id' => "163189570072-ue30g5086kaps43upgmo5mf5upg54apps.googleusercontent.com",
//         'redirect_uri' => 'http://localhost/PemWeb/TugasPwebClassroom/callback.php',
//         'scope' => 'email profile', // Pastikan scope memiliki spasi, bukan koma
//         'access_type' => 'online',
//         'prompt' => 'select_account',
//     ];
//     $auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
//     header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
// }
?> -->