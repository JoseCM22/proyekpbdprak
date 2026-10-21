CREATE DATABASE db_toko_elektronik

USE db_toko_elektronik

-- tabel master
CREATE TABLE `role` (
	idrole INT NOT NULL AUTO_INCREMENT,
	nama_role VARCHAR(100) NOT NULL,
	PRIMARY KEY (`idrole`)
	);

CREATE TABLE satuan (
	idsatuan INT NOT NULL AUTO_INCREMENT,
	nama_satuan VARCHAR(45) NOT NULL,
	`status` TINYINT NOT NULL,
	PRIMARY KEY (`idsatuan`)
	);

CREATE TABLE vendor (
	idvendor INT NOT NULL AUTO_INCREMENT,
	nama_vendor VARCHAR(100) NOT NULL,
	badan_hukum CHAR(1) NOT NULL,
	`status` CHAR(1) NOT NULL,
	PRIMARY KEY (idvendor)
	);

-- tabel tingkat 1
CREATE TABLE `user` (
	iduser INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(45) NOT NULL,
	`password` VARCHAR(100) NOT NULL,
	idrole INT NOT NULL,
	PRIMARY KEY (iduser),
	CONSTRAINT fk_user_role FOREIGN KEY (idrole) REFERENCES `role`(idrole)
	);

CREATE TABLE barang (
	idbarang INT NOT NULL AUTO_INCREMENT,
	jenis CHAR(1) NOT NULL,
	nama VARCHAR(45) NOT NULL,
	idsatuan INT NOT NULL,
	`status` TINYINT NOT NULL,
	harga INT NOT NULL,
	PRIMARY KEY (idbarang),
	CONSTRAINT fk_barang_satuan FOREIGN KEY (idsatuan) REFERENCES satuan(idsatuan)
	);

-- tabel tingkat 2
CREATE TABLE margin_penjualan (
	idmargin_penjualan INT NOT NULL AUTO_INCREMENT,
	created_at TIMESTAMP NOT NULL,
	persen DOUBLE NOT NULL,
	STATUS TINYINT NOT NULL,
	iduser INT NOT NULL,
	updated_at TIMESTAMP NOT NULL,
	PRIMARY KEY (idmargin_penjualan),
	CONSTRAINT fk_margin_penjualan_user FOREIGN KEY (iduser) REFERENCES `user`(iduser)
	);
	
CREATE TABLE pengadaan (
	idpengadaan BIGINT NOT NULL AUTO_INCREMENT,
	`timestamp` TIMESTAMP NOT NULL,
	user_iduser INT NOT NULL,
	`status` CHAR(1) NOT NULL,
	vendor_idvendor INT NOT NULL,
	subtotal_nilai INT NOT NULL,
	ppn INT NOT NULL,
	total_nilai INT NOT NULL,
	PRIMARY KEY (idpengadaan),
	CONSTRAINT fk_pengadaan_user FOREIGN KEY (user_iduser) REFERENCES `user`(iduser),
	CONSTRAINT fk_pengadaan_vendor FOREIGN KEY (vendor_idvendor) REFERENCES vendor(idvendor)
	);
	
CREATE TABLE kartu_stok (
	idkartu_stok BIGINT NOT NULL AUTO_INCREMENT,
	jenis_transaksi CHAR(1) NOT NULL,
	masuk INT NOT NULL,
	keluar INT NOT NULL,
	stock INT NOT NULL,
	created_at TIMESTAMP NOT NULL,
	idtransaksi INT NOT NULL,
	idbarang INT NOT NULL,
	PRIMARY KEY (idkartu_stok),
	CONSTRAINT fk_kartu_stok_barang FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
	);
	
-- tabel tingkat 3
CREATE TABLE detail_pengadaan (
	iddetail_pengadaan BIGINT NOT NULL AUTO_INCREMENT,
	harga_satuan INT NOT NULL,
	jumlah INT NOT NULL,
	sub_total int NOT NULL,
	idbarang INT NOT NULL,
	idpengadaan BIGINT NOT NULL,
	PRIMARY KEY (iddetail_pengadaan),
	CONSTRAINT fk_det_pengadaan_barang FOREIGN KEY (idbarang) REFERENCES barang(idbarang),
	CONSTRAINT fk_det_pengadaan_pengadaan FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan)
	);
	
