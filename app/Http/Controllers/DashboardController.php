<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FOCVisit;
use App\Models\OutsourcePost;
use App\Models\Program;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request){

        $visits = Visit::where('activity_type_id', 1);
        $visit_filter = $request->visit_filter;
        $this->applyFilters($visits, $visit_filter);
        $visitsCount = $visits->count();

        $calls = Visit::where('activity_type_id',2);
        $call_filter = $request->call_filter;
        $this->applyFilters($calls, $call_filter);
        $callsCount = $calls->count();

        $visitsFOC =  FOCVisit::where('id', '>', 0);
        $visitsFOC_filter = $request->visitsFOC_filter;
        $this->applyFilters($visitsFOC, $visitsFOC_filter);

        $visitsFOCCount = $visitsFOC->count();




        $trafficChart_filter = $request->trafficChart_filter;

        $visitst_rafficChart = Visit::where('activity_type_id', 1);
        $this->applyFilters($visitst_rafficChart, $trafficChart_filter);
        $visitst_rafficChartCount = $visitst_rafficChart->count();

        $calls_rafficChart = Visit::where('activity_type_id', 2);
        $this->applyFilters($calls_rafficChart, $trafficChart_filter);
        $calls_rafficChartCount = $calls_rafficChart->count();

        $visitsFOC_rafficChart = FOCVisit::where('id', '>', 0);
        $this->applyFilters($visitsFOC_rafficChart, $trafficChart_filter);
        $visitsFOC_rafficChartFOCCount = $visitsFOC_rafficChart->count();

        $programs =  Program::where('id', '>', 0);
        $programs_filter = $request->programs_filter;
        $this->applyFiltersCreateAt($programs, $programs_filter);
        $programs_filterCount = $programs->count();

        $sub_programs =  SubProgram::where('id', '>', 0);
        $sub_programs_filter = $request->sub_programs_filter;
        $this->applyFiltersCreateAt($sub_programs, $sub_programs_filter);
        $sub_programs_filterCount = $sub_programs->count();

        $patients =  SubProgramPatient::where('id', '>', 0);
        $patients_filter = $request->patients_filter;
        $this->applyFiltersCreateAt($patients, $patients_filter);
        $patients_filterCount = $patients->count();

        $clients =  Client::where('id', '>', 0);
        $clients_filter = $request->clients_filter;
        $this->applyFiltersCreateAt($clients, $clients_filter);
        $clients_filterCount = $clients->count();


        $filterApexCharts = $request->apexcharts;

        $visitsApexCharts = Visit::where('activity_type_id', 1)->whereNotNull('start_at');
        $visitsApexChartsData = $this->applyFiltersApexCharts($visitsApexCharts, $filterApexCharts);


        $callsApexCharts = Visit::where('activity_type_id', 2)->whereNotNull('start_at');
        $callsApexChartsData = $this->applyFiltersApexCharts($callsApexCharts, $filterApexCharts);


        $visitsFOCApexCharts = FOCVisit::where('id', '>', 0);
        $visitsFOCApexChartsData = $this->applyFiltersApexCharts($visitsFOCApexCharts, $filterApexCharts);


        if ($request->ajax()) {
            return response()->json([
                'visitsFOCApexChartsData' => $visitsFOCApexChartsData,
                'callsApexChartsData' => $callsApexChartsData,
                'visitsApexChartsData' => $visitsApexChartsData,

                'visits' => $visitsCount,
                'calls' => $callsCount,
                'visitsFOC' => $visitsFOCCount,

                'visits_rafficChart' => $visitst_rafficChartCount,
                'calls_rafficChart' => $calls_rafficChartCount,
                'visitsFOC_rafficChart' => $visitsFOC_rafficChartFOCCount,

                'clients' => $clients_filterCount,
                'patients' => $patients_filterCount,
                'sub_programs' => $sub_programs_filterCount,
                'programs' => $programs_filterCount,
            ]);
        }
        return view('dashboard.index',['visits'=>$visits]);
    }

    function applyFilters($query, $filter) {
        if ($filter === 'Today') {
            $query->whereDate('start_at', Carbon::today());
        } elseif ($filter === 'Month') {
            $query->whereMonth('start_at', Carbon::now()->month);
        } elseif ($filter === 'Year') {
            $query->whereYear('start_at', Carbon::now()->year);
        }
    }
    function applyFiltersCreateAt($query, $filter) {
        if ($filter === 'Today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'Month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        } elseif ($filter === 'Year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }
    }


    function applyFiltersApexCharts($query, $filter) {
        $data = [];

        $today = Carbon::today();

        if ($filter === 'Today') {
            $hoursData = $query->whereDate('start_at', $today)
                ->selectRaw('HOUR(start_at) as label, COUNT(*) as value')
                ->groupByRaw('HOUR(start_at)')
                ->get();

            $data = $this->generateChartData($filter, $hoursData);
        } elseif ($filter === 'Month') {
            $daysData = $query->whereYear('start_at', $today->year)
                ->whereMonth('start_at', $today->month)
                ->selectRaw('DAY(start_at) as label, COUNT(*) as value')
                ->groupByRaw('DAY(start_at)')
                ->get();

            $data = $this->generateChartData($filter, $daysData);
        } elseif ($filter === 'Year') {
            $monthsData = $query->whereYear('start_at', $today->year)
                ->selectRaw('MONTH(start_at) as label, COUNT(*) as value')
                ->groupByRaw('MONTH(start_at)')
                ->get();

            $data = $this->generateChartData($filter, $monthsData);
        }

        return $data;
    }



    function generateChartData($filter, $data) {
        $chartData = [];

        if ($filter === 'Today') {
            for ($hour = 1; $hour <= 24; $hour++) {
                $hourDateTime = now()->setHour($hour)->startOfHour();
                $chartData[] = ['label' => $hourDateTime->toDateTimeString(), 'value' => 0];
            }

            foreach ($data as $item) {
                $hour = $item->label;
                $hourDateTime = now()->setHour($hour)->startOfHour();
                $chartData[$hour - 1]['value'] = $item->value;
                $chartData[$hour - 1]['label'] = $hourDateTime->toDateTimeString();
            }
        } elseif ($filter === 'Month') {
            $daysInMonth = now()->daysInMonth;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dayDateTime = now()->setDay($day)->startOfDay();
                $chartData[] = ['label' => $dayDateTime->toDateTimeString(), 'value' => 0];
            }

            foreach ($data as $item) {
                $day = $item->label;
                $dayDateTime = now()->setDay($day)->startOfDay();
                $chartData[$day - 1]['value'] = $item->value;
                $chartData[$day - 1]['label'] = $dayDateTime->toDateTimeString();
            }
        } elseif ($filter === 'Year') {
            for ($month = 1; $month <= 12; $month++) {
                $monthDateTime = now()->setMonth($month)->startOfMonth();
                $chartData[] = ['label' => $monthDateTime->toDateTimeString(), 'value' => 0];
            }

            foreach ($data as $item) {
                $month = $item->label;
                $monthDateTime = now()->setMonth($month)->startOfMonth();
                $chartData[$month - 1]['value'] = $item->value;
                $chartData[$month - 1]['label'] = $monthDateTime->toDateTimeString();
            }
        }

        return $chartData;
    }



}
