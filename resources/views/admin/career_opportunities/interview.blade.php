<div class="rounded border p-[30px]">

   <div
          class="w-100 p-[30px] rounded border"
          :style="{'border-color': 'var(--primary-color)'}">
          <table class="min-w-full divide-y divide-gray-200" id="todayInterview">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Type')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Interview ID')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Contractor Name')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Date')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Start Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' End Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Location')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Vendor')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Vendor')}}
                </th>

              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

          <table class="min-w-full divide-y divide-gray-200" id="otherInterview">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Type')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Interview ID')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Contractor Name')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Date')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Start Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' End Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Location')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Vendor')}}
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
        initializeDataTable('#todayInterview', `{{ route('admin.jobTodayInterview',$job->id ) }}`, [
		    { data: 'type', name: 'type' },
		    { data: 'id', name: 'id' },
		    { data: 'consultant_name', name: 'consultant_name' },
		    { data: 'date', name: 'date' },
		    { data: 'start_time', name: 'start_time' },
		    { data: 'end_time', name: 'end_time' },
		    { data: 'location', name: 'location' },
		    { data: 'vendor_name', name: 'vendor_name' },
		    { data: 'action', name: 'action', orderable: false, searchable: false }
		]);
  }
});

document.addEventListener('DOMContentLoaded', function() {
    if (window.$) {
        initializeDataTable('#otherInterview', `{{ route('admin.jobOtherInterview',$job->id ) }}`, [
        { data: 'type', name: 'type' },
        { data: 'id', name: 'id' },
        { data: 'consultant_name', name: 'consultant_name' },
        { data: 'date', name: 'date' },
        { data: 'start_time', name: 'start_time' },
        { data: 'end_time', name: 'end_time' },
        { data: 'location', name: 'location' },
        { data: 'vendor_name', name: 'vendor_name' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]);
  }
});


    </script>
