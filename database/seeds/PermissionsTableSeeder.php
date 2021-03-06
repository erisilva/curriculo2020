<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	// permissões possíveis para o cadastro de operadores do sistema
    	// user = operador
        DB::table('permissions')->insert([
            'name' => 'user-index',
            'description' => 'Lista de operadores',
        ]);
        DB::table('permissions')->insert([
            'name' => 'user-create',
            'description' => 'Registrar novo operador',
        ]);
        DB::table('permissions')->insert([
            'name' => 'user-edit',
            'description' => 'Alterar dados do operador',
        ]);
        DB::table('permissions')->insert([
            'name' => 'user-delete',
            'description' => 'Excluir operador',
        ]);
        DB::table('permissions')->insert([
            'name' => 'user-show',
            'description' => 'Mostrar dados do operador',
        ]);
        DB::table('permissions')->insert([
            'name' => 'user-export',
            'description' => 'Exportação de dados dos operadores',
        ]);


		// permissões possíveis para o cadastro de perfis do sistema
        //role = perfil
        DB::table('permissions')->insert([
            'name' => 'role-index',
            'description' => 'Lista de perfis',
        ]);
        DB::table('permissions')->insert([
            'name' => 'role-create',
            'description' => 'Registrar novo perfil',
        ]);
        DB::table('permissions')->insert([
            'name' => 'role-edit',
            'description' => 'Alterar dados do perfil',
        ]);
        DB::table('permissions')->insert([
            'name' => 'role-delete',
            'description' => 'Excluir perfil',
        ]);
        DB::table('permissions')->insert([
            'name' => 'role-show',
            'description' => 'Alterar dados do perfil',
        ]);
        DB::table('permissions')->insert([
            'name' => 'role-export',
            'description' => 'Exportação de dados dos perfis',
        ]);

        // permissões possíveis para o cadastro de permissões do sistema
        //permission = permissão de acesso
        DB::table('permissions')->insert([
            'name' => 'permission-index',
            'description' => 'Lista de permissões',
        ]);
        DB::table('permissions')->insert([
            'name' => 'permission-create',
            'description' => 'Registrar nova permissão',
        ]);
        DB::table('permissions')->insert([
            'name' => 'permission-edit',
            'description' => 'Alterar dados da permissão',
        ]);
        DB::table('permissions')->insert([
            'name' => 'permission-delete',
            'description' => 'Excluir permissão',
        ]);
        DB::table('permissions')->insert([
            'name' => 'permission-show',
            'description' => 'Mostrar dados da permissão',
        ]);
        DB::table('permissions')->insert([
            'name' => 'permission-export',
            'description' => 'Exportação de dados das permissões',
        ]);

        // cargos
        DB::table('permissions')->insert([
            'name' => 'cargo-index',
            'description' => 'Lista de cargos',
        ]);
        DB::table('permissions')->insert([
            'name' => 'cargo-create',
            'description' => 'Registrar novo cargo',
        ]);
        DB::table('permissions')->insert([
            'name' => 'cargo-edit',
            'description' => 'Alterar dados do cargo',
        ]);
        DB::table('permissions')->insert([
            'name' => 'cargo-delete',
            'description' => 'Excluir cargo',
        ]);
        DB::table('permissions')->insert([
            'name' => 'cargo-show',
            'description' => 'Mostrar dados do cargo',
        ]);
        DB::table('permissions')->insert([
            'name' => 'cargo-export',
            'description' => 'Exportação de dados dos cargos',
        ]);


        // especialidades
        DB::table('permissions')->insert([
            'name' => 'especialidade-index',
            'description' => 'Lista de especialidades',
        ]);
        DB::table('permissions')->insert([
            'name' => 'especialidade-create',
            'description' => 'Registrar nova especialidade',
        ]);
        DB::table('permissions')->insert([
            'name' => 'especialidade-edit',
            'description' => 'Alterar dados da especialidade',
        ]);
        DB::table('permissions')->insert([
            'name' => 'especialidade-delete',
            'description' => 'Excluir especialidade',
        ]);
        DB::table('permissions')->insert([
            'name' => 'especialidade-show',
            'description' => 'Mostrar dados da especialidade',
        ]);
        DB::table('permissions')->insert([
            'name' => 'especialidade-export',
            'description' => 'Exportação de dados das especialidades',
        ]);



    }
}
