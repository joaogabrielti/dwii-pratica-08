<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeterinarioController extends Controller {
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
        $veterinarios = Veterinario::with('especialidade')->get();
        return view('models.veterinario.index', compact('veterinarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('models.veterinario.create');
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
            'crmv' => ['required', 'string'],
            'especialidade_id' => ['required', 'numeric']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $veterinario = Veterinario::create($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json(Veterinario::with('especialidade')->find($veterinario->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Veterinario  $veterinario
     * @return \Illuminate\Http\Response
     */
    public function show(Veterinario $veterinario) {
        return response()->json(Veterinario::with('especialidade')->find($veterinario->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Veterinario  $veterinario
     * @return \Illuminate\Http\Response
     */
    public function edit(Veterinario $veterinario) {
        return view('models.veterinario.create', compact('veterinario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Veterinario  $veterinario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Veterinario $veterinario) {
        $validatedData = array_map('mb_strtoupper', $request->validate([
            'nome' => ['required', 'string'],
            'crmv' => ['required', 'string'],
            'especialidade_id' => ['required']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $veterinario->update($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json(Veterinario::with('especialidade')->find($veterinario->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Veterinario  $veterinario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Veterinario $veterinario) {
        DB::beginTransaction();
        try {
            $veterinario->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($veterinario);
    }
}
