<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->is_admin) {
            return Redirect::route('dashboard');
        }

        return Inertia::render('Ticket/Types', [
            'ticketTypesPaginated' => TicketType::paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255'
        ]);

        TicketType::create($request->all());

        return redirect('/types');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|max:255'
        ]);

        TicketType::where('id', $request->id)->update(['type' => $validated['type']]);

        return Redirect::route('types');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ticketType = TicketType::where('type', $request->type)->first();
        $ticketType->delete();

        return redirect('/types');
    }
}
