<template>
  <div>
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">Kelola Petugas</h1>
        <p class="mt-1.5 text-sm text-slate-500">Kelola akun petugas parkir</p>
      </div>
      <div class="flex gap-2">
        <NuxtLink
          to="/admin/dashboard"
          class="rounded-xl border border-slate-100 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:text-sky-600"
        >
          ← Dashboard
        </NuxtLink>
        <NuxtLink
          to="/admin/users/tambah"
          class="rounded-xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:brightness-110"
        >
          + Tambah Petugas
        </NuxtLink>
      </div>
    </div>

    <div v-if="loading" class="rounded-2xl border border-slate-100 bg-white py-16 text-center text-sm text-slate-400 shadow-sm">
      Memuat data...
    </div>

    <div v-else-if="users.length === 0" class="rounded-2xl border border-slate-100 bg-white p-12 text-center shadow-sm">
      <div class="text-4xl mb-4">👥</div>
      <p class="text-slate-500">Belum ada petugas terdaftar</p>
    </div>

    <div v-else class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left">
          <tr>
            <th class="px-6 py-3 font-semibold text-slate-600">No</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Nama</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Email</th>
            <th class="px-6 py-3 font-semibold text-slate-600">No. HP</th>
            <th class="px-6 py-3 font-semibold text-slate-600">Role</th>
            <th class="px-6 py-3 font-semibold text-slate-600 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="(user, index) in users" :key="user.id" class="transition hover:bg-sky-50/50">
            <td class="px-6 py-4 text-slate-500">{{ index + 1 }}</td>
            <td class="px-6 py-4 font-medium text-slate-800">{{ user.name }}</td>
            <td class="px-6 py-4 text-slate-600">{{ user.email }}</td>
            <td class="px-6 py-4 text-slate-600">{{ user.no_hp || '-' }}</td>
            <td class="px-6 py-4">
              <span class="inline-block rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <NuxtLink
                  :to="`/admin/users/edit/${user.id}`"
                  class="rounded-lg bg-sky-100 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-200"
                >
                  Edit
                </NuxtLink>
                <button
                  @click="hapus(user)"
                  class="rounded-lg bg-pink-100 px-3 py-1.5 text-xs font-semibold text-pink-700 transition hover:bg-pink-200"
                >
                  Hapus
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      v-if="showConfirm"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
      <div class="mx-4 w-full max-w-sm rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl">
        <h3 class="mb-2 text-lg font-bold text-slate-800">Konfirmasi Hapus</h3>
        <p class="mb-6 text-sm text-slate-500">
          Apakah Anda yakin ingin menghapus petugas <strong class="text-slate-800">{{ userToDelete?.name }}</strong>?
        </p>
        <div class="flex justify-end gap-2">
          <button
            @click="showConfirm = false"
            class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200"
          >
            Batal
          </button>
          <button
            @click="konfirmasiHapus"
            :disabled="deleting"
            class="rounded-xl bg-gradient-to-r from-pink-400 to-rose-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-pink-200 transition hover:brightness-110 disabled:opacity-60"
          >
            {{ deleting ? 'Menghapus...' : 'Hapus' }}
          </button>
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

const users = ref([]);
const loading = ref(true);
const showConfirm = ref(false);
const userToDelete = ref(null);
const deleting = ref(false);

async function loadUsers() {
  loading.value = true;
  try {
    const res = await $api.get('/users');
    users.value = res.data.data;
  } catch (e) {
    console.error('Gagal memuat users:', e);
  } finally {
    loading.value = false;
  }
}

function hapus(user) {
  userToDelete.value = user;
  showConfirm.value = true;
}

async function konfirmasiHapus() {
  deleting.value = true;
  try {
    await $api.delete(`/users/${userToDelete.value.id}`);
    showConfirm.value = false;
    userToDelete.value = null;
    await loadUsers();
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal menghapus petugas');
  } finally {
    deleting.value = false;
  }
}

onMounted(loadUsers);
</script>