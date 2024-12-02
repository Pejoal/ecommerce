<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { watch } from "vue";

const form = useForm({
  title: "",
  RechnungNr: "",
  KundenNr: "",
  Datum: null,
  Monat: "",
  DienstleistungDatum: "",
  Stunden: 0,
  Stundenlohn: 0,
  Summe: 0,
  ZzglMwSt: 19,
  Gesamtbetrag: 0,
  Verwendungszweck: "",
});

const store = async () => {
  try {
    const response = await axios.post(route("generate.pdf"), form, {
      responseType: "blob",
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "document.pdf");
    document.body.appendChild(link);
    link.click();
    form.reset();
  } catch (error) {
    console.error("Error generating PDF:", error);
  }
};

// Watch changes in Stunden and Stundenlohn and update Summe
watch(
  () => [form.RechnungNr],
  ([newRechnungNr]) => {
    form.Verwendungszweck = newRechnungNr;
  }
);

// Watch changes in Stunden and Stundenlohn and update Summe
watch(
  () => [form.Stunden, form.Stundenlohn],
  ([newStunden, newStundenlohn]) => {
    form.Summe = (newStunden * newStundenlohn).toFixed(2);
  }
);

// Watch changes in Summe and ZzglMwSt and update Gesamtbetrag
watch(
  () => [form.Summe, form["ZzglMwSt"]],
  ([newSumme, newMwst]) => {
    const summeValue = parseFloat(newSumme) || 0;
    const mwstValue = parseFloat(newMwst) || 0;
    form.Gesamtbetrag = (summeValue + (summeValue * mwstValue) / 100).toFixed(
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
        <input
          id="title"
          v-model="form.title"
          type="text"
          placeholder="Enter title"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
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
          >RechnungNr</label
        >
        <input
          id="rechnung-nr"
          v-model="form['RechnungNr']"
          type="text"
          placeholder="Enter RechnungNr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors['RechnungNr']" class="text-red-600 text-sm">
            {{ form.errors["RechnungNr"] }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="kunden-nr" class="block text-sm font-medium text-gray-700"
          >KundenNr</label
        >
        <input
          id="kunden-nr"
          v-model="form['KundenNr']"
          type="text"
          placeholder="Enter KundenNr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors['KundenNr']" class="text-red-600 text-sm">
            {{ form.errors["KundenNr"] }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="datum" class="block text-sm font-medium text-gray-700"
          >Datum</label
        >
        <input
          id="datum"
          v-model="form.Datum"
          type="date"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Datum" class="text-red-600 text-sm">
            {{ form.errors.Datum }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="monat" class="block text-sm font-medium text-gray-700"
          >Monat</label
        >
        <input
          id="monat"
          v-model="form.Monat"
          type="text"
          placeholder="Enter Monat"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Monat" class="text-red-600 text-sm">
            {{ form.errors.Monat }}
          </p>
        </Transition>
      </section>

      <section>
        <label
          for="dienstleistung-datum"
          class="block text-sm font-medium text-gray-700"
          >DienstleistungDatum</label
        >
        <input
          id="dienstleistung-datum"
          v-model="form['DienstleistungDatum']"
          type="text"
          placeholder="Enter DienstleistungDatum"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.errors['DienstleistungDatum']"
            class="text-red-600 text-sm"
          >
            {{ form.errors["DienstleistungDatum"] }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="stunden" class="block text-sm font-medium text-gray-700"
          >Stunden</label
        >
        <input
          id="stunden"
          v-model="form.Stunden"
          type="number"
          placeholder="Enter Stunden"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Stunden" class="text-red-600 text-sm">
            {{ form.errors.Stunden }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="stundenlohn" class="block text-sm font-medium text-gray-700"
          >Stundenlohn</label
        >
        <input
          id="stundenlohn"
          v-model="form.Stundenlohn"
          type="number"
          placeholder="Enter Stundenlohn"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Stundenlohn" class="text-red-600 text-sm">
            {{ form.errors.Stundenlohn }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="summe" class="block text-sm font-medium text-gray-700"
          >Summe</label
        >
        <input
          id="summe"
          v-model="form.Summe"
          type="number"
          placeholder="Enter Summe"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Summe" class="text-red-600 text-sm">
            {{ form.errors.Summe }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="zzgl-mwst" class="block text-sm font-medium text-gray-700"
          >ZzglMwSt</label
        >
        <div class="flex items-center">
          <input
            id="zzgl-mwst"
            v-model="form['ZzglMwSt']"
            type="number"
            placeholder="Enter ZzglMwSt"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
          <span class="ml-2">%</span>
        </div>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors['ZzglMwSt']" class="text-red-600 text-sm">
            {{ form.errors["ZzglMwSt"] }}
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
          v-model="form.Gesamtbetrag"
          type="text"
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
          v-model="form['Verwendungszweck']"
          type="text"
          placeholder="Enter Verwendungszweck"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors.Verwendungszweck" class="text-red-600 text-sm">
            {{ form.errors.Verwendungszweck }}
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
  </main>
</template>
