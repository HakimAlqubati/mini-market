<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
    table {
        border-collapse: collapse;
    }

    /*.a4-paper {*/
    /*    height: 29.7cm;*/
    /* width: 21cm; */
    /*    position: relative;*/
    /*}*/

    /*.footer {*/
    /*    bottom: 0;*/
    /*    position: absolute;*/
    /*}*/

    /*.header-table tr td {*/
    /*    border-top: 1px solid #DF1E98;*/
    /*    background: white;*/
    /*    border-bottom: 1 px solid #DF1E98;*/
    /*}*/

    /*.table-body tr th {*/
    /*    border-bottom: 1px solid #af237c;*/
    /*    border-top: 1px solid #af237c;*/
    /*}*/

    /*.table-body tr td {*/
    /*    background-color: white;*/
    /*    border-bottom: 1px solid #af237c;*/
    /*}*/
</style>





<table class="table table-bordered" style="direction: rtl;">



    <tr>
        <td colspan="3" style="border:none;">
            العميل : {{ \App\Models\User::find($orderData->created_by)->name }}
        </td>

        <td colspan="3" rowspan="3" style="border:none; padding-right: 0px;  ">
            <img src="https://www.cloudsnap.tech/alsafeer-garden/storage/settings/May2022/jQO6VebM5cNNYcrW2Sbd.png"
                alt="" style="height: 60px;">
        </td>

        <td colspan="3" style="border: none;">
            رقم الطلب: <?php echo $orderData->id; ?>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="border:none;" style="padding: 0px;  ">
            رقم الهاتف : {{ \App\Models\User::find($orderData->created_by)->phone_no }}
        </td>

        <td colspan="3" style="border:none;" style="">
            <p style="text-align: right">
                التاريخ : {{ $orderData->created_at }}
            </p>
        </td>
    </tr>

    <tr style=";">
        <td colspan="3" style="border:none;" style="padding: 0px;  ">
            الايميل : {{ \App\Models\User::find($orderData->created_by)->email }}
        </td>

        <td colspan="3" style="border:none;  ">
            <p style="text-align: right">
                <?php
                
                if ($orderData->order_state == 'cancelled') {
                    $state = 'ملغي';
                } elseif ($orderData->order_state == 'completed') {
                    $state = 'مكتمل';
                } elseif ($orderData->order_state == 'processing') {
                    $state = 'قيد المعالجة';
                } elseif ($orderData->order_state == 'ordered') {
                    $state = 'قيد الطلب';
                }
                ?>
                الحالة: {{ $state }}
            </p>
        </td>
    </tr>

    <tr>
        <td colspan="8" style="border:none;">
            العنوان: {{ $orderData->customer_address }}
        </td>
    </tr>



    <tr style="border-top: 1px solid black;">
        <td colspan="8" style="border-top: 1px solid;">

            الأصناف
            <br>
            <br>
            <br>
        </td>
    </tr>




    <tr style="; border: 1px solid; font-size:13px;">
        <td style="text-align: center; border: 1px solid; width: 6px;">الرقم</td>
        <td style="text-align: center; border: 1px solid; padding: 0px; width: 100px;"> كود الصنف </td>
        <td style="text-align: center; border: 1px solid"> اسم الصنف </td>
        <td style="text-align: center; border: 1px solid">اللون</td>
        <td style="text-align: center; border: 1px solid"> الوحدة </td>
        <td style="text-align: center; border: 1px solid"> الكمية </td>

        <td style="text-align: center; border: 1px solid"> السعر </td>
        <td style="text-align: center; border: 1px solid;"> الإجمالي </td>


    </tr>

    <?php $total = 0; ?>
    @foreach ($orderItemsData as $key => $value)
        <tr style="font-size:13px;">
            <td style="text-align: center; border: 1px solid"> {{ $key + 1 }} </td>
            <td style="text-align: center; border: 1px solid; padding: 0px;">
                {{ \App\Models\Product::find($value->product_id)->code }}
            </td>
            <td style="text-align: center; border: 1px solid">
                {{ \App\Models\Product::find($value->product_id)->name }} </td>

            <td style="text-align: center; border: 1px solid"> {{ \App\Models\Color::find($value->color_id)?->name }}
            </td>
            <td style="text-align: center; border: 1px solid"> {{ \App\Models\Unit::find($value->unit_id)->name }}
            </td>
            <td style="text-align: center; border: 1px solid"> {{ $value->qty }} </td>

            <td style="text-align: center; border: 1px solid">{{ $value->price }} </td>
            <td style="text-align: center; border: 1px solid"> {{ $value->price * $value->qty }} </td>

        </tr>
        <?php $total += $value->price * $value->qty; ?>
    @endforeach


<tr>
    <td colspan="8">
        <br>
        <br>
        <br>
    </td>
</tr>
<tr>
    <td colspan="8">
        
        <div style="text-align: right; font-weight: bold">
            الإجمالي الكلي: {{ $total }}
        </div>

    </td>
    </tr>
    
    <tr>
    <td colspan="8">
        <div style="text-align: right;  ">
    الملاحظات:
    <br>

    {{ $orderData->notes }}
</div>
    </td>
</tr>
</table>

 
 

