<template>
  <div
    class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 bg-gradient-to-br from-pink-300 via-purple-300 to-sky-300"
    style="background-color: #c9a0e8;"
  >
    <!-- Background Decoration -->
    <div
      class="absolute top-10 right-16 text-8xl opacity-[0.15] rotate-12 select-none"
    >
      🅿️
    </div>

    <div
      class="absolute bottom-16 left-10 text-7xl opacity-[0.15] -rotate-12 select-none"
    >
      🚗
    </div>

    <div
      class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.25),transparent_60%)]"
    ></div>

    <div
      class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.2),transparent_50%)]"
    ></div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-sm">

      <!-- Header -->
      <div class="text-center mb-8">
        <span
          class="inline-flex items-center gap-1.5 bg-white/50 backdrop-blur-sm border border-white/60 text-purple-500 text-xs font-medium px-3 py-1.5 rounded-full mb-4 shadow-sm"
        >
          🎫 Sistem Informasi Parkir
        </span>

        <h1 class="text-3xl font-bold text-neutral-700 leading-tight">
          Kelola Parkir<br />
          Lebih Mudah & Cepat
        </h1>

        <p class="text-neutral-500 text-sm mt-2">
          Masuk untuk mengakses dashboard
        </p>
      </div>

      <!-- Login Card -->
      <div
        class="bg-white/60 backdrop-blur-xl border border-white/70 rounded-2xl shadow-xl p-6"
      >

        <!-- Error -->
        <p
          v-if="error"
          class="text-rose-500 text-sm mb-4 text-center bg-rose-100/70 border border-rose-200 rounded-lg py-2 px-3"
        >
          {{ error }}
        </p>

        <!-- Email -->
        <label class="block text-sm font-medium text-neutral-600 mb-1.5">
          📧 Email
        </label>

        <input
          v-model="email"
          type="email"
          placeholder="nama@parkir.com"
          autocomplete="email"
          :disabled="loading"
          class="w-full bg-white/70 border border-purple-100 rounded-xl px-4 py-2.5 mb-4 text-sm text-neutral-700 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-transparent disabled:opacity-50"
        />

        <!-- Password -->
        <label class="block text-sm font-medium text-neutral-600 mb-1.5">
          🔐 Password
        </label>

        <div class="relative mb-4">
          <input
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            autocomplete="current-password"
            :disabled="loading"
            class="w-full bg-white/70 border border-purple-100 rounded-xl px-4 py-2.5 pr-11 text-sm text-neutral-700 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-transparent disabled:opacity-50"
            @keyup.enter="login"
          />

          <!-- Show Password -->
          <button
            type="button"
            @click="showPassword = !showPassword"
            :disabled="loading"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-purple-500 transition disabled:opacity-50"
            tabindex="-1"
          >
            <svg
              v-if="showPassword"
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="1.8"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"
              />
            </svg>

            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="w-5 h-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="1.8"
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

        <!-- Login Sebagai -->
        <label class="block text-sm font-medium text-neutral-600 mb-2">
          👤 Login Sebagai
        </label>

        <div class="grid grid-cols-2 gap-3 mb-4">

          <!-- Pilihan Petugas -->
          <button
            type="button"
            :disabled="loading"
            @click="loginAs = 'petugas'"
            :class="[
              'relative rounded-2xl border-2 p-4 transition-all duration-200',
              loginAs === 'petugas'
                ? 'border-purple-400 bg-purple-100/80 shadow-md scale-[1.02]'
                : 'border-white/70 bg-white/50 hover:bg-white/80'
            ]"
          >
            <div class="text-4xl mb-2">
              👮‍♀️
            </div>

            <p class="text-sm font-semibold text-neutral-700">
              Petugas
            </p>

            <p class="text-xs text-neutral-500 mt-1">
              Kelola parkir
            </p>

            <span
              v-if="loginAs === 'petugas'"
              class="absolute top-2 right-2 text-purple-500 text-sm"
            >
              ✅
            </span>
          </button>

          <!-- Pilihan Admin -->
          <button
            type="button"
            :disabled="loading"
            @click="loginAs = 'admin'"
            :class="[
              'relative rounded-2xl border-2 p-4 transition-all duration-200',
              loginAs === 'admin'
                ? 'border-sky-400 bg-sky-100/80 shadow-md scale-[1.02]'
                : 'border-white/70 bg-white/50 hover:bg-white/80'
            ]"
          >
            <div class="text-4xl mb-2">
              👩‍💼
            </div>

            <p class="text-sm font-semibold text-neutral-700">
              Admin
            </p>

            <p class="text-xs text-neutral-500 mt-1">
              Kelola sistem
            </p>

            <span
              v-if="loginAs === 'admin'"
              class="absolute top-2 right-2 text-sky-500 text-sm"
            >
              ✅
            </span>
          </button>

        </div>

        <!-- Lupa Password -->
        <div class="text-right mb-6">
          <NuxtLink
            to="/forgot-password"
            class="text-xs font-medium text-purple-400 hover:text-purple-600 transition"
          >
            🔑 Lupa Password?
          </NuxtLink>
        </div>

        <!-- Login Button -->
        <button
          type="button"
          @click="login"
          :disabled="loading || !email || !password"
          class="w-full bg-gradient-to-r from-pink-400 via-purple-400 to-sky-400 hover:from-pink-500 hover:via-purple-500 hover:to-sky-500 text-white text-sm font-semibold py-3 rounded-full transition disabled:opacity-50 flex items-center justify-center gap-2 shadow-md"
        >
          <span v-if="loading">
            ⏳ Memproses...
          </span>

          <span v-else>
            🚀 Masuk ke Dashboard
          </span>
        </button>
      </div>

      <!-- Footer -->
      <p class="text-center text-neutral-500 text-xs mt-6">
        🅿️ Parkir Plaza Andalas — Sistem Terintegrasi
      </p>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: [],
});

