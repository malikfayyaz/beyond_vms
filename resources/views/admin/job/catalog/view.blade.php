@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
          <header
          class="mx-4 py-2 px-4 mt-4 rounded flex justify-between items-center transition-all duration-300 ease-in-out"
          :style="{'background-color': 'var(--primary-color)'}"
        >
          <!-- Search Bar -->
          <div class="search-bar">
            <div class="relative w-full">
              <input
                type="text"
                placeholder="Search..."
                class="w-full custom-placeholder text-white pl-10 pr-4 py-2 rounded-md focus:outline-none focus:ring-2 transition-colors duration-200"
                :class="{
      'border-none': true,
      'bg-transparent': true,
      'placeholder-white': !darkMode,
      'placeholder-white': darkMode,
      'text-white': !darkMode,
      'text-white': darkMode,
      'focus:ring-blue-500': currentTheme === 'theme-1',
      'focus:ring-green-500': currentTheme === 'theme-2',
      'focus:ring-purple-500': currentTheme === 'theme-3',
    }"
                style="background-color: transparent !important"
              />
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <i class="fas fa-search text-white cursor-pointer"></i>
              </div>
            </div>
          </div>
          <!-- Header Nav -->
          <div class="header-nav flex items-center gap-4">
            <div x-data="{ open: false }" class="relative">
              <button
                @click="open = !open"
                class="flex items-center focus:outline-none w-10 h-10 rounded-full flex items-center justify-center"
              >
                <i class="fa-solid fa-palette text-white"></i>
              </button>

              <div
                x-show="open"
                @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
              >
                <a
                  href="#"
                  @click="setTheme('theme-1')"
                  class="block px-4 py-2 text-sm text-gray-900 flex items-center"
                  :class="{'bg-blue-500 text-white hover:bg-blue-600': currentTheme === 'theme-1', 'bg-white hover:text-white hover:bg-gray-400': currentTheme !== 'theme-1'}"
                >
                  Theme 1
                </a>
                <a
                  href="#"
                  @click="setTheme('theme-2')"
                  class="block px-4 py-2 text-sm text-gray-900 flex items-center"
                  :class="{'bg-green-500 text-white hover:bg-green-600': currentTheme === 'theme-2', 'bg-white hover:text-white hover:bg-gray-400': currentTheme !== 'theme-2'}"
                >
                  Theme 2
                </a>
                <a
                  href="#"
                  @click="setTheme('theme-3')"
                  class="block px-4 py-2 text-sm text-gray-900 flex items-center"
                  :class="{'bg-purple-500 text-white hover:bg-purple-600': currentTheme === 'theme-3', 'bg-white hover:text-white hover:bg-gray-400': currentTheme !== 'theme-3'}"
                >
                  Theme 3
                </a>
              </div>
            </div>
            <div x-data="{ open: false, notifications: 8 }" class="relative">
              <button
                @click="open = !open"
                class="relative focus:outline-none hover:bg-blue-600 rounded-full w-10 h-10"
              >
                <div class="p-2 flex justify-center items-center">
                  <i class="fas fa-bell text-white"></i>
                </div>
                <div
                  class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full"
                  x-text="notifications"
                ></div>
              </button>
              <div
                x-show="open"
                @click.away="open = false"
                class="absolute right-0 mt-2 w-80 bg-white rounded-md overflow-hidden shadow-xl z-10"
              >
                <div
                  class="px-4 py-3 flex justify-between items-center border-b border-gray-200"
                >
                  <span class="font-semibold text-gray-700">Notifications</span>
                  <button
                    class="text-gray-500 hover:text-gray-700"
                    title="Mark all as read"
                  >
                    <i class="fas fa-envelope-open"></i>
                  </button>
                </div>
                <div class="max-h-64 overflow-y-auto">
                  <div
                    class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100"
                  >
                    <div
                      class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3"
                    >
                      JD
                    </div>
                    <div class="flex-grow">
                      <p class="text-sm font-medium text-gray-900">
                        New message from John Doe
                      </p>
                      <p class="text-xs text-gray-500">
                        Hey, how's it going? Just wanted to check in...
                      </p>
                    </div>
                    <div class="flex-shrink-0 ml-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    </div>
                  </div>

                  <div
                    class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100"
                  >
                    <img
                      src="../assets/images/1.png"
                      alt="Sarah Smith"
                      class="w-10 h-10 rounded-full mr-3"
                    />
                    <div class="flex-grow">
                      <p class="text-sm font-medium text-gray-900">
                        Sarah Smith liked your post
                      </p>
                      <p class="text-xs text-gray-500">
                        Sarah Smith liked your recent post about web design
                        trends...
                      </p>
                    </div>
                  </div>

                  <div
                    class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100"
                  >
                    <div
                      class="flex-shrink-0 w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3"
                    >
                      CF
                    </div>
                    <div class="flex-grow">
                      <p class="text-sm font-medium text-gray-900">
                        New comment from Charles Franklin
                      </p>
                      <p class="text-xs text-gray-500">
                        Charles Franklin commented on your article about UX
                        design...
                      </p>
                    </div>
                    <div class="flex-shrink-0 ml-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 bg-gray-100 border-t border-gray-200">
                  <button
                    class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                  >
                    View all notifications
                  </button>
                </div>
              </div>
            </div>
            <div x-data="{ open: false }" class="relative">
              <button
                @click="open = !open"
                class="flex items-center focus:outline-none"
              >
                <img
                  src="../assets/images/1.png"
                  alt="Profile"
                  class="w-10 h-10 rounded-full object-cover"
                />
              </button>

              <div
                x-show="open"
                @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
              >
                <div class="px-4 py-3">
                  <div class="flex items-center">
                    <img
                      src="../assets/images/1.png"
                      alt="Profile"
                      class="w-10 h-10 rounded-full object-cover mr-3"
                    />
                    <div>
                      <p class="text-sm font-medium text-gray-900">John Doe</p>
                      <p class="text-xs text-gray-500">Admin</p>
                    </div>
                  </div>
                </div>

                <hr class="border-gray-200" />

                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-user w-5 h-5 mr-2"></i> My Profile
                </a>
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-cog w-5 h-5 mr-2"></i> Settings
                </a>
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-dollar-sign w-5 h-5 mr-2"></i> Billing
                </a>

                <hr class="border-gray-200" />

                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-question-circle w-5 h-5 mr-2"></i> FAQ
                </a>

                <hr class="border-gray-200" />

                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-sign-out-alt w-5 h-5 mr-2"></i> Logout
                </a>
              </div>
            </div>
          </div>
        </header>
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
