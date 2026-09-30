<template>
  <div class="mx-auto max-w-6xl">
    <!-- HEADER -->
    <div class="mb-6">
      <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Dashboard Petugas</p>
      <h1 class="mt-1 text-2xl font-extrabold text-slate-800">Selamat Datang 👋</h1>
    </div>

    <!-- HERO BANNER -->
    <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 p-8 text-white shadow-lg shadow-purple-200/50">
      <div class="pointer-events-none absolute -top-12 -right-10 h-48 w-48 rounded-full bg-white/20 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-16 left-1/3 h-44 w-44 rounded-full bg-pink-300/30 blur-3xl"></div>

      <div class="pointer-events-none absolute right-8 top-1/2 hidden min-[420px]:block -translate-y-1/2">
        <div class="flex h-28 w-28 rotate-6 items-center justify-center rounded-full bg-white/20 shadow-inner backdrop-blur-md">
          <span class="select-none text-7xl drop-shadow-lg">🚗</span>
        </div>
      </div>

      <span class="inline-flex items-center gap-2 rounded-full bg-white/25 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur-md">
        <span class="relative flex h-2 w-2">
          <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-300 opacity-75"></span>
          <span class="relative inline-flex h-2 w-2 rounded-full bg-green-300"></span>
        </span>
        Sistem Parkir Aktif
      </span>

      <h2 class="mt-3 max-w-lg text-2xl font-extrabold text-white sm:text-3xl">
        Kelola Parkir Lebih Mudah & Cepat
      </h2>

      <p class="mt-1 max-w-xl text-sm text-white/90">
        Pantau member, transaksi parkir, pembayaran, dan laporan dari satu dashboard.
      </p>
    </div>

    <div v-if="loading" class="rounded-3xl border border-slate-100 bg-white py-16 text-center text-sm text-slate-400 shadow-sm">
      Memuat data...
    </div>

    <!-- GRID STATISTIK RINGKASAN (4 CARD SEJAJAR) -->
    <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
      <!-- CARD 1: TOTAL MEMBER -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md">
        <div class="flex items-start justify-between">
          <div class="rounded-2xl bg-sky-100 p-3 text-sky-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">Aktif</span>
        </div>
        <p class="mt-4 text-3xl font-bold text-slate-800">{{ stats.totalMember }}</p>
        <p class="mt-1 text-xs text-slate-400">Member terdaftar</p>
      </div>

      <!-- CARD 2: KENDARAAN MASUK / TRANSAKSI -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md">
        <div class="flex items-start justify-between">
          <div class="rounded-2xl bg-pink-100 p-3 text-pink-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 002 12.9V16c0 1.1.9 2 2 2m0 0a2 2 0 104 0 2 2 0 00-4 0zm12 0a2 2 0 104 0 2 2 0 00-4 0zM19 17v-1c0-.6-.4-1-1-1s-1 .4-1 1v1m-8-7v2m0 2v2" />
            </svg>
          </div>
          <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-600">Hari ini</span>
        </div>
        <p class="mt-4 text-3xl font-bold text-slate-800">{{ stats.kendaraanMasuk }}</p>
        <p class="mt-1 text-xs text-slate-400">Transaksi kendaraan</p>
      </div>

      <!-- CARD 3: PENDAPATAN / TOTAL TRANSAKSI -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md">
        <div class="flex items-start justify-between">
          <div class="rounded-2xl bg-amber-100 p-3 text-amber-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-600">Hari ini</span>
        </div>
        <p class="mt-4 text-3xl font-bold text-slate-800">Rp {{ formatRupiah(stats.pendapatanHariIni) }}</p>
        <p class="mt-1 text-xs text-slate-400">Total transaksi</p>
      </div>

      <!-- CARD 4: SEDANG PARKIR / BERJALAN -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md">
        <div class="flex items-start justify-between">
          <div class="rounded-2xl bg-sky-100 p-3 text-sky-600">
            <span class="flex h-6 w-6 items-center justify-center text-lg font-extrabold">P</span>
          </div>
          <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-600">Berjalan</span>
        </div>
        <p class="mt-4 text-3xl font-bold text-slate-800">{{ stats.sedangParkir }}</p>
        <p class="mt-1 text-xs text-slate-400">Kendaraan di area parkir</p>
      </div>
    </div>

    <!-- SUB-SECTION BOTTOM (2 KOLOM SEJAJAR) -->
    <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
      <!-- KOLOM KIRI: AKSES CEPAT -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="rounded-2xl bg-pink-100 p-3 text-pink-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-800">AKSES CEPAT</h3>
            <p class="text-xs text-slate-400">Menu pintasan perintah harian</p>
          </div>
        </div>

        <div class="mt-5 space-y-3">
          <NuxtLink to="/petugas/member/select" class="group flex items-center justify-between rounded-2xl p-3 transition hover:bg-sky-50">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-sky-100 p-2.5 text-sky-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </span>
              <div>
                <p class="text-sm font-semibold text-slate-700">Kelola Member</p>
                <p class="text-xs text-slate-400">Kelola data member parkir</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </NuxtLink>

          <NuxtLink to="/petugas/scan" class="group flex items-center justify-between rounded-2xl p-3 transition hover:bg-purple-50">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-purple-100 p-2.5 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 12h10" />
                </svg>
              </span>
              <div>
                <p class="text-sm font-semibold text-slate-700">Kelola Transaksi</p>
                <p class="text-xs text-slate-400">Scan & pembayaran kendaraan</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </NuxtLink>

          <NuxtLink to="/petugas/laporan" class="group flex items-center justify-between rounded-2xl p-3 transition hover:bg-pink-50">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-pink-100 p-2.5 text-pink-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </span>
              <div>
                <p class="text-sm font-semibold text-slate-700">Laporan</p>
                <p class="text-xs text-slate-400">Rekap & laporan transaksi</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </NuxtLink>
        </div>
      </div>

      <!-- KOLOM KANAN: MONITORING · AKTIVITAS TERBARU -->
      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="rounded-2xl bg-purple-100 p-3 text-purple-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-bold text-slate-800">MONITORING · AKTIVITAS TERBARU</h3>
            <p class="text-xs text-slate-400">Pantau aktivitas parkir secara real-time</p>
          </div>
        </div>

        <ul class="mt-5 space-y-4">
          <li class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-sky-100 p-2.5 text-sky-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l-6-6m6 6l6-6" />
                </svg>
              </span>
              <p class="text-sm font-medium text-slate-700">Kendaraan masuk</p>
            </div>
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-600">POS 1</span>
          </li>

          <li class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-purple-100 p-2.5 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
              </span>
              <p class="text-sm font-medium text-slate-700">Member melakukan tap</p>
            </div>
            <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-600">MEMBER</span>
          </li>

          <li class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="rounded-xl bg-emerald-100 p-2.5 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </span>
              <p class="text-sm font-medium text-slate-700">Pembayaran parkir</p>
            </div>
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">LUNAS</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeUnmount } from 'vue';

