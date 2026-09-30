<template>
  <div class="min-h-screen bg-slate-50 lg:flex">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
      @click="sidebarOpen = false"
    ></div>

    <aside
      class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-hidden bg-gradient-to-b from-sky-400 via-purple-500 to-pink-500 text-white shadow-2xl transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-sky-300/40 blur-3xl"></div>
      <div class="pointer-events-none absolute top-1/3 -left-20 h-52 w-52 rounded-full bg-white/20 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-20 -right-10 h-56 w-56 rounded-full bg-pink-300/40 blur-3xl"></div>

      <div class="relative flex items-center gap-3 px-6 py-6">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-xl shadow-lg shadow-sky-900/30">
          🚗
        </div>
        <div class="min-w-0">
          <p class="truncate text-base font-extrabold uppercase leading-tight tracking-wide">Parkir Plaza Andalas</p>
          <p class="text-xs font-medium text-white/80">Sistem Informasi Parkir</p>
        </div>
      </div>

      <div class="relative mx-6 h-px bg-white/20"></div>

      <div class="relative px-6 py-5">
        <div class="flex items-center gap-3 rounded-2xl bg-white/15 p-4 ring-1 ring-white/30 backdrop-blur-md">
          <div class="relative">
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-pink-500 text-sm font-bold text-white">
              {{ initials }}
            </div>
            <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-green-400"></span>
          </div>
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold">Petugas</p>
            <span class="inline-flex items-center gap-1.5 text-xs text-white/80">
              <span class="h-2 w-2 rounded-full bg-green-400"></span> Online
            </span>
          </div>
        </div>
      </div>

      <div class="relative px-6">
        <p class="text-xs font-bold uppercase tracking-widest text-white/70">Menu Utama</p>
      </div>

      <nav class="relative flex-1 space-y-2 overflow-y-auto px-4 py-4">
        <NuxtLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="group flex items-start gap-3 rounded-xl px-4 py-3 text-sm transition"
          :class="
            isActive(item)
              ? 'bg-white/20 font-bold shadow-inner backdrop-blur-md'
              : 'font-medium text-white/80 hover:bg-white/10 hover:text-white'
          "
        >
          <span
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-base"
            :class="isActive(item) ? 'bg-white/30' : 'bg-white/10 group-hover:bg-white/20'"
          >
            {{ item.icon }}
          </span>
          <span class="min-w-0">
            <span class="block">{{ item.label }}</span>
            <span class="block text-xs font-normal text-white/70">{{ item.sub }}</span>
          </span>
        </NuxtLink>
      </nav>

      <div class="relative p-5">
        <div class="flex items-center gap-3 rounded-2xl bg-white/15 p-4 ring-1 ring-white/30 backdrop-blur-md">
          <span class="relative flex h-3 w-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex h-3 w-3 rounded-full bg-green-400"></span>
          </span>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold">Sistem Aktif</p>
            <p class="text-xs text-white/80">Semua layanan berjalan normal</p>
          </div>
        </div>
        <button
          @click="logout"
          class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-white/20 px-4 py-2.5 text-sm font-semibold text-white transition backdrop-blur-md hover:bg-white/30"
        >
          {{ loggingOut ? 'Keluar...' : 'Logout' }}
        </button>
      </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
      <header class="sticky top-0 z-20 flex items-center justify-between bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 px-5 py-4 shadow-md lg:hidden">
        <button
          @click="sidebarOpen = true"
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-md transition hover:bg-white/30"
          aria-label="Buka menu"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-sm backdrop-blur-md">🚗</span>
          <p class="text-sm font-bold text-white">Petugas Parkir</p>
        </div>
      </header>

      <main class="min-w-0 flex-1 bg-slate-50 p-5 sm:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
const { $api } = useNuxtApp();
const router = useRouter();
const route = useRoute();

const navItems = [
  {
    to: '/petugas/member/select',
    label: 'Kelola Member',
    sub: 'Data & pembayaran member',
    icon: '👥',
    matches: ['/petugas/member'],
  },
  {
    to: '/petugas/transaksi',
    label: 'Kelola Transaksi',
    sub: 'Pembayaran kendaraan',
    icon: '📥',
    matches: ['/petugas/transaksi'],
  },
  {
    to: '/petugas/keluar',
    label: 'Scan Keluar',
    sub: 'Kendaraan & member keluar',
    icon: '📤',
    matches: ['/petugas/keluar'],
  },
  {
    to: '/petugas/laporan',
    label: 'Laporan',
    sub: 'Ringkasan parkir',
    icon: '📊',
    exact: true,
  },
];

const sidebarOpen = ref(false);
const loggingOut = ref(false);
const userName = ref('');

const initials = computed(() => {
  const parts = userName.value.trim().split(/\s+/);
  const first = parts[0]?.[0] || 'P';
  const last = parts.length > 1 ? parts[parts.length - 1][0] : '';
  return (first + last).toUpperCase();
});

function isActive(item) {
  if (item.exact) return route.path === item.to;
  return (item.matches || [item.to]).some((p) => route.path.startsWith(p));
}

async function logout() {
  loggingOut.value = true;
  try {
    await $api.post('/logout');
  } catch (e) {}
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_user');
  router.push('/login');
}

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('auth_user') || '{}');
  userName.value = user.name || '';
});
</script>