<?php

namespace App\Http\Controllers;

use App\Services\TaniSyncMockData;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __construct(private readonly TaniSyncMockData $mockData)
    {
    }

    public function index(): View
    {
        return view('landing.index', [
            'landing' => $this->mockData->landing(),
        ]);
    }
}
