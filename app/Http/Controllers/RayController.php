<?php

namespace App\Http\Controllers;

use App\Models\Ray;
use App\Traits\HttpResponses;
use App\Http\Resources\RaysResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRayRequest;
use App\Http\Requests\UpdateRayRequest;

class RayController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return RaysResource::collection(
        #   Ray::where('user_id',Auth::user()->id)->get() ) ; // get rays thats users are authenticated

        return RaysResource::collection(
            Ray::where('doctor_id', Auth::user()->id)->get()
        ); // get rays thats users are authenticated

    }



    public function store(StoreRayRequest $request)
    {
        $request->validated($request->all());


        $ray = Ray::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'doctor_id' => Auth::user()->id, /* This is wrong */
            'user_id' => $request->user_id,

            'ray_name' => $request -> ray_name,
            'result'   => $request -> result,
            'date'     => $request -> date,
        ]);

        return new RaysResource($ray);
    }


    public function show(Ray $ray)
    {
        if (Auth::user()->id !== $ray->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new RaysResource($ray);
    }


    public function update(UpdateRayRequest $request, Ray $ray)
    {
        $ray = Ray::find($ray->id);
        if (Auth::user()->id !== $ray->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $ray->update($request->all());
        $ray->save();

        return new RaysResource($ray);
    }

    public function destroy(Ray $ray)
    {
        // way 1 :
        // $ray->delete();
        // return $this->success('Ray was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($ray) ? $this->isNotAuthorized($ray) : $ray->delete();
        if (Auth::user()->id !== $ray->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        } else {
            $ray->delete();
            return $this->success('Ray Deleted Successfuly ', null, 200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($ray)
    {
        if (Auth::user()->id !== $ray->doctor_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
