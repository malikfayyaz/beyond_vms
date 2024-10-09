

@extends('layouts.authentication')

@section('content')
<div
        x-data="{
          email: '',
          password: '',
          emailError: '',
          passwordError: '',
          showPassword: false,
          validateEmail() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.email) {
              this.emailError = 'Email is required';
            } else if (!emailRegex.test(this.email)) {
              this.emailError = 'Invalid email format';
            } else {
              this.emailError = '';
            }
          },
          validatePassword() {
            if (!this.password) {
              this.passwordError = 'Password is required';
            } else if (this.password.length < 8) {
              this.passwordError = 'Password must be at least 8 characters long';
            } else {
              this.passwordError = '';
            }
          },
          submitForm() {
            this.validateEmail();
            this.validatePassword();
            if (!this.emailError && !this.passwordError) {
              console.log('Login submitted:', this.email, this.password);
             document.getElementById('loginForm').submit();

            }
          }
        }"
        class="bg-white p-8 rounded-lg shadow-md w-96 transition duration-300 ease-in-out transform hover:scale-105"
      >
      @include('layouts.partials.alerts') <!-- Include the partial view -->
        <form @submit.prevent="submitForm" action="{{ route('login.post') }}" method="POST" id="loginForm">
          @csrf




          <div class="mb-4">
            <label
              for="email"
              class="block text-gray-700 text-sm font-bold mb-2"
            >
              Email Address
            </label>
            <input
              type="email"
              id="email"
              name="email"
              x-model="email"
              @blur="validateEmail"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter your email"
              required
            />
            <p
              x-show="emailError"
              x-text="emailError"
              class="text-red-500 text-xs mt-1"
            ></p>

          </div>
          <div class="mb-6">
            <label
              for="password"
              class="block text-gray-700 text-sm font-bold mb-2"
            >
              Password
            </label>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                id="password"
                name="password"
                x-model="password"
                @blur="validatePassword"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                placeholder="Enter your password"
                required
              />
              <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                @click="showPassword = !showPassword"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5 text-gray-500"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </button>
            </div>
            <p
              x-show="passwordError"
              x-text="passwordError"
              class="text-red-500 text-xs mt-1"
            ></p>

          </div>
          <div class="flex items-center justify-between mb-6">
            <button
              type="submit"
              class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out"
            >
              Login
            </button>
            <a
              href="{{ route('password.request') }}"
              class="text-sm text-blue-500 hover:text-blue-600"
            >
              Forgot your password?
            </a>
          </div>
        </form>
        </div>
        @endsection
