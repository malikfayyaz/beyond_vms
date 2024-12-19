<div class="rounded border p-[30px]">
  <div
    class="w-100 p-[30px] rounded border"
    :style="{'border-color': 'var(--primary-color)'}"
    x-data="accountManager({{ 'null' }})">
    <div class="container mx-auto p-4">
      <div class="flex flex-wrap mb-4">
          <div class="w-1/2 pr-2">
            <label for="user_id" class="block mb-2">{{translate('PMO Team Member')}} <span class="text-red-500">*</span></label>
            <select
                id="user_id"
                name="user_id"
                class="w-full p-2 border rounded h-10 bg-white"
                x-model="formData.user_id"
                x-ref="user_id"
            >
                <option value="" disabled>{{translate('Select Team Member')}}</option>
                @foreach($admins as $data)
                    <option value="{{ $data->id }}">{{ $data->first_name }} {{ $data->last_name}}</option>
                @endforeach

            </select>
            <p class="text-red-500 text-sm mt-1" x-text="user_idError"></p>
        </div>
        <input type="hidden" name="job_id" value="{{ $job->id }}">
        <div class="flex mb-4">
          <button
              @click="submitData()"
              class="bg-blue-500 text-white px-4 py-2 rounded mr-2 mt-[30px]"
          >{{translate('Add')}}
          </button>
      </div>
    </div>
  </div>
    <table class="min-w-full divide-y divide-gray-200" id="dataTable">
      <thead class="bg-gray-50">
        <tr>
          <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Sr. #
          </th> -->
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            {{translate('Name')}}
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
           {{translate(' Email')}}
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
           {{translate(' Action')}}
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
      table = initializeDataTable('#dataTable', `{{ route('admin.pmoteammember',$job->id) }}`, [

       { data: 'name', name: 'name' },
       { data: 'email', name: 'email' },
       { data: 'action', name: 'action', orderable: false, searchable: false }
   ]);
  }
});
  function accountManager(editIndex) {

    return {
      formData: {
          user_id: "",
      },
      user_idError:"",
      editIndex: editIndex,
      searchTerm: "",
      error: 0,
      currentUrl: `{{ url()->current() }}`,

      validateFields() {
          this.error = 0;

          if (this.formData.user_id === "") {
              this.user_idError = `Please select Team Member`;
              this.error += 1;
          } else {
              this.user_idError = "";
          }
      },

      submitData() {
          this.validateFields();
          if (this.error === 0) {
              let formData = new FormData();
              formData.append('user_id', this.formData.user_id);

              let url = '{{ route('admin.pmoteammember', $job->id) }}';
              if (this.editIndex !== null) {
                  url = '{{ route("admin.pmoteammember", $job->id) }}';
                  url = url.replace(':id', editIndex);
                  formData.append('_method', 'PUT');
              }

              ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
              table.ajax.reload();
          }
      }
  };
}

    </script>
