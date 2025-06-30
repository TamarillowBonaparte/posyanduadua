# Posyandu Mobile API Integration Guide

## Koneksi API Web & Mobile

API telah diperbarui untuk mendukung kedua platform (web admin dan mobile parent) dengan kontrol akses berdasarkan peran (role). Berikut adalah panduan untuk mengintegrasikan aplikasi mobile dengan API baru.

## Perubahan pada ApiService

Update file `api_service.dart` Anda dengan base URL yang benar:

```dart
class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api';
  // Tambahkan path khusus untuk endpoint mobile
  static const String mobilePrefix = 'mobile';
  
  // Metode untuk endpoint mobile (gunakan untuk endpoint parent)
  String getMobileEndpoint(String endpoint) {
    return '$mobilePrefix/$endpoint';
  }

  // Gunakan method ini untuk endpoint parent
  Future<Map<String, dynamic>> getMobile(String endpoint) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/${getMobileEndpoint(endpoint)}'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load data');
    }
  }

  // Metode get yang sudah ada tetap digunakan untuk general endpoint
  Future<Map<String, dynamic>> get(String endpoint) async {
    // Implementasi tetap sama
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/$endpoint'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load data');
    }
  }

  // Metode lainnya tetap sama
  // ...
}
```

## Perubahan pada AuthService

Update file `auth_service.dart` untuk menambahkan parameter role pada respons login:

```dart
Future<Map<String, dynamic>> login({
  required String nik,
  required String password,
}) async {
  try {
    print('Memulai proses login...');
    
    // Login dengan parameter platform=mobile
    final response = await http.post(
      Uri.parse('${ApiService.baseUrl}/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'nik': nik,
        'password': password,
        'platform': 'mobile', // Tambahkan parameter ini
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200 && data['status'] == 'success') {
      print('Login berhasil');
      final prefs = await SharedPreferences.getInstance();
      
      // Store user data from API
      await prefs.setString('token', data['token']);
      await prefs.setString('nik', data['pengguna']['nik']);
      await prefs.setString('nama_ibu', data['pengguna']['nama_ibu'] ?? '');
      await prefs.setString('role', data['pengguna']['role']); // Simpan role
      
      if (data['pengguna']['alamat'] != null) {
        await prefs.setString('alamat', data['pengguna']['alamat']);
      }
      
      if (data['pengguna']['usia'] != null) {
        await prefs.setInt('usia', data['pengguna']['usia']);
      }
      
      // Save current child data if available
      if (data['pengguna']['anak'] != null && data['pengguna']['anak'].isNotEmpty) {
        final currentChild = data['pengguna']['anak'][0];
        await prefs.setString('nama_anak', currentChild['nama']);
        await prefs.setInt('usia_bulan_anak', currentChild['usia_bulan']);
        await prefs.setString('jenis_kelamin_anak', currentChild['jenis_kelamin']);
      }

      return {
        'success': true,
        'message': data['message'] ?? 'Login berhasil',
        'data': data['pengguna'],
      };
    } else {
      return {
        'success': false,
        'message': data['message'] ?? 'NIK atau password salah',
      };
    }
  } catch (e) {
    print('Error during login: $e');
    return {
      'success': false,
      'message': 'Terjadi kesalahan: $e',
    };
  }
}
```

## Mengakses Endpoint Spesifik untuk Parent

Untuk mengakses endpoint parent, gunakan `getMobile` method dari ApiService:

```dart
// Contoh penggunaan
Future<List<Map<String, dynamic>>> getPerkembanganAnak(int anakId) async {
  try {
    // Gunakan getMobile untuk endpoint khusus parent
    final data = await _apiService.getMobile('perkembangan/anak/$anakId');
    
    if (data['status'] == 'success') {
      return List<Map<String, dynamic>>.from(data['perkembangan']);
    } else {
      throw Exception('Gagal mendapatkan data perkembangan anak');
    }
  } catch (e) {
    print('Error getting perkembangan anak: $e');
    return [];
  }
}
```

## Endpoint yang Tersedia

### Endpoint Umum (Menggunakan `get` biasa)
- `GET /api/anak/pengguna/{pengguna_id}` - Mendapatkan data anak untuk pengguna tertentu
- `GET /api/anak/{id}` - Mendapatkan data anak berdasarkan ID
- `GET /api/perkembangan/anak/{anak_id}` - Mendapatkan data perkembangan untuk anak tertentu
- `GET /api/perkembangan/{id}` - Mendapatkan data perkembangan berdasarkan ID
- `GET /api/stunting/anak/{anak_id}` - Mendapatkan data stunting untuk anak tertentu
- `GET /api/stunting/{id}` - Mendapatkan data stunting berdasarkan ID

