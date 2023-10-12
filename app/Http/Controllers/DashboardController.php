<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use App\Models\TicketType;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Http\Controllers\TicketController;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sort = FacadesRequest::input('sort');
        $query = $this->getAdminOrUserQuery((bool) Auth::user()->is_admin);

        if ($sort) {
            $query->orderBy($sort, FacadesRequest::input('direction'));
        }

        $query->when(FacadesRequest::input('search'), function ($query, $search) {
            $query->where('content', 'like', "%{$search}%")
                ->orWhere('tickets.id', 'like', "%{$search}%")
                ->orWhere('comment', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('ticket_types.type', 'like', "%{$search}%");
        });



        // $tickets = Auth::user()->is_admin
        //     ? Ticket::query()
        //         ->with('ticketType')
        //         ->when(FacadesRequest::input('search'), function($query, $search) {
        //             $query->where('content', 'like', "%{$search}%")
        //                 ->orWhere('comment', 'like', "%{$search}%")
        //                 ->orWhere('status', 'like', "%{$search}%");
        //         })
        //         ->when(FacadesRequest::input('sort'), function ($query, $sort) {
        //             $query->orderBy($sort, FacadesRequest::input('direction'));
        //         })
        //         ->paginate(5)
        //         ->withQueryString()
        //     : Auth::user()
        //         ->tickets()
        //         ->with('ticketType')
        //         ->when(FacadesRequest::input('search'), function($query, $search) {
        //             $query->where(function ($query) use ($search) {
        //                 $query->where('content', 'like', "%{$search}%")
        //                 ->orWhere('comment', 'like', "%{$search}%")
        //                 ->orWhere('status', 'like', "%{$search}%");
        //             });
        //         })
        //         ->when(FacadesRequest::input('sort'), function ($query, $sort) {
        //             $query->orderBy($sort, FacadesRequest::input('direction'));
        //         })
        //         ->paginate(5)
        //         ->withQueryString();

        return Inertia::render('Dashboard', [
            'tickets' => $query->paginate(5)->withQueryString(),
            'ticketTypes' => TicketType::all(),
            'filters' => FacadesRequest::only(['search', 'sort', 'direction'])
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
            'content' => 'required|string',
            'images' => 'required',
        ]);

        $temporaryFiles = TemporaryFile::query()->where('user_id', Auth::user()->id)->get();

        // if ($temporaryFile) {
        //     Storage::copy(
        //         TicketController::TICKET_TMP . $temporaryFile->folder . '/' . $temporaryFile->file,
        //         'tickets/' . $temporaryFile->folder . '/' . $temporaryFile->file
        //     );

        //     Ticket::create([
        //         'ticket_type_id' => $request->type,
        //         'content' => $request->content,
        //         'images' => $temporaryFile->folder . '/' . $temporaryFile->file,
        //         'comment' => $request?->comment,
        //         'status' => 'new',
        //         'user_id' => Auth::id()
        //     ]);

        //     Storage::deleteDirectory(TicketController::TICKET_TMP . $temporaryFile->folder);
        //     $temporaryFile->delete();
        // }

        if ($temporaryFiles) {
            $folderUID = uniqid();
            $imagePaths = [];
            foreach ($temporaryFiles as $file) {
                Storage::copy(
                    TicketController::TICKET_TMP . $file->folder . '/' . $file->file,
                    'tickets/' . $folderUID . '/' . $file->file
                );
                $imagePaths[] = $folderUID . '/' . $file->file;

                Storage::deleteDirectory(TicketController::TICKET_TMP . $file->folder);
                $file->delete();
            }

            Ticket::create([
                'ticket_type_id' => $request->type,
                'content' => $request->content,
                'images' => implode(',', $imagePaths),
                'comment' => $request?->comment,
                'status' => 'new',
                'user_id' => Auth::id()
            ]);
        }

        $query = $this->getAdminOrUserQuery((bool) Auth::user()->is_admin);

        return Inertia::render('Dashboard', [
            'tickets' => $query->paginate(5),
            'types' => TicketType::all()
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * If user is admin return all ticket query, if not just for authencticated user
     *
     * @param bool $isAdmin
     * @return mixed
     */
    protected function getAdminOrUserQuery(bool $isAdmin = false): mixed
    {
        $queryType = $isAdmin ? Ticket::query() : Auth::user()->tickets()->getQuery();

        // ::with('tickets')->orderBy(
        //      TicketType::select('type')->whereColumn('ticket_types.id', 'tickets.ticket_type_id')
        //  );
        //
        // this query is more performant than query above
        return $queryType->select(['tickets.*', 'ticket_types.type as ticket_type'])
            ->join('ticket_types', 'ticket_types.id', '=', 'tickets.ticket_type_id');
    }
}
