db_toko_elektronik-- 
CREATE OR REPLACE VIEW vu_master_user AS
SELECT iduser AS "ID Pegawai", username AS Pegawai, nama_role AS "Role"
FROM user u
JOIN role r
ON u.idrole = r.idrole;

SELECT * from vu_master_user;

CREATE OR REPLACE VIEW vu_master_barang AS
SELECT idbarang AS "ID Barang", 
CASE jenis
	WHEN 'K' THEN 'Komponen'
	WHEN 'L' THEN 'Laptop'
	WHEN 'E' THEN 'Elektronik Rumah Tangga'
	WHEN 'H' THEN 'Handphone'
	ELSE 'Lainnya'
END AS Jenis,
nama AS "Nama", nama_satuan AS "Satuan", 
CASE b.status
	WHEN 1 THEN 'Aktif'
	WHEN 0 THEN 'Nonaktif'
END AS "Status",
harga AS "Harga"
FROM barang b
JOIN satuan s
ON b.idsatuan = s.idsatuan;

SELECT * FROM vu_master_barang;

CREATE OR REPLACE VIEW vu_master_vendor AS
SELECT idvendor AS "Vendor ID", nama_vendor AS Company, 
CASE badan_hukum
	WHEN "P" THEN 'PT'
	WHEN "C" THEN 'CV'
	ELSE 'Lainnya'
END AS "Badan Hukum",
CASE v.status
	WHEN "A" THEN "Aktif"
	WHEN "N" THEN "Nonaktif"
END AS "Status"
FROM vendor v;

CREATE OR REPLACE VIEW vu_master_satuan AS 
SELECT idsatuan AS "Satuan ID", nama_satuan AS "Satuan", 
CASE status
	WHEN 1 THEN 'Aktif'
	WHEN 0 THEN 'Nonaktif'
END AS "Status" 
FROM satuan;

CREATE OR REPLACE VIEW vu_master_marginP AS
SELECT idmargin_penjualan AS "Margin ID", persen AS "Margin", 
CASE STATUS
	WHEN 1 THEN 'Aktif'
	WHEN 0 THEN 'Nonaktif'
END AS "Status"
FROM margin_penjualan;

CREATE OR REPLACE VIEW vu_master_role AS
SELECT idrole AS "Role ID", nama_role AS "Role"
FROM role;

SELECT * FROM vu_master_role;