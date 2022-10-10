<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return response()->json([
            'message'   => 'All data successfully displayed!',
            'data'      => Auth::user()->notes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title'         => 'required|max:255',
            'description'   => 'required',
            'count'         => 'required|min:1',
            'price'         => 'required|min:1',
            'type'          => 'required|in:income,expense',
            'date'          => 'required|date',
        ]);

        $validated['user_id']   = auth()->user()->id;
        $validated['date']      = date('Y-m-d', strtotime($request->date));

        $note   = new Note($validated);
        $note->save();

        return response()->json([
            'message'   => 'Save, has been successfully',
            'data'      => $note
        ]);
    }

    public function show(Request $request, $id)
    {
        $note   = Note::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (empty($note)) {
            abort('404', 'Data not found!');
        }
        return response()->json([
            'message'   => 'success',
            'data'      => $note
        ]);
    }

    public function update(Request $request, $id)
    {
        $note   = Note::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (empty($note)) {
            abort('404', 'Data not found!');
        }

        $validated = $this->validate($request, [
            'title'         => 'required|max:255',
            'description'   => 'required',
            'count'         => 'required|min:1',
            'price'         => 'required|min:1',
            'type'          => 'required|in:income,expense',
            'date'          => 'required|date',
        ]);

        $validated['user_id']   = auth()->user()->id;
        $validated['date']      = date('Y-m-d', strtotime($request->date));

        $note->update($validated);

        return response()->json([
            'message'   => 'Updated, has been successfully',
            'data'      => $note
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $note   = Note::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (empty($note)) {
            abort('404', 'Data not found!');
        }
        $note->delete();
        return response()->json([
            'message'   => 'Deleted, has been successfully'
        ]);
    }
}
