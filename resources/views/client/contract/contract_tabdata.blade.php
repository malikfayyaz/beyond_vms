<div x-show="activePage === 'tab2'">
<div class="rounded border p-[30px]">
    <div class="flex w-full m-[20px]">
        <p class="font-bold text-blue-400 flex items-center ">
            <i class="fa fa-user-circle mr-2"></i>
            {{$contract->consultant->full_name}}
        </p>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400 mb-2"></i>
                <div class="mt-2">
                    <span class="block text-2xl font-bold text-blue-400">$36,280.00</span>
                    <span class="text-gray-800">Approved Budget</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-3xl text-blue-400"></i>
                <div class="mt-2">
                    <span class="block text-xl font-bold text-blue-400">$0.00</span>
                    <span class="text-gray-800">Spend Pending Approval Amount</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400"></i>
                <div class="mt-2">
                    <span id="remaining_spend_amount" class="block text-2xl font-bold text-blue-400">$33,886.00</span>
                    <span class="text-gray-800">Remaining Spend Amount</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400"></i>
                <div class="mt-2">
                    <span id="spend_to_date" class="block text-2xl font-bold text-blue-400">$2,394.00</span>
                    <span class="text-gray-800">Spend to Date</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- {{$contract->consultant->full_name}} -->
<!-- <pre>{{ json_encode($contract, JSON_PRETTY_PRINT) }}</pre>  -->
</div>
<div x-show="activePage === 'tab3'">
    <div class="bg-white mx-4 my-8 rounded p-8">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Contract BU</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200" id="listing">
                <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business Percentage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Contract Start Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contract->careerOpportunity->careerOpportunitiesbu as $record)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->buName->name     }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->percentage }}%
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->created_at }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $contract->start_date }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $contract->getContractStatus($contract->status)  }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div x-show="activePage === 'tab4'" x-data="{
    note: '',
    contractId: '{{ $contract->id }}',
    submitForm() {
        console.log('Submitting form with note:', this.note);
        let formData = new FormData();
        formData.append('note', this.note);
        formData.append('contract_id', this.contractId);
        const url = '{{ route('client.saveComments') }}';
        // Make sure ajaxCall is defined
        ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
    }
}">
    <div class="row padding_cls">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show_hd_message" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <strong>Message!</strong> <span class="insert_message"></span>.
            </div>

            <div id="notesmessagewarning"></div>

            <div class="col-12">
                <div class="interview-notes-comments p-t-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="media">
                                <form @submit.prevent="submitForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="media-body">
                                        <label for="comment">Add Notes</label>
                                        <input type="hidden" id="hidden_contract_id" value="{{ $contract->id }}">
                                        <textarea id="comment" x-model="note" required class="form-control" placeholder="Enter text ..." style="width: 100%; min-height: 100px"></textarea>
                                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                                        <button type="button" class="btn btn-success wait_comment mt-3" style="display: none;">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Existing comments -->
                            @foreach ($contract->contractNotes as $note)
                                <div class="uiv2-note-wrapper">
                                    <div class="dialogbox">
                                        <div class="body">
                                            <span class="tip tip-up"></span>
                                            <div class="message">
                                                <p><strong>{{ $note->notes }}</strong></p>
                                            </div>
                                            <p class="postedby meta-inner pull-left">
                                                Posted By: {{ $note->posted_by_type }}
                                            </p>
                                            <p class="postedby meta-inner pull-left">
                                                Name: {{ Auth::user()->name }}
                                            </p>
                                            <p class="meta-inner pull-left" style="color: #8b92ca;">
                                                {{ $note->created_at->format('m/d/Y') }} at {{ $note->created_at->format('H:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div x-show="activePage === 'tab5'">
    <div class="w-2/4 bg-white h-[1024px] mx-4 rounded p-8">
        @if ($contract->submission->resume)
            @php $fileExtension = pathinfo($contract->submission->resume, PATHINFO_EXTENSION);
            @endphp
            <object
                data="{{ asset('storage/submission_resume/' . $contract->submission->resume) }}"
                type="application/{{$fileExtension}}"
                width="100%"
                height="100%"
            >
                <p>
                    Alternative text - include a link
                    <a href="{{ asset('storage/submission_resume/' . $contract->submission->resume) }}">to the PDF!</a>
                </p>
            </object>
        @else
            <p>No resume available.</p>
        @endif
    </div>
</div>
