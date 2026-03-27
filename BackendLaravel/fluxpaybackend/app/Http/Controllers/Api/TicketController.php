<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    
    public function index()
    {
        $tickets = Ticket::with('negocio')->get();

        return response()->json($tickets);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'cliente' => 'required|string',
            'mensaje' => 'required|string',
            'negocio_id' => 'required|exists:negocio,id',
            'prioridad' => 'required|string'
        ]);

        $ticket = Ticket::create([
            'cliente' => $request->cliente,
            'mensaje' => $request->mensaje,
            'estado' => 'Pendiente', 
            'prioridad' => $request->prioridad,
            'negocio_id' => $request->negocio_id
        ]);

        return response()->json([
            'message' => 'Ticket creado correctamente',
            'ticket' => $ticket
        ], 201);
    }

   
    public function show($id)
    {
        $ticket = Ticket::with('negocio')->findOrFail($id);

        return response()->json($ticket);
    }

   
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
    'estado' => 'required|string',
    'prioridad' => 'nullable|string'
]);

$ticket->update([
    'estado' => $request->estado,
    'prioridad' => $request->prioridad ?? $ticket->prioridad
]);

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'ticket' => $ticket
        ]);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return response()->json([
            'message' => 'Ticket eliminado'
        ]);
    }
}