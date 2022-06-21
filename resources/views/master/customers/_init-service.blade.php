@for ($i = 0; $i < count($customer->services); $i++)
    <tr>
        <td>Layanan / Service</td>
        <td><b>{{  $customer->services[$i]->billing_type_id == 1 ? $customer->services[$i]->service->name . ' - CASH' : $customer->services[$i]->service->name . ' - TEMPO' }}</b></td>
    </tr>
@endfor