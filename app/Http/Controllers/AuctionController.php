<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Resources\AuctionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\AuctionCondition;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return AuctionResource::collection(Auction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAuctionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuctionRequest $request)
    {
        $data = $request->validated();

        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath  = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $auction = Auction::create($data);

                // Create new conditons
                foreach ($data['conditions'] as $condition) {
                    $condition['auction_id'] = $auction->id;
                    $this->createCondition($condition);
                }

        return new AuctionResource($auction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $auction->user_id) {
            return abort( code: 403, message: 'Unauthorized action.');
        }
        return new AuctionResource($auction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAuctionRequest  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuctionRequest $request, Auction $auction)
    {
        $data = $request->validated();

        // Check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            // If there is an old image, delete it
            if ($auction->image) {
                $absolutePath = public_path($auction->image);
                File::delete($absolutePath);
            }
        }

        // Update auction in the database
        $auction->update($data);

        // Get ids as plain array of existing conditions
        $existingIds = $auction->conditions()->pluck('id')->toArray();
        // Get ids as plain array of new conditions
        $newIds = Arr::pluck($data['conditions'], 'id');
        // Find conditions to delete
        $toDelete = array_diff($existingIds, $newIds);
        //Find conditions to add
        $toAdd = array_diff($newIds, $existingIds);

        // Delete conditions by $toDelete array
        AuctionCondition::destroy($toDelete);

        // Create new conditions
        foreach ($data['conditions'] as $condition) {
            if (in_array($condition['id'], $toAdd)) {
                $condition['auction_id'] = $auction->id;
                $this->createCondition($condition);
            }
        }

        // Update existing conditions
        $conditionMap = collect($data['conditions'])->keyBy('id');
        foreach ($auction->conditions as $condition) {
            if (isset($conditionMap[$condition->id])) {
                $this->updateCondition($condition, $conditionMap[$condition->id]);
            }
        }

        return new AuctionResource($auction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $auction->user_id) {
            return abort( code: 403, message: 'Unauthorized action.');
        }
        
        $auction->delete();
            // If there is an old image, delete it
            if ($auction->image) {
                $absolutePath = public_path($auction->image);
                File::delete($absolutePath);
            }            
        return response( content: '', status: 204);
    }
    
    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }
    private function createCondition($data)
    {
        $validator = Validator::make($data, [
            'condition' => 'required|string',
            'type' => ['required', Rule::in([
                Auction::TYPE_TEXT,
            ])],
            'auction_id' => 'exists:App\Models\Auction,id'
        ]);

        return AuctionCondition::create($validator->validated());
    }
    private function updateCondition(AuctionCondition $condition, $data)
    {
       
        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\AuctionCondition,id',
            'condition' => 'required|string',
            'type' => ['required', Rule::in([
                Auction::TYPE_TEXT,
            ])],
        ]);

        return $condition->update($validator->validated());
    }
}
