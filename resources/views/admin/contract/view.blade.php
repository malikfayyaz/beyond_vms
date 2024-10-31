@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
            <div class="rounded mx-4 my-2">
                @include('admin.layouts.partials.alerts')
            </div>




    <div x-data="{ showFlyout: false }">
    <!-- Button to open flyout -->
    <button
        @click="showFlyout = true"
        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
    >
        Show Contract Details
    </button>

    <!-- Flyout Container -->
    <div
        x-show="showFlyout"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-0 flex z-50"
    >
        <!-- Backdrop -->
        <div
            class="fixed inset-0 bg-gray-600 bg-opacity-50"
            @click="showFlyout = false"
            aria-hidden="true"
        ></div>

        <!-- Flyout Panel (Now slides from left to right) -->
        <div class="relative w-120 bg-white shadow-xl p-6 ml-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Additional Budget Request {{'#'.$additionalBudget->id}}</h3>
                <button
                    @click="showFlyout = false"
                    class="text-gray-500 hover:text-gray-700"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Contract Details Content -->
            <div>
                <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
                    <div>
                        <h4 class="text-lg font-bold">{{ $contract->consultant->full_name }}</h4>
                        <!-- Career Opportunity Name (Smaller Text) -->
                        <div class="text-sm text-gray-300">{{ $contract->careerOpportunity->alternative_job_title }}</div>
                    </div>
                    <!-- Status -->
                    <div>Status: {{ $additionalBudget->status }}</div>
                </div>
            </div>
            @if($additionalBudget)
                <div class="mt-4 bg-gray-100 p-4 rounded-lg">
                    <h5 class="text-md font-semibold">Additional Budget Request Details</h5>
                    <ul class="list-disc ml-4 mt-2 text-gray-600">
                        <li>Requested Amount: ${{ $additionalBudget->amount }}</li>
                        <li>Reason For Additional Budget: {{ getSettingTitleById($additionalBudget->additional_budget_reason) }}</li>
                        <li>Effective Date: {{ \Carbon\Carbon::parse($additionalBudget->effective_date)->format('m/d/Y') }}</li>
                        <li>Notes: {{ $additionalBudget->notes }}</li>
                    </ul>
                </div>
            @endif
                <p class="mt-2 text-gray-600">This contract is valid for the next 12 months and includes provisions for...</p>
                <div class="mt-4">
                    <h5 class="text-md font-semibold">Key Terms:</h5>
                    <ul class="list-disc ml-4 mt-2 text-gray-600">
                        <li>Payment schedule: Monthly</li>
                        <li>Termination clause: 30 days notice</li>
                        <li>Renewal option: Yes</li>
                    </ul>
                </div>
                <div class="mt-4">
                    <h5 class="text-md font-semibold">Parties Involved:</h5>
                    <ul class="list-disc ml-4 mt-2 text-gray-600">
                        <li>Client: ABC Corporation</li>
                        <li>Contractor: XYZ Solutions</li>
                    </ul>
                </div>
            </div>

            <!-- Close Button -->
            <div class="mt-6">
                <button
                    @click="showFlyout = false"
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
    </div>









            <div class="mx-2 my-4 rounded px-8 w-full flex justify-end items-center gap-4 ">
                <div x-data="{ showModal: false, status: {{ json_encode($contract->termination_status) }} }">
                    <a href="javascript:void(0);" 
                        class="btn bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500" 
                        @click="showModal = true"
                        x-show="status != 2"
                        :class="{ 'opacity-50 pointer-events-none': status == 2 }">
                        Temporary Close Contract 
                    </a>
                    <!-- The Modal -->
                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" 
                        @click.away="showModal = false">
                        <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center p-4 border-b">
                                <h4 class="text-lg font-semibold">Temporarily Close Contract </h4>
                                <button type="button" class="text-gray-500 hover:text-gray-700 bg-transparent" @click="showModal = false">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-4">
                                <form x-data="closeAssignmentTemp()" @submit.prevent="submitData()" class="reject-form space-y-4">
                                @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Reason for Closing <i class="fa fa-asterisk text-red-600"></i>:</label>
                                        <select 
                                            x-model="formData.close_contr_reason"
                                            id="close_contr_reason" 
                                            name="close_contr_reason"
                                            class="w-full px-3 py-2 border rounded-md"
                                            :class="{'border-red-500': errors.close_contr_reason}">
                                            <option value="">Select</option>
                                            @foreach (checksetting(28) as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <p x-show="errors.close_contr_reason" class="text-red-500 text-xs italic" x-text="errors.close_contr_reason"></p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Note <i class="fa fa-asterisk text-red-600"></i>:</label>
                                        <textarea 
                                            x-model="formData.close_contr_note"
                                            name="close_contr_note" 
                                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            :class="{'border-red-500': errors.close_contr_note}">
                                        </textarea>
                                        <p x-show="errors.close_contr_note" class="text-red-500 text-xs italic" x-text="errors.close_contr_note"></p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end space-x-4 mt-2">
                                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500">Submit</button>
                                        <button type="button" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-500" @click="showModal = false">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($contract->termination_status == 2)
                <form x-data="openContract()" @submit.prevent="submitData()">
                    @csrf

                    <button type="submit"
                        class="px-4 py-2 capitalize bg-green-500 text-white rounded hover:bg-green-600"
                    >
                        Open Contract
                    </button>
                </form>
                @endif

                @if(!in_array($contract->status, array(2,3,7,14)) && ($contract->termination_status != 2 ) )
                <a href="{{ route('admin.contracts.edit',  ['contract' => $contract->id]) }}"
                    type="button"
                    class="px-4 py-2 capitalize bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                >
                    Update Contract
                </a>
                @endif
            </div>
            @if($contract->status == 3 && $contract->termination_status == 2)
                <div class="rounded mx-4 my-2 p-4 bg-red-100 text-sm">
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Reason for Termination/Closing: </span>
                        <span class="text-red-800"> {{$contract->reasonClose->title}} </span>
                    </p>
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Termination/Closing Notes: </span>
                        <span class="text-red-800"> {{$contract->termination_notes}} </span>
                    </p>
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Date: </span>
                        <span class="text-red-800"> {{$contract->formatted_termination_date}} </span>
                    </p>
                </div>
            @endif

            @if(!empty($extensionReq))
                <div class="rounded mx-4 my-2 flex">
                    <div class="flex items-center bg-white border border-gray-200 rounded shadow-lg">
                        @if(!empty($extensionReq) && ($extensionReq->ext_status == 1 || $extensionReq->ext_vendor_approval==1))
                            <div x-data="flyout()" class="relative" >
                                <!-- Trigger Link -->
                                <a href="javascript:void(0)"
                                @click="openPanel($event)"
                                class="uiv2-js-openpanel flex justify-between items-center px-4 py-2 text-sm text-gray-700"
                                >
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i> 
                                    <span class="mx-2">Pending Extension Request</span>
                                    <span class="text-blue-500 font-semibold">View</span>
                                </a>

                                <!-- Flyout Overlay -->
                                <div x-show="isOpen"
                                    @click="closePanel"
                                    class="fixed inset-0 bg-black bg-opacity-50 z-40"
                                    x-transition></div>

                                <!-- Flyout Panel -->
                                <div x-show="isOpen"
                                    @click.stop
                                    class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24 p-3"
                                    x-transition>
                                    <div class="text-right my-2">
                                        <a @click="closePanel" class="text-gray-500 hover:text-gray-700 border p-1 rounded"><i class="fas fa-times"></i></a>
                                    </div>
                                    
                                    @include('admin.contract.contract_workflow')
                                    
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        <div class="bg-white mx-4 my-8 rounded p-8">
            <div x-data="{ activePage: 'tab1' }" class="mb-4">
                <ul class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1">
                    <!-- Tab 1: Active Jobs -->
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab1'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab1' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab1' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                            <span class="capitalize">Contract Info</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab1' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                    <!-- Tab 2: Pending Release Jobs -->
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab2'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab2' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab2' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-registered"></i>
                            <span class="capitalize">Budget</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab2' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>


                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab3'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab3' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab3' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">BU</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab3' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab4'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab4' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab4' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-lock"></i>
                            <span class="capitalize">Add Notes</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab4' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab5'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab5' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab5' ? 'var(--primary-color)' : ''}"
                        >
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="capitalize">Resume</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab5' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                </ul>
                    <div class="mt-6">
                        <div x-show="activePage === 'tab1'">
                            @include('admin.contract.contract_info')
                        </div>

                        <div x-show="activePage === 'tab2'">
                            @include('admin.contract.contract_bu')
                        </div>

                        <div x-show="activePage === 'tab3'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                        <div x-show="activePage === 'tab4'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                        <div x-show="activePage === 'tab5'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                    </div>
            </div>
        </div>
    </div>


