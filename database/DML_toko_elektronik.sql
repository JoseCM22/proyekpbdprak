INSERT INTO `role` (nama_role) VALUES 
	('Admin'),
	('Manajer'),
	('Kasir'),
	('Sales'),
	('Teknisi');
	
INSERT INTO satuan (nama_satuan, `status`) VALUES
	('Unit', 1),
	('Pcs', 1),
	('Box', 1),
	('Set', 0),
	('Roll', 0);
	
INSERT INTO vendor (nama_vendor, badan_hukum, `status`) VALUES
	('PT Panasonic Gobel Indonesia', 'P', 'A'),
	('PT Maspion Elektronik', 'P', 'A'),
	('PT Samsung Electronics Indonesia', 'P', 'A'),
	('CV Makmur Aksesoris', 'C', 'N'),
	('PT Astrindo Senayasa', 'P', 'A'),
	('PT Asus Indonesia', 'P', 'A');
	
INSERT INTO `user` (username, `password`, idrole) VALUES
	('ADM_Agus', 'adminagus', 1),
	('MNG_Lutpi', 'lutpijago', 2),
	('TKN_Dedi', 'teknikbos', 5),
	('KSR_Lala', 'kasir123', 3),
	('SLS_Ruru', 'otwsales', 4),
	('KSR_Amang', 'kasir456', 3);
	
INSERT INTO barang (jenis, nama, idsatuan, `status`, harga) VALUES
	('K', 'GeForce RTX 5070 Ti', 2, 1, 23240000),
	('L', 'ASUS ROG Strix G16', 1, 1, 45000000),
	('E', 'Miyako MCM-508 SBC 1.8L', 1, 1, 359000),
	('E', 'Samsung Crystal UHD DU7000', 1, 1, 4500000),
	('H', 'Redmi Note 15', 1, 0, 3399000);
	
INSERT INTO margin_penjualan (created_at, persen, STATUS, iduser, updated_at) VALUES
	(NOW(), 0.10, 0, 1, NOW()),
	(NOW(), 0.20, 1, 1, NOW()),
	(NOW(), 0.30, 0, 1, NOW()),
	(NOW(), 0.40, 0, 2, NOW()),
	(NOW(), 0.50, 0, 2, NOW()); 