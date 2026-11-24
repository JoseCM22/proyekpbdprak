/* Delimiter changed to ; */
/* Connecting to 127.0.0.1 via MariaDB or MySQL (TCP/IP), username root, using password: No ... */
SELECT CONNECTION_ID();
SHOW VARIABLES;
/* Characterset: utf8mb4 */
SHOW /*!50002 GLOBAL */ STATUS;
SELECT NOW();
/* Connected. Thread-ID: 28 */
/* Reading function definitions from C:\laragon\bin\heidisql\functions-mysql8.ini */
SHOW TABLES FROM `information_schema`;
SHOW DATABASES;
SHOW OPEN TABLES FROM db_toko_elektronik WHERE `in_use`!=0;
USE `db_toko_elektronik`;
/* Entering session "UTS" */
SELECT `DEFAULT_COLLATION_NAME` FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME`='db_toko_elektronik';
SHOW TABLE STATUS FROM `db_toko_elektronik`;
SHOW FUNCTION STATUS WHERE `Db`='db_toko_elektronik';
SHOW PROCEDURE STATUS WHERE `Db`='db_toko_elektronik';
SHOW TRIGGERS FROM `db_toko_elektronik`;
SELECT *, EVENT_SCHEMA AS `Db`, EVENT_NAME AS `Name` FROM information_schema.`EVENTS` WHERE `EVENT_SCHEMA`='db_toko_elektronik';
/* Loading file "C:\Users\Administrator\Downloads\Dump20260903.sql" (39,2 KiB) into query tab #1 */
/* Scaling controls to screen DPI: 100% */
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='vu_master_vendor' ORDER BY ORDINAL_POSITION;
SHOW INDEXES FROM `vu_master_vendor` FROM `db_toko_elektronik`;
SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE   CONSTRAINT_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='vu_master_vendor'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   TABLE_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='vu_master_vendor'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT CURRENT_USER();
SHOW CREATE VIEW `db_toko_elektronik`.`vu_master_vendor`;
SELECT CAST(LOAD_FILE(CONCAT(IFNULL(@@GLOBAL.datadir, CONCAT(@@GLOBAL.basedir, 'data/')), 'db_toko_elektronik/vu_master_vendor.frm')) AS CHAR CHARACTER SET utf8);
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='barang' ORDER BY ORDINAL_POSITION;
SHOW INDEXES FROM `barang` FROM `db_toko_elektronik`;
SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE   CONSTRAINT_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='barang'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   TABLE_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='barang'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SHOW COLLATION;
SHOW ENGINES;
SHOW CREATE TABLE `db_toko_elektronik`.`barang`;
SELECT tc.CONSTRAINT_NAME, cc.CHECK_CLAUSE FROM `information_schema`.`CHECK_CONSTRAINTS` AS cc, `information_schema`.`TABLE_CONSTRAINTS` AS tc WHERE tc.CONSTRAINT_SCHEMA='db_toko_elektronik' AND tc.TABLE_NAME='barang' AND tc.CONSTRAINT_TYPE='CHECK' AND tc.CONSTRAINT_SCHEMA=cc.CONSTRAINT_SCHEMA AND tc.CONSTRAINT_NAME=cc.CONSTRAINT_NAME;
SELECT * FROM vu_master_satuan LIMIT 1;
/*  */
SELECT * FROM vu_master_vendor LIMIT 1;
/*  */
SELECT * FROM vu_master_user LIMIT 1;
/*  */
SELECT * FROM vu_master_barang LIMIT 1;
/*  */
SELECT * FROM vu_master_marginP LIMIT 1;
/*  */
/* Affected rows: 0  Found rows: 5  Warnings: 0  Duration for 5 queries: 0,015 sec. */
SELECT * FROM vu_master_satuan LIMIT 1;
/*  */
SELECT * FROM vu_master_vendor LIMIT 1;
/*  */
SELECT * FROM vu_master_user LIMIT 1;
/*  */
SELECT * FROM vu_master_barang LIMIT 1;
/*  */
SELECT * FROM vu_master_marginP LIMIT 1;
/*  */
SELECT * FROM vu_master_role LIMIT 1;
/*  */
/* Affected rows: 0  Found rows: 6  Warnings: 0  Duration for 6 queries: 0,000 sec. */
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='role' ORDER BY ORDINAL_POSITION;
SHOW INDEXES FROM `role` FROM `db_toko_elektronik`;
SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE   CONSTRAINT_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='role'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   TABLE_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='role'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SHOW CREATE TABLE `db_toko_elektronik`.`role`;
SELECT tc.CONSTRAINT_NAME, cc.CHECK_CLAUSE FROM `information_schema`.`CHECK_CONSTRAINTS` AS cc, `information_schema`.`TABLE_CONSTRAINTS` AS tc WHERE tc.CONSTRAINT_SCHEMA='db_toko_elektronik' AND tc.TABLE_NAME='role' AND tc.CONSTRAINT_TYPE='CHECK' AND tc.CONSTRAINT_SCHEMA=cc.CONSTRAINT_SCHEMA AND tc.CONSTRAINT_NAME=cc.CONSTRAINT_NAME;
SELECT * FROM `db_toko_elektronik`.`role` LIMIT 1000;
SELECT * FROM pengadaan;
/*  */
SELECT * FROM penerimaan;
/*  */
SELECT * FROM penjualan;
/*  */
SELECT * FROM retur;
/*  */
SELECT * FROM kartu_stok;
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 5 queries: 0,000 sec. */
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='vendor' ORDER BY ORDINAL_POSITION;
SHOW INDEXES FROM `vendor` FROM `db_toko_elektronik`;
SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE   CONSTRAINT_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='vendor'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   TABLE_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='vendor'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SHOW CREATE TABLE `db_toko_elektronik`.`vendor`;
SELECT tc.CONSTRAINT_NAME, cc.CHECK_CLAUSE FROM `information_schema`.`CHECK_CONSTRAINTS` AS cc, `information_schema`.`TABLE_CONSTRAINTS` AS tc WHERE tc.CONSTRAINT_SCHEMA='db_toko_elektronik' AND tc.TABLE_NAME='vendor' AND tc.CONSTRAINT_TYPE='CHECK' AND tc.CONSTRAINT_SCHEMA=cc.CONSTRAINT_SCHEMA AND tc.CONSTRAINT_NAME=cc.CONSTRAINT_NAME;
SELECT * FROM `db_toko_elektronik`.`vendor` LIMIT 1000;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='pengadaan' ORDER BY ORDINAL_POSITION;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='user' ORDER BY ORDINAL_POSITION;
/* Loading file "C:\Users\Administrator\Documents\jc temp\VIEW_toko_elektronik.sql" (1,4 KiB) into query tab #5 */
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='penerimaan' ORDER BY ORDINAL_POSITION;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='penjualan' ORDER BY ORDINAL_POSITION;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='margin_penjualan' ORDER BY ORDINAL_POSITION;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='retur' ORDER BY ORDINAL_POSITION;
SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA='db_toko_elektronik' AND TABLE_NAME='kartu_stok' ORDER BY ORDINAL_POSITION;
SHOW INDEXES FROM `kartu_stok` FROM `db_toko_elektronik`;
SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE   CONSTRAINT_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='kartu_stok'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   TABLE_SCHEMA='db_toko_elektronik'   AND TABLE_NAME='kartu_stok'   AND REFERENCED_TABLE_NAME IS NOT NULL;
SHOW CREATE TABLE `db_toko_elektronik`.`kartu_stok`;
SELECT tc.CONSTRAINT_NAME, cc.CHECK_CLAUSE FROM `information_schema`.`CHECK_CONSTRAINTS` AS cc, `information_schema`.`TABLE_CONSTRAINTS` AS tc WHERE tc.CONSTRAINT_SCHEMA='db_toko_elektronik' AND tc.TABLE_NAME='kartu_stok' AND tc.CONSTRAINT_TYPE='CHECK' AND tc.CONSTRAINT_SCHEMA=cc.CONSTRAINT_SCHEMA AND tc.CONSTRAINT_NAME=cc.CONSTRAINT_NAME;
SELECT CURRENT_USER();
SHOW CREATE FUNCTION `db_toko_elektronik`.`f_cek_stok`;
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID"
	a.idpengadaan AS "Pengadaan ID"
	v.nama_vendor as "Vendor"
	u.username AS "User"
	case p.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser

-- view penjualan
CREATE OR REPLACE VIEW vu_transaksi_penjualan AS
SELECT pe.idpenjualan AS "Penjualan ID",
	pe.subtotal_nilai AS "Subtotal",
	pe.ppn AS "PPN",
	pe.total_nilai AS "Total",
	m.persen AS "Margin Penjualan",
	u.username AS "User",
	pe.created_at AS "Created at"
	FROM penjualan pe
	JOIN margin_penjualan m ON pe.idmargin_penjualan = m.idmargin_penjualan
	JOIN `user` u ON pe.iduser = u.iduser;
/* SQL Error (1064): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'a.idpengadaan AS "Pengadaan ID"
	v.nama_vendor as "Vendor"
	u.username AS "Use' at line 4 */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 4 queries: 0,282 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID"
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case p.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser

-- view penjualan
CREATE OR REPLACE VIEW vu_transaksi_penjualan AS
SELECT pe.idpenjualan AS "Penjualan ID",
	pe.subtotal_nilai AS "Subtotal",
	pe.ppn AS "PPN",
	pe.total_nilai AS "Total",
	m.persen AS "Margin Penjualan",
	u.username AS "User",
	pe.created_at AS "Created at"
	FROM penjualan pe
	JOIN margin_penjualan m ON pe.idmargin_penjualan = m.idmargin_penjualan
	JOIN `user` u ON pe.iduser = u.iduser;
/* SQL Error (1064): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "U' at line 4 */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 4 queries: 0,141 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID",
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case p.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser

-- view penjualan
CREATE OR REPLACE VIEW vu_transaksi_penjualan AS
SELECT pe.idpenjualan AS "Penjualan ID",
	pe.subtotal_nilai AS "Subtotal",
	pe.ppn AS "PPN",
	pe.total_nilai AS "Total",
	m.persen AS "Margin Penjualan",
	u.username AS "User",
	pe.created_at AS "Created at"
	FROM penjualan pe
	JOIN margin_penjualan m ON pe.idmargin_penjualan = m.idmargin_penjualan
	JOIN `user` u ON pe.iduser = u.iduser;
/* SQL Error (1064): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'CREATE OR REPLACE VIEW vu_transaksi_penjualan AS
SELECT pe.idpenjualan AS "Penj' at line 18 */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 4 queries: 0,094 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID",
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case p.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser;
/* SQL Error (1054): Unknown column 'p.status' in 'field list' */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 5 queries: 0,110 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID",
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case p.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser;
/* SQL Error (1054): Unknown column 'p.status' in 'field list' */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 5 queries: 0,141 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID",
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case pr.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON r.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser;
/* SQL Error (1054): Unknown column 'r.idpengadaan' in 'on clause' */
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 1 of 5 queries: 0,078 sec. */
-- view pengadaan
CREATE OR REPLACE VIEW vu_transaksi_pengadaan AS
SELECT p.idpengadaan AS "Pengadaan ID",
	v.nama_vendor AS "Vendor",
	u.username AS "User",
	p.subtotal_nilai AS "Subtotal",
	p.ppn AS "PPN",
	p.total_nilai AS "Total",
	case p.`status`
		when 'P' Then 'Pending'
		when 'S' then 'Selesai'
	END AS "Status",
	p.timestamp AS "Timestamp"
	FROM pengadaan p
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON p.user_iduser = u.iduser;
/*  */
-- view penerimaan
CREATE OR REPLACE VIEW vu_transaksi_penerimaan AS
SELECT pr.idpenerimaan AS "Penerimaan ID",
	a.idpengadaan AS "Pengadaan ID",
	v.nama_vendor as "Vendor",
	u.username AS "User",
	case pr.`status`
		when 'P' then 'Pending'
		when 'S' then 'Selesai'
	END AS 'Status',
	pr.created_at AS "Created at"
	FROM penerimaan pr
	JOIN pengadaan a ON pr.idpengadaan = a.idpengadaan
	JOIN vendor v ON a.vendor_idvendor = v.idvendor
	JOIN `user` u ON pr.iduser = u.iduser;
/*  */
-- view penjualan
CREATE OR REPLACE VIEW vu_transaksi_penjualan AS
SELECT pe.idpenjualan AS "Penjualan ID",
	pe.subtotal_nilai AS "Subtotal",
	pe.ppn AS "PPN",
	pe.total_nilai AS "Total",
	m.persen AS "Margin Penjualan",
	u.username AS "User",
	pe.created_at AS "Created at"
	FROM penjualan pe
	JOIN margin_penjualan m ON pe.idmargin_penjualan = m.idmargin_penjualan
	JOIN `user` u ON pe.iduser = u.iduser;
/*  */
-- view retur
CREATE OR REPLACE VIEW vu_transaksi_retur AS
SELECT r.idretur AS "Retur ID",
	pr.idpenerimaan AS "Penerimaan ID",
	u.username AS "User",
	v.nama_vendor AS "Vendor",
	r.created_at AS "Created at"
	FROM retur r
	JOIN penerimaan pr ON r.idpenerimaan = pr.idpenerimaan
	JOIN pengadaan p ON pr.idpengadaan = p.idpengadaan
	JOIN vendor v ON p.vendor_idvendor = v.idvendor
	JOIN `user` u ON r.iduser = u.iduser;
/*  */
CREATE OR REPLACE VIEW vu_kartu_stok AS
SELECT k.idkartu_stok AS "Kartu Stok ID",
	k.created_at AS "Created at",
	b.nama AS "Nama Barang",
	case k.jenis_transaksi
		when 'T' then 'Penerimaan'
		when 'J' then 'Penjualan'
	END AS "Transaksi",
	k.idtransaksi AS "Transaksi ID",
	k.masuk AS "Masuk",
	k.keluar AS "Keluar",
	k.stock AS "Stock"
	FROM kartu_stok k
	JOIN barang b ON k.idbarang = b.idbarang;
/*  */
/* Affected rows: 0  Found rows: 0  Warnings: 0  Duration for 5 queries: 0,609 sec. */
SHOW DATABASES;
SELECT `DEFAULT_COLLATION_NAME` FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME`='db_toko_elektronik';
SHOW TABLE STATUS FROM `db_toko_elektronik`;
SHOW FUNCTION STATUS WHERE `Db`='db_toko_elektronik';
SHOW PROCEDURE STATUS WHERE `Db`='db_toko_elektronik';
SHOW TRIGGERS FROM `db_toko_elektronik`;
SELECT *, EVENT_SCHEMA AS `Db`, EVENT_NAME AS `Name` FROM information_schema.`EVENTS` WHERE `EVENT_SCHEMA`='db_toko_elektronik';
SELECT CURRENT_USER();
SHOW CREATE FUNCTION `db_toko_elektronik`.`f_cek_stok`;
/* Entering session "UTS" */