<?php

namespace App\Http\Controllers;

use App\Perpage;
use App\Especialidade;

use Response;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class EspecialidadeController extends Controller
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
    public function __construct(\App\Reports\EspecialidadeReport $pdf)
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
        if (Gate::denies('especialidade-index')) {
            abort(403, 'Acesso negado.');
        }

        $especialidades = new Especialidade;

        // ordena
        $especialidades = $especialidades->orderBy('descricao', 'asc');

        // se a requisição tiver um novo valor para a quantidade
        // de páginas por visualização ele altera aqui
        if(request()->has('perpage')) {
            session(['perPage' => request('perpage')]);
        }

        // consulta a tabela perpage para ter a lista de
        // quantidades de paginação
        $perpages = Perpage::orderBy('valor')->get();

        // paginação
        $especialidades = $especialidades->paginate(session('perPage', '5'));

        return view('especialidades.index', compact('especialidades', 'perpages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('cargo-create')) {
            abort(403, 'Acesso negado.');
        } 
        return view('especialidades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'descricao' => 'required',
        ]);

        $especialidade = $request->all();

        Especialidade::create($especialidade); //salva

        Session::flash('create_especialidade', 'Especialidade cadastrada com sucesso!');

        return redirect(route('especialidades.index')); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Gate::denies('especialidade-show')) {
            abort(403, 'Acesso negado.');
        }

        $especialidades = Especialidade::findOrFail($id);

        return view('especialidades.show', compact('especialidades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('especialidade-edit')) {
            abort(403, 'Acesso negado.');
        }

        $especialidade = Especialidade::findOrFail($id);

        return view('especialidades.edit', compact('especialidade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'descricao' => 'required',
        ]);

        $especialidade = Especialidade::findOrFail($id);
            
        $especialidade->update($request->all());
        
        Session::flash('edited_especialidade', 'Especialidade alterada com sucesso!');

        return redirect(route('especialidades.edit', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
