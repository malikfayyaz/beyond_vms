@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
    @include('admin.layouts.partials.header')
            <div class="mx-4 rounded p-8 ">
            @if(!in_array($contract->status, array(2,3,7,14)) && ($contract->termination_status != 2 || in_array($contract->workOrder->contract_type, [0, 1])) )
            <a href="{{ route('admin.contracts.edit',  ['contract' => $contract->id]) }}"
                type="button"
                class="px-4 py-2 capitalize bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
              >
                Update Contract
              </a>
              @endif
                @include('admin.layouts.partials.alerts')
            </div>
         <div class="bg-white mx-4 my-8 rounded p-8">
            <div x-data="{ activePage: 'tab1' }" class="mb-4">
                <ul class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1">
                    <!-- Tab 1: Active Jobs -->
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab1'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab1' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab1' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                            <span class="capitalize">Contract Info</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab1' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                    <!-- Tab 2: Pending Release Jobs -->
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab2'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab2' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab2' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-registered"></i>
                            <span class="capitalize">Budget</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab2' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>


                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab3'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab3' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab3' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">BU</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab3' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab4'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab4' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab4' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-lock"></i>
                            <span class="capitalize">Add Notes</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab4' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab5'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab5' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab5' ? 'var(--primary-color)' : ''}"
                        >
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="capitalize">Resume</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab5' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                </ul>
                    <div class="mt-6">
                        <div x-show="activePage === 'tab1'">
                        @include('admin.contract.contract_info')
                        </div>

                        <div x-show="activePage === 'tab2'">
                        @include('admin.contract.contract_bu')
                        </div>

                        <div x-show="activePage === 'tab3'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                        <div x-show="activePage === 'tab4'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                        <div x-show="activePage === 'tab5'">
                            @include('admin.contract.contract_tabdata')
                        </div>
                    </div>
                </div>

            </div>



@endsection