definePageMeta({
  layout: 'petugas',
});

const { $api } = useNuxtApp();

const loading = ref(true);
const stats = ref({
  totalMember: 0,
  kendaraanMasuk: 0,
  kendaraanKeluar: 0,
  pendapatanHariIni: 0,
  sedangParkir: 0,
});
const tikets = ref([]);
const chartData = ref({ labels: [], masuk: [], keluar: [] });
const jam = ref('00.00.00');
const shift = ref('PAGI');

function gantiShift() {
  shift.value = shift.value === 'PAGI' ? 'MALAM' : 'PAGI';
}

function formatRupiah(val) {
  return Number(val || 0).toLocaleString('id-ID');
}

function formatHari(value) {
  const d = new Date(value);
  return isNaN(d.getTime()) ? '-' : d.toLocaleDateString('id-ID', { weekday: 'short' });
}

function formatTanggalPenuh(value) {
  const d = new Date(value);
  return isNaN(d.getTime()) ? '-' : d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatWaktu(value) {
  if (!value) return '-';
  const d = new Date(value);
  if (isNaN(d.getTime())) return '-';
  return d.toLocaleString('id-ID', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  });
}

function isToday(value) {
  if (!value) return false;
  const date = new Date(value);
  const today = new Date();
  return (
    date.getFullYear() === today.getFullYear() &&
    date.getMonth() === today.getMonth() &&
    date.getDate() === today.getDate()
  );
}

function hitungPendapatan(tikets) {
  if (!Array.isArray(tikets)) return 0;
  return tikets
    .filter((t) => t.status === 'keluar' && isToday(t.waktu_keluar))
    .reduce((total, t) => {
      const masuk = new Date(t.waktu_masuk);
      const keluar = new Date(t.waktu_keluar);
      const menit = (keluar - masuk) / 60000;
      const jam = Math.max(1, Math.ceil(menit / 60)); // Minimal terhitung 1 jam
      return total + jam * 3000;
    }, 0);
}

// ---- GRAFIK (SVG line chart) ----
const CHART_W = 640;
const CHART_H = 200;
const CHART_PAD_X = 20;
const CHART_PAD_Y = 40;

function polylinePoints(values) {
  const max = Math.max(1, ...values);
  const n = values.length;
  return values.map((v, i) => {
    const x = CHART_PAD_X + (n <= 1 ? 0 : (i * (CHART_W - CHART_PAD_X * 2)) / (n - 1));
    const y = CHART_H - CHART_PAD_Y - (v / max) * (CHART_H - CHART_PAD_Y * 2);
    return [Math.round(x), Math.round(y)];
  });
}

