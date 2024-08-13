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
            "Problemas familiares",
            "Problemas Académicos",
            "Problemas de Salud",
            "Adicciones",
            "Comedor",
            "Visitas Domiciliarias",
            "Visitas Hospitalarias",
            "Evaluación Socioeconómica",
            "Bienestar Emocional y Consejería Social",
            "Subvenciones Económicas",
            "Subvenciones Alimentarias",
            "Inducciones",
            "Actividades Grupales",

            "Licencias por Salud",
            "Subsidios por Salud",
            "Subsidios por Maternidad",
            "Derecho Habientes",
            "Visitas Hospitalarias",
            "Visitas Domiciliarias",
            "Orientaciones Varias",
            "Baja por Fallecimiento",
            "Actividades Grupales",
            "Campañas de Salud",
            "Charlas",
            "Otros",

            "Gestión Administrativa",
            "Elaboración de Planes de Actividades",
            "Elaboración de Proyectos",
            "Elaboración Plan de Trabajo",
            "Elaboración Metas Físicas",
            "Elaboración Plan Memoria",
            "Documentos Varios",
            "Oficios",
            "Informes",
            "Cartas",
            "Memos",


        ];
        foreach ($typesAttentions as $typeAttention) {
            \App\Models\TypeAttention::create([
                'name' => $typeAttention,
                'status' => true,
            ]);
        }
    }
}
