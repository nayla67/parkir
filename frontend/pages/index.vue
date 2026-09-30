<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <div class="mx-auto max-w-3xl">

      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Menu Petugas</h1>
          <p class="mt-2 text-gray-500">Selamat datang, {{ userName }}</p>
        </div>
        <button
          @click="logout"
          class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition"
        >
          Logout
        </button>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">

        <NuxtLink
          to="/petugas/member/select"
          class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg"
        >
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">👤</div>
          <h2 class="text-lg font-bold text-gray-900">Member</h2>
          <p class="mt-1 text-sm text-gray-500">Kelola dan pilih member</p>
          <div class="mt-4 text-sm font-semibold text-blue-600">Buka Member →</div>
        </NuxtLink>

        <NuxtLink
          to="/petugas/transaksi"
          class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg"
        >
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">📥</div>
          <h2 class="text-lg font-bold text-gray-900">Kelola Transaksi</h2>
          <p class="mt-1 text-sm text-gray-500">Proses pembayaran & penagihan</p>
          <div class="mt-4 text-sm font-semibold text-green-600">Kelola Transaksi →</div>
        </NuxtLink>

        <NuxtLink
          to="/petugas/keluar"
          class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg"
        >
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-2xl">📤</div>
          <h2 class="text-lg font-bold text-gray-900">Scan Keluar</h2>
          <p class="mt-1 text-sm text-gray-500">Scan QR kendaraan yang keluar</p>
          <div class="mt-4 text-sm font-semibold text-red-600">Scan Keluar →</div>
        </NuxtLink>

        <NuxtLink
          to="/petugas/ticket"
          class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg"
        >
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-2xl">🎫</div>
          <h2 class="text-lg font-bold text-gray-900">Ticket</h2>
          <p class="mt-1 text-sm text-gray-500">Scan dan lihat informasi ticket</p>
          <div class="mt-4 text-sm font-semibold text-yellow-600">Buka Ticket →</div>
        </NuxtLink>

      </div>

    </div>
  </div>
</template>

<script setup>
const { $api } = useNuxtApp();
const router = useRouter();

const userName = ref('');

async function logout() {
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_user');
  router.push('/login');
  $api.post('/logout').catch(() => {}); // fire-and-forget, tidak diawait
}

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
  userName.value = user.name || '';
});
</script>
