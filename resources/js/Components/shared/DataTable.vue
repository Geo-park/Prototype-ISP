<template>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th v-for="col in columns" :key="col.key" class="p-4 font-semibold text-gray-600">
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, i) in data" :key="i" class="border-b hover:bg-gray-50">
                    <td v-for="col in columns" :key="col.key" class="p-4">
                        <slot :name="col.key" :row="row">
                            {{ row[col.key] }}
                        </slot>
                    </td>
                </tr>
                <tr v-if="data.length === 0">
                    <td :colspan="columns.length" class="p-4 text-center text-gray-400">
                        Tidak ada data
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
defineProps({
    columns: Array,
    data: Array,
})
</script>
