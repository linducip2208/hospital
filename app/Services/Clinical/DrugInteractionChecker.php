<?php

namespace App\Services\Clinical;

use App\Models\Patient;

class DrugInteractionChecker
{
    /**
     * Pasangan interaksi obat yang diketahui (nama generik lowercase).
     * Format: [obat_a, obat_b, tingkat, keterangan].
     */
    protected array $interactions = [
        ['warfarin', 'aspirin', 'major', 'Meningkatkan risiko perdarahan berat.'],
        ['warfarin', 'ibuprofen', 'major', 'Meningkatkan risiko perdarahan gastrointestinal.'],
        ['aspirin', 'ibuprofen', 'moderate', 'Mengurangi efek kardioprotektif aspirin.'],
        ['clopidogrel', 'omeprazole', 'moderate', 'Omeprazole menurunkan efektivitas clopidogrel.'],
        ['simvastatin', 'amlodipine', 'moderate', 'Meningkatkan kadar simvastatin, risiko miopati.'],
        ['metformin', 'kontras', 'major', 'Risiko asidosis laktat; hentikan sebelum pemeriksaan kontras.'],
        ['captopril', 'spironolactone', 'major', 'Risiko hiperkalemia berat.'],
        ['digoxin', 'furosemide', 'moderate', 'Hipokalemia akibat furosemide meningkatkan toksisitas digoxin.'],
        ['tramadol', 'sertraline', 'major', 'Risiko sindrom serotonin.'],
        ['ciprofloxacin', 'tizanidine', 'major', 'Meningkatkan kadar tizanidine, hipotensi & sedasi.'],
        ['amoxicillin', 'methotrexate', 'moderate', 'Meningkatkan toksisitas methotrexate.'],
        ['paracetamol', 'warfarin', 'moderate', 'Penggunaan rutin dapat meningkatkan efek warfarin.'],
    ];

    /**
     * Cek interaksi antar daftar obat + alergi pasien.
     *
     * @param  array<int,string>  $drugNames
     * @return array{interactions: array, allergies: array, has_warning: bool}
     */
    public function check(array $drugNames, ?Patient $patient = null): array
    {
        $normalized = array_map(fn ($d) => strtolower(trim($d)), array_filter($drugNames));

        $found = [];
        for ($i = 0; $i < count($normalized); $i++) {
            for ($j = $i + 1; $j < count($normalized); $j++) {
                $a = $normalized[$i];
                $b = $normalized[$j];
                foreach ($this->interactions as [$x, $y, $level, $note]) {
                    if ((str_contains($a, $x) && str_contains($b, $y)) || (str_contains($a, $y) && str_contains($b, $x))) {
                        $found[] = [
                            'drug_a' => $drugNames[$i],
                            'drug_b' => $drugNames[$j],
                            'level' => $level,
                            'note' => $note,
                        ];
                    }
                }
            }
        }

        $allergyHits = [];
        if ($patient && $patient->allergies) {
            $allergyList = preg_split('/[,;\n]+/', strtolower($patient->allergies));
            foreach ($normalized as $idx => $drug) {
                foreach ($allergyList as $allergy) {
                    $allergy = trim($allergy);
                    if ($allergy !== '' && str_contains($drug, $allergy)) {
                        $allergyHits[] = [
                            'drug' => $drugNames[$idx],
                            'allergy' => $allergy,
                        ];
                    }
                }
            }
        }

        return [
            'interactions' => $found,
            'allergies' => $allergyHits,
            'has_warning' => ! empty($found) || ! empty($allergyHits),
        ];
    }
}
