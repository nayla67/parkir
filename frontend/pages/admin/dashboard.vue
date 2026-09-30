<template>
  <div>
    <!-- HEADER -->
    <div
      class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-amber-200 via-rose-300 to-purple-300 p-8 text-purple-950 shadow-lg shadow-rose-200"
    >
      <div class="pointer-events-none absolute -top-12 -right-10 h-48 w-48 rounded-full bg-white/30 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-16 left-1/3 h-44 w-44 rounded-full bg-purple-200/40 blur-3xl"></div>
      <div class="pointer-events-none absolute right-10 top-4 select-none text-7xl opacity-20">
        🅿
      </div>

      <p class="text-xs font-semibold uppercase tracking-widest text-purple-900/70">
        Sistem Informasi Parkir
      </p>

      <h1 class="mt-2 text-2xl font-extrabold text-purple-950">
        Dashboard Super Admin
      </h1>

      <p class="mt-2 max-w-xl text-sm text-purple-900/80">
        Kelola petugas, pantau aktivitas parkir, dan akses laporan sistem dalam satu tempat.
      </p>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
      <div
        v-for="card in statCards"
        :key="card.label"
        class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
      >
        <div class="flex items-start justify-between">
          <div class="rounded-xl p-3" :class="card.iconBg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              :class="card.iconColor"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                :d="card.iconPath"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </div>

          <span
            class="rounded-full px-3 py-1 text-xs font-semibold"
            :class="card.badgeClass"
          >
            {{ card.badge }}
          </span>
        </div>

        <p class="mt-4 text-3xl font-bold text-slate-800">
          {{ card.valueText }}
        </p>

        <p class="mt-1 text-xs text-slate-400">
          {{ card.label }}
        </p>

        <div class="mt-4">
          <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
            <div
              class="h-full rounded-full"
              :class="card.progressBar"
              :style="{ width: card.progress + '%' }"
            ></div>
          </div>

          <div class="mt-1.5 flex items-center justify-between text-xs">
            <span class="font-medium" :class="card.trendClass">
              {{ card.trendIcon }} {{ Math.abs(card.trendPct) }}%
            </span>

            <span class="text-slate-400">
              {{ card.trendLabel }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- MONITORING + AKTIVITAS -->
    <div class="mt-8 grid grid-cols-1 gap-4 lg:grid-cols-3">

      <!-- MONITORING -->
      <div
        class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm lg:col-span-2"
      >
        <div
          class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
          <div class="flex items-center gap-3">
            <div
              class="rounded-xl bg-gradient-to-br from-purple-500 to-rose-500 p-3 text-white shadow-sm"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                />
              </svg>
            </div>

            <div>
              <h3 class="text-lg font-bold text-purple-950">
                Monitoring Transaksi & Pendapatan
              </h3>

              <p class="text-xs text-slate-400">
                Tren aktivitas parkir masuk & keluar
              </p>
            </div>
          </div>

          <div class="flex shrink-0 gap-1 rounded-full bg-slate-100 p-1">
            <button
              v-for="opt in periodOptions"
              :key="opt.value"
              class="rounded-full px-3 py-1 text-xs font-semibold transition"
              :class="
                activePeriod === opt.value
                  ? 'bg-gradient-to-r from-amber-200 via-rose-300 to-purple-300 text-purple-950 shadow-sm'
                  : 'text-slate-500 hover:text-slate-700'
              "
              @click="activePeriod = opt.value"
            >
              {{ opt.label }}
            </button>
          </div>
        </div>

        <!-- SUMMARY -->
        <div class="mt-5 grid grid-cols-3 gap-3">
          <div class="rounded-xl bg-purple-50 p-3 text-center">
            <p class="text-xs font-medium text-purple-600">
              Total Transaksi
            </p>

            <p class="mt-1 text-xl font-bold text-purple-950">
              {{ summaryStats.totalTransaksi }}
            </p>
          </div>

          <div class="rounded-xl bg-rose-50 p-3 text-center">
            <p class="text-xs font-medium text-rose-600">
              Total Pendapatan
            </p>

            <p class="mt-1 text-xl font-bold text-purple-950">
              Rp {{ formatRupiah(summaryStats.totalPendapatan) }}
            </p>
          </div>

          <div class="rounded-xl bg-amber-50 p-3 text-center">
            <p class="text-xs font-medium text-amber-600">
              Update Terakhir
            </p>

            <p class="mt-1 text-sm font-bold text-purple-950">
              {{ summaryStats.updateTerakhir }}
            </p>
          </div>
        </div>

        <!-- CHART -->
        <div class="mt-5">
          <svg
            viewBox="0 0 700 220"
            class="w-full"
            preserveAspectRatio="xMidYMid meet"
            font-family="system-ui, sans-serif"
          >
            <defs>
              <linearGradient
                id="gradMasuk"
                x1="0"
                y1="0"
                x2="0"
                y2="1"
              >
                <stop
                  offset="0%"
                  stop-color="#a855f7"
                  stop-opacity="0.25"
                />

                <stop
                  offset="100%"
                  stop-color="#a855f7"
                  stop-opacity="0.02"
                />
              </linearGradient>

              <linearGradient
                id="gradKeluar"
                x1="0"
                y1="0"
                x2="0"
                y2="1"
              >
                <stop
                  offset="0%"
                  stop-color="#f59e0b"
                  stop-opacity="0.25"
                />

                <stop
                  offset="100%"
                  stop-color="#f59e0b"
                  stop-opacity="0.02"
                />
              </linearGradient>

              <linearGradient
                id="lineGrad1"
                x1="0"
                y1="0"
                x2="1"
                y2="0"
              >
                <stop offset="0%" stop-color="#a855f7" />
                <stop offset="100%" stop-color="#f43f5e" />
              </linearGradient>

              <linearGradient
                id="lineGrad2"
                x1="0"
                y1="0"
                x2="1"
                y2="0"
              >
                <stop offset="0%" stop-color="#f59e0b" />
                <stop offset="100%" stop-color="#f43f5e" />
              </linearGradient>
            </defs>

            <line
              v-for="g in 5"
              :key="'g' + g"
              :x1="CHART_PAD_LEFT"
              :y1="chartGridY(g)"
              :x2="CHART_W - 15"
              :y2="chartGridY(g)"
              stroke="#e2e8f0"
              stroke-width="1"
              stroke-dasharray="4 4"
            />

            <text
              v-for="g in 5"
              :key="'yl' + g"
              :x="CHART_PAD_LEFT - 8"
              :y="chartGridY(g) + 3.5"
              text-anchor="end"
              fill="#94a3b8"
              font-size="10"
            >
              {{ chartYLabel(g) }}
            </text>

            <polygon
              :points="chartMasukFill"
              fill="url(#gradMasuk)"
            />

            <polygon
              :points="chartKeluarFill"
              fill="url(#gradKeluar)"
            />

            <polyline
              :points="chartMasukLine"
              fill="none"
              stroke="url(#lineGrad1)"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <polyline
              :points="chartKeluarLine"
              fill="none"
              stroke="url(#lineGrad2)"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <circle
              v-for="(pt, i) in chartMasukDots"
              :key="'dm' + i"
              :cx="pt[0]"
              :cy="pt[1]"
              r="3.5"
              fill="white"
              stroke="#a855f7"
              stroke-width="2"
            />

            <circle
              v-for="(pt, i) in chartKeluarDots"
              :key="'dk' + i"
              :cx="pt[0]"
              :cy="pt[1]"
              r="3.5"
              fill="white"
              stroke="#f59e0b"
              stroke-width="2"
            />

            <text
              v-for="(lbl, i) in chartData.labels"
              :key="'xl' + i"
              :x="chartX(i)"
              :y="CHART_H - 5"
              text-anchor="middle"
              fill="#94a3b8"
              font-size="10"
            >
              {{ lbl }}
            </text>
          </svg>
        </div>

        <div class="mt-3 flex items-center justify-center gap-6">
          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
            <span class="text-xs font-medium text-slate-500">
              Transaksi Masuk
            </span>
          </div>

          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <span class="text-xs font-medium text-slate-500">
              Transaksi Keluar
            </span>
          </div>
        </div>
      </div>

      <!-- AKTIVITAS TERBARU -->
      <div
        class="flex flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div
              class="rounded-xl bg-gradient-to-br from-amber-400 to-orange-400 p-3 text-white shadow-sm"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>

            <div>
              <h3 class="text-sm font-bold text-purple-950">
                Aktivitas Terbaru
              </h3>

              <span
                class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600"
              >
                <span class="relative flex h-2 w-2">
                  <span
                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"
                  ></span>

                  <span
                    class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"
                  ></span>
                </span>

                Live
              </span>
            </div>
          </div>
        </div>

        <!-- TABS -->
        <div class="mt-4 flex gap-1 rounded-full bg-slate-100 p-1">
          <button
            v-for="tab in activityTabs"
            :key="tab.value"
            class="flex-1 rounded-full px-2 py-1.5 text-xs font-semibold transition"
            :class="
              activityTab === tab.value
                ? 'bg-gradient-to-r from-amber-200 via-rose-300 to-purple-300 text-purple-950 shadow-sm'
                : 'text-slate-500 hover:text-slate-700'
            "
            @click="activityTab = tab.value"
          >
            {{ tab.label }}
          </button>
        </div>

        <!-- ACTIVITY LIST -->
        <ul class="mt-4 max-h-72 space-y-3 overflow-y-auto pr-1">
          <li
            v-for="item in filteredActivities"
            :key="item.id"
            class="flex items-center justify-between gap-3 rounded-xl px-2 py-2 transition hover:bg-slate-50"
          >
            <div class="flex min-w-0 items-center gap-3">
              <span
                class="shrink-0 rounded-xl p-2.5"
                :class="item.iconBg"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  :class="item.iconColor"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    :d="item.iconPath"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>

              <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-700">
                  {{ item.title }}
                </p>

                <p class="truncate text-xs text-slate-400">
                  {{ item.sub }}
                </p>
              </div>
            </div>

            <span
              class="shrink-0 text-xs font-medium text-slate-400"
              :title="item.fullTime"
            >
              {{ item.time }}
            </span>
          </li>

          <li
            v-if="filteredActivities.length === 0"
            class="py-8 text-center text-sm text-slate-400"
          >
            Belum ada aktivitas.
          </li>
        </ul>

        <NuxtLink
          to="/admin/rekap"
          class="group mt-4 flex items-center justify-center gap-1 rounded-xl border border-slate-200 py-2 text-sm font-semibold text-purple-700 transition hover:bg-purple-50"
        >
          Lihat Semua Aktivitas

          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 transition group-hover:translate-x-0.5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </NuxtLink>
      </div>
    </div>

    <!-- AKSI CEPAT -->
    <h2 class="mb-4 text-lg font-bold text-purple-950">
      Aksi Cepat
    </h2>

    <div class="grid gap-4 sm:grid-cols-2">
      <NuxtLink
        to="/admin/users"
        class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
      >
        <div
          class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 p-3 text-amber-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
            />
          </svg>
        </div>

        <h3 class="text-lg font-bold text-slate-800">
          Kelola Petugas
        </h3>

        <p class="mt-1 text-sm text-slate-500">
          Tambah, edit, hapus, dan kelola akun petugas parkir.
        </p>

        <div
          class="mt-4 text-sm font-semibold text-amber-600 transition group-hover:translate-x-1"
        >
          Kelola Petugas →
        </div>
      </NuxtLink>

      <NuxtLink
        to="/admin/rekap"
        class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
      >
        <div
          class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 p-3 text-rose-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
            />
          </svg>
        </div>

        <h3 class="text-lg font-bold text-slate-800">
          Laporan
        </h3>

        <p class="mt-1 text-sm text-slate-500">
          Lihat dan akses laporan aktivitas parkir berdasarkan periode.
        </p>

        <div
          class="mt-4 text-sm font-semibold text-rose-600 transition group-hover:translate-x-1"
        >
          Lihat Laporan →
        </div>
      </NuxtLink>
    </div>

    <!-- INFO -->
    <div
      class="mt-8 rounded-2xl border border-rose-200/60 bg-rose-50/50 p-6"
    >
      <div class="flex items-start gap-3">
        <div
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-lg"
        >
          💡
        </div>

        <div>
          <h3 class="font-semibold text-purple-950">
            Informasi Sistem
          </h3>

          <p class="mt-1 text-sm text-purple-900/70">
            Dashboard ini merupakan pusat pengendalian sistem parkir untuk Super Admin.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
});

