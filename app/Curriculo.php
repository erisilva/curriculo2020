<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome', 'email', 'cpf', 'genero', 'nascimento', 'cargo_id', 'registro', 'cep', 'logradouro', 'bairro', 'numero', 'complemento', 'cidade', 'uf', 'tel', 'cel1', 'cel2', 'origemcontagem', 'c20h', 'c24h', 'c30h', 'c40h', 'turnomanha', 'turnotarde', 'turnonoite', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom', 'deficiente', 'protadordoencas', 'qualdoenca', 'outrasdoenca', 'gestante', 'temparente', 'nomeparente', 'arquivoNome', 'arquivoLocal', 'arquivoUrl',
    ];

    protected $dates = ['created_at', 'nascimento'];

    public function cargo()
    {
        return $this->belongsTo('App\Cargo');
    }


}
