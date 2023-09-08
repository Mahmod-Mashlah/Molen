<?php

namespace App\Http\Controllers;

use App\Models\Analyzing;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AnalyzingsResource;
use App\Http\Requests\StoreAnalyzingRequest;
use App\Http\Requests\UpdateAnalyzingRequest;

class AnalyzingController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return AnalyzingsResource::collection(
        #   Analyzing::where('user_id',Auth::user()->id)->get() ) ; // get analizings thats users are authenticated

        return AnalyzingsResource::collection(
            Analyzing::where('doctor_id', Auth::user()->id)->get()
        ); // get analizings thats users are authenticated

    }



    public function store(StoreAnalyzingRequest $request)
    {
        $request->validated($request->all());


        $analizing = Analyzing::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'name' => $request->name,
            'result' => $request->result,
            'naturalizing' => $request->naturalizing,
            'date' => $request->date,

        ]);

        return new AnalyzingsResource($analizing);
    }


    public function show(Analyzing $analizing)
    {
        if (Auth::user()->id !== $analizing->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new AnalyzingsResource($analizing);
    }


    public function update(UpdateAnalyzingRequest $request, Analyzing $analizing)
    {
        $analizing = Analyzing::find($analizing->id);
        if (Auth::user()->id !== $analizing->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $analizing->update($request->all());
        $analizing->save();

        return new AnalyzingsResource($analizing);
    }

    public function destroy(Analyzing $analizing)
    {
        // way 1 :
        // $analizing->delete();
        // return $this->success('Analyzing was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($analizing) ? $this->isNotAuthorized($analizing) : $analizing->delete();
        if (Auth::user()->id !== $analizing->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $analizing->delete() ;
            return $this->success('Analyzing Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($analizing)
    {
        if (Auth::user()->id !== $analizing->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
