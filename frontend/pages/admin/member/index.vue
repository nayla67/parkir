<template>
  <div>
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">Kelola Member</h1>
        <p class="mt-1.5 text-sm text-slate-500">Data member parkir terdaftar</p>
      </div>
      <div class="flex gap-2">
        <NuxtLink
          to="/admin/dashboard"
          class="rounded-xl border border-slate-100 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:text-sky-600"
        >
          ← Dashboard
        </NuxtLink>
        <NuxtLink
          to="/petugas/member/tambah"
          class="rounded-xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:brightness-110"
        >
          + Tambah Member
        </NuxtLink>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left">
          <tr>
            <th class="px-6 py-3 font-semibold text-slate-600">Kode</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Nama Member</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Perusahaan</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Harga</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Status</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Expired</th>
            <th class="px-6 py-3 font-semibold text-slate-600 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-if="loading">
            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400">
              Memuat data member...
            </td>
          </tr>

          <tr v-else-if="errorMessage">
            <td colspan="7" class="px-6 py-12 text-center">
              <p class="text-sm font-semibold text-rose-500">{{ errorMessage }}</p>
              <button
                @click="ambilMember"
                class="mt-4 rounded-xl bg-gradient-to-r from-amber-400 via-rose-400 to-purple-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-rose-200 transition hover:brightness-110"
              >
                Coba Lagi
              </button>
            </td>
          </tr>

          <tr v-else-if="members.length === 0">
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-lg">👤</div>
              <p class="text-sm text-slate-400">Belum ada data member.</p>
            </td>
          </tr>

          <tr v-else v-for="(member, index) in members" :key="member.id" class="transition hover:bg-purple-50/40">
            <td class="px-6 py-4 font-semibold text-slate-800">{{ member.kode || '-' }}</td>
            <td class="px-6 py-4 font-medium text-slate-700">{{ member.nama || '-' }}</td>
            <td class="px-6 py-4 text-slate-500">{{ member.perusahaan || '-' }}</td>
            <td class="px-6 py-4 font-bold text-purple-600">Rp {{ formatHarga(member.harga) }}</td>
            <td class="px-6 py-4">
              <span
                class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                :class="member.status === 'lunas'
                  ? 'bg-emerald-100 text-emerald-600'
                  : 'bg-rose-100 text-rose-600'"
              >
                {{ (member.status || 'belum').toUpperCase() }}
              </span>
            </td>
            <td class="px-6 py-4 text-slate-500">{{ formatTanggal(member.expired_at) }}</td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <NuxtLink
                  :to="`/petugas/member/edit/${member.id}`"
                  class="rounded-lg bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-200"
                >
                  Edit
                </NuxtLink>
                <NuxtLink
                  :to="`/petugas/member/kartu/${member.id}`"
                  class="rounded-lg bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-700 transition hover:bg-purple-200"
                >
                  Kartu
                </NuxtLink>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
});

const { $api } = useNuxtApp();

const members = ref([]);
const loading = ref(true);
const errorMessage = ref('');

function formatHarga(harga) {
  return Number(harga || 0).toLocaleString('id-ID');
}

function formatTanggal(tanggal) {
  if (!tanggal) return '-';
  const date = new Date(tanggal);
  if (isNaN(date.getTime())) return '-';
  return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

async function ambilMember() {
  loading.value = true;
  errorMessage.value = '';
  try {
    const res = await $api.get('/members');
    const data = res?.data;
    if (Array.isArray(data)) {
      members.value = data;
    } else if (Array.isArray(data?.data)) {
      members.value = data.data;
    } else if (Array.isArray(data?.members)) {
      members.value = data.members;
    } else {
      members.value = [];
    }
  } catch (e) {
    console.error('Gagal mengambil member:', e);
    errorMessage.value = e?.response?.data?.message || e?.message || 'Gagal mengambil data member.';
  } finally {
    loading.value = false;
  }
}

onMounted(ambilMember);
</script>
