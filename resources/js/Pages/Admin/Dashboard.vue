<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import ResuableModal from "@/Components/ResuableModal.vue";

const props = defineProps({
  brands: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  },
  products: {
    type: Array,
    default: () => [],
  },
});

let showModal = ref(false);
const selectedProducts = ref([]);

const form = useForm({
  id: null,
  title: "",
  slug: "",
  quantity: "",
  description: "",
  published: false,
  in_stock: false,
  price: "",
  brand_id: "",
  category_id: "",
});

const toggleSelection = (productId) => {
  const index = selectedProducts.value.indexOf(productId);
  if (index > -1) {
    selectedProducts.value.splice(index, 1);
  } else {
    selectedProducts.value.push(productId);
  }
};

const isSelected = (productId) => selectedProducts.value.includes(productId);

const deleteProduct = (productId) => {
  if (confirm("Are you sure you want to delete this product?")) {
    form.delete(route("products.destroy", productId), {
      onSuccess: () => {
        selectedProducts.value = selectedProducts.value.filter(
          (id) => id !== productId
        );
      },
    });
  }
};

const massDeleteProducts = () => {
  if (confirm("Are you sure you want to delete the selected products?")) {
    form.delete(route("products.massDestroy"), {
      data: { ids: selectedProducts.value },
      onSuccess: () => {
        selectedProducts.value = [];
      },
    });
  }
};

const addProduct = () => {
  form.post(route("products.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editProduct = (productId) => {
  form.get(route("products.edit", productId), {
    onSuccess: (data) => {
      form.fill(data);
      showModal.value = true;
    },
  });
};
</script>

<template>
  <Head :title="'Products Dashboard'" />

  <AuthLayout>
    <div class="mb-4 flex space-x-2">
      <button
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
        @click="showModal = true"
      >
        Add Product
      </button>
      <button
        @click="massDeleteProducts"
        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
        :disabled="selectedProducts.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <!-- Create/Edit Product Modal -->
    <ResuableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%]']"
      :header="form.id ? 'Edit Product' : 'Create Product'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="addProduct" class="mx-2">
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

            <!-- Slug -->
            <div>
              <label for="slug" class="block text-sm font-medium text-gray-700"
                >Slug</label
              >
              <input
                id="slug"
                v-model="form.slug"
                type="text"
                placeholder="Enter slug"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.slug" class="text-red-600 text-sm">
                {{ form.errors.slug }}
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
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="in_stock" class="ml-2 text-sm text-gray-700"
                >In Stock</label
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
          </section>

          <footer class="flex items-center justify-center">
            <button
              class="btn btn-primary"
              type="submit"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Create
            </button>
          </footer>
        </form>
      </template>
    </ResuableModal>

    <div class="min-w-full border border-solid border-black overflow-x-auto">
      <table class="overflow-auto bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (selectedProducts.value = e.target.checked
                      ? products.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Title</th>
            <th class="p-1 text-left font-semibold">Slug</th>
            <th class="p-1 text-left font-semibold">Quantity</th>
            <th class="p-1 text-left font-semibold max-w-[10rem]">
              Description
            </th>
            <th class="p-1 text-left font-semibold">Published</th>
            <th class="p-1 text-left font-semibold">In Stock</th>
            <th class="p-1 text-left font-semibold">Price</th>
            <th class="p-1 text-left font-semibold">Brand</th>
            <th class="p-1 text-left font-semibold">Category</th>
            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="product in products"
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
            <td class="p-1">{{ product.slug }}</td>
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
            <td class="p-1">{{ product.price }}</td>
            <td class="p-1">{{ product.brand }}</td>
            <td class="p-1">{{ product.category }}</td>
            <td class="p-1 flex space-x-2">
              <button
                @click="editProduct(product.id)"
                class="bg-yellow-500 text-white py-1 px-2 rounded hover:bg-yellow-600"
              >
                Edit
              </button>
              <button
                @click="deleteProduct(product.id)"
                class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600"
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
