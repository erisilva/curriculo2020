<?php

namespace App\Http\Controllers;

use App\Curriculo;
use App\Cargo;

use App\Perpage;


use Response;

use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon; // tratamento de datas

use App\Rules\Cpf; // validação de um cpf

use Illuminate\Support\Facades\Redirect; // para poder usar o redirect

class ListaController extends Controller
{

    protected $pdf;

    /**
     * Construtor.
     *
     * precisa estar logado ao sistema
     * precisa ter a conta ativa (access)
     *
     * @return 
     */
    public function __construct(\App\Reports\CurriculoReport $pdf)
    {
        $this->middleware(['middleware' => 'auth']);
        $this->middleware(['middleware' => 'hasaccess']);

        $this->pdf = $pdf;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curriculos = new Curriculo;

        // filtros

        if (request()->has('codigo')){
            if (request('codigo') != ""){
                $curriculos = $curriculos->where('id', '=', request('codigo'));
            }
        }

        if (request()->has('nome')){
            $curriculos = $curriculos->where('nome', 'like', '%' . request('nome') . '%');
        }

        if (request()->has('cidade')){
            $curriculos = $curriculos->where('cidade', 'like', '%' . request('cidade') . '%');
        }

        if (request()->has('cargo_id')){
            if (request('cargo_id') != ""){
                $curriculos = $curriculos->where('cargo_id', '=', request('cargo_id'));
            }
        } 

        // ordena
        $curriculos = $curriculos->orderBy('id', 'desc');

        // se a requisição tiver um novo valor para a quantidade
        // de páginas por visualização ele altera aqui
        if(request()->has('perpage')) {
            session(['perPage' => request('perpage')]);
        }

        // consulta a tabela perpage para ter a lista de
        // quantidades de paginação
        $perpages = Perpage::orderBy('valor')->get();

        // paginação
        $curriculos = $curriculos->paginate(session('perPage', '5'))->appends([          
            'codigo' => request('codigo'),
            'nome' => request('nome'),
            'cargo_id' => request('cargo_id'),
            'cidade' => request('cidade'),      
            ]);

        // consulta a tabela dos cargos
        $cargos = Cargo::orderBy('id', 'asc')->get();

        return view('lista.index', compact('curriculos', 'perpages', 'cargos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $curriculo = Curriculo::findOrFail($id);

        return view('lista.show', compact('curriculo'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Curriculo::findOrFail($id)->delete();

        Session::flash('deleted_curriculo', 'Currículo enviado para a lixeira com sucesso!');

        return redirect(route('lista.index'));
    }


    /**
     * Exportação para planilha (csv)
     *
     * @param  int  $id
     * @return Response::stream()
     */
    public function exportcsv()
    {
        if (Gate::denies('cargo-export')) {
            abort(403, 'Acesso negado.');
        }

       $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=Curriculos_' .  date("Y-m-d H:i:s") . '.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $curriculos = DB::table('curriculos');

        //joins
        $curriculos = $curriculos->join('cargos', 'cargos.id', '=', 'curriculos.cargo_id');

        $curriculos = $curriculos->select(
            'curriculos.id as codigo', 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%d/%m/%Y\') AS data_cadastro'), 
            DB::raw('DATE_FORMAT(curriculos.created_at, \'%H:%i\') AS hora_cadastro'),
            'curriculos.nome',
            DB::raw('DATE_FORMAT(curriculos.nascimento, \'%d/%m/%Y\') AS data_nascimento'), 
            DB::raw('IF(curriculos.genero=\'f\', \'Feminino\', \'Masculino\') as genero'),
            'curriculos.cpf',
            'curriculos.email',
            'curriculos.cel1',
            'curriculos.cel2',
            'curriculos.tel',
            'curriculos.cep',
            'curriculos.logradouro',
            'curriculos.numero',
            'curriculos.complemento',
            'curriculos.bairro',
            'curriculos.cidade',
            'curriculos.uf',
            DB::raw('IF(curriculos.origemcontagem=\'s\', \'Sim\', \'Não\') as Reside_em_Contagem'),
            'cargos.descricao as cargo',
            'curriculos.registro',
            DB::raw('IF(curriculos.c20h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_20h'),
            DB::raw('IF(curriculos.c24h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_24h'),
            DB::raw('IF(curriculos.c30h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_30h'),
            DB::raw('IF(curriculos.c40h=\'s\', \'Sim\', \'Não\') as Carga_Horaria_40h'),
            DB::raw('IF(curriculos.turnomanha=\'s\', \'Sim\', \'Não\') as Turno_Manha'),
            DB::raw('IF(curriculos.turnotarde=\'s\', \'Sim\', \'Não\') as Turno_Tarde'),
            DB::raw('IF(curriculos.turnonoite=\'s\', \'Sim\', \'Não\') as Turno_Noite'),

            DB::raw('IF(curriculos.seg=\'s\', \'Sim\', \'Não\') as SEG'),
            DB::raw('IF(curriculos.ter=\'s\', \'Sim\', \'Não\') as TER'),
            DB::raw('IF(curriculos.qua=\'s\', \'Sim\', \'Não\') as QUA'),
            DB::raw('IF(curriculos.qui=\'s\', \'Sim\', \'Não\') as QUI'),
            DB::raw('IF(curriculos.sex=\'s\', \'Sim\', \'Não\') as SEX'),
            DB::raw('IF(curriculos.sab=\'s\', \'Sim\', \'Não\') as SAB'),
            DB::raw('IF(curriculos.dom=\'s\', \'Sim\', \'Não\') as DOM'),


            DB::raw('IF(curriculos.deficiente=\'s\', \'Sim\', \'Não\') as Deficiente'),

            DB::raw('IF(curriculos.protadordoencas=\'s\', \'Sim\', \'Não\') as Portador_Doenca_Cronica'),
            'curriculos.qualdoenca as Quais_Doencas',
            'curriculos.outrasdoenca as Outras_Doencas',

            DB::raw('IF(curriculos.gestante=\'s\', \'Sim\', \'Não\') as Gestante_Lactante'),

            DB::raw('IF(curriculos.temparente=\'s\', \'Sim\', \'Não\') as Tem_Parente_PMC'),
            'curriculos.nomeparente as Nome_Parente_PMC',
        );

        if (request()->has('codigo')){
            if (request('codigo') != ""){
                $curriculos = $curriculos->where('curriculos.id', '=', request('codigo'));
            }
        }

        if (request()->has('nome')){
            $curriculos = $curriculos->where('curriculos.nome', 'like', '%' . request('nome') . '%');
        }

        if (request()->has('cidade')){
            $curriculos = $curriculos->where('curriculos.cidade', 'like', '%' . request('cidade') . '%');
        }

        if (request()->has('cargo_id')){
            if (request('cargo_id') != ""){
                $curriculos = $curriculos->where('curriculos.cargo_id', '=', request('cargo_id'));
            }
        } 

        $curriculos = $curriculos->orderBy('curriculos.id', 'asc');

        $list = $curriculos->get()->toArray();

        # converte os objetos para uma array
        $list = json_decode(json_encode($list), true);

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

       $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            fputs($FH, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            foreach ($list as $row) {
                fputcsv($FH, $row, chr(9));
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    } 

    /**
     * Exportação para pdf
     *
     * @param  
     * @return 
     */
    public function exportpdf()
    {
        if (Gate::denies('cargo-export')) {
            abort(403, 'Acesso negado.');
        }

        $this->pdf->AliasNbPages();   
        $this->pdf->SetMargins(12, 10, 12);
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->AddPage();

        $cargos = DB::table('cargos');

        $cargos = $cargos->select('descricao');


        $cargos = $cargos->orderBy('descricao', 'asc');    


        $cargos = $cargos->get();

        foreach ($cargos as $cargo) {
            $this->pdf->Cell(186, 6, utf8_decode($cargo->descricao), 0, 0,'L');
            $this->pdf->Ln();
        }

        $this->pdf->Output('D', 'Cargos_' .  date("Y-m-d H:i:s") . '.pdf', true);
        exit;

    }
}