CREATE TABLE penerimaan (
	idpenerimaan BIGINT NOT NULL AUTO_INCREMENT,
	created_at TIMESTAMP NOT NULL,
	`status` CHAR(1) NOT NULL,
	idpengadaan BIGINT NOT NULL,
	iduser INT NOT NULL,
	PRIMARY KEY (idpenerimaan),
	CONSTRAINT fk_penerimaan_pengadaan FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan),
	CONSTRAINT fk_penerimaan_user FOREIGN KEY (iduser) REFERENCES `user`(iduser)
	);
	
CREATE TABLE penjualan (
	idpenjualan INT NOT NULL AUTO_INCREMENT,
	created_at TIMESTAMP NOT NULL,
	subtotal_nilai INT NOT NULL,
	ppn INT NOT NULL,
	total_nilai INT NOT NULL,
	iduser INT NOT NULL,
	idmargin_penjualan INT NOT NULL,
	PRIMARY KEY (idpenjualan),
	CONSTRAINT fk_penjualan_user FOREIGN KEY (iduser)
	REFERENCES `user`(iduser),
	CONSTRAINT fk_penjualan_margin_penjualan FOREIGN KEY (idmargin_penjualan)
	REFERENCES margin_penjualan(idmargin_penjualan)
	);
	
-- tabel tingkat 4
CREATE TABLE detail_penerimaan (
	iddetail_penerimaan BIGINT NOT NULL AUTO_INCREMENT,
	idpenerimaan BIGINT NOT NULL,
	barang_idbarang INT NOT NULL,
	jumlah_terima INT NOT NULL,
	harga_satuan_terima INT NOT NULL,
	sub_total_terima INT NOT NULL,
	PRIMARY KEY (iddetail_penerimaan),
	CONSTRAINT fk_det_penerimaan_penerimaan FOREIGN KEY (idpenerimaan)
	REFERENCES penerimaan(idpenerimaan),
	CONSTRAINT fk_det_penerimaan_barang FOREIGN KEY (barang_idbarang)
	REFERENCES barang(idbarang)
	);
	
CREATE TABLE detail_penjualan (
	iddetail_penjualan BIGINT NOT NULL AUTO_INCREMENT,
	harga_satuan INT NOT NULL,
	jumlah INT NOT NULL,
	subtotal INT NOT NULL,
	penjualan_idpenjualan INT NOT NULL,
	idbarang INT NOT NULL,
	PRIMARY KEY (iddetail_penjualan),
	CONSTRAINT fk_det_penjualan_penjualan FOREIGN KEY (penjualan_idpenjualan)
	REFERENCES penjualan(idpenjualan),
	CONSTRAINT fk_det_penjualan_barang FOREIGN KEY (idbarang)
	REFERENCES barang(idbarang)
	);
	
CREATE TABLE retur (
	idretur BIGINT NOT NULL AUTO_INCREMENT,
	created_at TIMESTAMP NOT NULL,
	idpenerimaan BIGINT NOT NULL,
	iduser INT NOT NULL,
	PRIMARY KEY (idretur),
	CONSTRAINT fk_retur_penerimaan FOREIGN KEY (idpenerimaan)
	REFERENCES penerimaan(idpenerimaan),
	CONSTRAINT fk_retur_user FOREIGN KEY (iduser)
	REFERENCES `user`(iduser)
	);
	
CREATE TABLE detail_retur (
	iddetail_retur INT NOT NULL AUTO_INCREMENT,
	jumlah INT NOT NULL,
	alasan VARCHAR(200) NOT NULL,
	idretur BIGINT NOT NULL,
	iddetail_penerimaan BIGINT NOT NULL,
	PRIMARY KEY (iddetail_retur),
	CONSTRAINT fk_det_retur_retur FOREIGN KEY (idretur)
	REFERENCES retur(idretur),
	CONSTRAINT fk_det_retur_det_penerimaan FOREIGN KEY (iddetail_penerimaan)
	REFERENCES detail_penerimaan(iddetail_penerimaan)
	);