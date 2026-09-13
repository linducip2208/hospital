# Pharmacy Flow

Prescription berstatus `issued` masuk antrean farmasi. Apoteker menjalankan dispensing dengan row lock. Setiap item wajib menunjuk master drug dan batch belum expired. Batch diurutkan expiry ascending (FEFO), dapat memecah quantity ke beberapa batch, dan setiap perubahan dicatat sebagai stock movement.

Prescription tidak mengurangi stok. Purchase order otomatis hanya membuat PO draft; penerimaan PO berstatus ordered membuat batch, menambah stock, stock movement purchase, dan jurnal inventory/AP.
