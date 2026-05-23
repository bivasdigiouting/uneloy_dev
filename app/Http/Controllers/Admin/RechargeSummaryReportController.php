<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use App\Repositories\Interfaces\RechargeReportRepositoryInterface;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Http\Request;

class RechargeSummaryReportController extends Controller
{
    protected RechargeReportRepositoryInterface $reports;

    protected RechargeServiceRepositoryInterface $services;

    protected RechargeOperatorRepositoryInterface $operators;

    public function __construct(
        RechargeReportRepositoryInterface $reports,
        RechargeServiceRepositoryInterface $services,
        RechargeOperatorRepositoryInterface $operators
    ) {
        $this->reports = $reports;
        $this->services = $services;
        $this->operators = $operators;
    }

    /**
     * Display the Recharge Summary Report with filters.
     */
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page', 15));
        $filters = [
            'service_id' => $request->get('service_id'),
            'operator_id' => $request->get('operator_id'),
            'search' => $request->get('search'),
        ];

        $services = $this->services->getActive();
        $operators = collect();
        if (! empty($filters['service_id'])) {
            $operators = $this->operators->getByServiceId((int) $filters['service_id']);
        }

        $results = $this->reports->paginateSummary($filters, $perPage);

        return view('admin.recharge-summary-report.index', compact('services', 'operators', 'results', 'filters'));
    }
}