const { $api } = useNuxtApp();

const userName = ref('');
const loading = ref(true);

const summary = ref({
  tiket_aktif: 0,
  tiket_hari_ini: 0,
  pendapatan_hari_ini: 0,
  total_petugas: 0,
  total_member: 0,
});

const totalMemberAktif = ref(0);
const totalPetugasAktif = ref(0);

const tickets = ref([]);

/* =========================================================
   FORMAT RUPIAH
========================================================= */

function formatRupiah(val) {
  return Number(val || 0).toLocaleString('id-ID');
}

/* =========================================================
   FORMAT WAKTU
========================================================= */

function parseDate(value) {
  if (!value) return null;

  if (value instanceof Date) {
    return isNaN(value.getTime()) ? null : value;
  }

  const str = String(value).trim();

  if (!str) return null;

  let date;

  if (
    /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/.test(str)
  ) {
    date = new Date(
      str.length === 16 ? `${str}:00` : str
    );
  }

  else if (
    /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/.test(str)
  ) {
    date = new Date(
      str.replace(' ', 'T')
    );
  }

  else {
    date = new Date(str);
  }

  return isNaN(date.getTime()) ? null : date;
}

function formatJam(value) {
  const date = parseDate(value);

  if (!date) return '-';

  return date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  });
}

