-- Function
delimiter $$
CREATE FUNCTION f_cek_stok (par_idbarang INT)
returns INT DETERMINISTIC 
BEGIN
	DECLARE var_stok_akhir INT;
	
	SELECT COALESCE(SUM(masuk) - SUM(keluar),0) INTO var_stok_akhir
	FROM kartu_stok
	WHERE par_idbarang = idbarang;
	
	RETURN var_stok_akhir;
END $$
delimiter ;

-- trigger
delimiter $$
CREATE TRIGGER t_insert_det_penjualan
AFTER INSERT ON detail_penjualan
FOR EACH ROW
BEGIN
	DECLARE var_stok_before INT;
	DECLARE var_stok_current INT;
	
	SET var_stok_before = f_cek_stok(NEW.idbarang);
	SET var_stok_current = var_stok_before - new.jumlah;
	
	INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
	VALUES (
		'K', 0, NEW.jumlah, v_stok_current, NOW(), NEW.penjualan_idpenjualan, NEW.idbarang)
	;
END $$
delimiter ;