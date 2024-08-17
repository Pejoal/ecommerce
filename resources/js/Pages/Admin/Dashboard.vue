<script setup>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import ResuableModal from "@/Components/ResuableModal.vue";
import axios from "axios";

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
  currencies: {
    type: Array,
    default: [],
  },
});

let showModal = ref(false);
let allImages = ref([]);

const form = useForm({
  id: null,
  title: "",
  quantity: "",
  description: "",
  published: false,
  in_stock: false,
  premium_delivery: false,
  price: "",
  currency_id: 0,
  brand_id: 0,
  category_id: 0,
  images: [],
});

const massForm = useForm({
  selectedProducts: [],
});

const toggleSelection = (productId) => {
  const index = massForm.selectedProducts.indexOf(productId);
  if (index > -1) {
    massForm.selectedProducts.splice(index, 1);
  } else {
    massForm.selectedProducts.push(productId);
  }
};

const isSelected = (productId) => massForm.selectedProducts.includes(productId);

const deleteProduct = (productId) => {
  if (confirm("Are you sure you want to delete this product?")) {
    form.delete(route("admin.products.destroy", productId), {
      onSuccess: () => {
        massForm.selectedProducts = massForm.selectedProducts.filter(
          (id) => id !== productId
        );
      },
    });
  }
};

const massDeleteProducts = () => {
  if (confirm("Are you sure you want to delete the selected products?")) {
    massForm.delete(route("admin.products.massDestroy"), {
      onSuccess: () => {
        massForm.selectedProducts.value = [];
      },
    });
  }
};

