<div class="rounded border p-[30px]">

   <div
          class="w-100 p-[30px] rounded border"
          :style="{'border-color': 'var(--primary-color)'}">
          <table class="min-w-full divide-y divide-gray-200" id="submissionTable">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Status')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Submission ID')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Candidate Name')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Vendor')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Start Date')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Flag')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Bill Rate')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Unique ID')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Action')}}
                </th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
      </div>



</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.$) {
        initializeDataTable('#submissionTable', `{{ route('vendor.jobSubmission' ,  $job->id ) }}`, [
		    { data: 'status', name: 'status' },
		    { data: 'submissionID', name: 'submissionID' },
		    { data: 'candidateName', name: 'candidateName' },
		    { data: 'vendor', name: 'vendor' },
		    { data: 'startDate', name: 'startDate' },
		    { data: 'flag', name: 'flag' },
		    { data: 'billRate', name: 'billRate' },
		    { data: 'uniqueID', name: 'uniqueID' },
		    { data: 'action', name: 'action', orderable: false, searchable: false }
		]);
    }
});


    </script>
