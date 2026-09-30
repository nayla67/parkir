<template>
  <div>
    <!-- HEADER -->
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-purple-600">Petugas</p>

        <h1 class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">
          Laporan Sistem Parkir
        </h1>

        <p class="mt-1.5 text-sm text-slate-500">
          Ringkasan & analisis statistik parkir
        </p>
      </div>

      <div class="flex gap-2">
        <NuxtLink
          to="/petugas"
          class="rounded-xl border border-slate-100 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:text-sky-600"
        >
          ← Dashboard
        </NuxtLink>

        <button
          @click="exportPdf"
          :disabled="!rekap"
          class="rounded-xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:brightness-110 disabled:opacity-50"
        >
          ⬇ Export PDF
        </button>
      </div>
    </div>

    <!-- FILTER -->
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
      <div class="flex flex-wrap items-end gap-4">
        <div>
          <label class="mb-1.5 block text-sm font-medium text-slate-700">
            Periode
          </label>

          <select
            v-model="tipe"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-400"
          >
            <option value="harian">Harian</option>
            <option value="mingguan">Mingguan</option>
            <option value="bulanan">Bulanan</option>
          </select>
        </div>

        <div>
          <label class="mb-1.5 block text-sm font-medium text-slate-700">
            Tanggal / Bulan
          </label>

          <input
            v-model="date"
            type="date"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-400"
          />
        </div>

        <button
          @click="loadData"
          :disabled="loading"
          class="rounded-xl bg-gradient-to-r from-amber-400 via-rose-400 to-purple-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-200 transition hover:brightness-110 disabled:opacity-60"
        >
          {{ loading ? 'Memuat...' : 'Tampilkan' }}
        </button>
      </div>
    </div>

    <!-- ERROR -->
    <div
      v-if="errorMessage"
      class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-5 text-sm text-red-600"
    >
      <p class="font-semibold">Gagal memuat laporan</p>
      <p class="mt-1">{{ errorMessage }}</p>
    </div>

    <!-- LOADING -->
    <div
      v-if="loading"
      class="rounded-2xl border border-slate-100 bg-white py-16 text-center text-sm text-slate-400 shadow-sm"
    >
      Memuat data laporan...
    </div>

    <!-- DATA -->
    <template v-else-if="rekap">
      <!-- RINGKASAN -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <!-- TOTAL MASUK -->
        <div
          class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
        >
          <div
            class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600"
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
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
          </div>

          <p class="text-sm text-slate-500">Total Masuk</p>

          <p class="mt-1 text-3xl font-bold text-slate-800">
            {{ rekap.summary.total_masuk }}
          </p>
        </div>

        <!-- TOTAL KELUAR -->
        <div
          class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
        >
          <div
            class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-rose-100 text-rose-600"
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

          <p class="text-sm text-slate-500">Total Keluar</p>

          <p class="mt-1 text-3xl font-bold text-slate-800">
            {{ rekap.summary.total_keluar }}
          </p>
        </div>

        <!-- PENDAPATAN -->
        <div
          class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
        >
          <div
            class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-purple-600"
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
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>

          <p class="text-sm text-slate-500">Total Pendapatan</p>

          <p class="mt-1 text-3xl font-bold text-slate-800">
            Rp {{ formatRupiah(rekap.summary.total_pendapatan) }}
          </p>
        </div>

        <!-- RATA-RATA -->
        <div
          class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md"
        >
          <div
            class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-500"
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
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>
          </div>

          <p class="text-sm text-slate-500">Rata-rata Masuk / Hari</p>

          <p class="mt-1 text-3xl font-bold text-slate-800">
            {{ rekap.summary.rata_rata_per_hari }}
          </p>
        </div>
      </div>

      <!-- GRAFIK -->
      <div
        class="mt-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
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
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>
          </div>

          <div>
            <h3 class="text-lg font-bold text-purple-950">
              Tren Pendapatan & Transaksi ({{ chartUnit }})
            </h3>

            <p class="text-xs text-slate-400">
              Periode: {{ rekap.periode }}
            </p>
          </div>
        </div>

        <div class="mt-5 overflow-x-auto">
          <svg
            viewBox="0 0 700 240"
            class="w-full min-w-[600px]"
            preserveAspectRatio="xMidYMid meet"
            font-family="system-ui, sans-serif"
          >
            <defs>
              <linearGradient id="gradPendapatan" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#a855f7" stop-opacity="0.25" />
                <stop offset="100%" stop-color="#a855f7" stop-opacity="0.02" />
              </linearGradient>

              <linearGradient id="gradMasuk" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.25" />
                <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.02" />
              </linearGradient>

              <linearGradient id="linePendapatan" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="#a855f7" />
                <stop offset="100%" stop-color="#f43f5e" />
              </linearGradient>

              <linearGradient id="lineMasuk" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="#f59e0b" />
                <stop offset="100%" stop-color="#f43f5e" />
              </linearGradient>
            </defs>

            <line
              v-for="i in 5"
              :key="'gl' + i"
              :x1="CHART_PAD_LEFT"
              :y1="gridY(i)"
              :x2="CHART_W - 15"
              :y2="gridY(i)"
              stroke="#e2e8f0"
              stroke-width="1"
              stroke-dasharray="4 4"
            />

            <text
              v-for="i in 5"
              :key="'yl' + i"
              :x="CHART_PAD_LEFT - 8"
              :y="gridY(i) + 3.5"
              text-anchor="end"
              fill="#94a3b8"
              font-size="10"
            >
              {{ yLabel(i) }}
            </text>

            <polygon :points="pendapatanFill" fill="url(#gradPendapatan)" />

            <polygon :points="masukFill" fill="url(#gradMasuk)" />

            <polyline
              :points="pendapatanLine"
              fill="none"
              stroke="url(#linePendapatan)"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <polyline
              :points="masukLine"
              fill="none"
              stroke="url(#lineMasuk)"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <circle
              v-for="(pt, i) in pendapatanDots"
              :key="'pd' + i"
              :cx="pt[0]"
              :cy="pt[1]"
              r="3.5"
              fill="white"
              stroke="#a855f7"
              stroke-width="2"
            />

            <circle
              v-for="(pt, i) in masukDots"
              :key="'md' + i"
              :cx="pt[0]"
              :cy="pt[1]"
              r="3.5"
              fill="white"
              stroke="#f59e0b"
              stroke-width="2"
            />

            <text
              v-for="(lbl, i) in chartLabels"
              :key="'xl' + i"
              :x="chartX(i)"
              :y="CHART_H - 5"
              text-anchor="middle"
              fill="#94a3b8"
              font-size="9"
            >
              {{ lbl }}
            </text>
          </svg>
        </div>

        <div class="mt-3 flex flex-wrap items-center justify-center gap-6">
          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
            <span class="text-xs font-medium text-slate-500">Pendapatan</span>
          </div>

          <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
            <span class="text-xs font-medium text-slate-500">
              Transaksi Masuk
            </span>
          </div>
        </div>
      </div>

      <!-- REKAP PER HARI (mingguan / bulanan saja) -->
      <div
        v-if="tipe !== 'harian'"
        class="mt-6 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
      >
        <div class="border-b border-slate-100 px-6 py-4">
          <h2 class="text-lg font-bold text-slate-800">
            Rekap per Hari —
            <span class="text-purple-600">{{ rekap.periode }}</span>
          </h2>
        </div>

        <div
          v-if="rekap.detail.length === 0"
          class="p-12 text-center text-sm text-slate-400"
        >
          Tidak ada data
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full min-w-[650px] text-sm">
            <thead class="bg-slate-50 text-left">
              <tr>
                <th class="px-6 py-3 font-semibold text-slate-600">Tanggal</th>

                <th
                  v-if="tipe === 'mingguan'"
                  class="px-6 py-3 font-semibold text-slate-600"
                >
                  Hari
                </th>

                <th class="px-6 py-3 font-semibold text-slate-600">Masuk</th>
                <th class="px-6 py-3 font-semibold text-slate-600">Keluar</th>
                <th class="px-6 py-3 text-right font-semibold text-slate-600">
                  Pendapatan
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="(item, index) in rekap.detail"
                :key="item.tanggal + index"
                class="transition hover:bg-purple-50/50"
              >
                <td class="px-6 py-4 font-medium text-slate-800">
                  {{ formatTanggal(item.tanggal) }}
                </td>

                <td
                  v-if="tipe === 'mingguan'"
                  class="px-6 py-4 text-slate-600"
                >
                  {{ item.nama_hari || '-' }}
                </td>

                <td class="px-6 py-4 font-medium text-amber-600">
                  {{ item.total_masuk }}
                </td>

                <td class="px-6 py-4 font-medium text-rose-600">
                  {{ item.total_keluar }}
                </td>

                <td class="px-6 py-4 text-right font-semibold text-slate-800">
                  Rp {{ formatRupiah(item.total_pendapatan) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- DETAIL TRANSAKSI PER TIKET -->
      <div
        class="mt-6 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
      >
        <div
          class="flex items-center justify-between border-b border-slate-100 px-6 py-4"
        >
          <h2 class="text-lg font-bold text-slate-800">
            Detail Transaksi —
            <span class="text-purple-600">{{ rekap.periode }}</span>
          </h2>

          <span class="text-xs text-slate-400">
            {{ rekap.tiket.length }} tiket
          </span>
        </div>

        <div
          v-if="rekap.tiket.length === 0"
          class="p-12 text-center text-sm text-slate-400"
        >
          Tidak ada data transaksi
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full min-w-[900px] text-sm">
            <thead class="bg-slate-50 text-left">
              <tr>
                <th class="px-6 py-3 font-semibold text-slate-600">
                  Kode Tiket
                </th>
                <th class="px-6 py-3 font-semibold text-slate-600">
                  Plat Nomor
                </th>
                <th class="px-6 py-3 font-semibold text-slate-600">
                  Kendaraan
                </th>
                <th class="px-6 py-3 font-semibold text-slate-600">
                  Waktu Masuk
                </th>
                <th class="px-6 py-3 font-semibold text-slate-600">
                  Waktu Keluar
                </th>
                <th class="px-6 py-3 text-right font-semibold text-slate-600">
                  Biaya
                </th>
                <th class="px-6 py-3 text-center font-semibold text-slate-600">
                  Status
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="(t, index) in rekap.tiket"
                :key="t.kode_tiket + index"
                class="transition hover:bg-purple-50/50"
              >
                <td
                  class="px-6 py-4 font-mono text-xs font-semibold text-slate-800"
                >
                  {{ t.kode_tiket }}
                </td>

                <td class="px-6 py-4 font-medium uppercase text-slate-800">
                  {{ t.plat_nomor }}
                </td>

                <td class="px-6 py-4 text-slate-600">
                  {{
                    t.jenis_kendaraan === 'mobil'
                      ? '🚗 Mobil'
                      : t.jenis_kendaraan === 'motor'
                        ? '🏍️ Motor'
                        : '-'
                  }}
                </td>

                <td class="px-6 py-4 text-slate-600">
                  {{ formatWaktu(t.waktu_masuk) }}
                </td>

                <td class="px-6 py-4 text-slate-600">
                  {{ formatWaktu(t.waktu_keluar) }}
                </td>

                <td class="px-6 py-4 text-right font-semibold text-slate-800">
                  <span
                    v-if="t.is_member"
                    class="text-xs font-medium text-purple-600"
                  >
                    Member
                  </span>
                  <span v-else-if="t.status === 'masuk'" class="text-slate-400">
                    -
                  </span>
                  <span v-else>Rp {{ formatRupiah(t.biaya) }}</span>
                </td>

                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                    :class="
                      t.status === 'masuk'
                        ? 'bg-amber-100 text-amber-700'
                        : 'bg-emerald-100 text-emerald-700'
                    "
                  >
                    {{ t.status === 'masuk' ? 'Masuk' : 'Keluar' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

definePageMeta({
  layout: 'petugas',
});

const { $api } = useNuxtApp();

const tipe = ref('harian');
const date = ref(new Date().toISOString().split('T')[0]);

const loading = ref(false);
const rekap = ref(null);
const errorMessage = ref('');

// =========================
// FORMAT
// =========================

function formatRupiah(value) {
  return Number(value || 0).toLocaleString('id-ID');
}

function formatTanggal(value) {
  if (!value) return '-';

  const d = new Date(value);

  if (isNaN(d.getTime())) {
    return value;
  }

  return d.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
}

function formatWaktu(value) {
  if (!value) return '-';

  const d = new Date(value);

  if (isNaN(d.getTime())) {
    return value;
  }

  return d.toLocaleString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

// =========================
// MAPPING TIKET
// =========================

/*
 * Nama field dibuat fleksibel (beberapa kemungkinan nama).
 * Kalau nama kolom di backend beda, tambahkan di sini.
 */
function mapTiket(t) {
  const waktuKeluar = t.waktu_keluar || null;

  return {
    kode_tiket: t.kode_tiket || t.qr_code || t.nomor_tiket || '-',
    plat_nomor: t.nomor_polisi || t.plat_nomor || '-',
    jenis_kendaraan: String(
      t.jenis_kendaraan || t.tipe_kendaraan || ''
    ).toLowerCase(),
    waktu_masuk: t.waktu_masuk || null,
    waktu_keluar: waktuKeluar,
    biaya: Number(t.total_bayar ?? t.biaya ?? 0),
    is_member: t.jenis === 'member',
    // belum ada waktu_keluar = kendaraan masih di dalam
    status: waktuKeluar ? 'keluar' : 'masuk',
  };
}

// =========================
// NORMALIZE DATA API
// =========================

function normalize(data, tipeLaporan) {
  if (!data) {
    return null;
  }

  const detail = Array.isArray(data.detail) ? data.detail : [];

  // =========================
  // HARIAN
  // =========================

  if (tipeLaporan === 'harian') {
    /*
     * Backend harian mengirim:
     *
     * total_masuk
     * total_keluar
     * total_pendapatan
     * detail[]  (tiket asli)
     */

    const totalMasuk = Number(data.total_masuk || 0);
    const totalKeluar = Number(data.total_keluar || 0);
    const totalPendapatan = Number(data.total_pendapatan || 0);

    const tanggal =
      data.tanggal || new Date().toISOString().split('T')[0];

    return {
      periode: formatTanggal(tanggal),

      summary: {
        total_masuk: totalMasuk,
        total_keluar: totalKeluar,
        total_pendapatan: totalPendapatan,
        rata_rata_per_hari: totalMasuk,
      },

      detail: [
        {
          tanggal,
          total_masuk: totalMasuk,
          total_keluar: totalKeluar,
          total_pendapatan: totalPendapatan,
        },
      ],

      tiket: detail.map(mapTiket),
    };
  }

  // =========================
  // MINGGUAN / BULANAN
  // =========================

  /*
   * Backend mengirim:
   *
   * periode
   * ringkasan
   * detail (agregat per hari)
   * tiket  (daftar tiket, perlu ditambahkan di backend)
   */

  const ringkasan = data.ringkasan || {};

  const totalMasuk =
    ringkasan.total_masuk != null
      ? Number(ringkasan.total_masuk)
      : detail.reduce(
          (total, item) => total + Number(item.total_masuk || 0),
          0
        );

  const totalKeluar =
    ringkasan.total_keluar != null
      ? Number(ringkasan.total_keluar)
      : detail.reduce(
          (total, item) => total + Number(item.total_keluar || 0),
          0
        );

  const totalPendapatan =
    ringkasan.total_pendapatan != null
      ? Number(ringkasan.total_pendapatan)
      : detail.reduce(
          (total, item) => total + Number(item.total_pendapatan || 0),
          0
        );

  const tiketRaw = Array.isArray(data.tiket)
    ? data.tiket
    : Array.isArray(data.detail_tiket)
      ? data.detail_tiket
      : [];

  return {
    periode: data.periode || '-',

    summary: {
      total_masuk: totalMasuk,
      total_keluar: totalKeluar,
      total_pendapatan: totalPendapatan,

      rata_rata_per_hari: Math.round(
        totalMasuk / Math.max(1, detail.length)
      ),
    },

    detail,

    tiket: tiketRaw.map(mapTiket),
  };
}

// =========================
// LOAD DATA
// =========================

async function loadData() {
  loading.value = true;
  errorMessage.value = '';
  rekap.value = null;

  try {
    console.log('Mengambil laporan:', tipe.value, date.value);

    const response = await $api.get(`/rekap/${tipe.value}`, {
      params: {
        date: date.value,
      },
    });

    console.log('Response laporan:', response.data);

    const data = response.data?.data ?? response.data;

    console.log(
  'Contoh tiket mentah:',
  JSON.stringify((data.detail || data.tiket || [])[0], null, 2)
);

    rekap.value = normalize(data, tipe.value);

    console.log('Data laporan setelah normalize:', rekap.value);
  } catch (error) {
    console.error('Gagal memuat laporan:', error);

    /*
     * TIDAK ADA DUMMY DATA LAGI.
     * Kalau API gagal, tampilkan error asli.
     */

    errorMessage.value =
      error?.response?.data?.message ||
      error?.message ||
      'Tidak dapat mengambil data laporan dari server.';
  } finally {
    loading.value = false;
  }
}

// =========================
// GRAFIK
// =========================

const CHART_W = 700;
const CHART_H = 240;
const CHART_PAD_LEFT = 50;

function parseWaktu(value) {
  if (!value) return null;

  const d = new Date(String(value).replace(' ', 'T'));

  return isNaN(d.getTime()) ? null : d;
}

// harian = per jam, mingguan/bulanan = per hari
const chartUnit = computed(() =>
  tipe.value === 'harian' ? 'per jam' : 'per hari'
);

const chartRows = computed(() => {
  if (!rekap.value) return [];

  // HARIAN: 24 jam, dihitung dari data tiket
  if (tipe.value === 'harian') {
    const jam = Array.from({ length: 24 }, (_, h) => ({
      label: `${String(h).padStart(2, '0')}:00`,
      masuk: 0,
      pendapatan: 0,
    }));

    rekap.value.tiket.forEach((t) => {
      const masuk = parseWaktu(t.waktu_masuk);
      if (masuk) jam[masuk.getHours()].masuk += 1;

      // pendapatan dihitung pada jam kendaraan keluar
      const keluar = parseWaktu(t.waktu_keluar);
      if (keluar && !t.is_member) {
        jam[keluar.getHours()].pendapatan += Number(t.biaya || 0);
      }
    });

    return jam;
  }

  // MINGGUAN / BULANAN: satu titik per hari
  return rekap.value.detail.map((item) => ({
    label: item.tanggal
      ? new Date(`${item.tanggal}T00:00:00`).toLocaleDateString('id-ID', {
          day: 'numeric',
          month: 'short',
        })
      : '-',
    masuk: Number(item.total_masuk || 0),
    pendapatan: Number(item.total_pendapatan || 0),
  }));
});

// label sumbu X dijarangkan kalau titiknya banyak
const chartLabels = computed(() => {
  const rows = chartRows.value;
  const step = rows.length > 16 ? Math.ceil(rows.length / 12) : 1;

  return rows.map((row, i) => (i % step === 0 ? row.label : ''));
});

function gridY(i) {
  return Math.round(15 + ((i - 1) / 4) * (CHART_H - 40));
}

function yMax() {
  return Math.max(
    1,
    ...pendapatanSeries.value,
    ...masukSeries.value
  );
}

function yLabel(i) {
  const max = yMax();

  return Math.round((max * (5 - i)) / 4);
}

function chartX(i) {
  const n = chartRows.value.length;

  if (n <= 1) {
    return CHART_PAD_LEFT;
  }

  return Math.round(
    CHART_PAD_LEFT + (i * (CHART_W - 15 - CHART_PAD_LEFT)) / (n - 1)
  );
}

function toSvgPoints(values) {
  const max = yMax();
  const n = values.length;

  return values.map((value, i) => {
    const x =
      CHART_PAD_LEFT +
      (n <= 1 ? 0 : (i * (CHART_W - 15 - CHART_PAD_LEFT)) / (n - 1));

    const y =
      CHART_H - 25 - (Number(value || 0) / max) * (CHART_H - 55);

    return [Math.round(x), Math.round(y)];
  });
}

function areaFill(values) {
  const points = toSvgPoints(values);

  if (points.length < 2) {
    return '';
  }

  const baseY = CHART_H - 25;

  return `${points[0][0]},${baseY} ${points
    .map((point) => point.join(','))
    .join(' ')} ${points[points.length - 1][0]},${baseY}`;
}

const pendapatanSeries = computed(() =>
  chartRows.value.map((row) => row.pendapatan)
);

const masukSeries = computed(() =>
  chartRows.value.map((row) => row.masuk)
);

const pendapatanLine = computed(() => {
  return toSvgPoints(pendapatanSeries.value)
    .map((point) => point.join(','))
    .join(' ');
});

const masukLine = computed(() => {
  return toSvgPoints(masukSeries.value)
    .map((point) => point.join(','))
    .join(' ');
});

const pendapatanDots = computed(() => {
  return toSvgPoints(pendapatanSeries.value);
});

const masukDots = computed(() => {
  return toSvgPoints(masukSeries.value);
});

const pendapatanFill = computed(() => {
  return areaFill(pendapatanSeries.value);
});

const masukFill = computed(() => {
  return areaFill(masukSeries.value);
});

// =========================
// EXPORT PDF
// =========================

function exportPdf() {
  if (!rekap.value) {
    return;
  }

  const pdf = new jsPDF({
    orientation: 'landscape',
    unit: 'mm',
    format: 'a4',
  });

  const summary = rekap.value.summary;

  pdf.setFontSize(14);

  pdf.text('LAPORAN PARKIR - PARKIR PLAZA ANDALAS', 148, 15, {
    align: 'center',
  });

  pdf.setFontSize(10);

  pdf.text(`Periode: ${rekap.value.periode}`, 148, 22, {
    align: 'center',
  });

  pdf.text(`Total Masuk: ${summary.total_masuk}`, 14, 32);
  pdf.text(`Total Keluar: ${summary.total_keluar}`, 14, 38);
  pdf.text(
    `Total Pendapatan: Rp ${formatRupiah(summary.total_pendapatan)}`,
    14,
    44
  );

  autoTable(pdf, {
    startY: 50,
    head: [
      [
        'No',
        'Kode Tiket',
        'Plat Nomor',
        'Kendaraan',
        'Waktu Masuk',
        'Waktu Keluar',
        'Biaya',
        'Status',
      ],
    ],
    body: rekap.value.tiket.map((t, i) => [
      i + 1,
      t.kode_tiket,
      String(t.plat_nomor).toUpperCase(),
      t.jenis_kendaraan
        ? t.jenis_kendaraan.charAt(0).toUpperCase() +
          t.jenis_kendaraan.slice(1)
        : '-',
      formatWaktu(t.waktu_masuk),
      formatWaktu(t.waktu_keluar),
      t.is_member
        ? 'Member'
        : t.status === 'masuk'
          ? '-'
          : `Rp ${formatRupiah(t.biaya)}`,
      t.status === 'masuk' ? 'Masuk' : 'Keluar',
    ]),
    styles: { fontSize: 8, cellPadding: 2 },
    headStyles: { fillColor: [147, 51, 234] },
    columnStyles: { 0: { halign: 'center', cellWidth: 10 } },
  });

  pdf.save(`laporan-parkir-${tipe.value}-${date.value}.pdf`);
}

// =========================
// LOAD AWAL
// =========================

onMounted(() => {
  loadData();
});
</script>