### Endpoint Parent/Mobile (Menggunakan `getMobile`)
- `GET /api/mobile/anak/pengguna/{pengguna_id}` - Mendapatkan data anak untuk pengguna tertentu (parent only)
- `GET /api/mobile/anak/{id}` - Mendapatkan data anak berdasarkan ID (parent only)
- `GET /api/mobile/perkembangan/anak/{anak_id}` - Mendapatkan data perkembangan untuk anak tertentu (parent only)
- `GET /api/mobile/perkembangan/{id}` - Mendapatkan data perkembangan berdasarkan ID (parent only)
- `GET /api/mobile/stunting/anak/{anak_id}` - Mendapatkan data stunting untuk anak tertentu (parent only)
- `GET /api/mobile/stunting/{id}` - Mendapatkan data stunting berdasarkan ID (parent only)

### Catatan Penting
1. API sekarang menyediakan keamanan berbasis peran (role-based security)
2. Role "parent" hanya bisa mengakses data baca
3. Role "admin" memiliki akses penuh (CRUD)
4. Login perlu menambahkan parameter `platform: 'mobile'` untuk aplikasi mobile
5. API akan menolak akses untuk role yang salah dengan kode status 403 

# Panduan Integrasi API Mobile Posyandu

Dokumen ini berisi pedoman dan informasi tentang endpoint API yang tersedia untuk aplikasi mobile Posyandu.

## Autentikasi

Semua endpoint API (kecuali login dan register) memerlukan autentikasi dengan Bearer Token.

### Login

```
POST /api/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "token": "YOUR_ACCESS_TOKEN",
    "user": {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com",
      "role": "orang_tua"
    }
  }
}
```

### Register

```
POST /api/register
```

**Request Body:**
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Registrasi berhasil",
  "data": {
    "token": "YOUR_ACCESS_TOKEN",
    "user": {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com",
      "role": "orang_tua"
    }
  }
}
```

## Endpoint API Mobile

### Informasi Anak

#### Mendapatkan Daftar Anak berdasarkan ID Pengguna

```
GET /api/mobile/anak/pengguna/{pengguna_id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Data anak berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama_anak": "Nama Anak",
      "tanggal_lahir": "2022-01-01",
      "jenis_kelamin": "L",
      "tempat_lahir": "Jakarta"
    }
  ]
}
```

#### Mendapatkan Detail Anak

```
GET /api/mobile/anak/{id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Detail anak berhasil diambil",
  "data": {
    "id": 1,
    "nama_anak": "Nama Anak",
    "tanggal_lahir": "2022-01-01",
    "jenis_kelamin": "L",
    "tempat_lahir": "Jakarta",
    "berat_lahir": "3.2",
    "panjang_lahir": "50"
  }
}
```

### Perkembangan Anak

#### Mendapatkan Riwayat Perkembangan Anak

```
GET /api/mobile/perkembangan/anak/{anak_id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Data perkembangan anak berhasil diambil",
  "data": [
    {
      "id": 1,
      "anak_id": 1,
      "tanggal": "2023-01-01",
      "berat_badan": "5.5",
      "tinggi_badan": "60.0"
    }
  ]
}
```

#### Mendapatkan Detail Perkembangan

```
GET /api/mobile/perkembangan/{id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Detail perkembangan berhasil diambil",
  "data": {
    "id": 1,
    "anak_id": 1,
    "tanggal": "2023-01-01",
    "berat_badan": "5.5",
    "tinggi_badan": "60.0",
    "anak": {
      "id": 1,
      "nama_anak": "Nama Anak"
    }
  }
}
```

### Jadwal

#### Mendapatkan Jadwal Mendatang untuk Anak

```
GET /api/mobile/jadwal/upcoming/anak/{anakId}
```

**Response:**
```json
{
  "status": "success",
  "message": "Jadwal mendatang berhasil diambil",
  "data": [
    {
      "id": 1,
      "tanggal": "2023-01-15",
      "jenis": "imunisasi",
      "keterangan": "Imunisasi DPT"
    }
  ]
}
```

#### Mendapatkan Jadwal Terdekat untuk Anak

```
GET /api/mobile/jadwal/nearest/{anakId}
```

**Response:**
```json
{
  "status": "success",
  "message": "Jadwal terdekat berhasil diambil",
  "data": {
    "id": 1,
    "tanggal": "2023-01-15",
    "jenis": "imunisasi",
    "keterangan": "Imunisasi DPT",
    "hari_tersisa": 5
  }
}
```

### Imunisasi

#### Mendapatkan Riwayat Imunisasi Anak

```
GET /api/mobile/imunisasi/anak/{anakId}
```

**Response:**
```json
{
  "status": "success",
  "message": "Data imunisasi anak berhasil diambil",
  "data": [
    {
      "id": 1,
      "anak_id": 1,
      "tanggal": "2022-12-01",
      "jenis_imunisasi": "BCG",
      "keterangan": "Sudah dilakukan"
    }
  ]
}
```

### Vitamin

#### Mendapatkan Riwayat Vitamin Anak

```
GET /api/mobile/vitamin/anak/{anakId}
```

**Response:**
```json
{
  "status": "success",
  "message": "Data vitamin anak berhasil diambil",
  "data": [
    {
      "id": 1,
      "anak_id": 1,
      "tanggal": "2022-12-01",
      "jenis_vitamin": "Vitamin A",
      "keterangan": "Sudah diberikan"
    }
  ]
}
```

### Stunting

#### Mendapatkan Riwayat Stunting Anak

```
GET /api/mobile/stunting/anak/{anak_id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Data stunting anak berhasil diambil",
  "data": [
    {
      "id": 1,
      "anak_id": 1,
      "tanggal": "2023-01-01",
      "status": "Tidak Stunting",
      "tinggi_badan": "60.0",
      "berat_badan": "5.5"
    }
  ]
}
```

#### Mendapatkan Detail Stunting

```
GET /api/mobile/stunting/{id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Detail stunting berhasil diambil",
  "data": {
    "id": 1,
    "anak_id": 1,
    "tanggal": "2023-01-01",
    "status": "Tidak Stunting",
    "tinggi_badan": "60.0",
    "berat_badan": "5.5",
    "usia": "6 bulan",
    "catatan": "Perkembangan baik"
  }
}
```

### Artikel

#### Mendapatkan Daftar Artikel

```
GET /api/mobile/artikel
```

**Query Parameters:**
- `per_page` (opsional): Jumlah artikel per halaman (default: 10)
- `page` (opsional): Halaman yang diinginkan
- `search` (opsional): Kata kunci untuk mencari artikel

**Response:**
```json
{
  "status": "success",
  "message": "Daftar artikel berhasil diambil",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "judul": "Makanan sehat",
        "gambar_artikel": "nfYhckwyJ9TLRC7o6D929XDjiKtFBzSTQjGYi7KD.jpg",
        "gambar_url": "http://posyandu.test/storage/artikel/nfYhckwyJ9TLRC7o6D929XDjiKtFBzSTQjGYi7KD.jpg",
        "isi_artikel": "Makanan yang mengandung nutrisi lengkap dan seimbang...",
        "tanggal": "2023-03-07",
        "created_at": "2023-03-07T08:08:36.000000Z",
        "updated_at": "2023-03-07T08:08:36.000000Z"
      }
    ],
    "first_page_url": "http://posyandu.test/api/mobile/artikel?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://posyandu.test/api/mobile/artikel?page=1",
    "links": [...],
    "next_page_url": null,
    "path": "http://posyandu.test/api/mobile/artikel",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

