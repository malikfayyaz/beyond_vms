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
                          <li class="{{ request()->routeIs('client.career-opportunities.index') ? 'active' : '' }}">
                              <a href="{{ route('client.career-opportunities.index') }}"
                                 class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                                  {{translate('Job')}}
                              </a>
                          </li>
{{--                      @endcan--}}
                      @can('catalog-index')
                          {{--<li class="{{ request()->routeIs('client.catalog.index') ? 'active' : '' }}">
                              <a href="{{ route('client.catalog.index') }}"
                                 class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                                  Job Catalog
                              </a>
                          </li>--}}
                      @endcan
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
                <li class="{{ request()->routeIs('client.submission.index') ? 'active' : ''}}">
                  <a href="{{ route('client.submission.index') }}"
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
                <li class="{{ request()->routeIs('client.interview.index') ? 'active' : ''}}">
                  <a href="{{ route('client.interview.index') }}"
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
                    <li class="{{ request()->routeIs('client.offer.index') ? 'active' : ''}}">
                    <a href="{{ route('client.offer.index') }}"
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
                      <li class="{{ request()->routeIs('client.workorder.index') ? 'active' : ''}}">
                          <a href="{{ route('client.workorder.index') }}"
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
                      <li class="{{ request()->routeIs('client.contracts.index') ? 'active' : ''}}">
                          <a href="{{ route('client.contracts.index') }}"
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
                <i class="fas fa-shopping-cart w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >eCommerce</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <li>
                  <a
                    href="#"
                    class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700"
                    >Shop</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700"
                    >Details</a
                  >
                </li>
              </ul>
            </li>
            <li>
              <a
                href="#"
                class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-envelope w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >Email</span
                >
                <span
                  class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full"
                  x-show="!miniSidebar"
                  x-cloak
                  >3</span
                >
              </a>
            </li>
            <li>
              <a
                href="#"
                class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                :class="{'justify-center': miniSidebar}"
              >
                <i class="fas fa-comment w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >Chat</span
                >
              </a>
            </li>
          </ul>
        </div>
      </aside>
