@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Order Management</h2>
        
        <form id="myForm" action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <label class="col-form-label col-md-2 fw-bold">User:</label>
                <div class="col-md-6">
                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                        <option value="">Select a User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="productsTable">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="products[0][name]" class="form-control @error('products.0.name') is-invalid @enderror">
                                @error('products.0.name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="products[0][qty]" class="form-control qty @error('products.0.qty') is-invalid @enderror">
                                @error('products.0.qty')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="products[0][amount]" class="form-control amount @error('products.0.amount') is-invalid @enderror">
                                @error('products.0.amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" name="products[0][total]" class="form-control total bg-light" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger removeRow">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-2">
                <button type="button" id="addRow" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add More</button>
            </div>

            <div class="mt-3 p-2 text-end bg-light rounded shadow-sm">
                <h4 class="fw-bold">Grand Total: <span id="grandTotal" class="text-primary">0.00</span></h4>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Submit Order</button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let index = 1;

    document.getElementById('addRow').addEventListener('click', function () {
        if (validateFields()) {
            appendProductRow();
        }
    });

    document.getElementById('myForm').addEventListener('submit', function (e) {
        if (!validateFields()) {
            e.preventDefault();
        }
    });

    function validateFields() {
        let isValid = true;
        if (!validateUser()) isValid = false;

        document.querySelectorAll("#productsTable tbody tr").forEach((row) => {
            let nameField = row.querySelector('[name^="products"][name$="[name]"]');
            let qtyField = row.querySelector('[name^="products"][name$="[qty]"]');
            let amountField = row.querySelector('[name^="products"][name$="[amount]"]');

            clearErrors(nameField, qtyField, amountField);

            if (!validateName(nameField)) isValid = false;
            if (!validateQuantity(qtyField)) isValid = false;
            if (!validateAmount(amountField)) isValid = false;
        });

        return isValid;
    }

    function validateUser() {
        let userField = document.querySelector('[name="user_id"]');
        clearErrors(userField);

        if (userField.value.trim() === '') {
            setError(userField, "Please select a user.");
            return false;
        }

        return true;
    }

    function validateName(field) {
        if (field.value.trim() === '') {
            setError(field, "The name field is required.");
            return false;
        }
        return true;
    }

    function validateQuantity(field) {
        if (field.value.trim() === '') {
            setError(field, "The quantity field is required.");
            return false;
        } else if (field.value <= 0) {
            setError(field, "The quantity must be greater than 0.");
            return false;
        }
        return true;
    }

    function validateAmount(field) {
        if (field.value.trim() === '') {
            setError(field, "The amount field is required.");
            return false;
        } else if (field.value <= 0) {
            setError(field, "The amount must be greater than 0.");
            return false;
        }
        return true;
    }

    function setError(field, message) {
        field.classList.add("is-invalid");
        let errorSpan = document.createElement("span");
        errorSpan.className = "text-danger error";
        errorSpan.innerText = message;
        field.parentNode.appendChild(errorSpan);
    }

    function clearErrors(...fields) {
        fields.forEach((field) => {
            field.classList.remove("is-invalid");
            let errorSpan = field.parentNode.querySelector(".error");
            if (errorSpan) {
                errorSpan.remove();
            }
        });
    }

    function appendProductRow() {
        let table = document.querySelector("#productsTable tbody");
        let newRow = `
            <tr>
                <td><input type="text" name="products[${index}][name]" class="form-control"></td>
                <td><input type="number" name="products[${index}][qty]" class="form-control qty"></td>
                <td><input type="number" name="products[${index}][amount]" class="form-control amount"></td>
                <td><input type="text" name="products[${index}][total]" class="form-control total bg-light" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger removeRow">Remove</button>
                </td>
            </tr>`;
        table.insertAdjacentHTML("beforeend", newRow);
        index++;
    }

    document.addEventListener('input', function (event) {
        if (event.target.classList.contains('qty') || event.target.classList.contains('amount')) {
            let row = event.target.closest('tr');
            let qty = row.querySelector('.qty').value || 0;
            let amount = row.querySelector('.amount').value || 0;
            let total = qty * amount;
            row.querySelector('.total').value = total.toFixed(2);
            updateGrandTotal();
        }
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('removeRow')) {
            event.target.closest('tr').remove();
            updateGrandTotal();
        }
    });

    function updateGrandTotal() {
        let totals = document.querySelectorAll('.total');
        let grandTotal = Array.from(totals).reduce((sum, el) => sum + parseFloat(el.value || 0), 0);
        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
    }
});
</script>
@endsection
