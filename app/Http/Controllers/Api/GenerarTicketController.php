<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; // <-- Ahora sí coincide
use App\Models\User;
use App\Notifications\BusinessEventNotification;
use Illuminate\Support\Facades\Log;

class GenerarTicketController extends Controller 
{
    public function index() 
    {
        try {
            // Usamos Tickets en plural
            return response()->json(Ticket::orderBy('created_at', 'desc')->get(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Usamos Tickets en plural
            $ticket = Ticket::create([
                'cliente'    => $request->cliente ?? 'Usuario FluxPay', 
                'mensaje'    => $request->mensaje, 
                'prioridad'  => $request->prioridad,
                'estado'     => 'Pendiente',
                'negocio_id' => 1,
            ]);

            User::where('idrol', 1)->get()->each(function (User $admin) use ($ticket) {
                $admin->notify(new BusinessEventNotification(
                    'Nuevo evento de negocio',
                    "El negocio #{$ticket->negocio_id} creó un ticket con prioridad {$ticket->prioridad}.",
                    'business_ticket_created'
                ));
            });

            return response()->json($ticket, 201);
        } catch (\Exception $e) {
            Log::error("Error en Ticket Store: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}