function timeAgo(value) {
  const date = parseDate(value);

  if (!date) return '-';

  const now = new Date();

  let diff = now.getTime() - date.getTime();

  if (diff < 0) {
    diff = 0;
  }

  const seconds = Math.floor(diff / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);

  if (seconds < 10) {
    return 'baru saja';
  }

  if (minutes < 1) {
    return `${seconds} detik lalu`;
  }

  if (minutes < 60) {
    return `${minutes} menit lalu`;
  }

  if (hours < 24) {
    return `${hours} jam lalu`;
  }

  if (days === 1) {
    return 'kemarin';
  }

  return `${days} hari lalu`;
}

function formatFullTime(value) {
  const date = parseDate(value);

  if (!date) return '-';

  return date.toLocaleString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false,
  });
}

/* =========================================================
   STAT CARDS
========================================================= */

const statCards = computed(() => [
  {
    label: 'Total Petugas Aktif',
    valueText: String(totalPetugasAktif.value),
    badge: 'Aktif',
    badgeClass: 'bg-amber-100 text-amber-600',
    iconBg: 'bg-amber-100',
    iconColor: 'text-amber-600',
    iconPath:
      'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    progress: 75,
    progressBar: 'bg-gradient-to-r from-amber-400 to-orange-400',
    trendPct: 4,
    trendIcon: '▲',
    trendClass: 'text-emerald-600',
    trendLabel: 'dari kemarin',
  },

  {
    label: 'Total Transaksi Hari Ini',
    valueText: String(summary.value.tiket_hari_ini || 0),
    badge: 'Hari ini',
    badgeClass: 'bg-purple-100 text-purple-600',
    iconBg: 'bg-purple-100',
    iconColor: 'text-purple-600',
    iconPath:
      'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    progress: 62,
    progressBar: 'bg-gradient-to-r from-purple-400 to-purple-600',
    trendPct: 12,
    trendIcon: '▲',
    trendClass: 'text-emerald-600',
    trendLabel: 'dari kemarin',
  },

  {
    label: 'Total Pendapatan Hari Ini',
    valueText:
      'Rp ' +
      formatRupiah(summary.value.pendapatan_hari_ini || 0),
    badge: 'Hari ini',
    badgeClass: 'bg-rose-100 text-rose-600',
    iconBg: 'bg-rose-100',
    iconColor: 'text-rose-600',
    iconPath:
      'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    progress: 48,
    progressBar: 'bg-gradient-to-r from-rose-400 to-pink-500',
    trendPct: 8,
    trendIcon: '▲',
    trendClass: 'text-emerald-600',
    trendLabel: 'dari kemarin',
  },

  {
    label: 'Total Member Aktif',
    valueText: String(totalMemberAktif.value),
    badge: 'Aktif',
    badgeClass: 'bg-orange-100 text-orange-500',
    iconBg: 'bg-orange-100',
    iconColor: 'text-orange-500',
    iconPath:
      'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    progress: 90,
    progressBar: 'bg-gradient-to-r from-orange-400 to-amber-400',
    trendPct: 2,
    trendIcon: '▼',
    trendClass: 'text-rose-500',
    trendLabel: 'dari kemarin',
  },
]);

