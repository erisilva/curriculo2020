<?php

use Illuminate\Database\Seeder;

class CargosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->insert([
            'descricao' => 'Médico - Pediatra - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Médico - Ginecologista Obstetra - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Médico - Psiquiatra - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Médico - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Agente de Combate à Endemias - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Assistente Administrativo - 30h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Assistente Social - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Auxiliar em Saúde Bucal - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Cirurgião-Dentista - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Cirurgião-Dentista da Família - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Enfermeiro - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Farmacêutico Bioquímico - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Fisioterapeuta - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Nutricionista - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Psicólogo - 20h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Técnico em Enfermagem - 30h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Técnico em Enfermagem - 40h',
        ]);
        DB::table('cargos')->insert([
            'descricao' => 'Técnico em Saúde Bucal - 30h',
        ]);
    }
}
