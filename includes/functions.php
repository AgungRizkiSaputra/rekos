<!-- includes/functions.php -->
<?php
require_once 'config.php';

function getKriteria() {
    global $conn;
    $query = "SELECT * FROM kriteria";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAlternatif() {
    global $conn;
    $query = "SELECT * FROM alternatif";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getNilaiAlternatif($id_alternatif) {
    global $conn;
    $query = "SELECT n.id_kriteria, k.nama_kriteria, k.jenis, n.nilai 
              FROM nilai_alternatif n
              JOIN kriteria k ON n.id_kriteria = k.id_kriteria
              WHERE n.id_alternatif = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_alternatif);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function hitungWP() {
    $alternatifs = getAlternatif();
    $kriterias = getKriteria();
    
    // Hitung total bobot
    $total_bobot = array_sum(array_column($kriterias, 'bobot'));
    
    // Normalisasi bobot
    foreach ($kriterias as &$kriteria) {
        $kriteria['bobot_normalisasi'] = $kriteria['bobot'] / $total_bobot;
    }
    unset($kriteria);
    
    $hasil = [];
    
    foreach ($alternatifs as $alternatif) {
        $nilais = getNilaiAlternatif($alternatif['id_alternatif']);
        $vektor = 1;
        
        foreach ($nilais as $nilai) {
            $kriteria = array_filter($kriterias, function($k) use ($nilai) {
                return $k['id_kriteria'] == $nilai['id_kriteria'];
            });
            $kriteria = array_values($kriteria)[0];
            
            if ($kriteria['jenis'] == 'cost') {
                $nilai_normalisasi = min(array_column($nilais, 'nilai')) / $nilai['nilai'];
            } else {
                $nilai_normalisasi = $nilai['nilai'] / max(array_column($nilais, 'nilai'));
            }
            
            $vektor *= pow($nilai_normalisasi, $kriteria['bobot_normalisasi']);
        }
        
        $hasil[] = [
            'id_alternatif' => $alternatif['id_alternatif'],
            'nama_alternatif' => $alternatif['nama_alternatif'],
            'alamat' => $alternatif['alamat'],
            'harga' => $alternatif['harga'],
            'deskripsi' => $alternatif['deskripsi'],
            'gambar' => $alternatif['gambar'],
            'vektor' => $vektor
        ];
    }
    
    // Urutkan berdasarkan vektor terbesar
    usort($hasil, function($a, $b) {
        return $b['vektor'] <=> $a['vektor'];
    });
    
    return $hasil;
}
?>