@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div>
          <div class="mx-4 rounded p-8">
              @include('admin.layouts.partials.alerts')
              <div class="w-full flex justify-end items-center gap-4">
            @if (!in_array($submission->resume_status, array(6, 7, 2, 15, 8, 9, 11, 12)) && (!in_array($submission->careerOpportunity->jobStatus, array(4, 12))) && $submission->careerOpportunity->interview_process == 'Yes')
              <a href="{{ route('admin.interview.create',  ['id' => $submission->id]) }}"
                type="button"
                class="px-4 py-2 capitalize bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              >
                  {{translate('Schedule interview')}}
                </a>
                @endif
                @if (
                    in_array($submission->resume_status, [3, 7, 4, 5, 10]) &&
                    (empty($offer) || ($offer && ($offer->status == 2 || $offer->status == 13)) && $offer->status != 12) &&
                    !in_array($submission->careerOpportunity->jobStatus, [23, 24, 4, 1, 5])
                )
                <a href="{{ route('admin.offer.create',  ['id' => $submission->id]) }}"
                type="button"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              >
                    {{translate('create offer')}}
              </a>
             @endif
              @if($submission->careerOpportunity->jobStatus != 5 && $submission->resume_status != 12)
                <div x-data="addSubWizarForm()" x-init="mounted()">
                  @if($submission->resume_status != 6)
                    <button
                          type="button"
                          @click="rejectCandidate({{ $submission->id }})"
                          aria-label="Reject candidate {{ $submission->consultant->full_name }}"
                          class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize"
                    >
                        {{translate('Reject Candidate')}}
                    </button>
                  @endif
                  @if ( !in_array($submission->resume_status, array(8, 11, 6)))
                    @if (!in_array($submission->resume_status, array(3, 4, 5, 7, 9, 15)) && (!in_array($submission->careerOpportunity->jobStatus, array(4))))
                      <button
                          type="button"
                          @click="shortlistCandidate({{ $submission->id }})"
                          aria-label="Shortlist candidate {{ $submission->consultant->full_name }}"
                          class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 capitalize"
                      >
                          {{translate(' Shortlist')}}
                           </button>
                         @endif
                       @endif

                     </div>
                   @endif
                   <a href="{{ route('admin.submission.index') }}">
                  <button
                      type="button"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                  >
                      {{translate(' Back to Submissions')}}
                  </button>
              </a>

            </div>
          </div>
          @if($submission->resume_status == 6)
                <div class="rounded mx-4 my-2 p-4 bg-red-100 text-sm">
                  @if(!empty($submission->reason_for_rejection))
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Reason for Rejection: </span>
                        <span class="text-red-800">  {{$submission->rejectionReason->title}} </span>
                    </p>
                  @endif
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Rejected By: </span>
                        <span class="text-red-800"> {{$submission->rejectedBy->name}} </span>
                    </p>
                  @if(!empty($submission->note_for_rejection))
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Rejection Notes: </span>
                        <span class="text-red-800"> {{$submission->note_for_rejection}} </span>
                    </p>
                  @endif
                    <p>
                        <span class="font-bold m-b-10 text-red-800">Rejected Date & Time: </span>
                        <span class="text-red-800">  {{$submission->formatted_date_rejected}} </span>
                    </p>
                </div>
            @endif
            @if($submission->resume_status == 12)
              <div class="rounded mx-4 my-2 p-4 bg-red-100 text-sm">
                  <p>
                    <span class="font-bold m-b-10 text-red-800">Reason for Withdrawn: </span>
                    <span class="text-red-800">  {{$submission->rejectionReason->title}} </span>
                  </p>
                  <p>
                      <span class="font-bold m-b-10 text-red-800">Withdrawn By: </span>
                      <span class="text-red-800"> {{$submission->rejectedBy->name}} </span>
                  </p>
                  <p>
                      <span class="font-bold m-b-10 text-red-800">Withdrawn Notes: </span>
                      <span class="text-red-800"> {{$submission->note_for_rejection}} </span>
                  </p>

                  <p>
                      <span class="font-bold m-b-10 text-red-800">Withdrawn Date & Time: </span>
                      <span class="text-red-800">  {{$submission->formatted_date_rejected}} </span>
                  </p>
              </div>
            @endif
          <div class="flex gap-8">
            <div class="w-2/4 bg-white mx-4 rounded p-8">
              <!-- Tabs -->
              <div
                x-data="{
  selectedId: null,
  init() {
      // Set the first available tab on the page on page load.
      this.$nextTick(() => this.select(this.$id('tab', 1)))
  },
  select(id) {
      this.selectedId = id
  },
  isSelected(id) {
      return this.selectedId === id
  },
  whichChild(el, parent) {
      return Array.from(parent.children).indexOf(el) + 1
  }
}"
                x-id="['tab']"
                class="w-full"
              >
                <!-- Tab List -->
                <ul
                  x-ref="tablist"
                  @keydown.right.prevent.stop="$focus.wrap().next()"
                  @keydown.home.prevent.stop="$focus.first()"
                  @keydown.page-up.prevent.stop="$focus.first()"
                  @keydown.left.prevent.stop="$focus.wrap().prev()"
                  @keydown.end.prevent.stop="$focus.last()"
                  @keydown.page-down.prevent.stop="$focus.last()"
                  role="tablist"
                  class="-mb-px flex items-center text-gray-500 bg-gray-100 py-1 px-1 rounded-t-lg gap-4"
                >
                  <!-- Tab -->
                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full  bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center gap-3 px-5 py-2.5 hover:rounded-lg hover:bg-white bg-transparent capitalize"
                      role="tab"
                    >
                      <i class="fa-regular fa-thumbs-up"></i>
                      <span class="capitalize">{{translate('submission')}}</span>
                    </button>
                  </li>

                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center px-5 py-2.5 bg-transparent hover:rounded-lg hover:bg-white gap-3"
                      role="tab"
                    >
                      <i class="fa-solid fa-money-bill"></i>
                      <span class="capitalize">{{translate('Rates')}}</span>
                    </button>
                  </li>
                </ul>

                <!-- Panels -->
                <div
                  role="tabpanels"
                  class="rounded-b-md border border-gray-200 bg-white"
                >
                  <!-- First Tab -->
                  <section
                  x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                  :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                  role="tabpanel"
                  class=""
              >
                  <div
                      class="bg-white shadow rounded-lg"
                  >
                      <div class="divide-y divide-gray-200">
                          <!-- Update each field accordingly -->
                          <div class="flex justify-between py-3 px-4">
                              <span class="text-gray-600">{{translate('Candidate Name:')}}</span>
                              <span class="font-semibold">{{$submission->consultant->full_name}}</span>
                          </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Status:')}}</span>
                          <span
                            class="bg-green-500 text-white px-2 py-1 rounded-full text-sm"
                          >{{\App\Models\CareerOpportunitySubmission::getSubmissionStatus($submission->resume_status)}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Unique ID:')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->unique_id}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Last 4 Numbers of National ID:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->national_id}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Worker Preferred Language:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->language}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Availability Date:')}}</span>
                          <span
                            class="font-semibold"
                          >{{formatDate($submission->estimate_start_date)}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
                          <span class="text-gray-600">{{translate('Job Profile:')}}</span>
                          <span
                            class="font-semibold"
                          >
                          <a class="text-blue-400 font-semibold cursor-pointer"
                            onclick="openJobDetailsModal({{ $submission->careerOpportunity->id }})"
                            >{{$submission->careerOpportunity->title}} ({{$submission->careerOpportunity->id}})</a
                          >
                        </span>
                        <x-job-details />
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Hiring Manager:')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->careerOpportunity->hiringManager->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Resume:')}}</span>
                          <span
                            class="font-semibold"
                            >{{$submission->resume}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Vendor Name:')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->vendor->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('OT Eligible?')}}</span>
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->ot_exempt_position == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Is This Worker or Will This Worker Need Sponsorship
                            Now or In The Future?:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->require_employment_visa_sponsorship == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Is this Candidate willing to Commute to
                            Office?:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{ ucfirst($submission->willing_relocate == 'yes' ? 'Yes' : 'No') }}</span>
                        </div>
                        <!-- <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Do you have the right to represent?:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="jobDetails.rightRepresent"
                          ></span>
                        </div> -->
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Preferred Name')}}</span>
                            <span
                                class="font-semibold"
                            >{{$submission->preferred_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Gender')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant?->genDer?->title ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Race')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant?->race?->title ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Has this candidate ever worked for any Gallagher
                            company in any capacity?:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->retiree == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Country:')}}</span>
                          <span
                            class="font-semibold"
                          >{{$submission->location->country->name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Virtual/Remote Candidate?')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->remote_contractor == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Submission Date:')}}</span>
                          <span
                            class="font-semibold"
                          >{{formatDate($submission->formatted_created_at)}}</span>
                        </div>

                        @if(!empty($submission->submission_details) && $submission->submission_details != '[]')
                          @php
                            $submissionDetails = json_decode($submission->submission_details, true); // Decode JSON into an array
                          @endphp
                          <div class="flex items-center justify-between py-3 px-4 border-t">
                            <h3 class="flex items-center gap-2">
                              <i
                                class="fa-solid fa-cash-register"
                                :style="{'color': 'var(--primary-color)'}"
                              ></i
                              ><span :style="{'color': 'var(--primary-color)'}"
                                >{{translate('Additional Data')}}</span
                              >
                            </h3>
                          </div>
                          @php
                              $fieldLabels = collect($formFields)->pluck('label', 'name')->toArray();
                          @endphp
                          @foreach ($submissionDetails as $key => $value)
                            <div class="flex items-center justify-between py-3 px-4 border-t">
                              <div class="w-2/4">
                                <h4 class="font-medium">{{ $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</h4>
                              </div>
                              <div class="w-2/4">
                                @if(is_array($value))
                                    {{-- Handle array values --}}
                                    <p class="font-light">{{ implode(', ', $value) }}</p>
                                @else
                                    {{-- Handle scalar values --}}
                                    <p class="font-light">{{ $value }}</p>
                                @endif
                              </div>
                            </div>
                          @endforeach
                        @endif
                      </div>
                    </div>
                  </section>
                  <!-- Second Tab-->
                  <section
                    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                    role="tabpanel"
                    class=""
                  >
                    <div
                      class="bg-white shadow-md rounded-lg overflow-hidden w-full"
                    >
                      <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                          <span class="text-gray-600">{{translate('Rate Type:')}}</span>
                          <span class="font-semibold"></span>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-md">
                          <div
                            class="flex items-center text-blue-600 font-semibold mb-2"
                          >
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            <span>{{translate('Bill Rate (For Vendor)')}}</span>
                          </div>
                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{translate('Bill Rate:')}}</span>
                            <span
                              class="font-semibold"

                            >${{$submission->vendor_bill_rate}}</span>
                          </div>
                          <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-600">{{translate('Over Time Rate:')}}</span>
                            <span
                              class="font-semibold"
                            >${{$submission->client_over_time_rate}}</span>
                          </div>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-md">
                          <div
                            class="flex items-center text-blue-600 font-semibold mb-2"
                          >
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            <span>{{translate('Bill Rate (For Client)')}}</span>
                          </div>
                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{translate('Bill Rate:')}}</span>
                            <span
                              class="font-semibold"
                            >${{$submission->bill_rate}}</span>
                          </div>
                          <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-600">{{translate('Over Time Rate:')}}</span>
                            <span
                              class="font-semibold"
                            >${{$submission->client_over_time_rate}}</span>
                          </div>
                        </div>

                        <div>
                          <div
                            class="flex items-center text-blue-600 font-semibold mb-2"
                          >
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{translate('Location')}}</span>
                          </div>
                          <div class="flex justify-between items-start">
                            <span class="text-gray-600">{{translate('Location Name:')}}</span>
                            <span
                              class="font-semibold text-right"
                            >{{$submission->location->LocationDetails}}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
            <div class="w-2/4 bg-white h-[1024px] mx-4 rounded p-8">
            @if ($submission->resume)
           @php $fileExtension = pathinfo($submission->resume, PATHINFO_EXTENSION); @endphp
              <object
                data="{{ asset('storage/submission_resume/' . $submission->resume) }}"
                type="application/{{$fileExtension}}"
                width="100%"
                height="100%"
              >
                <p>
                  Alternative text - include a link
                  <a href="{{ asset('storage/submission_resume/' . $submission->resume) }}">to the PDF!</a>
                </p>
              </object>
            @else
              <p>{{translate('No resume available.')}} </p>
            @endif
            </div>
          </div>
        </div>

    </div>

    <script>
      function openJobDetailsModal(jobId) {
        fetch(`/job-details/${jobId}`)
          .then(response => response.json())
          .then(data => {
              const event = new CustomEvent('job-details-updated', {
                      detail: data,
                      bubbles: true,
                      composed: true
                  });
                  // console.log(event.detail.data);

                  document.dispatchEvent(event);
          })
          .catch(error => console.error('Error:', error));
      }
    </script>
    @endsection
