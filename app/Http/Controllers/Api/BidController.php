<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Traits\DataProvider;
use App\Http\Requests\BidRequest;
use App\Http\Requests\BidUpdateRequest;
use App\Jobs\MailJob;
use App\Models\Bid;
use Illuminate\Http\Request;


class BidController extends Controller
{

    use DataProvider;

    /**
     * @OA\Get(
     * path="/api/bid",
     * summary="bid list",
     * description="bid list",
     * operationId="bid_list",
     * tags={"bid"},
     * @OA\Parameter(
     *      name="filter",
     *      description="фильтрация по колонки выборки",
     *      example="filter[status]=Resolved",
     *      example="filter[status]=Active",
     *      in="query",
     *      @OA\Schema(
     *           type="object",
     *          @OA\Property(
     *             property="status",
     *             type="string",
     *             enum={"Active", "Resolved"},
     *             example="Resolved"
     *        )
     *      )
     *   ),
     * @OA\Response(
     *    response=200,
     *    description="list"
     *     )
     * )
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage =  $request->per_page ?? 10;

        $result = $this->prepareModel($request,  Bid::query())->paginate((int) $perPage);


        return response()->json(['data' => $result]);
    }


    /**
     * @OA\Post(
     * path="/api/bid",
     * summary="add bid",
     * description="add bid",
     * operationId="bid_add",
     * tags={"bid"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Add info",
     *    @OA\JsonContent(
     *       required={"email","name", "message"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="name", type="string", example="Sergey"),
     *       @OA\Property(property="message", type="string", example="Test"),
     *    ),
     * ),
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="validation error",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="The name field is required"),
     *       @OA\Property(property="email", type="string", example="The email field is required"),
     *       @OA\Property(property="message", type="string", example="The message field is required"),
     *        )
     *     )
     * )
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BidRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BidRequest $request)
    {
        $params = $request->all();
        $params['status'] = 'Active';
        $newBid = Bid::create($params);

        return response()->json(['data' => $newBid], 201);
    }

    /**
     * * @OA\Put(
     * path="/api/bid",
     * summary="edit bid",
     * description="edit bid",
     * operationId="edit_bid",
     * tags={"bid"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Change bid",
     *    @OA\JsonContent(
     *       required={"comment"},
     *       @OA\Property(property="comment", type="string", example="Test"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="validation error",
     *    @OA\JsonContent(
     *       @OA\Property(property="comment", type="string", example="The comment field is required")
     *        )
     *     )
     * )
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\BidUpdateRequest $request
     * @param  \App\Models\Bid $bid
     * @return \Illuminate\Http\Response
     */
    public function update(BidUpdateRequest $request, int $id)
    {
        $bid = Bid::find($id);

        if (is_null($bid)) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }

        $bid->update([
            'comment' => $request->comment,
            'status' => 'Resolved'
        ]);

        MailJob::dispatch($bid->email, $bid->comment);

        return response()->json(['data' => $bid]);
    }

}
