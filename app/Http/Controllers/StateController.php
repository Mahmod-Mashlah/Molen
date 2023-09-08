<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StatesResource;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;

class StateController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return StatesResource::collection(
        #   State::where('user_id',Auth::user()->id)->get() ) ; // get states thats users are authenticated

        return StatesResource::collection(
            State::where('doctor_id', Auth::user()->id)->get()
        ); // get states thats users are authenticated

    }



    public function store(StoreStateRequest $request)
    {
        $request->validated($request->all());


        $state = State::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'date' => $request->date,
            'state' => $request->state,
            'indications' => $request->indications,
            'personalizing' => $request->personalizing,
        ]);

        return new StatesResource($state);
    }


    public function show(State $state)
    {
        if (Auth::user()->id !== $state->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new StatesResource($state);
    }


    public function update(UpdateStateRequest $request, State $state)
    {
        $state = State::find($state->id);
        if (Auth::user()->id !== $state->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $state->update($request->all());
        $state->save();

        return new StatesResource($state);
    }

    public function destroy(State $state)
    {
        // way 1 :
        // $state->delete();
        // return $this->success('State was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($state) ? $this->isNotAuthorized($state) : $state->delete();
        if (Auth::user()->id !== $state->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $state->delete() ;
            return $this->success('State Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($state)
    {
        if (Auth::user()->id !== $state->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