const storeProduct = () => {
  form.post(route("admin.products.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
      allImages.value = [];
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const updateProduct = () => {
  form.patch(route("admin.products.update", form.id), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
      allImages.value = [];
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editProduct = (productId) => {
  axios
    .get(route("admin.products.edit", productId))
    .then((response) => {
      form.id = response.data.id;
      form.title = response.data.title;
      form.quantity = response.data.quantity;
      form.description = response.data.description;
      form.published = response.data.published;
      form.in_stock = response.data.in_stock;
      form.premium_delivery = response.data.premium_delivery;
      form.price = response.data.price;
      form.currency_id = response.data.currency_id;
      form.brand_id = response.data.brand_id;
      form.category_id = response.data.category_id;
      allImages.value = response.data.images;

      showModal.value = true;
    })
    .catch((error) => console.error(error));
};

const addProduct = () => {
  showModal.value = true;
  form.reset();
  allImages.value = [];
};

const imagesRef = ref(null);
const filtersVisible = ref(false);

const toggleFilters = () => {
  filtersVisible.value = !filtersVisible.value;
};

const brandQuery = ref("");
const categoryQuery = ref("");
const isBrandsDropdownOpen = ref(false);
const isCategoriesDropdownOpen = ref(false);
const selectedBrands = ref([]);
const selectedCategories = ref([]);

const pageForm = useForm({
  selectedBrands: [],
  selectedCategories: [],
  premium_delivery: false,
  in_stock: false,
  minPrice: 0,
  maxPrice: 0,
  perPage: 5,
  search: "",
});

const applyFilters = () => {
  pageForm.selectedBrands = selectedBrands.value.map((brand) => brand.id);
  pageForm.selectedCategories = selectedCategories.value.map(
    (category) => category.id
  );

  pageForm.get(route("admin.dashboard"), {
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
  pageForm.get(url, {
    preserveState: true,
    preserveScroll: true,
  });
};

const clearFilters = () => {
  pageForm.minPrice = 0;
  pageForm.maxPrice = 0;
  pageForm.search = "";
  pageForm.selectedBrands = [];
  pageForm.selectedCategories = [];
  pageForm.premium_delivery = false;
  pageForm.in_stock = false;

  selectedBrands.value = [];
  selectedCategories.value = [];

  pageForm.get(route("admin.dashboard"), {
    preserveState: true,
    preserveScroll: true,
  });
};

const uploadProductPhotos = () => {
  form.post(route("admin.products.photo.update"), {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false;
      imagesRef.value.value = "";
    },
  });
};

const deleteImage = (id) => {
  form.delete(route("admin.products.photo", id), {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false;
    },
  });
};

const handleNewImages = (files) => {
  form.images = files;
};
</script>

<template>
  <Head :title="'Products Dashboard'" />

  <AuthLayout>
    <section>
      <button @click="toggleFilters" class="font-bold text-lg px-2">
        Toggle Filters
      </button>

      <transition name="slide-down">
        <section v-if="filtersVisible" class="p-2 bg-slate-200 rounded-lg">
          <!-- Search Filter -->
          <div class="mb-4">
            <h3 class="text-lg font-semibold">Search</h3>
            <input
              v-model="pageForm.search"
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
                v-model.number="pageForm.minPrice"
                @input="applyFilters"
                type="number"
                placeholder="Min Price"
                class="w-1/2 border rounded-lg p-2"
                min="0"
                step="50"
              />
              <input
                v-model.number="pageForm.maxPrice"
                @input="applyFilters"
                type="number"
                placeholder="Max Price"
                class="w-1/2 border rounded-lg p-2"
                min="0"
                step="50"
              />
            </div>
          </div>

          <div class="flex items-center space-x-2 py-2 border-t border-black">
            <input
              id="premiumDelivery"
              type="checkbox"
              v-model="pageForm.premium_delivery"
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
              v-model="pageForm.in_stock"
              @change="applyFilters"
              class="h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded-full"
            />
            <label for="in_stock" class="text-gray-700"> In Stock </label>
          </div>

          <div class="flex items-center space-x-2 py-2">
            <button class="btn btn-danger" @click="clearFilters">
              Clear Filters
            </button>
          </div>
        </section>
      </transition>
    </section>

    <div class="p-2 flex items-center gap-2">
      <button class="btn btn-primary" @click="addProduct">Add Product</button>
      <button
        @click="massDeleteProducts"
        class="btn btn-danger"
        :disabled="massForm.selectedProducts.length === 0"
      >
        Delete Selected
      </button>

      <div class="flex items-center ml-auto">
        <select
          v-model="pageForm.perPage"
          id="perPage"
          @change="applyFilters"
          class="rounded-lg"
        >
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
        </select>

        <label for="perPage" class="text-gray-700 font-bold"> Per Page </label>
      </div>
    </div>

    <!-- Create/Edit Product Modal -->
    <ResuableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-full']"
      :header="form.id ? 'Edit Product' : 'Create Product'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="storeProduct" class="mx-2">
          <section class="space-y-4">
            <!-- Title -->
            <div>
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
              <p v-if="form.errors.title" class="text-red-600 text-sm">
                {{ form.errors.title }}
              </p>
            </div>

            <!-- Quantity -->
            <div>
              <label
                for="quantity"
                class="block text-sm font-medium text-gray-700"
                >Quantity</label
              >
              <input
                id="quantity"
                v-model="form.quantity"
                type="number"
                placeholder="Enter quantity"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.quantity" class="text-red-600 text-sm">
                {{ form.errors.quantity }}
              </p>
            </div>

            <!-- Description -->
            <div>
              <label
                for="description"
                class="block text-sm font-medium text-gray-700"
                >Description</label
              >
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                placeholder="Enter description"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              ></textarea>
              <p v-if="form.errors.description" class="text-red-600 text-sm">
                {{ form.errors.description }}
              </p>
            </div>

            <!-- Published -->
            <div class="flex items-center">
              <input
                id="published"
                v-model="form.published"
                :checked="form.published"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="published" class="ml-2 text-sm text-gray-700"
                >Published</label
              >
            </div>

            <!-- In Stock -->
            <div class="flex items-center">
              <input
                id="in_stock"
                v-model="form.in_stock"
                :checked="form.in_stock"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="in_stock" class="ml-2 text-sm text-gray-700"
                >In Stock</label
              >
            </div>

            <!-- Premium Delivery -->
            <div class="flex items-center">
              <input
                id="premium_delivery"
                v-model="form.premium_delivery"
                :checked="form.premium_delivery"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="premium_delivery" class="ml-2 text-sm text-gray-700"
                >Premium Delivery</label
              >
            </div>

            <!-- Price -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700"
                >Price</label
              >
              <input
                id="price"
                v-model="form.price"
                type="number"
                step="0.01"
                placeholder="Enter price"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.price" class="text-red-600 text-sm">
                {{ form.errors.price }}
              </p>
            </div>

            <!-- Currency -->
            <div>
              <label
                for="currency_id"
                class="block text-sm font-medium text-gray-700"
                >Currency</label
              >
              <select
                id="currency_id"
                v-model="form.currency_id"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option value="">Select Currency</option>
                <option
                  v-for="currency in props.currencies"
                  :key="currency.id"
                  :value="currency.id"
                >
                  {{ currency.symbol }}
                </option>
              </select>
              <p v-if="form.errors.currency_id" class="text-red-600 text-sm">
                {{ form.errors.currency_id }}
              </p>
            </div>

            <!-- Brand -->
            <div>
              <label
                for="brand_id"
                class="block text-sm font-medium text-gray-700"
                >Brand</label
              >
              <select
                id="brand_id"
                v-model="form.brand_id"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option value="">Select Brand</option>
                <option
                  v-for="brand in props.brands"
                  :key="brand.id"
                  :value="brand.id"
                >
                  {{ brand.name }}
                </option>
              </select>
              <p v-if="form.errors.brand_id" class="text-red-600 text-sm">
                {{ form.errors.brand_id }}
              </p>
            </div>

            <!-- Category -->
            <div>
              <label
                for="category_id"
                class="block text-sm font-medium text-gray-700"
                >Category</label
              >
              <select
                id="category_id"
                v-model="form.category_id"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option value="">Select Category</option>
                <option
                  v-for="category in props.categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
              <p v-if="form.errors.category_id" class="text-red-600 text-sm">
                {{ form.errors.category_id }}
              </p>
            </div>

            <div class="flex justify-end">
              <button
                v-if="form.id"
                form="updateProduct"
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Update Product
              </button>
              <button
                v-else
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Create Product
              </button>
            </div>
          </section>
        </form>

        <form
          class="p-2 sm:p-4 shadow sm:rounded-lg"
          @submit.prevent="uploadProductPhotos"
        >
          <section class="flex justify-between flex-col sm:flex-row">
            <div class="my-2">
              <label class="pr-2" for="images"> Images </label>
              <input
                id="images"
                type="file"
                ref="imagesRef"
                multiple
                @input="handleNewImages($event.target.files)"
              />
            </div>
            <button
              class="btn btn-success"
              type="submit"
              :disabled="form.processing"
            >
              Upload
            </button>
          </section>
          <p
            v-if="form.errors.images"
            class="text-sm bg-red-600 rounded-md my-1 px-2 py-1"
          >
            {{ form.errors.images }}
          </p>
          <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
          >
            {{ form.progress.percentage }}%
          </progress>
          <Transition
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
            class="transition ease-in-out"
          >
            <p v-if="form.recentlySuccessful" class="text-sm">Uploaded</p>
          </Transition>

          <div v-for="image in allImages" :key="image.id" class="relative">
            <img
              :src="image.image"
              alt="Product Image"
              class="w-32 h-32 object-cover rounded-md border border-gray-300"
            />
            <button
              @click.prevent="deleteImage(image.id)"
              class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </form>
        <form
          @submit.prevent="updateProduct"
          id="updateProduct"
          class="hidden"
        ></form>
      </template>
    </ResuableModal>

    <!-- Product Table -->
    <div class="lg:flex items-center justify-center overflow-x-auto w-full">
      <table class="w-full bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (massForm.selectedProducts = e.target.checked
                      ? props.products.data.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Title</th>
            <th class="p-1 text-left font-semibold">Quantity</th>
            <th class="p-1 text-left font-semibold max-w-[10rem]">
              Description
            </th>
            <th class="p-1 text-left font-semibold">Published</th>
            <th class="p-1 text-left font-semibold">In Stock</th>
            <th class="p-1 text-left font-semibold">Premium</th>
            <th class="p-1 text-left font-semibold">Price</th>
            <th class="p-1 text-left font-semibold">Currency</th>
            <th class="p-1 text-left font-semibold">Brand</th>
            <th class="p-1 text-left font-semibold">Category</th>
            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="product in props.products.data"
            :key="product.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-1">
              <input
                type="checkbox"
                :value="product.id"
                @change="toggleSelection(product.id)"
                :checked="isSelected(product.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-1">{{ product.id }}</td>
            <td class="p-1">{{ product.title }}</td>
            <td class="p-1">{{ product.quantity }}</td>
            <td class="p-1 truncate max-w-[10rem]">
              {{ product.description }}
            </td>
            <td class="p-1 font-bold text-green-600" v-if="product.published">
              Yes
            </td>
            <td class="p-1 font-bold text-red-600" v-else>No</td>
            <td class="p-1 font-bold text-green-600" v-if="product.in_stock">
              Yes
            </td>
            <td class="p-1 font-bold text-red-600" v-else>No</td>
            <td
              class="p-1 font-bold text-green-600"
              v-if="product.premium_delivery"
            >
              Yes
            </td>
            <td class="p-1 font-bold text-red-600" v-else>No</td>
            <td class="p-1">{{ product.price }}</td>
            <td class="p-1">{{ product.currency }}</td>
            <td class="p-1">{{ product.brand }}</td>
            <td class="p-1">{{ product.category }}</td>
            <td class="p-1 flex space-x-2">
              <button @click="editProduct(product.id)" class="btn btn-success">
                Edit
              </button>
              <button @click="deleteProduct(product.id)" class="btn btn-danger">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

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
  </AuthLayout>
</template>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: max-height 0.3s ease-in-out;
}

.slide-down-enter-from,
.slide-down-leave-to {
  max-height: 0;
  overflow: hidden;
}

.slide-down-enter-to,
.slide-down-leave-from {
  max-height: 50rem;
  overflow: auto;
}
</style>
