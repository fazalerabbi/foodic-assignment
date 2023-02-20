<?php
namespace App\Http\Controllers;
use \App\Http\Repository\OrderRepositoryInterface;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    // create the constructor
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }
    /**
     * Create a new order.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create( OrderRequest $request ) : JsonResponse
    {
        $validatedData = $request->validated();
        $order = $this->orderRepository->create($validatedData);

        if ($order) {
            if (is_string($order)) {
                return response()->json([
                    'message' => 'Error creating order',
                    'error' => $order,
                ], 500);
            }
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        }
        return response()->json([
            'message' => 'Error creating order',
            'error' => 'Something went wrong',
        ], 500);

    }
}
