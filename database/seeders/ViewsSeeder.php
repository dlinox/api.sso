<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doesViewExist = DB::select("SHOW TABLES LIKE 'attentions_view'");
        if (!empty($doesViewExist)) {
            DB::statement('DROP VIEW attentions_view');
        }


        $sql = "
        CREATE VIEW attentions_view AS
        SELECT 
            attentions.*,
            CASE
                WHEN attentions.person_type = '001' THEN 
                    (SELECT CONCAT_WS(' ', s.name, s.paternal_surname, s.maternal_surname) FROM students s WHERE s.id = attentions.person_id)
                WHEN attentions.person_type = '002' THEN 
                    (SELECT CONCAT_WS(' ', p.name, p.paternal_surname, p.maternal_surname) FROM professors p WHERE p.id = attentions.person_id)
                WHEN attentions.person_type = '003' THEN 
                    (SELECT CONCAT_WS(' ', w.name, w.paternal_surname, w.maternal_surname) FROM workers w WHERE w.id = attentions.person_id)
                WHEN attentions.person_type = '004' THEN 
                    (SELECT CONCAT_WS(' ', e.name, e.paternal_surname, e.maternal_surname) FROM externals e WHERE e.id = attentions.person_id)
            END AS person_name,
            -- document_number,

            CASE
                WHEN attentions.person_type = '001' THEN 
                    (SELECT s.document_number FROM students s WHERE s.id = attentions.person_id)
                WHEN attentions.person_type = '002' THEN 
                    (SELECT p.document_number FROM professors p WHERE p.id = attentions.person_id)
                WHEN attentions.person_type = '003' THEN 
                    (SELECT w.document_number FROM workers w WHERE w.id = attentions.person_id)
                WHEN attentions.person_type = '004' THEN 
                    (SELECT e.document_number FROM externals e WHERE e.id = attentions.person_id)
            END AS person_document,
            CASE
                WHEN attentions.person_type = '001' THEN 
                    (SELECT s.student_code FROM students s WHERE s.id = attentions.person_id)
                WHEN attentions.person_type = '002' THEN 
                    (SELECT p.code FROM professors p WHERE p.id = attentions.person_id)
                WHEN attentions.person_type = '003' THEN 
                    (SELECT w.code FROM workers w WHERE w.id = attentions.person_id)
                WHEN attentions.person_type = '004' THEN 
                    '-'
            END AS person_code,
            CASE
                WHEN attentions.person_type = '001' THEN 
                    (SELECT c.name FROM careers c WHERE c.code = (SELECT s.career_code FROM students s WHERE s.id = attentions.person_id))
                WHEN attentions.person_type = '002' THEN 
                    (SELECT c.name FROM careers c WHERE c.code = (SELECT p.career_code FROM professors p WHERE p.id = attentions.person_id))
                WHEN attentions.person_type = '003' THEN 
                    (SELECT o.name FROM offices o WHERE o.id = (SELECT w.office_id FROM workers w WHERE w.id = attentions.person_id))
                WHEN attentions.person_type = '004' THEN 'Externo'
            END AS unit_name,

            CASE
                WHEN attentions.person_type = '001' THEN 
                    (SELECT c.code FROM careers c WHERE c.code = (SELECT s.career_code FROM students s WHERE s.id = attentions.person_id))
                WHEN attentions.person_type = '002' THEN 
                    (SELECT c.code FROM careers c WHERE c.code = (SELECT p.career_code FROM professors p WHERE p.id = attentions.person_id))
                WHEN attentions.person_type = '003' THEN 
                    (SELECT o.id FROM offices o WHERE o.id = (SELECT w.office_id FROM workers w WHERE w.id = attentions.person_id))
                WHEN attentions.person_type = '004' THEN 'Externo'
            END AS unit_code,

            type_attentions.name AS type_attention_name,
            users.office_id AS user_office_id
        FROM 
            attentions
        JOIN 
            type_attentions ON type_attentions.id = attentions.type_attention_id
        JOIN 
            users ON users.id = attentions.user_id
        LEFT JOIN 
            offices ON offices.id = users.office_id;
        ";

        DB::statement($sql);
    }
}
