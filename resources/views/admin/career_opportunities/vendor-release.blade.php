@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          @include('admin.layouts.partials.header')


          <div  x-data="{ tab: 'activejobs' }" class="bg-white mx-4 my-8 rounded p-8">
             @if($job->jobStatus == 2)
              <div x-data="{
                    rejectionReason: '{{ $job->rejectionReason ? $job->rejectionReason->title : ' ' }}',
                    notes: '{{ $job->note_for_rejection }}',
                    rejectedBy: '{{ $job->rejectionUser ? $job->rejectionUser->name : '' }}',
                    rejectionDate: '{{ $job->date_rejected }}'
                }">
                    <div class="alert alert-danger">
                        <span class="bold">Rejection Reason:</span> <span x-text="rejectionReason"></span><br>
                        <span class="bold">Notes:</span> <span x-text="notes"></span><br>
                        <span class="bold">Rejected By:</span> <span x-text="rejectedBy"></span><br>
                        <span class="bold">Rejection Date:</span> <span x-text="rejectionDate"></span>
                    </div>
                </div>
              @endif


          <div class="mb-4">
            <ul
              class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
            >
              <li class="flex justify-center">
                <a
                @click="tab = 'activejobs'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'activejobs' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">active jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">156</span>
                  </div>
                </a>
              </li>

              <li class="flex justify-center items-center">
                <a
                  href="#page2"
                  class="w-full flex justify-center items-center gap-3 bg-white rounded-lg shadow py-4"
                  :style="{'color': 'var(--primary-color)'}"
                  ><i class="fa-regular fa-registered"></i
                  ><span class="capitalize">Pending Release Job</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                    :style="{'background-color': 'var(--primary-color)'}"
                  >
                    <span class="text-[10px]">56</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                @click="tab = 'jobworkflow'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'jobworkflow' }"
                class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-fill"></i>
                  <span class="capitalize">Workflow</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">20</span>
                  </div>
                </a>
              </li>

               <li class="flex justify-center" x-data="{ status: {{ $job->jobStatus }} }" x-show="status === 3 || status === 5">
                <a
                  @click="tab = 'vendorrelease'; window.location.href = '/admin/career-opportunities/{{$job->id}}/vendorrelease'"
                  :class="{ 'border-blue-500 text-blue-500': tab === 'vendorrelease' }"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
              >
                  <i class="fa-solid fa-fill"></i>
                  <span class="capitalize">Vendor Release</span>
                  <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                      <span class="text-[10px]">20</span>
                  </div>
              </a>
            </li>

              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-lock"></i>
                  <span class="capitalize">closed jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">2957</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-spinner"></i>
                  <span class="capitalize">pending - PMO</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">0</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fas fa-drafting-compass"></i>
                  <span class="capitalize">draft</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">30</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-briefcase"></i>
                  <span class="capitalize">all jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">4320</span>
                  </div>
                </a>
              </li>
            </ul>
          </div>

<script>
</script>


  @endsection
