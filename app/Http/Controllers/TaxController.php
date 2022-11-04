<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Imports\TaxImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mappers\TaxMapper;

class TaxController extends Controller
{
    public function index()
    {
        return Tax::filterByExpediente(request('expediente'))->orderByRaw('CAST(EXP as unsigned) DESC')->paginate();
    }

    public function show(Tax $tax)
    {
        return $tax;
    }

    public function validity()
    {
        return TaxMapper::addFolio(
            Tax::whereUuid(request('uuid'))->first(),
            request('type'),
        );
    }

    public function store()
    {
        Excel::import(new TaxImport, request()->file('file'));

        return response(['success' => true], 201);
    }

    public function destroy(Tax $tax)
    {
        return $tax->delete();
    }
}