/* =========================================================
   AKTIVITAS
========================================================= */

const activityTabs = [
  { label: 'Semua', value: 'semua' },
  { label: 'Transaksi', value: 'transaksi' },
  { label: 'Petugas', value: 'petugas' },
  { label: 'Member', value: 'member' },
];

const activityTab = ref('semua');

const activities = computed(() => {
  const result = [];

  tickets.value.forEach((ticket) => {
    if (ticket.waktu_keluar) {
      result.push({
        id: `keluar-${ticket.id}`,
        type: 'transaksi',
        title: 'Transaksi Keluar',
        sub: ticket.kode_tiket || `Tiket #${ticket.id}`,
        time: timeAgo(ticket.waktu_keluar),
        fullTime: formatFullTime(ticket.waktu_keluar),
        dateValue: parseDate(ticket.waktu_keluar)?.getTime() || 0,
        iconBg: 'bg-pink-100',
        iconColor: 'text-pink-500',
        iconPath:
          'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
      });
    }

    if (ticket.waktu_masuk) {
      result.push({
        id: `masuk-${ticket.id}`,
        type: 'transaksi',
        title: 'Transaksi Masuk',
        sub: ticket.kode_tiket || `Tiket #${ticket.id}`,
        time: timeAgo(ticket.waktu_masuk),
        fullTime: formatFullTime(ticket.waktu_masuk),
        dateValue: parseDate(ticket.waktu_masuk)?.getTime() || 0,
        iconBg: 'bg-emerald-100',
        iconColor: 'text-emerald-600',
        iconPath: 'M12 4v16m0 0l-6-6m6 6l6-6',
      });
    }
  });

  return result
    .sort((a, b) => b.dateValue - a.dateValue)
    .slice(0, 20);
});

