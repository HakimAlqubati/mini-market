<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\UnitPrice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return Product::all();
    // }

    public function view(): View
    {

        $path_exploded = explode("/", url()->current());
        $id =  end($path_exploded);


      


        $productData = Product::find($id);

        $unitPrices = UnitPrice::get();

        // dd($unitPrices);

        // $branchData = Branch::where('corporation_id', $id)->get();



        return view(

            'export-product',
            [
                'unitPrices' => $unitPrices

            ]
        );
    }
}
