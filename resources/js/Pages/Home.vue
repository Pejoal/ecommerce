<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps } from "vue"; // Import defineProps function
import { ref, computed } from "vue";

const query = ref("");
const isOpen = ref(false);
const selectedItems = ref([]);
const suggestions = ref([
  "Apple",
  "Banana",
  "Orange",
  "Mango",
  "Pineapple",
  "Grapes",
]);

const filteredSuggestions = computed(() => {
  return suggestions.value
    .filter((item) => item.toLowerCase().includes(query.value.toLowerCase()))
    .filter((item) => !selectedItems.value.includes(item)); // Exclude selected items
});

const onInput = () => {
  if (query.value.length > 0) {
    openDropdown();
  } else {
    closeDropdown();
  }
};

const openDropdown = () => {
  isOpen.value = true;
};

const closeDropdown = () => {
  setTimeout(() => {
    // Delay to allow click events to register
    isOpen.value = false;
  }, 200);
};

const toggleSelection = (item) => {
  if (selectedItems.value.includes(item)) {
    removeItem(item);
  } else {
    selectedItems.value.push(item);
    query.value = "";
    closeDropdown();
  }
};

const removeItem = (item) => {
  selectedItems.value = selectedItems.value.filter((i) => i !== item);
};

const isSelected = (item) => {
  return selectedItems.value.includes(item);
};

const props = defineProps({
  brands: {
    type: Array,
    default: [],
  },
  categories: {
    type: Array,
    default: [],
  },
});
</script>

<template>
  <Head>
    <title>{{ trans("words.home") }}</title>
  </Head>
  <AuthLayout>
    <main class="grid grid-cols-1 md:grid-cols-3">
      <section class="bg-blue-50 p-2">
        <h2 class="text-xl font-bold underline">Filters</h2>

        <div class="relative">
          <!-- Input Field -->
          <div
            class="flex flex-wrap items-center gap-2 p-2 border rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500"
            @click="openDropdown"
          >
            <template v-for="(item, index) in selectedItems">
              <span
                :key="index"
                class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full flex items-center"
              >
                {{ item }}
                <button
                  type="button"
                  class="ml-2 text-gray-600 hover:text-gray-800"
                  @click.stop="removeItem(item)"
                >
                  &times;
                </button>
              </span>
            </template>
            <input
              v-model="query"
              @input="onInput"
              @blur="closeDropdown"
              type="text"
              placeholder="Search..."
              class="w-full border-none outline-none bg-transparent"
            />
          </div>

          <!-- Dropdown Menu -->
          <div
            v-if="isOpen && filteredSuggestions.length > 0"
            class="absolute w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
          >
            <ul class="max-h-60 overflow-y-auto">
              <li
                v-for="(item, index) in filteredSuggestions"
                :key="index"
                @mousedown.prevent="toggleSelection(item)"
                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
              >
                <span v-if="isSelected(item)" class="font-semibold">{{
                  item
                }}</span>
                <span v-else>{{ item }}</span>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <section class="col-span-2 p-2">Products</section>
    </main>
  </AuthLayout>
</template>
