<?php
include_once("bootstrap.php");

try {
    $db = new DBconnection();
    
    // Inisialisasi Model
    $pengadaanModel  = new PengadaanModel($db);
    $penerimaanModel = new PenerimaanModel($db);
    $barangModel     = new BarangModel($db);
    $vendorModel     = new VendorModel($db);
    $userModel       = new UserModel($db);
    $satuanModel     = new SatuanModel($db);
    $marginModel     = new MarginModel($db);

    $daftarPO         = $pengadaanModel->find_all();   // vu_transaksi_pengadaan
    $daftarPenerimaan = $penerimaanModel->find_all();  // vu_transaksi_penerimaan

    // Hitung Metrik Utama
    $totalPO        = count($daftarPO);
    $totalTerima    = count($daftarPenerimaan);
    $poPending      = 0;
    $nominalBelanja = 0;

    foreach ($daftarPO as $po) {
        if (($po['status_kode'] ?? '') === 'P') {
            $poPending++;
        }
        $nominalBelanja += (int) ($po['total_nilai'] ?? 0);
    }

    // Ringkasan Data Master
    $ringkasanMaster = [
        ['judul' => 'Katalog Barang',    'ket' => 'Item elektronik & komponen', 'link' => 'barang.php',           'badge' => '#e7f1ff', 'color' => '#0d6efd', 'jumlah' => count($barangModel->find_all())],
        ['judul' => 'Vendor Supplier',   'ket' => 'Mitra pemasok terdaftar',    'link' => 'vendor.php',           'badge' => '#e8f5e9', 'color' => '#198754', 'jumlah' => count($vendorModel->find_all())],
        ['judul' => 'Pegawai & User',    'ket' => 'Admin, kasir & teknisi',     'link' => 'user.php',             'badge' => '#fff3cd', 'color' => '#d97706', 'jumlah' => count($userModel->find_all())],
        ['judul' => 'Satuan Unit',       'ket' => 'Unit, Pcs, Box, dll.',       'link' => 'satuan.php',           'badge' => '#e0f2fe', 'color' => '#0284c7', 'jumlah' => count($satuanModel->find_all())],
        ['judul' => 'Margin Penjualan',  'ket' => 'Aturan persentase profit',   'link' => 'margin_penjualan.php', 'badge' => '#fce7f3', 'color' => '#db2777', 'jumlah' => count($marginModel->find_all())],
    ];

    // 5 Transaksi Pengadaan Terbaru
    $poTerbaru = array_slice($daftarPO, 0, 5);

} catch (DatabaseException $e) {
    die("Kesalahan Database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= Konfigurasi::APP_NAME ?></title>
    <style>
        .dash-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 60%, #084298 100%);
            color: #fff;
            padding: 26px 30px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.22);
        }
        .dash-hero h2 {
            margin: 0 0 6px 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .dash-hero p {
            margin: 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.86);
        }
        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-quick {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-quick-light {
            background: #ffffff;
            color: #0d6efd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
        .btn-quick-light:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
        }
        .btn-quick-outline {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        .btn-quick-outline:hover {
            background: rgba(255, 255, 255, 0.24);
            transform: translateY(-1px);
        }

        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }
        .kpi-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }
        .kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .kpi-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .kpi-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: bold;
        }
        .kpi-value {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .kpi-foot {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
        }

        /* Layout 2 Kolom Utama */
        .dash-split {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 22px;
            align-items: start;
        }
        @media (max-width: 992px) {
            .dash-split {
                grid-template-columns: 1fr;
            }
        }
        .panel-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 22px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }
        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .panel-head h3 {
            margin: 0;
            font-size: 16px;
            color: #0f172a;
        }
        .panel-head a {
            font-size: 13px;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }
        .panel-head a:hover {
            text-decoration: underline;
        }

        /* Badge Status */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-selesai { background: #dcfce7; color: #15803d; }
        .badge-batal   { background: #fee2e2; color: #b91c1c; }

        /* List Master Data */
        .master-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .master-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: all 0.15s ease;
        }
        .master-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateX(3px);
        }
        .master-info strong {
            display: block;
            font-size: 14px;
            color: #1e293b;
        }
        .master-info span {
            font-size: 12px;
            color: #64748b;
        }
        .master-count {
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php include_once("sidebar.php"); ?>

    <div class="main-content">
        <!-- Hero Banner & Quick Actions -->
        <div class="dash-hero">
            <div>
                <h2>Selamat Datang di <?= Konfigurasi::APP_NAME ?></h2>
                <p>Pantau ringkasan pengadaan barang, penerimaan gudang, dan inventaris toko secara real-time.</p>
            </div>
            <div class="quick-actions">
                <a href="pengadaan.php" class="btn-quick btn-quick-light">+ Buat Pengadaan (PO)</a>
                <a href="penerimaan.php" class="btn-quick btn-quick-outline">Terima Barang</a>
                <a href="kartu_stok.php" class="btn-quick btn-quick-outline">Cek Kartu Stok</a>
            </div>
        </div>

        <!-- KPI Metrics Grid -->
        <div class="kpi-grid">
            <a href="pengadaan.php" class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">Total Nota PO</span>
                    <span class="kpi-icon" style="background:#e7f1ff; color:#0d6efd;">PO</span>
                </div>
                <div class="kpi-value"><?= $totalPO ?></div>
                <div class="kpi-foot" style="color:#0d6efd;">Lihat semua dokumen PO &rarr;</div>
            </a>

            <a href="pengadaan.php" class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">PO Pending</span>
                    <span class="kpi-icon" style="background:#fef3c7; color:#d97706;">!</span>
                </div>
                <div class="kpi-value"><?= $poPending ?></div>
                <div class="kpi-foot" style="color:#d97706;">Menunggu penyelesaian &rarr;</div>
            </a>

            <a href="penerimaan.php" class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">Penerimaan Gudang</span>
                    <span class="kpi-icon" style="background:#dcfce7; color:#15803d;">IN</span>
                </div>
                <div class="kpi-value"><?= $totalTerima ?></div>
                <div class="kpi-foot" style="color:#15803d;">Dokumen barang masuk &rarr;</div>
            </a>

            <a href="pengadaan.php" class="kpi-card">
                <div class="kpi-top">
                    <span class="kpi-label">Total Nilai Belanja</span>
                    <span class="kpi-icon" style="background:#f3e8ff; color:#7e22ce;">Rp</span>
                </div>
                <div class="kpi-value" style="font-size: 20px; padding-top: 4px;">
                    Rp <?= number_format($nominalBelanja, 0, ',', '.') ?>
                </div>
                <div class="kpi-foot">Akumulasi PO + PPN 11%</div>
            </a>
        </div>

        <!-- Split Section: Tabel Transaksi Terbaru (Kiri) + Ringkasan Master Data (Kanan) -->
        <div class="dash-split">
            <!-- Kolom Kiri: Aktivitas Pengadaan -->
            <div class="panel-box">
                <div class="panel-head">
                    <h3>Transaksi Pengadaan Terbaru</h3>
                    <a href="pengadaan.php">Lihat Semua &rarr;</a>
                </div>

                <table>
                    <tr>
                        <th>ID PO</th>
                        <th>Vendor</th>
                        <th>Petugas</th>
                        <th>Total (+PPN)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <?php if (empty($poTerbaru)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 24px;">
                                Belum ada transaksi pengadaan yang tercatat.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($poTerbaru as $po): ?>
                            <?php 
                                $kode = $po['status_kode'] ?? 'P';
                                $badgeClass = $kode === 'S' ? 'badge-selesai' : ($kode === 'B' ? 'badge-batal' : 'badge-pending');
                            ?>
                            <tr>
                                <td><strong>#<?= $po['idpengadaan'] ?></strong></td>
                                <td><?= htmlspecialchars($po['nama_vendor']) ?></td>
                                <td><?= htmlspecialchars($po['username']) ?></td>
                                <td><strong>Rp <?= number_format($po['total_nilai'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge-status <?= $badgeClass ?>">
                                        <?= htmlspecialchars($po['status_ket']) ?>
                                    </span>
                                </td>
                                <td class="aksi">
                                    <a href="pengadaan.php?detail=<?= $po['idpengadaan'] ?>" class="edit">Kelola</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Kolom Kanan: Ringkasan Data Master -->
            <div class="panel-box">
                <div class="panel-head">
                    <h3>Ringkasan Data Master</h3>
                    <a href="barang.php">Katalog &rarr;</a>
                </div>

                <div class="master-list">
                    <?php foreach ($ringkasanMaster as $m): ?>
                        <a href="<?= $m['link'] ?>" class="master-item">
                            <div class="master-info">
                                <strong><?= $m['judul'] ?></strong>
                                <span><?= $m['ket'] ?></span>
                            </div>
                            <span class="master-count" style="background: <?= $m['badge'] ?>; color: <?= $m['color'] ?>;">
                                <?= $m['jumlah'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>