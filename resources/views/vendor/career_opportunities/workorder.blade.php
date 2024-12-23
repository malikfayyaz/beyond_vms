<div class="rounded border p-[30px]">
  <div
    class="w-100 p-[30px] rounded border"
    :style="{'border-color': 'var(--primary-color)'}">
    <table class="min-w-full divide-y divide-gray-200" id="workorderTable">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Status')}}
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Offer ID')}}
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
             {{translate(' Contractor Name')}}
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Offer Date')}}
            </th>

            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{translate('Bill Rate')}}
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{translate('Location')}}
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
          initializeDataTable('#workorderTable', `{{ route('vendor.jobWorkorder' ,  $job->id ) }}`, [
		    { data: 'status', name: 'status' },
		    { data: 'id', name: 'id' },
		    { data: 'consultant_name', name: 'consultant_name' },
            { data: 'start_date', name: 'start_date' },
            { data: 'bill_rate', name: 'bill_rate' },
		    { data: 'location', name: 'location' },
		    { data: 'action', name: 'action', orderable: false, searchable: false }
		]);
    }
});


</script>
