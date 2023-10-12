<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use App\Models\Ticket;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TicketController extends Controller
{
    const TICKET_TMP = 'tickets/tmp/';

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Tickets/Render');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     *
     * @param Request $request
     * @return string
     * @throws BindingResolutionException
     */
    public function temporaryUpload(Request $request) {
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $fileName = $image->getClientOriginalName();
            $folder = uniqid('ticket', true);
            $image->storeAs(self::TICKET_TMP . $folder, $fileName);

            TemporaryFile::create([
                'folder' => $folder,
                'file' => $fileName,
                'user_id' => Auth::user()->id
            ]);

            return $folder;
        }

        return '';
    }


    /**
     *
     * @param mixed $folder
     * @return string
     */
    public function temporaryDelete($folder) {
        $temporaryFile = TemporaryFile::where('folder', $folder)->first();

        if ($temporaryFile) {
            Storage::deleteDirectory('tickets/tmp/' . $temporaryFile->folder);

            $temporaryFile->delete();
        }

        return '';
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
    public function edit(Request $request)
    {
        Ticket::find($request->id)->update(['status' => 'closed']);

        return Redirect::route('dashboard', $request->query());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|string'
        ]);

        Ticket::find($request->id)->update(['comment' => $validated['comment']]);

        return Redirect::route('dashboard', $request->query());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
