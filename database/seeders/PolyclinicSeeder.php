<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Polyclinic;
use Illuminate\Database\Seeder;

class PolyclinicSeeder extends Seeder
{
    public function run(): void
    {
        $polys = [
            ['name' => 'Poli Umum', 'code' => 'UMUM', 'description' => 'Pelayanan kesehatan umum dan pemeriksaan dasar', 'floor' => 'Lantai 1', 'phone' => '021-5001'],
            ['name' => 'Poli Gigi', 'code' => 'GIGI', 'description' => 'Perawatan gigi, tambal, cabut, scaling', 'floor' => 'Lantai 1', 'phone' => '021-5002'],
            ['name' => 'Poli Jantung', 'code' => 'JANTUNG', 'description' => 'Diagnosis dan penanganan penyakit kardiovaskular', 'floor' => 'Lantai 2', 'phone' => '021-5003'],
            ['name' => 'Poli Saraf', 'code' => 'SARAF', 'description' => 'Penanganan gangguan neurologis dan stroke', 'floor' => 'Lantai 2', 'phone' => '021-5004'],
            ['name' => 'Poli Mata', 'code' => 'MATA', 'description' => 'Pemeriksaan dan operasi mata, katarak', 'floor' => 'Lantai 2', 'phone' => '021-5005'],
            ['name' => 'Poli THT', 'code' => 'THT', 'description' => 'Telinga, Hidung, Tenggorokan', 'floor' => 'Lantai 2', 'phone' => '021-5006'],
            ['name' => 'Poli Kulit & Kelamin', 'code' => 'KULIT', 'description' => 'Penyakit kulit dan kelamin', 'floor' => 'Lantai 3', 'phone' => '021-5007'],
            ['name' => 'Poli Anak', 'code' => 'ANAK', 'description' => 'Pelayanan kesehatan anak dan imunisasi', 'floor' => 'Lantai 3', 'phone' => '021-5008'],
            ['name' => 'Poli Kebidanan & Kandungan', 'code' => 'OBGYN', 'description' => 'Kehamilan, persalinan, dan kesehatan reproduksi', 'floor' => 'Lantai 3', 'phone' => '021-5009'],
            ['name' => 'Poli Bedah', 'code' => 'BEDAH', 'description' => 'Bedah umum, minor, dan laparoskopi', 'floor' => 'Lantai 4', 'phone' => '021-5010'],
            ['name' => 'Poli Orthopedi', 'code' => 'ORTHO', 'description' => 'Tulang, sendi, dan cedera olahraga', 'floor' => 'Lantai 4', 'phone' => '021-5011'],
            ['name' => 'Poli Penyakit Dalam', 'code' => 'PDLM', 'description' => 'Diabetes, hipertensi, ginjal, dan metabolik', 'floor' => 'Lantai 4', 'phone' => '021-5012'],
        ];

        $doctors = Doctor::all();

        foreach ($polys as $index => $data) {
            $poli = Polyclinic::create($data);
            // Assign 1-2 random doctors to each poli
            $poli->doctors()->attach(
                $doctors->random(min(2, $doctors->count()))->pluck('id')->toArray()
            );
        }
    }
}
