@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Timesheet</h2>
                </div>

                <div>
                    <select x-ref="jobLaborCategory" name="contract_id" x-model="formData.jobLaborCategory"
                                    class="w-full select2-single custom-style" data-field="jobLaborCategory"
                                    id="jobLaborCategory">
                    <option value="">Select Contract</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}">{{ $contract->id }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="flex-1 flex items-end">
                    <button @click="addBusinessUnit" type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        <i class="fas fa-plus"></i> Save 
                    </button>
                </div>

                <x-job-details />

                
            </div>
        </div>
    </div>

    <script>
    </script>
@endsection