const { $api } = useNuxtApp();

const email = ref('');
const password = ref('');
const showPassword = ref(false);
const loading = ref(false);
const error = ref(null);

const loginAs = ref('petugas');

async function login() {
  error.value = null;

  if (!email.value || !password.value) {
    error.value = 'Email dan password wajib diisi.';
    return;
  }

  loading.value = true;

  try {
    console.log('Mengirim login...');

    const res = await $api.post('/login', {
      email: email.value,
      password: password.value,
    });

    console.log('LOGIN RESPONSE:', res.data);

    const responseData = res?.data?.data;

    if (!responseData) {
      throw new Error('Response login tidak memiliki data.');
    }

    const token = responseData.token;
    const user = responseData.user;

    console.log('TOKEN DARI API:', token);
    console.log('USER DARI API:', user);

    if (!token) {
      throw new Error('Token tidak ditemukan dari response login.');
    }

    if (!user) {
      throw new Error('Data user tidak ditemukan dari response login.');
    }

    localStorage.setItem('auth_token', token);

    localStorage.setItem(
      'auth_user',
      JSON.stringify(user)
    );

    localStorage.removeItem('token');

    const savedToken = localStorage.getItem('auth_token');

    console.log('TOKEN TERSIMPAN:', savedToken);
    console.log(
      'USER TERSIMPAN:',
      localStorage.getItem('auth_user')
    );

    if (!savedToken) {
      throw new Error('Token gagal disimpan ke localStorage.');
    }

    const role = user.role;

    console.log('ROLE USER:', role);
    console.log('LOGIN SEBAGAI:', loginAs.value);

    const isAdmin =
      role === 'admin' ||
      role === 'superadmin' ||
      role === 'super_admin';

    const isPetugas = role === 'petugas';

    if (loginAs.value === 'admin') {
      if (!isAdmin) {
        throw new Error(
          'Akun ini tidak memiliki akses Admin.'
        );
      }

      await navigateTo('/admin/dashboard');

    } else if (loginAs.value === 'petugas') {
      if (!isPetugas) {
        throw new Error(
          'Akun ini tidak memiliki akses Petugas.'
        );
      }

      await navigateTo('/petugas');
    }

  } catch (err) {
    console.error('LOGIN ERROR:', err);

    error.value =
      err?.response?.data?.message ||
      err?.data?.message ||
      err?.message ||
      'Email atau password salah.';

  } finally {
    loading.value = false;
  }
}
</script>