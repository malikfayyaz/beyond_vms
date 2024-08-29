


@extends('layouts.authentication')

@section('content')
      <div
        x-data="{
          email: '',
          emailError: '',
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
          submitForm() {
            this.validateEmail();
            if (!this.emailError) {
              console.log('Password reset requested for:', this.email);
              // Add your password reset logic here
               document.getElementById('forgotForm').submit();
            }
          }
        }"
        class="bg-white p-8 rounded-lg shadow-md w-96 transition duration-300 ease-in-out transform hover:scale-105"
      >
       
        <h2 class="text-2xl font-bold text-center mb-6">Forgot Password</h2>
        @include('layouts.partials.alerts') <!-- Include the partial view -->
        <form @submit.prevent="submitForm" method="POST" action="{{ route('password.email') }}" id="forgotForm">
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
              x-model="email"
              name="email" 
              :value="old('email')"
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
          <div class="flex flex-col space-y-4 mb-6">
            <button
              type="submit"
              class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out"
            >
            {{ __('Email Password Reset Link') }}
            </button>
            <a
              href="{{ route('login') }}"
              class="text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out"
            >
              Back to Login
            </a>
          </div>
        </form>
      </div>
      @endsection