<script>
    function closeAssignmentTemp() {
    return {
        formData: {
            close_contr_reason: '',
            close_contr_note: ''
        },
        errors: {},

        validateFields() {
            this.errors = {}; // Reset errors

            let errorCount = 0;

            if (this.formData.close_contr_reason === "") {
                this.errors.close_contr_reason = "Close assignment reason is required";
                errorCount++;
            }

            if (this.formData.close_contr_note.trim() === "") {
                this.errors.close_contr_note = "Close assignment note is required";
                errorCount++;
            }

            return errorCount === 0; // Returns true if no errors
        },

        submitData() {
            if (this.validateFields()) {
                const formData = new FormData();
                formData.append('close_contr_reason', this.formData.close_contr_reason);
                formData.append('close_contr_note', this.formData.close_contr_note);

                // Specify your form submission URL
                const url = '{{ route("contract.reject_contract", $contract->id) }}';

                // Send AJAX request using ajaxCall function
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
            }
        },

        onSuccess(response) {
            window.location.href = response.redirect_url;
        }
        }
    }

    function openContract() {
        return {
            submitData() {
                const url = "{{ route('contract.open_contract', $contract->id) }}";
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]]);
            },

            onSuccess(response) {
                window.location.href = response.redirect_url;
            }
        }
    }

    function flyout() {
        return {
            isOpen: false,
            openPanel(event) {
                this.isOpen = true;
                
            },
            closePanel() {
                this.isOpen = false;
            }
        }
    }
</script>
@endsection
