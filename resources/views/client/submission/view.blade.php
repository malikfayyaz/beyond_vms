@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('client.layouts.partials.header')

        <div>
            @include('client.layouts.partials.alerts')
          <div class="mx-4 rounded p-8">
            <div class="w-full flex justify-end items-center gap-4">
            @if (!in_array($submission->resume_status, array(6, 7, 2, 15, 8, 9, 11)) && (!in_array($submission->careerOpportunity->jobStatus, array(4, 12))) && $submission->careerOpportunity->interview_process == 'Yes')
              <a href="{{ route('client.interview.create',  ['id' => $submission->id]) }}"

                type="button"
                class="px-4 py-2 capitalize bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              >
                schedule interview
              </a>
              @endif
                @if (
                  in_array($submission->resume_status, [3, 7, 4, 5, 10]) &&
                  (empty($offer) || ($offer && ($offer->status == 2 || $offer->status == 13)) && $offer->status != 12) &&
                  !in_array($submission->careerOpportunity->jobStatus, [23, 24, 4, 1, 5])
              )
              <a href="{{ route('client.offer.create',  ['id' => $submission->id]) }}"
                type="button"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              >
                create offer
              </a>
              @endif

              <a href="{{ route('client.submission.index') }}">
                  <button
                      type="button"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                  >
                      Back to Submissions
                  </button>
              </a>

            </div>
          </div>
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
                      <span class="capitalize">submission</span>
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
                      <span class="capitalize">rates</span>
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
                              <span class="text-gray-600">Candidate Name:</span>
                              <span class="font-semibold">{{$submission->consultant->full_name}}</span>
                          </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Status:</span>
                          <span
                            class="bg-green-500 text-white px-2 py-1 rounded-full text-sm"
                          >{{\App\Models\CareerOpportunitySubmission::getSubmissionStatus($submission->resume_status)}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Unique ID:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->unique_id}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Last 4 Numbers of National ID:</span
                          >
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->national_id}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Worker Preferred Language:</span
                          >
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->language}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Availability Date:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->estimate_start_date}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Job Profile:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->careerOpportunity->title}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Hiring Manager:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->careerOpportunity->hiringManager->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Resume:</span>
                          <span
                            class="font-semibold"
                            >{{$submission->resume}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Vendor Name:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->vendor->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">OT Eligible?</span>
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->ot_exempt_position == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Is This Worker or Will This Worker Need Sponsorship
                            Now or In The Future?:</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->require_employment_visa_sponsorship == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Is this Candidate willing to Commute to
                            Office?:</span
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
                          <span class="text-gray-600">Preferred Name</span>
                          <span
                            class="font-semibold"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Gender</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->genDer->title}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Race</span>
                          <span
                            class="font-semibold"
                          >{{$submission->consultant->race->title}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Has this candidate ever worked for any Gallagher
                            company in any capacity?:</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->retiree == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Country:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->location->country->name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Virtual/Remote Candidate?</span
                          >
                          <span
                            class="font-semibold"
                          >{{ucfirst($submission->remote_contractor == 'yes' ? 'Yes' : 'No')}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Submission Date:</span>
                          <span
                            class="font-semibold"
                          >{{$submission->formatted_created_at}}</span>
                        </div>
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
                          <span class="text-gray-600">Rate Type:</span>
                          <span class="font-semibold"></span>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-md">
                          <div
                            class="flex items-center text-blue-600 font-semibold mb-2"
                          >
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            <span>Bill Rate (For Vendor)</span>
                          </div>
                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">Bill Rate:</span>
                            <span
                              class="font-semibold"

                            >${{$submission->vendor_bill_rate}}</span>
                          </div>
                          <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-600">Over Time Rate:</span>
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
                            <span>Bill Rate (For Client)</span>
                          </div>
                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">Bill Rate:</span>
                            <span
                              class="font-semibold"
                            >${{$submission->bill_rate}}</span>
                          </div>
                          <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-600">Over Time Rate:</span>
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
                            <span>Location</span>
                          </div>
                          <div class="flex justify-between items-start">
                            <span class="text-gray-600">Location Name:</span>
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
              <object
                data="{{ asset('storage/submission_resume/' . $submission->resume) }}"
                type="application/pdf"
                width="100%"
                height="100%"
              >
                <p>
                  Alternative text - include a link
                  <a href="{{ asset('storage/submission_resume/' . $submission->resume) }}">to the PDF!</a>
                </p>
              </object>
            @else
              <p>No resume available.</p>
            @endif
            </div>
          </div>
        </div>

    </div>

    @endsection
