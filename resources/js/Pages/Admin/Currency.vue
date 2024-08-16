<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import ResuableModal from "@/Components/ResuableModal.vue";
import axios from "axios";

const props = defineProps({
  currencies: {
    type: Array,
    default: [],
  },
});

let showModal = ref(false);

const form = useForm({
  id: null,
  name: "",
  code: "",
  symbol: "",
});

const massForm = useForm({
  selectedCurrencies: [],
});

const toggleSelection = (currencyId) => {
  const index = massForm.selectedCurrencies.indexOf(currencyId);
  if (index > -1) {
    massForm.selectedCurrencies.splice(index, 1);
  } else {
    massForm.selectedCurrencies.push(currencyId);
  }
};

const isSelected = (currencyId) =>
  massForm.selectedCurrencies.includes(currencyId);

const deleteCurrency = (currencyId) => {
  if (confirm("Are you sure you want to delete this currency?")) {
    form.delete(route("admin.currencies.destroy", currencyId), {
      onSuccess: () => {
        massForm.selectedCurrencies = massForm.selectedCurrencies.filter(
          (id) => id !== currencyId
        );
      },
    });
  }
};

const massDeleteCurrencys = () => {
  if (confirm("Are you sure you want to delete the selected currencies?")) {
    massForm.delete(route("admin.currencies.massDestroy"), {
      onSuccess: () => {
        massForm.selectedCurrencies.value = [];
      },
    });
  }
};

const storeCurrency = () => {
  form.post(route("admin.currencies.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const updateCurrency = () => {
  form.patch(route("admin.currencies.update", form.id), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editCurrency = (currencyId) => {
  axios
    .get(route("admin.currencies.edit", currencyId))
    .then((response) => {
      form.id = response.data.id;
      form.name = response.data.name;
      form.code = response.data.code;
      form.symbol = response.data.symbol;

      showModal.value = true;
    })
    .catch((error) => console.error(error));
};

const addCurrency = () => {
  showModal.value = true;
  form.reset();
};
</script>

<template>
  <Head :title="'Currencys Dashboard'" />

  <AuthLayout>
    <div class="my-2 flex items-center justify-center space-x-2">
      <button class="btn btn-primary" @click="addCurrency">Add Currency</button>
      <button
        @click="massDeleteCurrencys"
        class="btn btn-danger"
        :disabled="massForm.selectedCurrencies.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <!-- Create/Edit Currency Modal -->
    <ResuableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-full']"
      :header="form.id ? 'Edit Currency' : 'Create Currency'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="storeCurrency" class="mx-2">
          <section class="space-y-4">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Name</label
              >
              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter name"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.name" class="text-red-600 text-sm">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Code -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Code</label
              >
              <input
                id="code"
                v-model="form.code"
                type="text"
                placeholder="Enter code"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.code" class="text-red-600 text-sm">
                {{ form.errors.code }}
              </p>
            </div>

            <!-- Symbol -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Symbol</label
              >
              <input
                id="symbol"
                v-model="form.symbol"
                type="text"
                placeholder="Enter symbol"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.symbol" class="text-red-600 text-sm">
                {{ form.errors.symbol }}
              </p>
            </div>

            <div class="flex justify-end">
              <button
                v-if="form.id"
                form="updateCurrency"
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Update Currency
              </button>
              <button
                v-else
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Create Currency
              </button>
            </div>
          </section>
        </form>
        <form
          @submit.prevent="updateCurrency"
          id="updateCurrency"
          class="hidden"
        ></form>
      </template>
    </ResuableModal>

    <!-- Currency Table -->
    <div class="flex items-center justify-center min-w-full overflow-x-auto">
      <table
        class="overflow-auto min-w-full bg-white border border-gray-200 rounded-lg"
      >
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (massForm.selectedCurrencies = e.target.checked
                      ? props.currencies.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Name</th>
            <th class="p-1 text-left font-semibold">Code</th>
            <th class="p-1 text-left font-semibold">Symbol</th>
            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="currency in props.currencies"
            :key="currency.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-1">
              <input
                type="checkbox"
                :value="currency.id"
                @change="toggleSelection(currency.id)"
                :checked="isSelected(currency.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-1">{{ currency.id }}</td>
            <td class="p-1">{{ currency.name }}</td>
            <td class="p-1">{{ currency.code }}</td>
            <td class="p-1">{{ currency.symbol }}</td>
            <td class="p-1 flex space-x-2">
              <button
                @click="editCurrency(currency.id)"
                class="btn btn-success"
              >
                Edit
              </button>
              <button
                @click="deleteCurrency(currency.id)"
                class="btn btn-danger"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthLayout>
</template>

<style scoped></style>
