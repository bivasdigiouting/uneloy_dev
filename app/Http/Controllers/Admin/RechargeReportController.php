<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use App\Repositories\Interfaces\RechargeReportRepositoryInterface;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use Illuminate\Http\Request;

class RechargeReportController extends Controller
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
     * Display the Recharge Report (transactional list) with filters.
     */
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page', 15));
        $filters = [
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'service_id' => $request->get('service_id'),
            'operator_id' => $request->get('operator_id'),
            'status' => $request->get('status', 'all'),
            'search' => $request->get('search'),
        ];

        $services = $this->services->getActive();
        $operators = collect();
        if (! empty($filters['service_id'])) {
            $operators = $this->operators->getByServiceId((int) $filters['service_id']);
        }

        $results = $this->reports->paginateTransactions($filters, $perPage);

        return view('admin.recharge-report.index', compact('services', 'operators', 'results', 'filters'));
    }
}
