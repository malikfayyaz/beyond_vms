<div x-show="activePage === 'tab3'">
<div class="bg-white mx-4 my-8 rounded p-8">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{translate('Contract BU')}}</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200" id="listing">
                <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Business Unit')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Business Percentage')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Created Date')}}</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        {{translate('Contract Start Date')}}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Status')}}</th>
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
                            {{ formatDateTime($record->created_at) }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ formatDateTime($contract->start_date) }}
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
        const url = '{{ route('admin.saveComments') }}';
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
                                        <label for="comment">{{translate('Add Notes')}}</label>
                                        <input type="hidden" id="hidden_contract_id" value="{{ $contract->id }}">
                                        <textarea id="comment" x-model="note" required class="form-control" placeholder="Enter text ..." style="width: 100%; min-height: 100px"></textarea>
                                        <button type="submit" class="btn btn-success mt-3">{{translate('Submit')}}</button>
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
                                                {{translate('Posted By:')}} {{ $note->posted_by_type }}
                                            </p>
                                            <p class="postedby meta-inner pull-left">
                                                {{translate('Name:')}}' {{ Auth::user()->name }}
                                            </p>
                                            <p class="meta-inner pull-left" style="color: #8b92ca;">
                                                {{ formatDateTime($note->created_at)}}
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
        <p>{{translate('No resume available.')}}</p>
    @endif
</div>
</div>
