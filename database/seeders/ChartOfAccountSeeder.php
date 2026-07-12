<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets (1-xxx) — normal_balance: debit
            ['account_code' => '1-001', 'account_name' => 'Kas', 'account_type' => 'asset', 'normal_balance' => 'debit'],
            ['account_code' => '1-002', 'account_name' => 'Piutang', 'account_type' => 'asset', 'normal_balance' => 'debit'],
            ['account_code' => '1-003', 'account_name' => 'Peralatan Medis', 'account_type' => 'asset', 'normal_balance' => 'debit'],
            ['account_code' => '1-004', 'account_name' => 'Bangunan', 'account_type' => 'asset', 'normal_balance' => 'debit'],
            ['account_code' => '1-005', 'account_name' => 'Kendaraan', 'account_type' => 'asset', 'normal_balance' => 'debit'],

            // Liabilities (2-xxx) — normal_balance: credit
            ['account_code' => '2-001', 'account_name' => 'Hutang Usaha', 'account_type' => 'liability', 'normal_balance' => 'credit'],
            ['account_code' => '2-002', 'account_name' => 'Hutang Bank', 'account_type' => 'liability', 'normal_balance' => 'credit'],

            // Equity (3-xxx) — normal_balance: credit
            ['account_code' => '3-001', 'account_name' => 'Modal', 'account_type' => 'equity', 'normal_balance' => 'credit'],
            ['account_code' => '3-002', 'account_name' => 'Laba Ditahan', 'account_type' => 'equity', 'normal_balance' => 'credit'],

            // Revenue (4-xxx) — normal_balance: credit
            ['account_code' => '4-001', 'account_name' => 'Pendapatan Rawat Jalan', 'account_type' => 'revenue', 'normal_balance' => 'credit'],
            ['account_code' => '4-002', 'account_name' => 'Pendapatan Rawat Inap', 'account_type' => 'revenue', 'normal_balance' => 'credit'],
            ['account_code' => '4-003', 'account_name' => 'Pendapatan Farmasi', 'account_type' => 'revenue', 'normal_balance' => 'credit'],
            ['account_code' => '4-004', 'account_name' => 'Pendapatan Lab', 'account_type' => 'revenue', 'normal_balance' => 'credit'],

            // Expense (5-xxx) — normal_balance: debit
            ['account_code' => '5-001', 'account_name' => 'Beban Gaji', 'account_type' => 'expense', 'normal_balance' => 'debit'],
            ['account_code' => '5-002', 'account_name' => 'Beban Obat', 'account_type' => 'expense', 'normal_balance' => 'debit'],
            ['account_code' => '5-003', 'account_name' => 'Beban ATK', 'account_type' => 'expense', 'normal_balance' => 'debit'],
            ['account_code' => '5-004', 'account_name' => 'Beban Listrik', 'account_type' => 'expense', 'normal_balance' => 'debit'],
            ['account_code' => '5-005', 'account_name' => 'Beban Pemeliharaan', 'account_type' => 'expense', 'normal_balance' => 'debit'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
