<table class="table table-bordered" style="direction: rtl;">

    <tr style="background: rgb(109, 109, 255)">
        <th>
            كود الصنف
        </th>

        <th colspan="3">
            اسم الصنف
        </th>


        <th colspan="2">
            الوحدة
        </th>
        <th colspan="2">
            السعر
        </th>
        <th colspan="2">
            الألوان
        </th>
    </tr>

    <tbody>
        @foreach ($unitPrices as $item)
            <tr style="background-color: red;">
                
                <th>
                    {{ \App\Models\Product::find($item->product_id)->code }}
                </th>

                <th colspan="3">
                    {{ \App\Models\Product::find($item->product_id)->name }}
                </th>

                <th colspan="2">
                    {{ \App\Models\Unit::find($item->unit_id)->name }}
                </th>

                <th colspan="2">
                    {{ $item->price }}
                </th>

                <th colspan="2">
                    @foreach (explode(',', $item->colors) as $color)
                        {{ \App\Models\Color::find($color)?->name }} {{ $color != null ? ',' : '' }}
                    @endforeach
                </th>

            </tr>
        @endforeach
    </tbody>


</table>
