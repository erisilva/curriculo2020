<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nome');

            $table->string('email');
            
            $table->string('cpf');

            $table->enum('genero', ['m', 'f']);

            $table->dateTime('nascimento');

            $table->biginteger('cargo_id')->unsigned(); // fk

            $table->string('registro')->nullable(); // registro de classe - opcional



            // endereço
            $table->string('cep');
            $table->string('logradouro');
            $table->string('bairro');
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->string('cidade');
            $table->string('uf');

            $table->string('tel')->nullable();
            $table->string('cel1'); // ogrigatorio
            $table->string('cel2')->nullable();


            $table->enum('origemcontagem', ['s', 'n']); // reside na região metropolitana de contagem

            // disponibilidade
            $table->enum('c20h', ['s', 'n']);
            $table->enum('c24h', ['s', 'n']);
            $table->enum('c30h', ['s', 'n']);
            $table->enum('c40h', ['s', 'n']);
            
            //turno
            $table->enum('turnomanha', ['s', 'n']);
            $table->enum('turnotarde', ['s', 'n']);
            $table->enum('turnonoite', ['s', 'n']);

            // dias da semana
            $table->enum('seg', ['s', 'n']);
            $table->enum('ter', ['s', 'n']);
            $table->enum('qua', ['s', 'n']);
            $table->enum('qui', ['s', 'n']);
            $table->enum('sex', ['s', 'n']);
            $table->enum('sab', ['s', 'n']);
            $table->enum('dom', ['s', 'n']);


            $table->enum('deficiente', ['s', 'n']);

            $table->enum('protadordoencas', ['s', 'n']);
            $table->string('qualdoenca')->nullable();
            $table->string('outrasdoenca')->nullable();


            $table->enum('gestante', ['s', 'n']);

            $table->enum('temparente', ['s', 'n']);
            $table->string('nomeparente')->nullable();






            //anexo
            $table->string('arquivoNome'); // nome do arquivo
            $table->string('arquivoLocal'); // pasta onde será salvo o arquivo
            $table->text('arquivoUrl'); // url completa do arquivo

            $table->softDeletes();
            $table->timestamps();


            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculos', function (Blueprint $table) {
            $table->dropForeign('curriculos_cargo_id_foreign');
        });

        Schema::dropIfExists('curriculos');
    }
}
