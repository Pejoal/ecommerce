// resources/js/Pages/PdfData/Index.vue
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
  <main class="min-h-screen p-2 bg-gray-100 overflow-x-hidden">
    <h1 class="text-2xl font-bold underline">PDF Data</h1>
    <table class="min-w-full divide-y divide-gray-200">
      <thead>
        <tr>
          <th>Title</th>
          <th>Rechnung Nr</th>
          <th>Kunden Nr</th>
          <th>Datum</th>
          <th>Monat</th>
          <th>Dienstleistung Datum</th>
          <th>Stunden</th>
          <th>Stundenlohn</th>
          <th>Summe</th>
          <th>Zzgl. MwSt</th>
          <th>Gesamtbetrag</th>
          <th>Verwendungszweck</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in pdfData" :key="data.id">
          <td>{{ data.title }}</td>
          <td>{{ data.rechnungNr }}</td>
          <td>{{ data.kundenNr }}</td>
          <td>{{ data.datum }}</td>
          <td>{{ data.monat }}</td>
          <td>{{ data.dienstleistungDatum }}</td>
          <td>{{ data.stunden }}</td>
          <td>{{ data.stundenlohn }}</td>
          <td>{{ data.summe }}</td>
          <td>{{ data.zzglMwst }}</td>
          <td>{{ data.gesamtbetrag }}</td>
          <td>{{ data.verwendungszweck }}</td>
        </tr>
      </tbody>
    </table>
    <Link
      :href="route('zana')"
      class="underline float-right rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Generate PDF
    </Link>
  </main>
</template>
