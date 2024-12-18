<aside
        class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out"
        :class="{'w-64': !miniSidebar, 'w-16': miniSidebar}"
        :style="{'background-color': 'var(--primary-color)'}"
        @mouseenter="miniSidebar = false"
        @mouseleave="miniSidebar = true"
        >
        <div class="h-full px-3 py-4 overflow-y-auto">
          <a
            href="#"
            class="flex items-center mb-5 overflow-hidden"
            :class="{'justify-center': miniSidebar}"
          >
            <i class="fas fa-user-circle text-white text-2xl"></i>
            <span
              class="self-center text-xl font-semibold whitespace-nowrap text-white ml-2"
              x-show="!miniSidebar"
              x-cloak
              >{{translate(ucfirst(session('selected_role')))}}</span
            >
          </a>
          <ul class="space-y-2 font-medium">
            <li>
              <a
                href="#"
                class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-tachometer-alt w-6 h-6"></i>
                <span class="ml-3" x-show="!miniSidebar" x-cloak
                  >{{translate('Dashboard')}}</span
                >
              </a>
            </li>
              <li x-data="{ open: false }">
                  <button
                      @click="open = !open"
                      class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                      :class="{'justify-center': miniSidebar}"
                  >
                      <i class="fas fa-cog w-6 h-6"></i>
                      <span
                          class="flex-1 ml-3 text-left whitespace-nowrap"
                          x-show="!miniSidebar"
                          x-cloak
                      >{{translate('Job')}}</span
                      >
                      <i
                          class="fas fa-chevron-down ml-auto"
                          x-show="!miniSidebar"
                          x-cloak
                      ></i>
                  </button>
                  <ul x-show="open" class="py-2 space-y-2" x-cloak>
                      <!-- Job create -->
{{--                      @can('job-index')--}}
                          <li class="{{ request()->routeIs('vendor.career-opportunities.index') ? 'active' : '' }}">
                              <a href="{{ route('vendor.career-opportunities.index') }}"
                                 class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                                  {{translate('Job')}}
                              </a>
                          </li>
{{--
                      @endcan
--}}
                  </ul>

              </li>
            <li x-data="{ open: false }">
              <button
                @click="open = !open"
                class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-shopping-cart w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >{{translate('Submission')}}</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <li>
                <li class="{{ request()->routeIs('vendor.submission.index') ? 'active' : ''}}">
                  <a href="{{ route('vendor.submission.index') }}"
                    class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      {{translate('Submission')}}
                  </a>
                </li>

              </ul>
            </li>

            <li x-data="{ open: false }">
              <button
                @click="open = !open"
                class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-shopping-cart w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >{{translate('Interview')}}</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <li>
                <li class="{{ request()->routeIs('vendor.interview.index') ? 'active' : ''}}">
                  <a href="{{ route('vendor.interview.index') }}"
                    class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      {{translate('Interview')}}
                  </a>
                </li>

              </ul>
            </li>

            <li x-data="{ open: false }">
              <button
                @click="open = !open"
                class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-shopping-cart w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >{{translate('Offer')}}</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                    <li class="{{ request()->routeIs('vendor.offer.index') ? 'active' : ''}}">
                    <a href="{{ route('vendor.offer.index') }}"
                        class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                        {{translate('Offer')}}
                    </a>
                </li>

              </ul>
            </li>
              <li x-data="{ open: false }">
                  <button
                      @click="open = !open"
                      class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                      :class="{'justify-center': miniSidebar}"
                  >
                      <i class="fas fa-shopping-cart w-6 h-6"></i>
                      <span
                          class="flex-1 ml-3 text-left whitespace-nowrap"
                          x-show="!miniSidebar"
                          x-cloak
                      >{{translate('WorkOrder')}}</span
                      >
                      <i
                          class="fas fa-chevron-down ml-auto"
                          x-show="!miniSidebar"
                          x-cloak
                      ></i>
                  </button>
                  <ul x-show="open" class="py-2 space-y-2" x-cloak>
                      <li class="{{ request()->routeIs('vendor.workorder.index') ? 'active' : ''}}">
                          <a href="{{ route('vendor.workorder.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              {{translate('WorkOrder')}}
                          </a>
                      </li>

                  </ul>
              </li>
              <li x-data="{ open: false }">
                  <button
                      @click="open = !open"
                      class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                      :class="{'justify-center': miniSidebar}"
                  >
                      <i class="fas fa-lock w-6 h-6"></i>
                      <span
                          class="flex-1 ml-3 text-left whitespace-nowrap"
                          x-show="!miniSidebar"
                          x-cloak
                      >{{translate('Contract')}}</span
                      >
                      <i
                          class="fas fa-chevron-down ml-auto"
                          x-show="!miniSidebar"
                          x-cloak
                      ></i>
                  </button>
                  <ul x-show="open" class="py-2 space-y-2" x-cloak>
                      <li class="{{ request()->routeIs('vendor.contracts.index') ? 'active' : ''}}">
                          <a href="{{ route('vendor.contracts.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              {{translate('Contract')}}
                          </a>
                      </li>

                  </ul>
              </li>

              <li x-data="{ open: false }">
                  <button
                      @click="open = !open"
                      class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                      :class="{'justify-center': miniSidebar}"
                  >
                      <i class="fas fa-lock w-6 h-6"></i>
                      <span
                          class="flex-1 ml-3 text-left whitespace-nowrap"
                          x-show="!miniSidebar"
                          x-cloak
                      >{{translate('Timesheet')}}</span
                      >
                      <i
                          class="fas fa-chevron-down ml-auto"
                          x-show="!miniSidebar"
                          x-cloak
                      ></i>
                  </button>
                  <ul x-show="open" class="py-2 space-y-2" x-cloak>
                      <li class="{{ request()->routeIs('vendor.timesheet.select_candidate') ? 'active' : ''}}">
                          <a href="{{ route('vendor.timesheet.select_candidate') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              {{translate('Add Timesheet')}}
                          </a>
                      </li>

                  </ul>
              </li>
            <ul>
            <li x-data="{ open: false }"  @click.away="open = false">
                <button
                    @click="open = !open"
                    class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                    :class="{'justify-center': miniSidebar}"
                >
                    <i class="fas fa-cog w-6 h-6"></i>
                    <span
                        class="flex-1 ml-3 text-left whitespace-nowrap"
                        x-show="!miniSidebar"
                        x-cloak
                    >{{translate('App Settings') }}</span
                    >
                    <i
                        class="fas fa-chevron-down ml-auto"
                        x-show="!miniSidebar"
                        x-cloak
                    ></i>
                </button>
                <ul x-show="open" class="py-2 space-y-2" x-cloak>
                    <!-- vendor Link -->
                    <li class="{{ request()->routeIs('vendor.staffmember.index') ? 'active' : '' }}">
                        <a href="{{ route('vendor.staffmember.index') }}"
                           class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                            {{ translate('Staff Member') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('users.profile') }}"
                           class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                            {{ translate('Vendor Profile') }}
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

          </ul>
        </div>
      </aside>
