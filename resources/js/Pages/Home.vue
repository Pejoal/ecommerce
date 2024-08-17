<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { defineProps, watch, computed } from "vue";
import { ref } from "vue";

const props = defineProps({
  products: {
    type: Object,
    default: {},
  },
  brands: {
    type: Array,
    default: [],
  },
  categories: {
    type: Array,
    default: [],
  },
});

const brandQuery = ref("");
const categoryQuery = ref("");
const isBrandsDropdownOpen = ref(false);
const isCategoriesDropdownOpen = ref(false);
const selectedBrands = ref([]);
const selectedCategories = ref([]);

const form = useForm({
  selectedBrands: [],
  selectedCategories: [],
  premium_delivery: false,
  in_stock: true,
  minPrice: 0,
  maxPrice: 0,
  perPage: 5,
  search: "",
});

const applyFilters = () => {
  form.selectedBrands = selectedBrands.value.map((brand) => brand.id);
  form.selectedCategories = selectedCategories.value.map(
    (category) => category.id
  );

  form.get(route("home"), {
    preserveState: true, // To preserve the state when navigating
    preserveScroll: true,
  });
};

watch([selectedBrands, selectedCategories], applyFilters);

const toggleBrandSelection = (brand) => {
  if (selectedBrands.value.some((selected) => selected.id === brand.id)) {
    removeBrand(brand);
  } else {
    selectedBrands.value.push(brand);
    brandQuery.value = "";
  }
  applyFilters();
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
    categoryQuery.value = "";
  }
  applyFilters();
};

const removeCategory = (category) => {
  selectedCategories.value = selectedCategories.value.filter(
    (c) => c.id !== category.id
  );
};

const filteredBrands = computed(() => {
  return props.brands
    .filter((brand) =>
      brand.name.toLowerCase().includes(brandQuery.value.toLowerCase())
    )
    .filter(
      (brand) =>
        !selectedBrands.value.some((selected) => selected.id === brand.id)
    );
});

const filteredCategories = computed(() => {
  return props.categories
    .filter((category) =>
      category.name.toLowerCase().includes(categoryQuery.value.toLowerCase())
    )
    .filter(
      (category) =>
        !selectedCategories.value.some(
          (selected) => selected.id === category.id
        )
    );
});

const openBrandsDropdown = () => {
  isBrandsDropdownOpen.value = true;
};

const openCategoriesDropdown = () => {
  isCategoriesDropdownOpen.value = true;
};

const closeBrandsDropdown = () => {
  isBrandsDropdownOpen.value = false;
};

const closeCategoriesDropdown = () => {
  isCategoriesDropdownOpen.value = false;
};

const fetchPage = (url) => {
  form.get(url, {
    preserveState: true,
    preserveScroll: true,
  });
};

