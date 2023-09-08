<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\FamiliesResource;
use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;

class FamilyController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return FamiliesResource::collection(
        #   Family::where('user_id',Auth::user()->id)->get() ) ; // get familys thats users are authenticated

        return FamiliesResource::collection(
            Family::where('doctor_id', Auth::user()->id)->get()
        ); // get familys thats users are authenticated

    }

    public function store(StoreFamilyRequest $request)
    {
        $request->validated($request->all());


        $family = Family::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'member' => $request->member,
            'description' => $request->description,
        ]);

        return new FamiliesResource($family);
    }


    public function show(Family $family)
    {
        if (Auth::user()->id !== $family->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new FamiliesResource($family);
    }


    public function update(UpdateFamilyRequest $request, Family $family)
    {
        $family = Family::find($family->id);
        if (Auth::user()->id !== $family->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $family->update($request->all());
        $family->save();

        return new FamiliesResource($family);
    }

    public function destroy(Family $family)
    {
        // way 1 :
        // $family->delete();
        // return $this->success('Family was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($family) ? $this->isNotAuthorized($family) : $family->delete();
        if (Auth::user()->id !== $family->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $family->delete() ;
            return $this->success('Family Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($family)
    {
        if (Auth::user()->id !== $family->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
