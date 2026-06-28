import { defineStore } from 'pinia';
import api from '@/bootstrap/api';

export const usePetaStore = defineStore('peta', {
  state: () => ({
    popOlt: [],
    odc: [],
    odp: [],
    pelanggan: [],
    loading: false,
    error: null,

    // Filter state
    filters: {
      showPopOlt: true,
      showOdc: true,
      showOdp: true,
      showPelanggan: true,
      statusKoneksi: 'semua', // 'semua' | 'aktif' | 'nonaktif'
      statusBayar: 'semua',   // 'semua' | 'lunas' | 'belum'
    },
  }),

  getters: {
    filteredPelanggan: (state) => {
      return state.pelanggan.filter((p) => {
        if (state.filters.statusKoneksi !== 'semua' && p.status_koneksi !== state.filters.statusKoneksi) {
          return false;
        }
        if (state.filters.statusBayar !== 'semua' && p.status_pembayaran !== state.filters.statusBayar) {
          return false;
        }
        return true;
      });
    },
  },

  actions: {
    async fetchSemuaData() {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.get('/peta/semua');
        const data = response.data;
        this.popOlt = data.pop_olt ?? [];
        this.odc = data.odc ?? [];
        this.odp = data.odp ?? [];
        this.pelanggan = data.pelanggan ?? [];
      } catch (err) {
        this.error = err.response?.data?.message ?? 'Gagal memuat data peta';
        console.error('[PetaStore] fetchSemuaData error:', err);
      } finally {
        this.loading = false;
      }
    },

    async fetchPopOlt() {
      try {
        const response = await api.get('/peta/pop-olt');
        this.popOlt = response.data;
      } catch (err) {
        console.error('[PetaStore] fetchPopOlt error:', err);
      }
    },

    async fetchOdc() {
      try {
        const response = await api.get('/peta/odc');
        this.odc = response.data;
      } catch (err) {
        console.error('[PetaStore] fetchOdc error:', err);
      }
    },

    async fetchOdp() {
      try {
        const response = await api.get('/peta/odp');
        this.odp = response.data;
      } catch (err) {
        console.error('[PetaStore] fetchOdp error:', err);
      }
    },

    async fetchPelanggan() {
      try {
        const response = await api.get('/peta/pelanggan');
        this.pelanggan = response.data;
      } catch (err) {
        console.error('[PetaStore] fetchPelanggan error:', err);
      }
    },

    setFilter(key, value) {
      this.filters[key] = value;
    },

    toggleLayer(layer) {
      this.filters[layer] = !this.filters[layer];
    },
  },
});
