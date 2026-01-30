<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiElementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiElementController extends Controller
{
    protected $aiElementService;

    public function __construct(AiElementService $aiElementService)
    {
        $this->aiElementService = $aiElementService;
    }

    /**
     * Find element by natural language description
     * 
     * POST /api/ai/find-element
     * Body: { "screenshot": "base64...", "description": "the blue submit button" }
     */
    public function findElement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'screenshot' => 'required|string',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $this->aiElementService->findElementByDescription(
            $request->screenshot,
            $request->description
        );

        return response()->json($result);
    }

    /**
     * Heal broken selector
     * 
     * POST /api/ai/heal-selector
     * Body: { "screenshot": "base64...", "failed_selector": "#old-btn", "description": "submit button" }
     */
    public function healSelector(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'screenshot' => 'required|string',
            'failed_selector' => 'required|string',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $this->aiElementService->healSelector(
            $request->screenshot,
            $request->failed_selector,
            $request->description
        );

        return response()->json($result);
    }
}
