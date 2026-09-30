<template>
  <div class="min-h-screen">
    <!-- HEADER -->
    <div class="mb-6">
      <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Kelola Transaksi</p>
      <h1 class="mt-1 text-2xl font-extrabold text-slate-800">Transaksi Parkir 💳</h1>
      <p class="mt-1 text-sm text-slate-500">Proses pembayaran non-member & penagihan member.</p>
    </div>

    <!-- TABS / TOGGLE -->
    <div class="mb-6 inline-flex rounded-2xl border border-slate-100 bg-white p-1.5 shadow-sm">
      <button
        @click="activeTab = 'non-member'"
        class="rounded-xl px-5 py-2.5 text-sm font-bold transition"
        :class="activeTab === 'non-member' ? 'bg-purple-500 text-white shadow-md shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'"
      >
        Pembayaran Non-Member
      </button>
      <button
        @click="activeTab = 'member'"
        class="rounded-xl px-5 py-2.5 text-sm font-bold transition"
        :class="activeTab === 'member' ? 'bg-sky-500 text-white shadow-md shadow-sky-200' : 'text-slate-500 hover:bg-slate-50'"
      >
        Penagihan Member
      </button>
    </div>

    <div v-if="loading" class="rounded-3xl border border-slate-100 bg-white py-16 text-center text-sm text-slate-400 shadow-sm">
      Memuat data...
    </div>

    <template v-else>
      <!-- STAT CARDS -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
          <div class="flex items-start justify-between">
            <div class="rounded-2xl bg-rose-100 p-3 text-rose-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-600">Di dalam</span>
          </div>
          <p class="mt-4 text-3xl font-bold text-slate-800">{{ stats.belumBayar }}</p>
          <p class="mt-1 text-xs text-slate-400">Belum bayar jumlah</p>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
          <div class="flex items-start justify-between">
            <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">Selesai</span>
          </div>
          <p class="mt-4 text-3xl font-bold text-slate-800">{{ stats.sudahBayar }}</p>
          <p class="mt-1 text-xs text-slate-400">Sudah bayar jumlah</p>
        </div>

        <div class="sm:col-span-2 xl:col-span-1 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
          <div class="flex items-start justify-between">
            <div class="rounded-2xl bg-amber-100 p-3 text-amber-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 0 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-600">Pendapatan</span>
          </div>
          <p class="mt-4 text-3xl font-bold text-slate-800">{{ formatRupiah(stats.totalPendapatan) }}</p>
          <p class="mt-1 text-xs text-slate-400">Total pendapatan</p>
        </div>
      </div>

      <!-- TAB: PEMBAYARAN NON-MEMBER -->
      <div v-if="activeTab === 'non-member'" class="mt-6 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
          <h2 class="text-base font-bold text-slate-800">Pembayaran Non-Member</h2>
          <p class="text-xs text-slate-400">Baris belum bayar dapat dilanjutkan ke proses pembayaran.</p>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-xs uppercase tracking-wider text-white">
                <th class="px-6 py-3 font-bold text-white">Kode Tiket</th>
                <th class="px-6 py-3 font-bold text-white">Plat Nomor</th>
                <th class="px-6 py-3 font-bold text-white">Waktu Masuk</th>
                <th class="px-6 py-3 font-bold text-white">Status</th>
                <th class="px-6 py-3 font-bold text-white">Biaya</th>
                <th class="px-6 py-3 text-right font-bold text-white">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in nonMemberList" :key="item.id" class="border-b border-slate-50 last:border-0">
                <td class="px-6 py-4 font-mono text-xs font-semibold text-purple-600">{{ item.kode_tiket || '-' }}</td>
                <td class="px-6 py-4 text-slate-700">{{ item.nomor_polisi || '-' }}</td>
                <td class="px-6 py-4 text-slate-700">{{ formatTanggal(item.waktu_masuk) }}</td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold"
                    :class="isBelumBayar(item)
                      ? 'bg-rose-100 text-rose-600'
                      : 'bg-emerald-100 text-emerald-600'"
                  >
                    {{ isBelumBayar(item) ? 'BELUM BAYAR' : 'SUDAH BAYAR' }}
                  </span>
                </td>
                <td class="px-6 py-4 font-semibold text-slate-800">{{ formatRupiah(isBelumBayar(item) ? estimasiBiaya(item) : item.total_bayar) }}</td>
                <td class="px-6 py-4 text-right">
                  <button
                    v-if="isBelumBayar(item)"
                    @click="prosesBayar(item)"
                    :disabled="bayarId === item.id"
                    class="rounded-xl bg-gradient-to-r from-emerald-400 to-emerald-500 px-4 py-2 text-xs font-bold text-white shadow-md shadow-emerald-200 transition hover:from-emerald-500 hover:to-emerald-600 disabled:opacity-50"
                  >
                    {{ bayarId === item.id ? 'Memproses...' : 'Proses Bayar' }}
                  </button>
                  <span v-else class="text-slate-300">-</span>
                </td>
              </tr>
              <tr v-if="!nonMemberList.length">
                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada transaksi.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TAB: PENAGIHAN MEMBER -->
      <div v-else class="mt-6 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
          <h2 class="text-base font-bold text-slate-800">Penagihan Member</h2>
          <p class="text-xs text-slate-400">Tagihan member yang belum lunas dapat diterima pembayarannya.</p>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-xs uppercase tracking-wider text-white">
                <th class="px-6 py-3 font-bold text-white">Kode</th>
                <th class="px-6 py-3 font-bold text-white">Member</th>
                <th class="px-6 py-3 font-bold text-white">Perusahaan</th>
                <th class="px-6 py-3 font-bold text-white">Tanggal Tagihan</th>
                <th class="px-6 py-3 font-bold text-white">Status</th>
                <th class="px-6 py-3 font-bold text-white">Jumlah</th>
                <th class="px-6 py-3 text-right font-bold text-white">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in tagihanList" :key="item.id" class="border-b border-slate-50 last:border-0">
                <td class="px-6 py-4 font-mono text-xs font-semibold text-sky-600">{{ item.member?.kode || '-' }}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">{{ item.member?.nama || '-' }}</td>
                <td class="px-6 py-4 text-slate-700">{{ item.member?.perusahaan || '-' }}</td>
                <td class="px-6 py-4 text-slate-700">{{ formatTanggal(item.tanggal_tagihan) }}</td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex rounded-full px-3 py-1 text-xs font-bold"
                    :class="item.status === 'lunas'
                      ? 'bg-emerald-100 text-emerald-600'
                      : 'bg-rose-100 text-rose-600'"
                  >
                    {{ item.status === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                  </span>
                </td>
                <td class="px-6 py-4 font-semibold text-slate-800">{{ formatRupiah(item.jumlah) }}</td>
                <td class="px-6 py-4 text-right">
                  <button
                    v-if="item.status !== 'lunas'"
                    @click="terimaTagihan(item)"
                    :disabled="bayarId === 'tagihan-' + item.id"
                    class="rounded-xl bg-gradient-to-r from-sky-400 to-sky-500 px-4 py-2 text-xs font-bold text-white shadow-md shadow-sky-200 transition hover:from-sky-500 hover:to-sky-600 disabled:opacity-50"
                  >
                    {{ bayarId === 'tagihan-' + item.id ? 'Memproses...' : 'Terima' }}
                  </button>
                  <span v-else class="text-slate-300">-</span>
                </td>
              </tr>
              <tr v-if="!tagihanList.length">
                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada tagihan member.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'petugas',
});

