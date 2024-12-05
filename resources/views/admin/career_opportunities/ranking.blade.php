<div class="rounded border p-[30px]">
  <div
    class="w-100 p-[30px] rounded border"
    :style="{'border-color': 'var(--primary-color)'}">
    <span>{{translate('Highest Bill Rate')}}</span>
    <table class="min-w-full divide-y divide-gray-200" id="rankingTable">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Candidate Name')}}
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate(' Start Date')}}
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Bill Rate')}}
          </th>

          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Submission Cost')}}
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
        initializeDataTable('#rankingTable', `{{ route('admin.jobRanking' ,  $job->id ) }}`, [
		    { data: 'candidateName', name: 'candidateName' },
		    { data: 'startDate', name: 'startDate' },
		    { data: 'billRate', name: 'billRate' },
		    { data: 'submissionCost', name: 'submissionCost' },
		    { data: 'action', name: 'action', orderable: false, searchable: false }
		]);
    }
});


    </script>
