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
            [
                'name' => 'Problemas familiares',
                'type' => '001',
            ],
            [
                'name' => 'Problemas Académicos',
                'type' => '001',
            ],
            [
                'name' => 'Problemas de Salud',
                'type' => '001',
            ],
            [
                'name' => 'Adicciones',
                'type' => '001',
            ],
            [
                'name' => 'Comedor',
                'type' => '001',
            ],
            [
                'name' => 'Visitas Domiciliarias',
                'type' => '001',
            ],
            [
                'name' => 'Visitas Hospitalarias',
                'type' => '001',
            ],
            [
                'name' => 'Evaluación Socioeconómica',
                'type' => '001',
            ],
            [
                'name' => 'Bienestar Emocional y Consejería Social',
                'type' => '001',
            ],
            [
                'name' => 'Subvenciones Económicas',
                'type' => '001',
            ],
            [
                'name' => 'Subvenciones Alimentarias',
                'type' => '001',
            ],
            [
                'name' => 'Inducciones',
                'type' => '001',
            ],
            [
                'name' => 'Actividades Grupales',
                'type' => '001',
            ],
            [
                'name' => 'Licencias por Salud',
                'type' => '002',
            ],
            [
                'name' => 'Subsidios por Salud',
                'type' => '002',
            ],
            [
                'name' => 'Subsidios por Maternidad',
                'type' => '002',
            ],
            [
                'name' => 'Derecho Habientes',
                'type' => '002',
            ],
            [
                'name' => 'Visitas Hospitalarias',
                'type' => '002',
            ],
            [
                'name' => 'Visitas Domiciliarias',
                'type' => '002',
            ],
            [
                'name' => 'Orientaciones Varias',
                'type' => '002',
            ],
            [
                'name' => 'Baja por Fallecimiento',
                'type' => '002',
            ],
            [
                'name' => 'Actividades Grupales',
                'type' => '002',
            ],
            [
                'name' => 'Campañas de Salud',
                'type' => '002',
            ],
            [
                'name' => 'Charlas',
                'type' => '002',
            ],
            [
                'name' => 'Otros',
                'type' => '002',
            ],
            [
                'name' => 'Gestión Administrativa',
                'type' => '003',
            ],
            [
                'name' => 'Elaboración de Planes de Actividades',
                'type' => '003',
            ],
            [
                'name' => 'Elaboración de Proyectos',
                'type' => '003',
            ],
            [
                'name' => 'Elaboración Plan de Trabajo',
                'type' => '003',
            ],
            [
                'name' => 'Elaboración Metas Físicas',
                'type' => '003',
            ],
            [
                'name' => 'Elaboración Plan Memoria',
                'type' => '003',
            ],
            [
                'name' => 'Documentos Varios',
                'type' => '003',
            ],
            [
                'name' => 'Oficios',
                'type' => '003',
            ],
            [
                'name' => 'Informes',
                'type' => '003',
            ],
            [
                'name' => 'Cartas',
                'type' => '003',
            ],
            [
                'name' => 'Memos',
                'type' => '003',
            ],
        ];

        foreach ($typesAttentions as $typeAttention) {
            \App\Models\TypeAttention::create([
                'name' => $typeAttention['name'],
                'type' => $typeAttention['type'],
                'status' => true,
            ]);
        }
    }
}