const filteredActivities = computed(() => {
  if (activityTab.value === 'semua') {
    return activities.value;
  }

  return activities.value.filter(
    (a) => a.type === activityTab.value
  );
});

/* =========================================================
   LOAD DATA TIKET
========================================================= */

async function loadTickets() {
  try {
    const res = await $api.get('/tiket');

    const data = Array.isArray(res.data)
      ? res.data
      : Array.isArray(res.data?.data)
        ? res.data.data
        : [];

    tickets.value = data;
  } catch (e) {
    console.error('Gagal memuat aktivitas tiket:', e);
    tickets.value = [];
  }
}

/* =========================================================
   LOAD DASHBOARD
========================================================= */

onMounted(async () => {
  try {
    const user = JSON.parse(
      localStorage.getItem('auth_user') || '{}'
    );

    userName.value = user.name || '';
  } catch {
    userName.value = '';
  }

  /*
   * SUMMARY
   */
  try {
    const res = await $api.get('/rekap/summary');

    if (res.data?.data) {
      summary.value = res.data.data;
    }
  } catch (e) {
    console.error('Gagal memuat summary:', e);
  }

  /*
   * PETUGAS
   */
  try {
    const resUsers = await $api.get('/users');

    const listUsers = Array.isArray(resUsers.data)
      ? resUsers.data
      : Array.isArray(resUsers.data?.data)
        ? resUsers.data.data
        : [];

    totalPetugasAktif.value = listUsers.filter(
      (u) => u.role === 'petugas'
    ).length;
  } catch (e) {
    console.error('Gagal memuat petugas:', e);
    totalPetugasAktif.value = 0;
  }

  /*
   * MEMBER
   */
  try {
    const resMember = await $api.get('/members');

    const list = Array.isArray(resMember.data)
      ? resMember.data
      : Array.isArray(resMember.data?.data)
        ? resMember.data.data
        : [];

    totalMemberAktif.value = list.filter(
      (m) => m.status === 'lunas'
    ).length;
  } catch (e) {
    console.error('Gagal memuat member:', e);
    totalMemberAktif.value = 0;
  }

  /*
   * TIKET / AKTIVITAS
   */
  await loadTickets();

  /*
   * Refresh chart harian setelah tiket ter-load,
   * karena chart harian dihitung dari data tiket.
   */
  if (activePeriod.value === 'harian') {
    await loadChartData('harian');
  }

  loading.value = false;
});

