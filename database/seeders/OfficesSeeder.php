<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            "Oficina de Bienestar",
            "Oficina de Salud",
            "Oficina de Trabajo",
            "Oficina de Educación",
            "Oficina de Cultura",
            "Oficina de Deportes",
            "Oficina de Recreación",
            "Oficina de Seguridad",
            "Oficina de Medio Ambiente",
            "Oficina de Comunicación",
            "Oficina de Asuntos Sociales",
            "Oficina de Asuntos Religiosos",
            "Oficina de Asuntos Políticos",
            "Oficina de Asuntos Económicos",
            "Oficina de Asuntos Internacionales",
        ];

        foreach ($offices as $office) {
            \App\Models\Office::create([
                'name' => $office,
                'status' => true,
            ]);
        }
    }
}
