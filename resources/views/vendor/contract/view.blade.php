@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            @include('vendor.layouts.partials.alerts')
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
                            <span class="capitalize">{{translate('Contract Info')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                 :style="{'background-color': activePage === 'tab1' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
{{--                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab2'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab2' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab2' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-registered"></i>
                            <span class="capitalize">{{translate('Budget</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                 :style="{'background-color': activePage === 'tab2' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>--}}
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab3'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab3' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab3' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">{{translate('Business Unit')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                 :style="{'background-color': activePage === 'tab3' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
{{--
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
--}}
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab5'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            :class="activePage === 'tab5' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab5' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-solid fa-briefcase"></i>
                            <span class="capitalize">{{translate('Resume')}}</span>
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
                        @include('vendor.contract.contract_info')
                    </div>
                    <div x-show="activePage === 'tab2'">
                        @include('vendor.contract.contract_tabdata')
                    </div>

                    <div x-show="activePage === 'tab3'">
                        @include('vendor.contract.contract_tabdata')
                    </div>
                    <div x-show="activePage === 'tab4'">
                        @include('vendor.contract.contract_tabdata')
                    </div>
                    <div x-show="activePage === 'tab5'">
                        @include('vendor.contract.contract_tabdata')
                    </div>
                </div>
            </div>

        </div>
@endsection
