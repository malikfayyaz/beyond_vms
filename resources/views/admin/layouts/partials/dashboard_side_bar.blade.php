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
              >{{ucfirst(session('selected_role'))}}</span
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
                  >Dashboard</span
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
                  >Job</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <!-- Job create -->
                  @can('job-index')
                      <li class="{{ request()->routeIs('admin.career-opportunities.index') ? 'active' : '' }}">
                          <a href="{{ route('admin.career-opportunities.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                               Job
                          </a>
                      </li>
                  @endcan
                  @can('catalog-index')
                  <li class="{{ request()->routeIs('admin.catalog.index') ? 'active' : '' }}">
                  <a href="{{ route('admin.catalog.index') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                       Job Catalog
                  </a>
                </li>
                  @endcan
              </ul>

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
                      >Roles & Permission</span
                      >
                      <i
                          class="fas fa-chevron-down ml-auto"
                          x-show="!miniSidebar"
                          x-cloak
                      ></i>
                  </button>
                  <ul x-show="open" class="py-2 space-y-2" x-cloak>

                      <li class="{{ request()->routeIs('users') ? 'active' : '' }}">
                          <a href="{{ route('users.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              Assign User Roles&Permissions
                          </a>
                      </li>
                      <li class="{{ request()->routeIs('roles') ? 'active' : '' }}">
                          <a href="{{ route('roles.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              Assign Roles to Permissions
                          </a>
                      </li>
                      @can('roles-index')
                      <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                          <a href="{{ route('roles.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              Roles
                          </a>
                      </li>
                      @endcan
                      @can('permissions-index')
                      <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                          <a href="{{ route('permissions.index') }}"
                             class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                              Permission
                          </a>
                      </li>
                      @endcan
                  </ul>

              </li>

            <!-- <li>
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
            </li> -->
            <!-- <li>
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
            </li> -->
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
                  >Data Point</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <!-- Account Code Menu Item -->
                <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'account-code' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'account-code']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Account Code
                  </a>
              </li>

               <!-- Spend Category Code Menu Item -->
               <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'spend-category-code' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'spend-category-code']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Spend Category Code
                  </a>
              </li>

              <li class="{{ request()->routeIs('admin.data.four') && request()->route('type') === 'currency' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.four', ['type' => 'currency']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Currency
                  </a>
              </li>

               <!-- workspace Code Menu Item -->
               <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'workspace' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'workspace']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Workspace
                  </a>
              </li>

               <!-- Program Industry Name Menu Item -->
               <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'program-industry-name' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'program-industry-name']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Program Industry Name
                  </a>
              </li>


              <!-- Departments Menu Item -->
              <li class="{{ request()->is('admin/three/departments') ? 'active' : '' }}">
                  <a href="{{ url('admin/three/departments') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Departments
                  </a>
              </li>

                <!-- cost center Menu Item -->
                <li class="{{ request()->is('admin/three/cost-center') ? 'active' : '' }}">
                  <a href="{{ url('admin/three/cost-center') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Cost Center
                  </a>
              </li>

               <!-- Business Unit Menu Item -->
                <li class="{{ request()->is('admin/three/busines-unit') ? 'active' : '' }}">
                  <a href="{{ url('admin/three/busines-unit') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Business Unit
                  </a>
              </li>

              <!-- Job Family Name Menu Item -->
              <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'job-family' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'job-family']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Job Family Name
                  </a>
              </li>

              <!-- Job Family group Name Menu Item -->
              <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'job-family-group' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'job-family-group']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Job Family Group
                  </a>
              </li>

              <li class="{{ request()->routeIs('admin.data.job_group_family_config') ? 'active' : '' }}">
                  <a href="{{ route('admin.data.job_group_family_config') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Job Family Group Configuration
                  </a>
              </li>

              <!-- Division Menu Item -->
              <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'division' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'division']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Division
                  </a>
              </li>

              <!-- Branch Menu Item -->
              <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'branch' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'branch']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Branch
                  </a>
              </li>

               <!-- Region Zone Menu Item -->
               <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'region-zone' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'region-zone']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Region Zone
                  </a>
              </li>

              <li class="{{ request()->routeIs('admin.data.division_branch_zone_config') ? 'active' : '' }}">
                  <a href="{{ route('admin.data.division_branch_zone_config') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Division Branch Zone Configuration
                  </a>
              </li>


               <!-- Brand Menu Item -->
               <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'brand' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'brand']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Brand
                  </a>
              </li>

              <!-- Group / Platform Menu Item -->
              <li class="{{ request()->routeIs('admin.data.two') && request()->route('type') === 'group-platform' ? 'active' : '' }}">
                  <a href="{{ route('admin.data.two', ['type' => 'group-platform']) }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Group / Platform
                  </a>
              </li>

               <!-- GL Code Menu Item -->
               <li class="{{ request()->is('admin/three/gl-code') ? 'active' : '' }}">
                  <a href="{{ url('admin/three/gl-code') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      GL Code
                  </a>
              </li>

              <!-- Location Menu Item -->
              <li class="{{ request()->routeIs('admin.data.location') ? 'active' : '' }}">
                  <a href="{{ route('admin.data.location') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Location
                  </a>
              </li>
               <!-- Setting Menu Item -->
               <li class="{{ request()->routeIs('admin.setting.info') ? 'active' : '' }}">
                  <a href="{{ route('admin.setting.info') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Setting
                  </a>
              </li>
              <li class="{{ request()->routeIs('admin.setting.markup') ? 'active' : '' }}">
                  <a href="{{ route('admin.setting.markup') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Markup
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
                <i class="fas fa-cog w-6 h-6"></i>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  x-show="!miniSidebar"
                  x-cloak
                  >System Users</span
                >
                <i
                  class="fas fa-chevron-down ml-auto"
                  x-show="!miniSidebar"
                  x-cloak
                ></i>
              </button>
              <ul x-show="open" class="py-2 space-y-2" x-cloak>
                <!-- Account Code Menu Item -->
                <li class="{{ request()->routeIs('admin.admin-users.index') ? 'active' : '' }}">
                  <a href="{{ route('admin.admin-users.index') }}"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700">
                      Admin Users
                  </a>
                </li>
              <ul>
            </li> 
          </ul>
        </div>
      </aside>
