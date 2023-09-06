<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Doctor;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotesResource;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;

class NoteController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return NotesResource::collection(
        #   Note::where('user_id',Auth::user()->id)->get() ) ; // get notes thats users are authenticated

        return NotesResource::collection(
            Note::where('doctor_id', Auth::user()->id)->get()
        ); // get notes thats users are authenticated

    }



    public function store(StoreNoteRequest $request)
    {
        $request->validated($request->all());


        $note = Note::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'title' => $request->title,
            'description' => $request->description,
        ]);

        return new NotesResource($note);
    }


    public function show(Note $note)
    {
        if (Auth::user()->id !== $note->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new NotesResource($note);
    }


    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note = Note::find($note->id);
        if (Auth::user()->id !== $note->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $note->update($request->all());
        $note->save();

        return new NotesResource($note);
    }

    public function destroy(Note $note)
    {
        // way 1 :
        // $note->delete();
        // return $this->success('Note was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($note) ? $this->isNotAuthorized($note) : $note->delete();
        if (Auth::user()->id !== $note->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $note->delete() ;
            return $this->success('Note Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($note)
    {
        if (Auth::user()->id !== $note->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