/* =========================================================
   AUTO REFRESH AKTIVITAS
========================================================= */

let activityTimer = null;

onMounted(() => {
  activityTimer = setInterval(async () => {
    await loadTickets();
  }, 30000);
});

onBeforeUnmount(() => {
  if (activityTimer) {
    clearInterval(activityTimer);
  }
});

/* =========================================================
   CHART
========================================================= */

const CHART_W = 700;
const CHART_H = 220;
const CHART_PAD_LEFT = 45;

const activePeriod = ref('7hari');

const periodOptions = [
  { label: 'Harian', value: 'harian' },
  { label: '7 Hari', value: '7hari' },
  { label: 'Bulanan', value: 'bulanan' },
];

const chartData = ref({
  labels: [],
  masuk: [],
  keluar: [],
  pendapatan: [],
});

const summaryStats = computed(() => {
  const totalM = chartData.value.masuk.reduce((a, b) => a + b, 0);
  const totalK = chartData.value.keluar.reduce((a, b) => a + b, 0);
  const totalP = (chartData.value.pendapatan || []).reduce((a, b) => a + b, 0);

  return {
    totalTransaksi: totalM + totalK,
    totalPendapatan: totalP,
    updateTerakhir: new Date().toLocaleString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
    }),
  };
});

function chartGridY(i) {
  const yTop = 15;
  const yBottom = CHART_H - 25;

  return Math.round(yTop + ((i - 1) / 4) * (yBottom - yTop));
}

function chartYLabel(i) {
  const max = Math.max(
    1,
    ...chartData.value.masuk,
    ...chartData.value.keluar
  );

  return Math.round((max * (5 - i)) / 4);
}

function chartX(i) {
  const n = chartData.value.labels.length;

  if (n <= 1) {
    return CHART_PAD_LEFT;
  }

  return Math.round(
    CHART_PAD_LEFT +
      (i * (CHART_W - 15 - CHART_PAD_LEFT)) / (n - 1)
  );
}

function toSvgPoints(values) {
  const max = Math.max(1, ...values);
  const n = values.length;

  const yTop = 15;
  const yBottom = CHART_H - 25;

  return values.map((v, i) => {
    const x =
      CHART_PAD_LEFT +
      (n <= 1
        ? 0
        : (i * (CHART_W - 15 - CHART_PAD_LEFT)) / (n - 1));

    const y = yBottom - (v / max) * (yBottom - yTop);

    return [Math.round(x), Math.round(y)];
  });
}

