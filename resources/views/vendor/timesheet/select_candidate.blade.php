@extends('vendor.layouts.app')

@section('content')
<!-- Sidebar -->
@include('vendor.layouts.partials.dashboard_side_bar')

<div class="ml-16">
    @include('vendor.layouts.partials.header')
    <div class="rounded border p-[30px]">
        <div class="w-100 p-[30px] rounded border" :style="{'border-color': 'var(--primary-color)'}"
            x-data="contractor({{ 'null' }})">
            <div class="container mx-auto p-4">
                <div class="flex flex-wrap mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="contract_id" class="block mb-2">Contractor <span class="text-red-500">*</span></label>
                        <select id="contract_id" name="contract_id" class="w-full p-2 border rounded h-10 bg-white"
                            x-model="formData.contract_id">
                            <option value="" disabled>Select Contractor</option>


                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="contract_idError"></p>
                    </div>
                    <div class="flex mb-4">
                        <button @click="submitData1()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Continue
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.$) {
        $( document ).ready(function() {
            loadCandidates();
        });

        function loadCandidates(){
           
                ajaxCall('/get-candiadte', 'GET', [[updateStatesDropdown, ['response', 'contract_id']]]);
         
        }
    }
});

function contractor(editIndex) {

    return {
        formData: {
            contract_id: "",
        },
        contract_idError: "",
        editIndex: editIndex,
        searchTerm: "",
        error: 0,
        currentUrl: `{{ url()->current() }}`,

        validateFields() {
            this.error = 0;

            if (this.formData.contract_id === "") {
                this.contract_idError = `Please select Contractor`;
                this.error += 1;
            } else {
                this.contract_idError = "";
            }
        },

        submitData1() {
            this.validateFields();
            if (this.error === 0) {
                let formData = new FormData();
                formData.append('contract_id', this.formData.contract_id);
                
                url = '{{ route("vendor.timesheet.step_one") }}';

                ajaxCall(url, 'POST', [
                    [onSuccess, ['response']]
                ], formData);
            }
        }
    };
}
</script>