-- ═══════════════════════════════════════════════════════════════
-- Master Data Essential — Production Seed
-- Generated: 2026-05-22 15:42:32
-- ═══════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- ── users ──
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("Admin Rumah Sakit","admin","admin@hospital.test","$2y$12$Nc8nSuhwICSFtMtytMy1kuizOJERAFKtT1h6CaKRlGdg9/3UEWDt.","admin");
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("Direktur","director","director@hospital.test","$2y$12$tsSI3J0ioph3o0LaLcM43erXK7awtmOCgMuy1qJ35NQfB8mWHnSri","director");
INSERT INTO `users` (`name`,`username`,`email`,`password`,`role`) VALUES ("IT Support","it.support","it.support@hospital.test","$2y$12$kHDExgNMUUclptZVIQGE1.SAvEvNW3pF/bQnLdF0IonDmUDOaqTYy","IT");

-- ── departments ──
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Administrasi","ADM",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Medis","MED",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Keperawatan","NUR",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Farmasi","FAR",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Keuangan","FIN",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("SDM","HRD",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("IT","IT",1);
INSERT INTO `departments` (`name`,`code`,`is_active`) VALUES ("Manajemen","MNG",1);

-- ── polyclinics ──
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("UMUM","Poli Umum",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("GIGI","Poli Gigi",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("ANAK","Poli Anak",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("MATA","Poli Mata",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("THT","Poli THT",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("JANTUNG","Poli Jantung",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("BEDAH","Poli Bedah",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("OBGYN","Poli Obgyn",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("KULIT","Poli Kulit Kelamin",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("SARAF","Poli Saraf",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("JIWA","Poli Psikiatri",1);
INSERT INTO `polyclinics` (`code`,`name`,`is_active`) VALUES ("FISIO","Poli Fisioterapi",1);

-- ── rooms ──
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("ICU-01","ICU",1,"1500000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K1-01","Kelas 1",2,"500000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K2-01","Kelas 2",4,"300000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("K3-01","Kelas 3",6,"150000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("NICU-01","NICU",1,"1800000.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("OK-01","OK",1,"0.00","available");
INSERT INTO `rooms` (`room_number`,`room_type`,`bed_count`,`price_per_day`,`status`) VALUES ("VIP-01","VIP",1,"1000000.00","available");

-- ── treatments ──
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Konsultasi Dokter Umum","konsultasi-dokter-umum","konsultasi","50000.00",15,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Konsultasi Dokter Spesialis","konsultasi-dokter-spesialis","konsultasi","200000.00",20,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Cek Tekanan Darah","cek-tekanan-darah","pemeriksaan","20000.00",5,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("EKG","ekg","pemeriksaan","150000.00",15,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Imunisasi BCG","imunisasi-bcg","imunisasi","100000.00",10,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Suntik Vitamin","suntik-vitamin","tindakan","75000.00",10,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Cabut Gigi","cabut-gigi","tindakan","250000.00",30,1);
INSERT INTO `treatments` (`name`,`slug`,`category`,`price`,`duration_minutes`,`is_active`) VALUES ("Tambal Gigi","tambal-gigi","tindakan","200000.00",45,1);

-- ── drugs ──
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Paracetamol 500mg","analgesik","tablet",1000,"500.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Amoxicillin 500mg","antibiotik","kapsul",500,"1500.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Ibuprofen 400mg","analgesik","tablet",800,"1000.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Antasida","lambung","tablet",600,"800.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("CTM","antihistamin","tablet",500,"300.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Vitamin B Complex","vitamin","tablet",1000,"600.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("OBH Sirup 100ml","batuk","botol",200,"12000.00",1);
INSERT INTO `drugs` (`name`,`category`,`unit`,`stock`,`price`,`is_active`) VALUES ("Salbutamol Inhaler","asma","inhaler",100,"85000.00",1);

-- ── chart_of_accounts ──
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1000","ASET","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1100","Kas","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1110","Kas di Tangan","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1120","Bank","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1200","Piutang Pasien","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1210","Piutang BPJS","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1220","Piutang Asuransi Swasta","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1300","Persediaan Obat","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("1400","Aset Tetap","asset","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2000","KEWAJIBAN","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2100","Utang Usaha","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("2200","Utang Gaji","liability","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3000","EKUITAS","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3100","Modal Pemilik","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("3200","Laba Ditahan","equity","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4000","PENDAPATAN","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4100","Pendapatan Konsultasi","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4200","Pendapatan Tindakan Medis","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4300","Pendapatan Penjualan Obat","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("4400","Pendapatan Rawat Inap","revenue","credit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5000","BEBAN","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5100","Beban Gaji Karyawan","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5200","Beban Listrik & Air","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5300","Beban Pembelian Obat","expense","debit",1);
INSERT INTO `chart_of_accounts` (`account_code`,`account_name`,`account_type`,`normal_balance`,`is_active`) VALUES ("5400","Beban Peralatan Medis","expense","debit",1);

SET FOREIGN_KEY_CHECKS=1;
