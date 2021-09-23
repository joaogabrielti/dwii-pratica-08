<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecialidadeController extends Controller {
    private $messages = [
        'required' => 'O preenchimento do campo [:attribute] é obrigatório!',
        'email' => 'O campo [:attribute] precisa ser um email válido!',
        'max' => 'O campo [:attribute] possui tamanho máximo de [:max] caracteres!',
        'min' => 'O campo [:attribute] possui tamanho mínimo de [:min] caracteres!',
        'unique' => 'O campo [:attribute] ja está cadastrado!',
        'size' => 'O campo [:attribute] precisa ter [:size] de tamanho!'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $especialidades = Especialidade::all();
        return view('models.especialidade.index', compact('especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('models.especialidade.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validatedData = array_map('mb_strtoupper', $request->validate([
            'nome' => ['required', 'string'],
            'descricao' => ['required', 'string']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $especialidade = Especialidade::create($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($especialidade);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function show(Especialidade $especialidade) {
        return response()->json($especialidade);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Especialidade $especialidade) {
        return view('models.especialidade.create', compact('especialidade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Especialidade $especialidade) {
        $validatedData = array_map('mb_strtoupper', $request->validate([
            'nome' => ['required', 'string'],
            'descricao' => ['required', 'string']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $especialidade->update($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($especialidade);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Especialidade $especialidade) {
        DB::beginTransaction();
        try {
            $especialidade->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($especialidade);
    }
}
