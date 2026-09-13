# Billing Flow

`Charge` menyimpan sumber layanan (`source_type/source_id`), kuantitas, snapshot harga, pasien, dan encounter. `BillingService::generateBill()` mengunci encounter dan pending charges, membuat bill items/invoice, lalu menandai charge sebagai billed.

`BillingService::receivePayment()` membuat payment dan `payment_allocations` dalam satu transaksi. Sisa bill dihitung dari allocation; partial payment menghasilkan `partial`, pembayaran terakhir menghasilkan `paid`. Overpayment ditolak. `RefundService` memeriksa batas refundable, mengembalikan saldo bill, dan membuat jurnal pembalik.
