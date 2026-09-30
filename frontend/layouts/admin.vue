<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 via-rose-50 to-purple-50 lg:flex">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm lg:hidden"
      @click="sidebarOpen = false"
    ></div>

    <aside
      class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-hidden bg-gradient-to-b from-amber-100 via-rose-200 to-purple-200 text-purple-950 shadow-2xl transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="pointer-events-none absolute -top-16 -right-16 h-56 w-56 rounded-full bg-amber-200/60 blur-3xl"></div>
      <div class="pointer-events-none absolute top-1/3 -left-20 h-52 w-52 rounded-full bg-purple-200/60 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-20 -right-10 h-56 w-56 rounded-full bg-rose-200/60 blur-3xl"></div>

      <div class="relative flex items-center gap-3 px-6 py-6">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-purple-400 text-xl text-white shadow-lg shadow-purple-900/20">
          🅿️
        </div>
        <div class="min-w-0">
          <p class="truncate text-lg font-extrabold leading-tight">Parkir Plaza Andalas</p>
          <p class="text-xs font-medium text-purple-900/70">Super Admin Panel</p>
        </div>
      </div>

      <div class="relative mx-6 h-px bg-purple-900/10"></div>

      <nav class="relative flex-1 space-y-1.5 overflow-y-auto px-4 py-6">
        <NuxtLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm transition"
          :class="
            isActive(item.to)
              ? 'bg-white/40 font-bold text-purple-950 shadow-inner backdrop-blur-md'
              : 'font-medium text-purple-900/70 hover:bg-white/25 hover:text-purple-950'
          "
        >
          <span
            class="flex h-8 w-8 items-center justify-center rounded-lg text-base"
            :class="isActive(item.to) ? 'bg-white/50' : 'bg-white/20 group-hover:bg-white/40'"
          >
            {{ item.icon }}
          </span>
          {{ item.label }}
        </NuxtLink>
      </nav>

      <div class="relative p-5">
        <div class="rounded-2xl bg-white/40 p-4 ring-1 ring-white/60 backdrop-blur-md">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-purple-400 text-sm font-bold text-white">
              {{ initials }}
            </div>
            <div class="min-w-0">
              <p class="truncate text-sm font-semibold text-purple-950">{{ userName }}</p>
              <p class="text-xs text-purple-900/70">{{ userRole }}</p>
            </div>
          </div>
          <button
            @click="logout"
            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-white/40 px-4 py-2.5 text-sm font-semibold text-purple-950 transition backdrop-blur-md hover:bg-white/60"
          >
            <span>{{ loggingOut ? 'Keluar...' : 'Logout' }}</span>
          </button>
        </div>
      </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
      <header class="sticky top-0 z-20 flex items-center justify-between bg-gradient-to-r from-amber-100 via-rose-200 to-purple-200 px-5 py-4 shadow-md lg:hidden">
        <button
          @click="sidebarOpen = true"
          class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/40 text-purple-950 backdrop-blur-md transition hover:bg-white/60"
          aria-label="Buka menu"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/40 text-sm backdrop-blur-md">🅿️</span>
          <p class="text-sm font-bold text-purple-950">Super Admin</p>
        </div>
      </header>

      <main class="min-w-0 flex-1 p-5 sm:p-8">
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
  { to: '/admin/dashboard', label: 'Dashboard', icon: '📊' },
  { to: '/admin/users', label: 'Kelola Petugas', icon: '👥' },
  { to: '/admin/rekap', label: 'Laporan', icon: '📈' },
];

const sidebarOpen = ref(false);
const loggingOut = ref(false);
const userName = ref('');
const userRole = ref('');

const initials = computed(() => {
  const parts = userName.value.trim().split(/\s+/);
  const first = parts[0]?.[0] || 'A';
  const last = parts.length > 1 ? parts[parts.length - 1][0] : '';
  return (first + last).toUpperCase();
});

const formattedRole = (role) => {
  if (role === 'super_admin') return 'Super Admin';
  if (role === 'petugas') return 'Petugas';
  if (role === 'member') return 'Member';
  return role || 'Pengguna';
};

function isActive(to) {
  if (to === '/admin/users') return route.path.startsWith('/admin/users');
  return route.path === to;
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
  userRole.value = formattedRole(user.role);
});
</script>