<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;

class EnquiryController extends Controller
{
    public function store(StoreEnquiryRequest $request): JsonResponse
    {
        $enquiry = Enquiry::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'product' => $request->product ?: 'General enquiry',
            'message' => $request->message,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Your enquiry has been received successfully.',
            'data' => $enquiry,
        ], 201);
    }
}
