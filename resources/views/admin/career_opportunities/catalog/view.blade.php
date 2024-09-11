@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
          @include('admin.layouts.partials.header')
          <div class="bg-white mx-4 my-8 rounded p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Enrollment Professional</h2>
            <button
              type="button"
              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              onclick="window.location.href='{{ route('admin.catalog.index') }}'"
            >
              back to job catalogs
            </button>
          </div>
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
                  <i class="fa-regular fa-eye"></i>
                  <span class="capitalize">catalog view</span>
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
                  <i class="fa-solid fa-address-card"></i>
                  <span class="capitalize">rate card</span>
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
                x-data='{
{{--        jobDetails: @json($job->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT)--}}
                jobDetails: @json($job->toArray())
                }'

                :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                role="tabpanel"
                class="p-8"
              >
                <h2 class="text-xl font-bold mb-4">Description</h2>
                <div class="flex gap-8">
                  <div class="p-8 bg-sky-100 rounded w-8/12">
                    <span
                        class="font-semibold"
                        x-text="jobDetails.job_description"
                    ></span>
                  </div>
                  <div class="w-4/12">
                    <div class="max-w-md w-full bg-white shadow rounded-lg overflow-hidden">
                        <div class="divide-y divide-gray-200">
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Job Labor Category:</span>
                          <span
                            class="font-semibold"
                            x-text="">{{ $categoryTitle }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Job Code:</span>
                          <span
                            class="font-semibold"
                            x-text="jobDetails.job_code"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Profile Worker Type:</span>
                          <span
                            class="font-semibold"
                            x-text="">{{ $profileWorkerTypeTitle }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Status:</span>
                          <span
                            class="bg-green-500 text-white px-2 py-1 rounded-full text-sm"
                            x-text="jobDetails.status"
                          ></span>
                        </div>
                      </div>
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
                <div class="mt-4">
                  <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="w-full">
                      <thead>
                        <tr class="bg-gray-50 text-left">
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            S.NO
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Job Level
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Minimum Bill Rate
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Maximum Bill Rate
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Currency
                          </th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200">
                      @foreach($templateratecard->templateratecard as $index => $ratecard)
                          <tr>
                              <td class="py-4 px-4 text-center text-sm">{{ $index + 1 }}</td>
                              <td class="py-4 px-4 text-center text-sm">{{ $ratecard->jobLevel->title }}</td>
                              <td class="py-4 px-4 text-center text-sm">{{ $ratecard->min_bill_rate }}</td>
                              <td class="py-4 px-4 text-center text-sm">{{ $ratecard->bill_rate }}</td>
                              <td class="py-4 px-4 text-center text-sm">{{ $ratecard->currency->title }}</td>
                          </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