const clearFilters = () => {
  form.minPrice = 0;
  form.maxPrice = 0;
  form.search = "";
  form.selectedBrands = [];
  form.selectedCategories = [];
  form.premium_delivery = false;
  form.in_stock = true;

  selectedBrands.value = [];
  selectedCategories.value = [];

  form.get(route("home"), {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <Head>
    <title>{{ trans("words.home") }}</title>
  </Head>
  <AuthLayout>
    <main class="grid grid-cols-1 md:grid-cols-4">
      <section class="bg-blue-50 p-2">
        <h2 class="text-xl font-bold underline">Filters</h2>

        <!-- Search Filter -->
        <div class="mb-4">
          <h3 class="text-lg font-semibold">Search</h3>
          <input
            v-model="form.search"
            @input="applyFilters"
            type="text"
            placeholder="Search by title..."
            class="w-full border rounded-lg p-2"
          />
        </div>

        <!-- Brands Filter -->
        <div class="py-2 border-t border-black">
          <h3 class="text-lg font-semibold">Brands</h3>
          <div class="relative">
            <div
              class="flex flex-wrap items-center gap-2 p-2 border rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500"
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
                v-model="brandQuery"
                @click="openBrandsDropdown"
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
                  <span class="ml-2">{{ brand.name }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Categories Filter -->
        <div class="py-2 border-t border-black">
          <h3 class="text-lg font-semibold">Categories</h3>
          <div class="relative">
            <div
              class="flex flex-wrap items-center gap-2 p-2 border rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500"
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
                v-model="categoryQuery"
                @click="openCategoriesDropdown"
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
                  <span class="ml-2">{{ category.name }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Price Range Filter -->
        <div class="py-2 border-t border-black">
          <h3 class="text-lg font-semibold">Price Range</h3>
          <div class="flex items-center space-x-2">
            <input
              v-model.number="form.minPrice"
              @input="applyFilters"
              type="number"
              placeholder="Min Price"
              class="w-1/2 border rounded-lg p-2"
              min="0"
              step="50"
            />
            <input
              v-model.number="form.maxPrice"
              @input="applyFilters"
              type="number"
              placeholder="Max Price"
              class="w-1/2 border rounded-lg p-2"
              min="0"
              step="50"
            />
          </div>
        </div>

        <div class="flex items-center space-x-2 pt-2 border-t border-black">
          <input
            id="premiumDelivery"
            type="checkbox"
            v-model="form.premium_delivery"
            @change="applyFilters"
            class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded-full"
          />
          <label for="premiumDelivery" class="text-gray-700">
            Premium Delivery
          </label>
        </div>

        <div class="flex items-center space-x-2 pt-2 border-black">
          <input
            id="in_stock"
            type="checkbox"
            v-model="form.in_stock"
            @change="applyFilters"
            class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded-full"
          />
          <label for="in_stock" class="text-gray-700"> In Stock </label>
        </div>

        <div
          class="flex items-center space-x-2 mt-2 pt-2 border-t border-black"
        >
          <select
            v-model="form.perPage"
            id="perPage"
            @change="applyFilters"
            class="rounded-lg"
          >
            <option :value="5">5</option>
            <option :value="10">10</option>
            <option :value="20">20</option>
            <option :value="50">50</option>
          </select>

          <label for="perPage" class="text-gray-700 font-bold">
            Per Page
          </label>
        </div>

        <div class="flex items-center space-x-2 py-2">
          <button class="btn btn-danger" @click="clearFilters">
            Clear Filters
          </button>
        </div>
      </section>

      <!-- End of first section (Filters) -->

      <section class="col-span-3 p-2">
        <h2 class="text-xl font-bold">Products</h2>
        <section class="flex flex-wrap gap-4">
          <div
            v-for="(product, index) in props.products.data"
            :key="index"
            class="flex-1 min-w-[240px] bg-white shadow-md rounded-lg p-4 border border-gray-200"
          >
            <Link
              :href="route('products.show', product.slug)"
              class="hover:font-bold"
            >
              <div class="flex flex-col items-center">
                <!-- Display Product Images -->
                <div class="flex flex-wrap gap-2 mb-4">
                  <img
                    v-if="product.images.length"
                    :src="product.images[0].image"
                    alt="Product Image"
                    class="w-40 h-40 object-cover rounded-md border border-gray-300"
                  />
                  <!-- Add more images if necessary -->
                </div>

                <h3 class="text-lg font-semibold mb-2">{{ product.title }}</h3>
                <p class="text-gray-600 mb-2">{{ product.description }}</p>
                <div class="mt-2 flex items-center">
                  <span class="font-medium text-gray-800">Price:</span>
                  <span class="ml-1 font-bold text-green-600">{{
                    product.currency + product.price
                  }}</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800">Published:</span>
                  <span
                    class="ml-1 font-bold text-green-600"
                    v-if="product.published"
                    >Yes</span
                  >
                  <span class="ml-1 font-bold text-red-600" v-else>No</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800">In Stock:</span>
                  <span
                    class="ml-1 font-bold text-green-600"
                    v-if="product.in_stock"
                    >Yes</span
                  >
                  <span class="ml-1 font-bold text-red-600" v-else>No</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800"
                    >Premium Delivery:</span
                  >
                  <span
                    class="ml-1 font-bold text-green-600"
                    v-if="product.premium_delivery"
                    >Yes</span
                  >
                  <span class="ml-1 font-bold text-red-600" v-else>No</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800">Quantity:</span>
                  <span class="ml-1 font-bold">{{ product.quantity }}</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800">Brand:</span>
                  <span class="ml-1 font-bold">{{ product.brand }}</span>
                </div>
                <div class="mt-1 flex items-center">
                  <span class="font-medium text-gray-800">Category:</span>
                  <span class="ml-1 font-bold">{{ product.category }}</span>
                </div>
              </div>
            </Link>
          </div>
        </section>

        <!-- Pagination Controls -->
        <section class="flex items-center justify-between my-2 bg-slate-300">
          <button
            v-if="props.products.prev_page_url"
            @click="fetchPage(props.products.prev_page_url)"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg"
          >
            Previous
          </button>
          <span class="mx-2"
            >{{ props.products.current_page }} /
            {{ props.products.last_page }}</span
          >
          <span class="mx-2">Total: {{ props.products.total }}</span>
          <button
            v-if="props.products.next_page_url"
            @click="fetchPage(props.products.next_page_url)"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg"
          >
            Next
          </button>
        </section>
      </section>
    </main>
  </AuthLayout>
</template>
