<!-- ...existing code... -->
<div class="form-group">
    <label for="tax_rate">Tax Rate</label>
    <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" value="{{ old('tax_rate', $order->tax_rate ?? '') }}">
</div>
<div class="form-group">
    <label for="tax_amount">Tax Amount</label>
    <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" value="{{ old('tax_amount', $order->tax_amount ?? '') }}">
</div>
<div class="form-group">
    <label for="discount_percentage">Discount Percentage</label>
    <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" class="form-control" value="{{ old('discount_percentage', $order->discount_percentage ?? '') }}">
</div>
<div class="form-group">
    <label for="discount_amount">Discount Amount</label>
    <input type="number" step="0.01" name="discount_amount" id="discount_amount" class="form-control" value="{{ old('discount_amount', $order->discount_amount ?? '') }}">
</div>
<div class="form-group">
    <label for="insurance_covered_amount">Insurance Covered Amount</label>
    <input type="number" step="0.01" name="insurance_covered_amount" id="insurance_covered_amount" class="form-control" value="{{ old('insurance_covered_amount', $order->insurance_covered_amount ?? '') }}">
</div>
<div class="form-group">
    <label for="patient_amount">Patient Amount</label>
    <input type="number" step="0.01" name="patient_amount" id="patient_amount" class="form-control" value="{{ old('patient_amount', $order->patient_amount ?? '') }}">
</div>
<!-- ...existing code... -->