const chartMasukPoints = computed(() => polylinePoints(chartData.value.masuk).map((p) => p.join(',')).join(' '));
const chartKeluarPoints = computed(() => polylinePoints(chartData.value.keluar).map((p) => p.join(',')).join(' '));
const chartMasukDots = computed(() => polylinePoints(chartData.value.masuk));
const chartKeluarDots = computed(() => polylinePoints(chartData.value.keluar));
const chartMasukFill = computed(() => {
  const pts = polylinePoints(chartData.value.masuk);
  if (pts.length < 2) return '';
  const baseY = CHART_H - CHART_PAD_Y;
  return `${pts[0][0]},${baseY} ${pts.map((p) => p.join(',')).join(' ')} ${pts[pts.length - 1][0]},${baseY}`;
});

const totalMasuk = computed(() => chartData.value.masuk.reduce((a, b) => a + b, 0));
const totalKeluar = computed(() => chartData.value.keluar.reduce((a, b) => a + b, 0));
const tanggalTerakhir = computed(() =>
  chartData.value.labels.length ? formatTanggalPenuh(chartData.value.labels[chartData.value.labels.length - 1]) : '-'
);

// ---- AKTIVITAS TERBARU ----
const aktivitas = computed(() => {
  if (!Array.isArray(tikets.value)) return [];
  return [...tikets.value]
    .sort((a, b) => new Date(b.waktu_keluar || b.waktu_masuk) - new Date(a.waktu_keluar || a.waktu_masuk))
    .slice(0, 6)
    .map((t) => ({
      kode: t.kode_tiket || '-',
      jenis: t.status === 'keluar' ? 'keluar' : 'masuk',
      waktu: t.waktu_keluar || t.waktu_masuk,
    }));
});

// ---- JAM REAL-TIME ----
function updateJam() {
  const d = new Date();
  jam.value = [d.getHours(), d.getMinutes(), d.getSeconds()].map((n) => String(n).padStart(2, '0')).join('.');
}

// ---- SHORTCUT KEYBOARD F1 - F4 ----
function onKeydown(e) {
  const key = String(e.key).toUpperCase();
  if (key === 'F1') {
    e.preventDefault();
    navigateTo('/petugas/scan');
  } else if (key === 'F2') {
    e.preventDefault();
    navigateTo('/petugas/keluar');
  } else if (key === 'F3') {
    e.preventDefault();
    navigateTo('/petugas/ticket');
  } else if (key === 'F4') {
    e.preventDefault();
    navigateTo('/petugas/member/select');
  }
}

onMounted(async () => {
  try {
    const [resMembers, resTikets] = await Promise.all([
      $api.get('/members'),
      $api.get('/tiket'),
    ]);

    const members = Array.isArray(resMembers.data) ? resMembers.data : resMembers.data?.data || [];
    const listTiket = Array.isArray(resTikets.data) ? resTikets.data : resTikets.data?.data || [];
    tikets.value = listTiket;

    const sameDay = (a, b) => {
      const d1 = new Date(a);
      const d2 = new Date(b);
      return (
        d1.getFullYear() === d2.getFullYear() &&
        d1.getMonth() === d2.getMonth() &&
        d1.getDate() === d2.getDate()
      );
    };

    const last7 = [...Array(7)].map((_, i) => {
      const d = new Date();
      d.setDate(d.getDate() - (6 - i));
      d.setHours(0, 0, 0, 0);
      return d;
    });

    stats.value = {
      totalMember: members.length,
      kendaraanMasuk: listTiket.filter((t) => t.status === 'masuk' && isToday(t.waktu_masuk)).length,
      kendaraanKeluar: listTiket.filter((t) => t.status === 'keluar' && isToday(t.waktu_keluar)).length,
      pendapatanHariIni: hitungPendapatan(listTiket),
      sedangParkir: listTiket.filter((t) => t.status === 'masuk').length,
    };

    chartData.value = {
      labels: last7,
      masuk: last7.map((d) => listTiket.filter((t) => t.status === 'masuk' && sameDay(t.waktu_masuk, d)).length),
      keluar: last7.map((d) => listTiket.filter((t) => t.status === 'keluar' && sameDay(t.waktu_keluar, d)).length),
    };
  } catch (e) {
    console.error('Gagal memuat statistik:', e);
  } finally {
    loading.value = false;
  }
});

onMounted(() => {
  updateJam();
  window.addEventListener('keydown', onKeydown);
  jamTimer = setInterval(updateJam, 1000);
});

let jamTimer = null;

onBeforeUnmount(() => {
  if (jamTimer) clearInterval(jamTimer);
  window.removeEventListener('keydown', onKeydown);
});
</script>