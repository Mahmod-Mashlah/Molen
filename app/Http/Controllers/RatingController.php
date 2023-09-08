<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RatingsResource;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;

class RatingController extends Controller
{
    use HttpResponses;

    public function index()
    {
        #  return RatingsResource::collection(
        #   Rating::where('user_id',Auth::user()->id)->get() ) ; // get ratings thats users are authenticated

        return RatingsResource::collection(
            Rating::where('user_id', Auth::user()->id)->get()
        ); // get ratings thats users are authenticated

    }



    public function store(StoreRatingRequest $request)
    {
        $request->validated($request->all());


        $rating = Rating::create([

            // 'doctor_id' => DB::table('doctors')
            //                  ->where('user_id', Auth::user()->id)->pluck('id'),

            'user_id' => Auth::user()->id, /* This is wrong */
            'doctor_id' => $request->doctor_id,

            'rate_value' => $request->rate_value,
        ]);

        return new RatingsResource($rating);
    }


    public function show(Rating $rating)
    {
        if (Auth::user()->id !== $rating->user_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above
        }
        return new RatingsResource($rating);
    }


    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $rating = Rating::find($rating->id);
        if (Auth::user()->id !== $rating->user_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
            // you should use the trait   (use HttpResponses ;) above

        }

        $rating->update($request->all());
        $rating->save();

        return new RatingsResource($rating);
    }

    public function destroy(Rating $rating)
    {
        // way 1 :
        // $rating->delete();
        // return $this->success('Rating was Deleted Successfuly ',null,204);

        // way 2 : (it is best to do it in Show & Update functions [Implement Private function below] )

        // return $this->isNotAuthorized($rating) ? $this->isNotAuthorized($rating) : $rating->delete();
        if (Auth::user()->id !== $rating->user_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
        else {
            $rating->delete() ;
            return $this->success('Rating Deleted Successfuly ',null,200);
        }
        // return true (1) if the delete successfuly occoured
    }

    private function isNotAuthorized($rating)
    {
        if (Auth::user()->id !== $rating->user_id) {
            return $this->error('', 'You are not Authorized to make this request', 403);
        }
    }
}
