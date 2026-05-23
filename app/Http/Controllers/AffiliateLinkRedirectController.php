<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLinkClick;
use App\Repositories\AffiliateLinkRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateLinkRedirectController extends Controller
{
    public function __construct(private AffiliateLinkRepositoryInterface $affiliateLinkRepo) {}

    public function __invoke(Request $request, string $code)
    {
        $link = $this->affiliateLinkRepo->findByCode($code);
        if (! $link) {
            abort(404);
        }

        if (! $link->isActiveOnDate()) {
            abort(404);
        }

        DB::transaction(function () use ($request, $link) {
            AffiliateLinkClick::create([
                'affiliate_link_id' => $link->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
            ]);

            $link->increment('clicks_count');
        });

        return redirect()->away($link->destination_url);
    }
}
