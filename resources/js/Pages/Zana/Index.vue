<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const form = useForm({
  title: "",
  "Rechnung Nr": "",
  "Kunden Nr": "",
  Datum: null,
  Monat: "",
  "Dienstleistung / Datum": "",
  Stunden: "",
  Stundenlohn: "",
  Summe: "",
  "Zzgl. MwSt": "19",
  Gesamtbetrag: "",
  Verwendungszweck: "",
});

const store = () => {
  alert("Soon...");
  // form.post(route(""), {
  //   onSuccess: () => {
  //     form.reset();
  //   },
  // });
};

const gesamtbetrag = computed(() => {
  const summe = parseFloat(form.Summe) || 0;
  const mwst = parseFloat(form["Zzgl. MwSt"]) || 0;
  return (summe + (summe * mwst) / 100).toFixed(2);
});
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
          >Rechnung Nr</label
        >
        <input
          id="rechnung-nr"
          v-model="form['Rechnung Nr']"
          type="text"
          placeholder="Enter Rechnung Nr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors['Rechnung Nr']" class="text-red-600 text-sm">
            {{ form.errors["Rechnung Nr"] }}
          </p>
        </Transition>
      </section>

      <section>
        <label for="kunden-nr" class="block text-sm font-medium text-gray-700"
          >Kunden Nr</label
        >
        <input
          id="kunden-nr"
          v-model="form['Kunden Nr']"
          type="text"
          placeholder="Enter Kunden Nr"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.errors['Kunden Nr']" class="text-red-600 text-sm">
            {{ form.errors["Kunden Nr"] }}
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
          >Dienstleistung / Datum</label
        >
        <input
          id="dienstleistung-datum"
          v-model="form['Dienstleistung / Datum']"
          type="text"
          placeholder="Enter Dienstleistung / Datum"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p
            v-if="form.errors['Dienstleistung / Datum']"
            class="text-red-600 text-sm"
          >
            {{ form.errors["Dienstleistung / Datum"] }}
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
          >Zzgl. MwSt</label
        >
        <div class="flex items-center">
          <input
            id="zzgl-mwst"
            v-model="form['Zzgl. MwSt']"
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
          <p v-if="form.errors['Zzgl. MwSt']" class="text-red-600 text-sm">
            {{ form.errors["Zzgl. MwSt"] }}
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
          v-model="gesamtbetrag"
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
          v-model="form.Verwendungszweck"
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
