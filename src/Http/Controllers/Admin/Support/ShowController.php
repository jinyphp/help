<?php

namespace Jiny\Help\Http\Controllers\Admin\Support;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Jiny\Help\Models\SiteSupport;

class ShowController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes
    }

    public function __invoke(Request $request, $id)
    {
        $support = SiteSupport::with(['user', 'assignedTo'])->findOrFail($id);

        return view('jiny-help::admin.support.requests.show', [
            'support' => $support,
        ]);
    }
}