const { $api } = useNuxtApp();

const loading = ref(true);
const activeTab = ref('non-member');
const bayarId = ref(null);

const tikets = ref([]);
const tagihan = ref([]);

const stats = computed(() => {
  const nonMember = tikets.value.filter((t) => t.jenis !== 'member');
  const belumBayar = nonMember.filter((t) => t.status === 'masuk').length;
  const sudahBayar = nonMember.filter((t) => t.status === 'keluar').length;
  const totalPendapatan = nonMember
    .filter((t) => t.status === 'keluar')
    .reduce((sum, t) => sum + Number(t.total_bayar || 0), 0);

  return { belumBayar, sudahBayar, totalPendapatan };
});

const nonMemberList = computed(() => tikets.value.filter((t) => t.jenis !== 'member'));

const tagihanList = computed(() => tagihan.value);

function isBelumBayar(item) {
  return item.status === 'masuk';
}

const TARIF_PER_JAM = 3000;

function estimasiBiaya(item) {
  const masuk = new Date(item.waktu_masuk);
  const sekarang = new Date();
  if (isNaN(masuk.getTime())) return 0;
  const menit = Math.max(0, (sekarang - masuk) / 60000);
  const jam = Math.max(1, Math.ceil(menit / 60));
  return jam * TARIF_PER_JAM;
}

async function prosesBayar(item) {
  if (confirm(`Proses pembayaran ${item.kode_tiket || 'tiket ini'}?`)) {
    bayarId.value = item.id;
    try {
      await $api.post('/keluar/bayar', { tiket_id: item.id });
      await muatData();
    } catch (error) {
      const pesan =
        error?.response?.data?.message ||
        error?.data?.message ||
        'Gagal memproses pembayaran.';
      alert(pesan);
    } finally {
      bayarId.value = null;
    }
  }
}

async function terimaTagihan(item) {
  if (confirm(`Terima pembayaran tagihan ${item.member?.nama || ''}?`)) {
    bayarId.value = 'tagihan-' + item.id;
    try {
      await $api.post(`/tagihan/${item.id}/bayar`);
      await muatData();
    } catch (error) {
      const pesan =
        error?.response?.data?.message ||
        error?.data?.message ||
        'Gagal memproses pembayaran tagihan.';
      alert(pesan);
    } finally {
      bayarId.value = null;
    }
  }
}

function formatTanggal(value) {
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

function formatRupiah(val) {
  return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
}

async function muatData() {
  loading.value = true;
  try {
    const [resTiket, resTagihan] = await Promise.all([
      $api.get('/tiket'),
      $api.get('/tagihan'),
    ]);

    const dataTiket = Array.isArray(resTiket.data) ? resTiket.data : resTiket.data?.items || [];
    const dataTagihan = Array.isArray(resTagihan.data) ? resTagihan.data : resTagihan.data?.items || [];

    tikets.value = dataTiket;
    tagihan.value = dataTagihan;
  } catch (error) {
    console.error('Gagal memuat transaksi:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(muatData);
</script>