<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { watch } from "vue";

const form = useForm({
  title: "",
  rechnungNr: "",
  kundenNr: "",
  datum: null,
  monat: "",
  dienstleistungDatum: "",
  stunden: 0,
  stundenlohn: 0,
  summe: 0,
  zzglMwst: 19,
  gesamtbetrag: 0,
  verwendungszweck: "",
});

const store = async () => {
  try {
    const response = await axios.post(route("generate.pdf"), form, {
      responseType: "blob",
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "Rechnung.pdf");
    document.body.appendChild(link);
    link.click();
    form.reset();
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};

// Watch changes in rechnungNr and update verwendungszweck
watch(
  () => [form.rechnungNr],
  ([newRechnungNr]) => {
    form.verwendungszweck = newRechnungNr;
  }
);

// Watch changes in stunden and stundenlohn and update summe
watch(
  () => [form.stunden, form.stundenlohn],
  ([newStunden, newStundenlohn]) => {
    form.summe = (newStunden * newStundenlohn).toFixed(2);
  }
);

// Watch changes in summe and zzglMwst and update gesamtbetrag
watch(
  () => [form.summe, form.zzglMwst],
  ([newSumme, newMwst]) => {
    const summeValue = parseFloat(newSumme) || 0;
    const mwstValue = parseFloat(newMwst) || 0;
    form.gesamtbetrag = (summeValue + (summeValue * mwstValue) / 100).toFixed(
      2
    );
  }
);
</script>

<template>
  <Head>
    <title>Zana PDF Generator</title>
  </Head>
  <main class="min-h-screen p-2 bg-gray-100 overflow-x-hidden">
    <h1 class="text-2xl font-bold underline">Zana PDF Generator</h1>

    <form @submit.prevent="store">
      <section>
        <label for="title" class="block text-sm font-medium text-gray-700"
          >Title</label
        >
        <textarea
          id="title"
          v-model="form.title"
          placeholder="Enter title"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        ></textarea>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.title" class="text-red-600 text-sm">
            {{ form.errors.title }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="rechnung-nr" class="block text-sm font-medium text-gray-700"
          >Rechnung Nr</label
        >
        <input
          id="rechnung-nr"
          v-model="form.rechnungNr"
          type="text"
          placeholder="Enter Rechnung Nr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.rechnungNr" class="text-red-600 text-sm">
            {{ form.errors.rechnungNr }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="kunden-nr" class="block text-sm font-medium text-gray-700"
          >Kunden Nr</label
        >
        <input
          id="kunden-nr"
          v-model="form.kundenNr"
          type="text"
          placeholder="Enter Kunden Nr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.kundenNr" class="text-red-600 text-sm">
            {{ form.errors.kundenNr }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="datum" class="block text-sm font-medium text-gray-700"
          >Datum</label
        >
        <input
          id="datum"
          v-model="form.datum"
          type="date"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.datum" class="text-red-600 text-sm">
            {{ form.errors.datum }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="monat" class="block text-sm font-medium text-gray-700"
          >Monat</label
        >
        <input
          id="monat"
          v-model="form.monat"
          type="text"
          placeholder="Enter Monat"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.monat" class="text-red-600 text-sm">
            {{ form.errors.monat }}
          </p>
        </Transition>
      </section>

      <section>
        <label
          for="dienstleistung-datum"
          class="block text-sm font-medium text-gray-700"
          >Dienstleistung Datum</label
        >
        <textarea
          id="dienstleistung-datum"
          v-model="form.dienstleistungDatum"
          placeholder="Enter Dienstleistung / Datum"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        ></textarea>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.errors.dienstleistungDatum"
            class="text-red-600 text-sm"
          >
            {{ form.errors.dienstleistungDatum }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="stunden" class="block text-sm font-medium text-gray-700"
          >Stunden</label
        >
        <input
          id="stunden"
          v-model="form.stunden"
          type="number"
          placeholder="Enter Stunden"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.stunden" class="text-red-600 text-sm">
            {{ form.errors.stunden }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="stundenlohn" class="block text-sm font-medium text-gray-700"
          >Stundenlohn</label
        >
        <input
          id="stundenlohn"
          v-model="form.stundenlohn"
          type="number"
          placeholder="Enter Stundenlohn"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.stundenlohn" class="text-red-600 text-sm">
            {{ form.errors.stundenlohn }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="summe" class="block text-sm font-medium text-gray-700"
          >Summe</label
        >
        <input
          id="summe"
          v-model="form.summe"
          type="number"
          placeholder="Enter Summe"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.summe" class="text-red-600 text-sm">
            {{ form.errors.summe }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="zzgl-mwst" class="block text-sm font-medium text-gray-700"
          >Zzgl. MwSt</label
        >
        <div class="flex items-center">
          <input
            id="zzgl-mwst"
            v-model="form.zzglMwst"
            type="number"
            placeholder="Enter Zzgl. MwSt"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
          <span class="ml-2">%</span>
        </div>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.zzglMwst" class="text-red-600 text-sm">
            {{ form.errors.zzglMwst }}
          </p>
        </Transition>
      </section>

      <section>
        <label
          for="gesamtbetrag"
          class="block text-sm font-medium text-gray-700"
          >Gesamtbetrag</label
        >
        <input
          id="gesamtbetrag"
          v-model="form.gesamtbetrag"
          type="number"
          readonly
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </section>

      <section>
        <label
          for="verwendungszweck"
          class="block text-sm font-medium text-gray-700"
          >Verwendungszweck</label
        >
        <input
          id="verwendungszweck"
          v-model="form.verwendungszweck"
          type="text"
          placeholder="Enter Verwendungszweck"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.verwendungszweck" class="text-red-600 text-sm">
            {{ form.errors.verwendungszweck }}
          </p>
        </Transition>
      </section>

      <button
        type="submit"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Generate PDF
      </button>
    </form>
    <Link
      :href="route('pdf.data.index')"
      class="underline float-right rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      All Data
    </Link>
  </main>
</template>
