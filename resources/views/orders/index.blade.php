<!-- ...existing code... -->
<th>Tax Rate</th>
<th>Tax Amount</th>
<th>Discount Percentage</th>
<th>Discount Amount</th>
<th>Insurance Covered Amount</th>
<th>Patient Amount</th>
<!-- ...existing code... -->
@foreach ($orders as $order)
<tr>
    <!-- ...existing code... -->
    <td>{{ $order->tax_rate }}</td>
    <td>{{ $order->tax_amount }}</td>
    <td>{{ $order->discount_percentage }}</td>
    <td>{{ $order->discount_amount }}</td>
    <td>{{ $order->insurance_covered_amount }}</td>
    <td>{{ $order->patient_amount }}</td>
    <!-- ...existing code... -->
</tr>
@endforeach
<!-- ...existing code... -->