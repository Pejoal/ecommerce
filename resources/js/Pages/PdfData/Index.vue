<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
  pdfData: Array,
});
</script>

<template>
  <Head>
    <title>PDF Data</title>
  </Head>
  <main class="min-h-screen p-4 bg-gray-100 overflow-x-auto">
    <h1 class="text-2xl font-bold underline mb-4">PDF Data</h1>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-50">
          <tr>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Title
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Rechnung Nr
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Kunden Nr
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Datum
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Monat
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Dienstleistung Datum
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Stunden
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Stundenlohn
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Summe
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Zzgl. MwSt
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Gesamtbetrag
            </th>
            <th
              class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Verwendungszweck
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="data in pdfData" :key="data.id">
            <td class="px-4 py-2 whitespace-nowrap">{{ data.title }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.rechnungNr }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.kundenNr }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.datum }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.monat }}</td>
            <td class="px-4 py-2 whitespace-nowrap">
              {{ data.dienstleistungDatum }}
            </td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.stunden }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.stundenlohn }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.summe }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.zzglMwst }}</td>
            <td class="px-4 py-2 whitespace-nowrap">{{ data.gesamtbetrag }}</td>
            <td class="px-4 py-2 whitespace-nowrap">
              {{ data.verwendungszweck }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Link
      :href="route('zana')"
      class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Generate PDF
    </Link>
  </main>
</template>
