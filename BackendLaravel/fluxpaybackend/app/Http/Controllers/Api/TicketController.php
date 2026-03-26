<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $ticket = Ticket::create($request->all());
        return response()->json($ticket);
    }
}