#### Mendapatkan Artikel Terbaru

```
GET /api/mobile/artikel/latest
```

**Query Parameters:**
- `limit` (opsional): Jumlah artikel yang diinginkan (default: 5)

**Response:**
```json
{
  "status": "success",
  "message": "Artikel terbaru berhasil diambil",
  "data": [
    {
      "id": 5,
      "judul": "Gizi",
      "gambar_artikel": "y1LRPBxJh7V4Px4I2hKLwqiDjHZRq77GfvouyUYt.png",
      "gambar_url": "http://posyandu.test/storage/artikel/y1LRPBxJh7V4Px4I2hKLwqiDjHZRq77GfvouyUYt.png",
      "isi_artikel": "Gizi anak merujuk pada pola makan dan nutrisi...",
      "tanggal": "2023-03-09",
      "created_at": "2023-03-07T22:40:06.000000Z",
      "updated_at": "2023-03-07T22:40:06.000000Z"
    }
  ]
}
```

#### Mendapatkan Detail Artikel

```
GET /api/mobile/artikel/{id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Detail artikel berhasil diambil",
  "data": {
    "id": 1,
    "judul": "Makanan sehat",
    "gambar_artikel": "nfYhckwyJ9TLRC7o6D929XDjiKtFBzSTQjGYi7KD.jpg",
    "gambar_url": "http://posyandu.test/storage/artikel/nfYhckwyJ9TLRC7o6D929XDjiKtFBzSTQjGYi7KD.jpg",
    "isi_artikel": "Makanan yang mengandung nutrisi lengkap dan seimbang, termasuk karbohidrat, protein, lemak sehat, vitamin, mineral, serta serat. Konsumsi makanan sehat sangat penting untuk menjaga kesehatan tubuh, meningkatkan daya tahan tubuh, serta mencegah berbagai penyakit.",
    "tanggal": "2023-03-07",
    "created_at": "2023-03-07T08:08:36.000000Z",
    "updated_at": "2023-03-07T08:08:36.000000Z"
  }
}
``` 