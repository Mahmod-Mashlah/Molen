<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SurgeriesResource;
use App\Http\Requests\StoreSurgeryRequest;
use App\Http\Requests\UpdateSurgeryRequest;

class SurgeryController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return SurgerysResource::collection(
        #   Surgery::where('user_id',Auth::user()->id)->get() ) ; // get surgerys thats users are authenticated

        return SurgeriesResource::collection(
            Surgery::where('doctor_id', Auth::user()->id)->get()
        ); // get surgerys thats users are authenticated

    }



    public function store(StoreSurgeryRequest $request)
    {
        $request->validated($request->all());


        $surgery = Surgery::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,


            'sugery_name' => $request -> sugery_name ,
            'result'      => $request -> result ,
            'date'        => $request -> date ,
        ]);

        return new SurgeriesResource($surgery);
    }


    public function show(Surgery $surgery)
    {
        if (Auth::user()->id !== $surgery->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new SurgeriesResource($surgery);
    }


    public function update(UpdateSurgeryRequest $request, Surgery $surgery)
    {
        $surgery = Surgery::find($surgery->id);
        if (Auth::user()->id !== $surgery->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $surgery->update($request->all());
        $surgery->save();

        return new SurgeriesResource($surgery);
    }

    public function destroy(Surgery $surgery)
    {
        // way 1 :
        // $surgery->delete();
        // return $this->success('Surgery was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($surgery) ? $this->isNotAuthorized($surgery) : $surgery->delete();
        if (Auth::user()->id !== $surgery->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        } else {
            $surgery->delete();
            return $this->success('Surgery Deleted Successfuly ', null, 200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($surgery)
    {
        if (Auth::user()->id !== $surgery->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
