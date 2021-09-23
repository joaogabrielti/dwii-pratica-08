<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller {
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
        $clientes = Cliente::all();
        return view('models.cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('models.cliente.create');
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
            'telefone' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:clientes']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $cliente = Cliente::create($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($cliente);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente) {
        return response()->json($cliente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente) {
        return view('models.cliente.create', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente) {
        $validatedData = array_map('mb_strtoupper', $request->validate([
            'nome' => ['required', 'string'],
            'telefone' => ['required', 'string'],
            'email' => ['required', 'email']
        ], $this->messages));

        DB::beginTransaction();
        try {
            $cliente->update($validatedData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente) {
        DB::beginTransaction();
        try {
            $cliente->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }

        return response()->json($cliente);
    }
}
