<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineProps, watch } from "vue";
import { ref, computed } from "vue";

const props = defineProps({
  brands: {
    type: Array,
    default: [],
  },
  categories: {
    type: Array,
    default: [],
  },
  products: {
    type: Array,
    default: [],
  },
});

const query = ref("");
const isBrandsDropdownOpen = ref(false);
const isCategoriesDropdownOpen = ref(false);
const selectedBrands = ref([]);
const selectedCategories = ref([]);
const filteredProducts = ref([]); 

const filteredBrands = computed(() => {
  return props.brands
    .filter((brand) =>
      brand.name.toLowerCase().includes(query.value.toLowerCase())
    )
    .filter(
      (brand) =>
        !selectedBrands.value.some((selected) => selected.id === brand.id)
    );
});

const filteredCategories = computed(() => {
  return props.categories
    .filter((category) =>
      category.name.toLowerCase().includes(query.value.toLowerCase())
    )
    .filter(
      (category) =>
        !selectedCategories.value.some(
          (selected) => selected.id === category.id
        )
    );
});

const filterProducts = () => {
  filteredProducts.value = props.products.filter(
    (product) =>
      (selectedBrands.value.length === 0 ||
        selectedBrands.value.some((brand) => brand.id === product.brand_id)) &&
      (selectedCategories.value.length === 0 ||
        selectedCategories.value.some(
          (category) => category.id === product.category_id
        ))
  );
};

watch([selectedBrands, selectedCategories], filterProducts);

const toggleBrandSelection = (brand) => {
  if (selectedBrands.value.some((selected) => selected.id === brand.id)) {
    removeBrand(brand);
  } else {
    selectedBrands.value.push(brand);
    query.value = "";
    closeDropdown();
  }
};

const removeBrand = (brand) => {
  selectedBrands.value = selectedBrands.value.filter((b) => b.id !== brand.id);
};

const toggleCategorySelection = (category) => {
  if (
    selectedCategories.value.some((selected) => selected.id === category.id)
  ) {
    removeCategory(category);
  } else {
    selectedCategories.value.push(category);
    query.value = "";
    closeDropdown();
  }
};

const removeCategory = (category) => {
  selectedCategories.value = selectedCategories.value.filter(
    (c) => c.id !== category.id
  );
};

const isBrandSelected = (brand) => {
  return selectedBrands.value.some((selected) => selected.id === brand.id);
};

const isCategorySelected = (category) => {
  return selectedCategories.value.some(
    (selected) => selected.id === category.id
  );
};

const openBrandsDropdown = () => {
  isBrandsDropdownOpen.value = true;
};

const openCategoriesDropdown = () => {
  isCategoriesDropdownOpen.value = true;
};

const closeBrandsDropdown = () => {
  setTimeout(() => {
    isBrandsDropdownOpen.value = false;
  }, 200);
};

const closeCategoriesDropdown = () => {
  setTimeout(() => {
    isCategoriesDropdownOpen.value = false;
  }, 200);
};

const onBrandInput = () => {
  if (query.value.length > 0) {
    openBrandsDropdown();
  } else {
    closeBrandsDropdown();
  }
};

const onCategoryInput = () => {
  if (query.value.length > 0) {
    openCategoriesDropdown();
  } else {
    closeCategoriesDropdown();
  }
};
</script>

<template>
  <Head>
    <title>{{ trans("words.home") }}</title>
  </Head>
  <AuthLayout>
    <main class="grid grid-cols-1 md:grid-cols-3">
      <section class="bg-blue-50 p-2">
        <h2 class="text-xl font-bold underline">Filters</h2>

        <!-- Brands Filter -->
        <div class="mb-4">
          <h3 class="text-lg font-semibold">Brands</h3>
          <div class="relative">
            <div
              class="flex flex-wrap items-center gap-2 p-2 border rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500"
              @click="openBrandsDropdown"
            >
              <template v-for="brand in selectedBrands" :key="brand.id">
                <span
                  class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full flex items-center"
                >
                  {{ brand.name }}
                  <button
                    type="button"
                    class="ml-2 text-gray-600 hover:text-gray-800"
                    @click.stop="removeBrand(brand)"
                  >
                    &times;
                  </button>
                </span>
              </template>
              <input
                v-model="query"
                @input="onBrandInput"
                @blur="closeBrandsDropdown"
                type="text"
                placeholder="Search brands..."
                class="w-full border-none outline-none bg-transparent"
              />
            </div>

            <div
              v-if="isBrandsDropdownOpen && filteredBrands.length > 0"
              class="absolute w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
            >
              <ul class="max-h-60 overflow-y-auto">
                <li
                  v-for="brand in filteredBrands"
                  :key="brand.id"
                  @mousedown.prevent="toggleBrandSelection(brand)"
                  class="px-4 py-2 flex items-center space-x-2 cursor-pointer hover:bg-gray-100"
                >
                  <input
                    type="checkbox"
                    :checked="isBrandSelected(brand)"
                    class="form-checkbox h-4 w-4 text-blue-600"
                  />
                  <span class="ml-2">{{ brand.name }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Categories Filter -->
        <div>
          <h3 class="text-lg font-semibold">Categories</h3>
          <div class="relative">
            <div
              class="flex flex-wrap items-center gap-2 p-2 border rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500"
              @click="openCategoriesDropdown"
            >
              <template
                v-for="category in selectedCategories"
                :key="category.id"
              >
                <span
                  class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full flex items-center"
                >
                  {{ category.name }}
                  <button
                    type="button"
                    class="ml-2 text-gray-600 hover:text-gray-800"
                    @click.stop="removeCategory(category)"
                  >
                    &times;
                  </button>
                </span>
              </template>
              <input
                v-model="query"
                @input="onCategoryInput"
                @blur="closeCategoriesDropdown"
                type="text"
                placeholder="Search categories..."
                class="w-full border-none outline-none bg-transparent"
              />
            </div>

            <div
              v-if="isCategoriesDropdownOpen && filteredCategories.length > 0"
              class="absolute w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
            >
              <ul class="max-h-60 overflow-y-auto">
                <li
                  v-for="category in filteredCategories"
                  :key="category.id"
                  @mousedown.prevent="toggleCategorySelection(category)"
                  class="px-4 py-2 flex items-center space-x-2 cursor-pointer hover:bg-gray-100"
                >
                  <input
                    type="checkbox"
                    :checked="isCategorySelected(category)"
                    class="form-checkbox h-4 w-4 text-blue-600"
                  />
                  <span class="ml-2">{{ category.name }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section class="col-span-2 p-2">
        <h2 class="text-xl font-bold">Products</h2>
        <!-- Display filtered products here -->
        <div
          v-for="(product, index) in filteredProducts"
          :key="index"
          class="p-4 border-b"
        >
          <h3 class="text-lg font-semibold">{{ product.name }}</h3>
          <p class="text-gray-600">{{ product.description }}</p>
        </div>
      </section>
    </main>
  </AuthLayout>
</template>
