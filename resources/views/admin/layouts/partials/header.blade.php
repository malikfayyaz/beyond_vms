
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
                      src="{{ asset('images/1.png') }}"
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
                  src="{{ asset('images/1.png') }}"
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
                      src="{{ asset('images/1.png') }}"
                      alt="Profile"
                      class="w-10 h-10 rounded-full object-cover mr-3"
                    />
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                      <p class="text-xs text-gray-500">{{ucfirst(session('selected_role'))}}</p>
                    </div>
                  </div>
                </div>

                <hr class="border-gray-200" />

                <a
                  href="{{ route('users.profile') }}"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <i class="fas fa-user w-5 h-5 mr-2"></i>My Profile
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
                onclick="event.preventDefault();
                        ajaxCall('{{ route('logout') }}', 'POST', [[onSuccess, ['response']]]);"
                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
              >
                <i class="fas fa-sign-out-alt w-5 h-5 mr-2"></i> {{ __('Logout') }}
              </a>
              </div>
            </div>
          </div>
        </header>
