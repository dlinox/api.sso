<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesAttentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $typesAttentions = [
            "Lic. Salud",
            "Subsicio por salud",
            "Subsidio por maternidad",
            "Lic. Por duelo",
            "Serecho habientes",
            "Casos sociales:",
            "Alcoholismo",
            "Otros",
        ];

        foreach ($typesAttentions as $typeAttention) {
            \App\Models\TypeAttention::create([
                'name' => $typeAttention,
                'status' => true,
            ]);
        }
    }
}