function areaFill(values) {
  const pts = toSvgPoints(values);

  if (pts.length < 2) {
    return '';
  }

  const baseY = CHART_H - 25;

  return `${pts[0][0]},${baseY} ${pts
    .map((p) => p.join(','))
    .join(' ')} ${pts[pts.length - 1][0]},${baseY}`;
}

const chartMasukLine = computed(() =>
  toSvgPoints(chartData.value.masuk)
    .map((p) => p.join(','))
    .join(' ')
);

const chartKeluarLine = computed(() =>
  toSvgPoints(chartData.value.keluar)
    .map((p) => p.join(','))
    .join(' ')
);

const chartMasukDots = computed(() =>
  toSvgPoints(chartData.value.masuk)
);

const chartKeluarDots = computed(() =>
  toSvgPoints(chartData.value.keluar)
);

const chartMasukFill = computed(() =>
  areaFill(chartData.value.masuk)
);

const chartKeluarFill = computed(() =>
  areaFill(chartData.value.keluar)
);

/* =========================================================
   CHART DATA — REAL DARI API
========================================================= */

function buildHarianChartFromTickets() {
  const jamList = [6, 8, 10, 12, 14, 16, 18, 20, 22];
  const labels = jamList.map((j) => `${String(j).padStart(2, '0')}:00`);
  const masuk = new Array(jamList.length).fill(0);
  const keluar = new Array(jamList.length).fill(0);
  const pendapatan = new Array(jamList.length).fill(0);

  const today = new Date();

  tickets.value.forEach((t) => {
    const dMasuk = parseDate(t.waktu_masuk);
    if (dMasuk && dMasuk.toDateString() === today.toDateString()) {
      const jam = dMasuk.getHours();
      let idx = jamList.findIndex((j, i) => jam < (jamList[i + 1] ?? 24));
      if (idx === -1) idx = jamList.length - 1;
      masuk[idx]++;
    }

    const dKeluar = parseDate(t.waktu_keluar);
    if (dKeluar && dKeluar.toDateString() === today.toDateString()) {
      const jam = dKeluar.getHours();
      let idx = jamList.findIndex((j, i) => jam < (jamList[i + 1] ?? 24));
      if (idx === -1) idx = jamList.length - 1;
      keluar[idx]++;
      pendapatan[idx] += Number(t.total_bayar || 0);
    }
  });

  return { labels, masuk, keluar, pendapatan };
}

async function fetchRekapChart(endpoint) {
  try {
    const res = await $api.get(`/rekap/${endpoint}`);
    const data = res.data?.data ?? res.data;
    const detail = Array.isArray(data?.detail) ? data.detail : [];

    return {
      labels: detail.map((item) => {
        if (!item.tanggal) return '-';
        return new Date(`${item.tanggal}T00:00:00`).toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'short',
        });
      }),
      masuk: detail.map((item) => Number(item.total_masuk || 0)),
      keluar: detail.map((item) => Number(item.total_keluar || 0)),
      pendapatan: detail.map((item) => Number(item.total_pendapatan || 0)),
    };
  } catch (e) {
    console.error(`Gagal memuat rekap ${endpoint}:`, e);
    return { labels: [], masuk: [], keluar: [], pendapatan: [] };
  }
}

async function loadChartData(period) {
  if (period === 'harian') {
    chartData.value = buildHarianChartFromTickets();
  } else if (period === '7hari') {
    chartData.value = await fetchRekapChart('mingguan');
  } else if (period === 'bulanan') {
    chartData.value = await fetchRekapChart('bulanan');
  }
}

watch(
  activePeriod,
  (val) => {
    loadChartData(val);
  },
  {
    immediate: true,
  }
);

/* =========================================================
   FORCE UPDATE RELATIVE TIME
========================================================= */

const currentTime = ref(Date.now());

let clockTimer = null;

onMounted(() => {
  clockTimer = setInterval(() => {
    currentTime.value = Date.now();
  }, 30000);
});

onBeforeUnmount(() => {
  if (clockTimer) {
    clearInterval(clockTimer);
  }
});

watch(currentTime, () => {
  activities.value;
});
</script>