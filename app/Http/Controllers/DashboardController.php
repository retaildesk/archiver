<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionsDataTable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use View;

class DashboardController extends Controller
{
    public function index(Request $request,TransactionsDataTable $dataTable){
        return $dataTable->render('dashboard');
    }
}
